<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    public $table_name = 'companies';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 90)->default('')->comment('公司名称');
            $table->string('mobile', 20)->default('')->comment('公司电话');
            $table->string('logo')->default('')->comment('公司logo');
            $table->json('images')->nullable()->comment('公司图片');
            $table->string('address', 255)->default('')->comment('公司地址');
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
        Schema::dropIfExists($this->table_name);
    }
}
