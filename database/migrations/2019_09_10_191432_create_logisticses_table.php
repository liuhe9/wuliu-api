<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsesTable extends Migration
{
    public $table_name = 'logisticses';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->string('tracking_no', 60)->unique()->comment('物流单号');
            $table->text('product_desc')->comment('货品简介');
            $table->string('note', 255)->default('')->comment('客户备注');
            $table->json('images')->comment('发货图片');
            $table->json('finish_images')->comment('签收图片');
            $table->integer('consigner_id')->comment('发货人');
            $table->integer('manager_id')->default(0)->comment('管理员');
            $table->string('receiver_name', 255)->comment('收货人');
            $table->string('receiver_mobile', 20)->comment('收货人手机');
            $table->string('from_address', 255)->default('')->comment('发货地址');
            $table->string('from_gps', 60)->default('')->comment('发货gps点');
            $table->string('to_address', 255)->default('')->comment('收货地址');
            $table->string('to_gps', 60)->default('')->comment('收货gps点');
            $table->tinyInteger('status')->default(0)->comment('状态');
            $table->timestamps();
            $table->softDeletes();

            $table->index('consigner_id');
            $table->index('manager_id');
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
