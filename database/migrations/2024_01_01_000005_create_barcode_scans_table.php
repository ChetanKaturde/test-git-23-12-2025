<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barcode_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barcode_id')->constrained()->onDelete('cascade');
            $table->string('scanner_device')->nullable();
            $table->string('scan_location')->nullable();
            $table->enum('scan_type', ['inventory', 'dispatch', 'quality_check', 'other'])->default('inventory');
            $table->json('scan_data')->nullable(); // Store additional scan information
            $table->unsignedBigInteger('scanned_by')->nullable();
            $table->timestamp('scanned_at');
            $table->timestamps();
            
            // Indexes
            $table->index(['barcode_id', 'scanned_at']);
            $table->index('scan_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('barcode_scans');
    }
};