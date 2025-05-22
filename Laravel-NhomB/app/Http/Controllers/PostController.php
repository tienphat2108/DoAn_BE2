<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Media;
use App\Models\PostReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Lấy danh sách bài viết với thông tin người dùng và media
    public function index()
    {
        $posts = Post::with(['user', 'media', 'likes', 'comments.user'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('crud_trangchu.trangchu', compact('posts'));
    }

    // Danh sách bài viết chờ duyệt hoặc bản nháp
    public function pending()
    {
        $posts = Post::whereIn('status', ['bản nháp', 'yêu cầu duyệt'])->paginate(10);
        return view('admin.posts.pending', compact('posts'));
    }

    // Hiển thị form tạo bài viết
    public function create()
    {
        return view('posts.create');
    }

    // Lưu bài viết mới
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4|max:10240',
            'scheduled_at' => 'nullable|date',
        ]);
        $user = Auth::user();
        $errors = [];
        // Kiểm tra thiếu media
        if (!$request->hasFile('media')) {
            $errors[] = 'Bài viết phải có ít nhất một hình ảnh hoặc video!';
        }
        // Kiểm tra trùng giờ đăng nếu có scheduled_at
        if ($request->filled('scheduled_at')) {
            $exists = Post::where('scheduled_at', $request->scheduled_at)
                ->where('status', '!=', 'canceled')
                ->exists();
            if ($exists) {
                $errors[] = 'Đã có bài viết khác được lên lịch vào thời gian này. Vui lòng chọn thời gian khác!';
            }
        }
        // Kiểm tra nội dung cấm/từ khóa sai
        $forbidden = ['cấm', 'vi phạm', 'bậy', 'xxx']; // Tùy chỉnh danh sách từ khóa cấm
        $content = strtolower($request->title . ' ' . $request->content);
        $violation = null;
        foreach ($forbidden as $word) {
            if (strpos($content, $word) !== false) {
                $violation = $word;
                break;
            }
        }
        if ($violation) {
            $post = Post::create([
                'title' => $request->title,
                'user_id' => $user->id,
                'status' => 'canceled',
                'content' => $request->content,
                'admin_note' => 'Bài viết bị hủy do chứa từ cấm: ' . $violation,
                'scheduled_at' => $request->scheduled_at,
            ]);
            \App\Models\PostHistory::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'action' => 'cancel',
                'details' => 'Bài viết bị hủy do chứa từ cấm: ' . $violation
            ]);
            return redirect()->route('canhan')->with('error', 'Bài viết bị hủy do vi phạm: chứa từ cấm "' . $violation . '"');
        }
        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        // Determine post status based on scheduled_at
        $status = $request->filled('scheduled_at') ? 'scheduled' : $request->input('status', 'pending');

        $post = Post::create([
            'title' => $request->title,
            'user_id' => $user->id,
            'content' => $request->content,
            'scheduled_at' => $request->scheduled_at,
            'status' => $status,
        ]);
        // Log trạng thái bài viết sau khi tạo
        \Illuminate\Support\Facades\Log::info('Post created with status: ' . $post->status . ' for post ID: ' . $post->id);
        // Lưu media nếu có
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('media', 'public');
                $type = $file->getClientMimeType();
                Media::create([
                    'post_id' => $post->id,
                    'file_url' => $path,
                    'file_type' => $type,
                ]);
            }
        }
        // Ghi lịch sử đăng bài
        \App\Models\PostHistory::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'action' => 'create',
            'details' => 'Bài viết mới được tạo'
        ]);
        // Sau khi tạo bài viết lên lịch
        if ($post->status === 'scheduled') {
            return redirect()->route('trangchu')->with('success', 'Bài viết đã được lên lịch thành công!');
        }
        // Sau khi hủy bài viết
        if ($post->status === 'canceled') {
            return redirect()->route('trangchu')->with('success', 'Bài viết đã được hủy lịch thành công!');
        }
        return redirect()->route('trangchu')->with('success', 'Đăng bài thành công!');
    }

    // Hiển thị form chỉnh sửa bài viết
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // Cập nhật bài viết
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'sometimes',
        ]);
        $post->title = $request->input('title');
        if ($request->has('content')) {
            $post->content = $request->input('content');
        }
        $post->save();
        // Ghi lịch sử cập nhật bài viết
        \App\Models\PostHistory::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'action' => 'edit',
            'details' => 'Bài viết đã được cập nhật'
        ]);
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'title' => $post->title]);
        }
        return redirect()->route('trangchu')->with('success', 'Cập nhật thành công!');
    }

    // Xoá bài viết
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        // Nếu muốn kiểm tra quyền xóa, thêm ở đây (ví dụ: chỉ cho xóa bài của mình)
        // if (auth()->id() !== $post->user_id) abort(403);
        // Ghi lịch sử xóa bài viết
        \App\Models\PostHistory::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'action' => 'delete',
            'details' => 'Bài viết đã bị xóa'
        ]);
        $post->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Xóa bài viết thành công!']);
        }
        return redirect()->back()->with('success', 'Xóa bài viết thành công!');
    }

    // Duyệt bài viết
    public function approve($id)
    {
        $post         = Post::findOrFail($id);
        $post->status = 'đã duyệt';
        $post->save();
        // Optionally gửi thông báo về cho người đăng
        return redirect()->back()->with('success', 'Bài viết đã được duyệt.');
    }

    // Từ chối bài viết
    public function reject($id)
    {
        $post         = Post::findOrFail($id);
        $post->status = 'bị từ chối';
        $post->save();
        return redirect()->back()->with('error', 'Đã từ chối bài viết.');
    }

    // Yêu cầu chỉnh sửa bài viết
    public function requestEdit(Request $request, $id)
    {
        $post             = Post::findOrFail($id);
        $post->status     = 'yêu cầu chỉnh sửa';
        $post->admin_note = $request->input('note');
        $post->save();
        return redirect()->back()->with('info', 'Đã gửi yêu cầu chỉnh sửa.');
    }

    // Xử lý like bài viết
    public function like(Post $post)
    {
        $user = auth()->user();
        if (!$post->likes()->where('user_id', $user->id)->exists()) {
            $post->likes()->create(['user_id' => $user->id]);
        }
        return response()->json(['success' => true]);
    }

    // Xử lý bỏ like bài viết
    public function unlike(Post $post)
    {
        $user = auth()->user();
        $post->likes()->where('user_id', $user->id)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Xử lý chia sẻ bài viết
     */
    public function share($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->increment('shares_count');
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật lượt chia sẻ',
                'shares_count' => $post->shares_count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi chia sẻ bài viết'
            ], 500);
        }
    }

    /**
     * Xử lý báo cáo bài viết
     */
    public function report(Request $request, Post $post)
    {
        $user = Auth::user();
        // Kiểm tra đã báo cáo chưa (1 user chỉ báo cáo 1 lần/post)
        $exists = PostReport::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->exists();
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Bạn đã báo cáo bài viết này!'], 409);
        }
        PostReport::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
        return response()->json(['success' => true, 'message' => 'Đã báo cáo bài viết!']);
    }

    // API autosave draft
    public function autosaveDraft(Request $request)
    {
        $user = Auth::user();
        $draftId = $request->input('draft_id');
        $title = $request->input('title');
        $content = $request->input('content');
        $post = null;
        if ($draftId) {
            $post = Post::where('id', $draftId)->where('user_id', $user->id)->where('status', 'bản nháp')->first();
            if ($post) {
                $post->title = $title;
                $post->content = $content;
                $post->save();
                // Ghi lịch sử cập nhật bản nháp
                \App\Models\PostHistory::create([
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                    'action' => 'edit',
                    'details' => 'Bản nháp đã được cập nhật'
                ]);
            }
        }
        if (!$post) {
            $post = Post::create([
                'title' => $title,
                'content' => $content,
                'user_id' => $user->id,
                'status' => 'bản nháp',
            ]);
            // Ghi lịch sử tạo bản nháp
            \App\Models\PostHistory::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'action' => 'create',
                'details' => 'Bản nháp mới được tạo'
            ]);
        }
        return response()->json(['success' => true, 'draft_id' => $post->id]);
    }

    public function postSchedule()
    {
        // Lấy tất cả bài viết cho tab 'All'
        $allPosts = Post::orderBy('created_at', 'desc')->with(['user', 'media'])->get();
        // Lấy bài viết đã lên lịch cho tab 'Lịch trình Đăng Bài'
        $scheduledPosts = Post::where('status', 'scheduled')->orderBy('scheduled_at', 'asc')->get();
        $histories = \App\Models\PostHistory::with(['post', 'user'])->orderBy('created_at', 'desc')->get();
        return view('admin.quanlylichdangbai', compact('scheduledPosts', 'histories', 'allPosts'));
    }
}
