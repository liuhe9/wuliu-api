<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWxTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template_id_short', 30)->comment('模板消息编号');
            $table->string('template_name', 60)->comment('模板消息名称');
            $table->string('template_example', 255)->comment('模板消息示例');
            $table->string('template_id', 128)->comment('模板消息id');
            $table->timestamps();

            $table->unique('template_id');
            $table->unique('template_id_short');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wx_templates');
    }
}
