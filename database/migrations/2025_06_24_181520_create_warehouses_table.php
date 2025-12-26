<?php

// 1. MIGRATION - Create warehouses table

// database/migrations/xxxx_xx_xx_create_warehouses_table.php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration

{

    public function up()

    {

        Schema::create('warehouses', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('address');

            $table->string('city');

            $table->string('state');

            $table->string('contact_phone')->nullable();

            $table->string('contact_email')->nullable();

            $table->enum('type', ['main', 'cold_storage', 'transit', 'distribution', 'temporary']);

            $table->boolean('is_default')->default(false);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            

            $table->index(['is_active', 'is_default']);

            $table->index(['city', 'state']);

        });

    }

    public function down()

    {

        Schema::dropIfExists('warehouses');

    }

};