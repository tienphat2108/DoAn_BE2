<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('id', 'name', 'email', 'created_at')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Người dùng',
            'Họ tên',
            'Email',
            'Ngày đăng ký',
        ];
    }
}
