<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsignersTable extends Migration
{
    public $table_name = 'consigners';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60)->comment('姓名');
            $table->string('mobile', 20)->comment('手机');
            $table->string('openid', 255)->default('')->comment('openid');
            $table->string('avatar', 255)->default('')->comment('头像');
            $table->string('nickname', 60)->default('')->comment('昵称');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('mobile');
            $table->index('openid');
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
