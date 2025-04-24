<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
        });

        // Cập nhật tài khoản admin có sẵn
        DB::table('users')
            ->where('email', 'admin1@gmail.com')
            ->update(['is_admin' => true]);
        
        DB::table('users')
            ->where('email', 'admin2@gmail.com')
            ->update(['is_admin' => true]);
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
}; 