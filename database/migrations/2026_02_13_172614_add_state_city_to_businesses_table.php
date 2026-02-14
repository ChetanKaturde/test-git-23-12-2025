<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('business_state')->nullable()->after('state');
            $table->string('business_city')->nullable()->after('business_state');
        });
    }

    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['business_state', 'business_city']);
        });
    }
};
