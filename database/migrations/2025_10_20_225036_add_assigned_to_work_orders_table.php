<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_to')->nullable()->after('status');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn('assigned_to');
        });
    }
};