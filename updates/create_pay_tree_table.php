<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayTreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pay_tree')) {
            Schema::create('pay_tree', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable()->comment('支付类型名称');
                $table->string('icon')->nullable()->comment('图标');
                $table->string('mark')->nullable()->comment('标识符');
                $table->integer('pay_type_id')->nullable()->comment('支付类型');
                $table->integer('parent_id')->nullable()->comment('父id');
                $table->integer('pay_set_id')->nullable()->comment('支付设置id');
                $table->integer('order')->nullable()->comment('排序');
                $table->boolean('status')->nullable()->comment('状态');
                $table->timestamps();
//                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExist('form_design');
    }
}
