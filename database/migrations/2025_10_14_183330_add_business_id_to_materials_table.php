<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('business_id')->default(1)->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->dropColumn('business_id');
        });
    }
};