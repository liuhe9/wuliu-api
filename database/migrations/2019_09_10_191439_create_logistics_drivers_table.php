<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsDriversTable extends Migration
{
    public $table_name = 'logistics_drivers';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->string('tracking_no', 60)->comment('物流单号');
            $table->integer('driver_id')->comment('司机');
            $table->string('license_plate', 30)->default('')->comment('车牌号');
            $table->string('latest_gps', 60)->default('')->comment('最后点位');
            $table->timestamps();

            $table->index('tracking_no');
            $table->index('driver_id');
            $table->index('latest_gps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table_name);
    }
}
