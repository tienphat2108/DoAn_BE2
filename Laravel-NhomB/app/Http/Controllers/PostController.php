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
        ]);
        $user = Auth::user();
        if (!$request->hasFile('media')) {
            return back()->withErrors(['media' => 'Bài viết phải có ít nhất một hình ảnh!'])->withInput();
        }
        $post = Post::create([
            'title' => $request->title,
            'user_id' => $user->id,
            'status' => 'pending',
            'content' => $request->content,
        ]);
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
        // Redirect
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
}
