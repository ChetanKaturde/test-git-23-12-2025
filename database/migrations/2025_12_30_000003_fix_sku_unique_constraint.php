<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->unique(['business_id', 'sku']);
        });
    }

    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropUnique(['business_id', 'sku']);
            $table->unique('sku');
        });
    }
};