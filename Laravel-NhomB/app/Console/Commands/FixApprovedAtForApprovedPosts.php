<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixApprovedAtForApprovedPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-approved-at-for-approved-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trường approved_at cho các bài đã duyệt mà chưa có giá trị này';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = \App\Models\Post::where('status', 'approved')
            ->whereNull('approved_at')
            ->update(['approved_at' => DB::raw('created_at')]);

        $this->info("Đã cập nhật $count bài viết approved_at = created_at cho các bài đã duyệt.");
    }
}
