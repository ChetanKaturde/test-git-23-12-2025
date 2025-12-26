
   <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('purchase_order_items', 'net_price')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                $table->decimal('net_price', 10, 2)->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('purchase_order_items', 'net_price')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                $table->dropColumn('net_price');
            });
        }
    }

};
