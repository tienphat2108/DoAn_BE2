<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\PostMedia;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        // Lấy người dùng hiện có
        $users = User::all();
        
        // Nếu không có người dùng nào, tạo một người dùng mẫu
        if ($users->isEmpty()) {
            $user = User::create([
                'username' => 'demo_user',
                'full_name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => bcrypt('password'),
                'avatar_url' => '/images/default-avatar.png',
                'is_admin' => false,
            ]);
            $users = collect([$user]);
        }

        // Tạo bài viết mẫu
        $posts = [
            [
                'title' => 'Chào mừng đến với Fite!',
                'content' => 'Đây là bài viết đầu tiên trên Fite. Chúng tôi rất vui được chào đón bạn!',
                'latitude' => 10.762622,
                'longitude' => 106.660172,
                'status' => 'draft'
            ],
            [
                'title' => 'Cuối tuần vui vẻ',
                'content' => 'Chúc mọi người có một cuối tuần thật vui vẻ và hạnh phúc!',
                'latitude' => 10.762622,
                'longitude' => 106.660172,
                'status' => 'draft'
            ],
            [
                'title' => 'Chia sẻ khoảnh khắc',
                'content' => 'Hãy chia sẻ những khoảnh khắc đáng nhớ của bạn với mọi người!',
                'latitude' => 10.762622,
                'longitude' => 106.660172,
                'status' => 'draft'
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::create([
                'user_id' => $users->random()->id,
                'title' => $postData['title'],
                'content' => $postData['content'],
                'latitude' => $postData['latitude'],
                'longitude' => $postData['longitude'],
                'status' => $postData['status']
            ]);

            // Thêm một số like ngẫu nhiên
            $randomUsers = $users->random(rand(1, min(3, $users->count())));
            foreach ($randomUsers as $user) {
                PostLike::create([
                    'post_id' => $post->post_id,
                    'user_id' => $user->id,
                ]);
            }

            // Thêm một số bình luận ngẫu nhiên
            $comments = [
                'Bài viết hay quá!',
                'Cảm ơn bạn đã chia sẻ!',
                'Tôi cũng nghĩ vậy!',
                'Chúc mừng bạn!',
            ];

            $randomUsers = $users->random(rand(1, min(3, $users->count())));
            foreach ($randomUsers as $user) {
                PostComment::create([
                    'post_id' => $post->post_id,
                    'user_id' => $user->id,
                    'content' => $comments[array_rand($comments)],
                ]);
            }
        }
    }
} 