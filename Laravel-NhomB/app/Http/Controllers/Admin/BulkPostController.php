<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BulkPostController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'posts' => 'required|array',
                'posts.*.title' => 'required|string|max:255',
                'posts.*.content' => 'required|string',
                'posts.*.scheduled_at' => 'required|date_format:Y-m-d H:i:s',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ: ' . $validator->errors()->first()
                ], 422);
            }

            // Begin transaction
            DB::beginTransaction();

            $successCount = 0;
            $errors = [];

            foreach ($request->posts as $postData) {
                try {
                    // Create new post
                    $post = new Post();
                    $post->title = $postData['title'];
                    $post->content = $postData['content'];
                    $post->scheduled_at = $postData['scheduled_at'];
                    $post->status = 'pending';
                    $post->user_id = auth()->id();
                    $post->save();

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Lỗi khi tạo bài viết '{$postData['title']}': " . $e->getMessage();
                }
            }

            // If all posts were created successfully
            if (empty($errors)) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'count' => $successCount,
                    'message' => "Đã lên lịch thành công {$successCount} bài viết"
                ]);
            }

            // If some posts failed
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lên lịch bài viết',
                'errors' => $errors
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
} 