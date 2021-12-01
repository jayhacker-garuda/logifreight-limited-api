<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuickAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quick_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_umi');
            $table->string('package_type');
            $table->string('purchased_location');
            $table->decimal('cost',10,2);
            $table->decimal('weight');
            $table->string('tracking_number');
            $table->string('company_tracking_number')->nullable();
            $table->string('shipping_company');
            $table->enum('status', ['in_transit', 'delivered', 'processing'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quick_alerts');
    }
}
