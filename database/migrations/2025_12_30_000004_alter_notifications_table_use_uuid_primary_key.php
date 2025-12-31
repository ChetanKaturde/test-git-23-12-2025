<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNotificationsTableUseUuidPrimaryKey extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Drop the auto-incrementing id column
            $table->dropColumn('id');
        });

        Schema::table('notifications', function (Blueprint $table) {
            // Add UUID primary key
            $table->uuid('id')->primary();
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Drop UUID id
            $table->dropColumn('id');
        });

        Schema::table('notifications', function (Blueprint $table) {
            // Restore auto-incrementing id
            $table->id();
        });
    }
}