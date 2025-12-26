<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['quotation_id']);
            $table->dropColumn('quotation_id');
        });
    }
}; 