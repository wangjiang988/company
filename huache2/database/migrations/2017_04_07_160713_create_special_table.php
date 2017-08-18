<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_special_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string("file_name", 100)->default('')->comment('文件名称');
            $table->bigInteger('area_id')->defalut(0)->comment('城市id');
            $table->tinyInteger('area_level')->default(2)->comment('地区等级');
            $table->bigInteger('user_id')->defalut(0)->comment('用户id');
            $table->tinyInteger('country_car')->default(0)->comment('车俩出厂国家');
            $table->string("use_car", 30)->default('')->comment('车俩出厂国家');
            $table->tinyInteger('licence_user_type')->default(0)->comment('上牌车主身份类别(0本地，1其他)');
            $table->string("licence_other", 120)->default('')->comment('上牌其他信息');
            $table->bigInteger('file_url')->default(0)->comment('图片上传id');
            $table->tinyInteger('status')->default(1)->comment('审核状态');
            $table->tinyInteger('is_delete')->default(0)->comment('删除状态');
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
        Schema::dropIfExists('car_area_special_files');
    }
}
