<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class PostsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.id', 'posts.title', 'posts.content', 'users.name as author_name', 'posts.created_at')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Bài viết',
            'Tiêu đề',
            'Nội dung',
            'Tác giả',
            'Ngày tạo',
        ];
    }
}
