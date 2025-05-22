<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('post_views');
    }

    public function down()
    {
        // Không cần rollback
    }
}; 