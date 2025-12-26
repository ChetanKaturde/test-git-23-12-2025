<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::table('quotations', function (Blueprint $table) {
        //     $table->timestamp('sent_at')->nullable();
        // });
        
        Schema::table('invoices', function (Blueprint $table) {
            $table->timestamp('sent_at')->nullable(); 
        });
    }

    public function down()
    {
        // Schema::table('quotations', function (Blueprint $table) {
        //     $table->dropColumn('sent_at');
        // });
        
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('sent_at');
        });
    }
};