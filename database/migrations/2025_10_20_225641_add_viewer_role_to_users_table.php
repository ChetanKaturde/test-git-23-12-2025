<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'purchase_team', 'inventory_manager', 'operator', 'viewer') NOT NULL DEFAULT 'operator'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'purchase_team', 'inventory_manager', 'operator') NOT NULL DEFAULT 'operator'");
    }
};