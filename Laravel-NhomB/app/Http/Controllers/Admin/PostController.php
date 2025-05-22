<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('admin.quanlybainguoidung', compact('posts'));
    }

    public function approve($id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'approved';
        $post->save();
        // Ghi lịch sử duyệt bài viết
        \App\Models\PostHistory::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'action' => 'approve',
            'details' => 'Bài viết đã được duyệt'
        ]);
        return redirect()->back()->with('success', 'Bài viết đã được duyệt.');
    }

    public function reject($id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'bị từ chối';
        $post->save();
        // Ghi lịch sử từ chối bài viết
        \App\Models\PostHistory::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'action' => 'reject',
            'details' => 'Bài viết đã bị từ chối'
        ]);
        return redirect()->back()->with('error', 'Đã từ chối bài viết.');
    }

    public function requestEdit(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'yêu cầu chỉnh sửa';
        $post->admin_note = $request->input('note');
        $post->save();
        // Ghi lịch sử yêu cầu chỉnh sửa bài viết
        \App\Models\PostHistory::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'action' => 'request_edit',
            'details' => 'Yêu cầu chỉnh sửa bài viết: ' . $request->input('note')
        ]);
        return redirect()->back()->with('info', 'Đã gửi yêu cầu chỉnh sửa.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        // Ghi lịch sử xóa bài viết
        \App\Models\PostHistory::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'action' => 'delete',
            'details' => 'Bài viết đã bị xóa bởi admin'
        ]);
        $post->delete();
        return redirect()->back()->with('success', 'Bài viết đã được xóa thành công.');
    }

    public function pendingPosts()
    {
        $posts = Post::where('status', 'pending')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.posts.pending', ['posts' => $posts]);
    }

    public function approvedPosts()
    {
        $posts = Post::where('status', 'approved')->orderBy('created_at', 'desc')->get();
        return view('admin.baidaduyet', compact('posts'));
    }

    public function postSchedule()
    {
        $scheduledPosts = Post::where('status', 'scheduled')->orderBy('scheduled_at', 'asc')->with('user')->get();
        $histories = \App\Models\PostHistory::with(['post', 'user'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.quanlylichdangbai', compact('scheduledPosts', 'histories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:pending,approved,scheduled',
            'scheduled_at' => 'nullable|date|after:now',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4|max:10240',
        ]);

        $validated['user_id'] = auth()->id();
        $post = Post::create($validated);

        // Ghi lại lịch sử
        PostHistory::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'action' => 'create',
            'details' => 'Bài viết mới được tạo'
        ]);

        // Lưu media nếu có
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('media', 'public');
                $type = $file->getClientMimeType();
                \App\Models\Media::create([
                    'post_id' => $post->id,
                    'file_url' => $path,
                    'file_type' => $type,
                ]);
            }
        }

        return redirect()->route('admin.quanlybainguoidung')
            ->with('success', 'Bài viết đã được tạo thành công.');
    }

    public function scheduleMulti(Request $request)
    {
        $postIds = $request->input('post_ids', []);
        $scheduledAts = $request->input('scheduled_at', []);
        $count = 0;
        foreach ($postIds as $postId) {
            $post = \App\Models\Post::find($postId);
            if ($post && !empty($scheduledAts[$postId])) {
                $post->scheduled_at = $scheduledAts[$postId];
                $post->status = 'scheduled';
                $post->save();
                $count++;
            }
        }
        return redirect()->route('admin.lichdangbai')->with('success', 'Đã lên lịch cho ' . $count . ' bài viết!');
    }

    public function bulkSchedule(Request $request)
    {
        $posts = $request->input('posts', []);
        $count = 0;
        foreach ($posts as $row) {
            if (!empty($row['title']) && !empty($row['scheduled_at'])) {
                $post = new \App\Models\Post();
                $post->title = $row['title'];
                $post->content = $row['content'] ?? '';
                $post->user_id = auth()->id(); // hoặc chọn user khác nếu cần
                $post->status = 'scheduled';
                $post->scheduled_at = $row['scheduled_at'];
                $post->save();
                $count++;
            }
        }
        return response()->json(['success' => true, 'count' => $count]);
    }

    // Chuyển bài viết từ Bản nháp sang Chờ duyệt
    public function moveToPending($id)
    {
        $post = Post::findOrFail($id);
        if ($post->status === 'bản nháp' || $post->status === 'draft') {
            $post->status = 'pending';
            $post->save();
            // Ghi lịch sử chuyển trạng thái
            \App\Models\PostHistory::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'action' => 'move_to_pending',
                'details' => 'Bài viết được chuyển từ bản nháp sang chờ duyệt bởi admin'
            ]);
            return redirect()->back()->with('success', 'Đã chuyển bài viết sang trạng thái chờ duyệt.');
        } else {
            return redirect()->back()->with('error', 'Bài viết không ở trạng thái bản nháp.');
        }
    }
} 