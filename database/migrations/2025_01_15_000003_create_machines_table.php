<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['cnc', 'lathe', 'welding', 'cutting', 'drilling', 'milling', 'other'])->default('other');
            $table->enum('status', ['available', 'in_use', 'maintenance', 'broken'])->default('available');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['business_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('machines');
    }
};