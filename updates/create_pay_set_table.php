<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaySetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pay_set')) {
            Schema::create('pay_set', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable()->comment('模板名称');
                $table->integer('pay_type')->nullable()->comment('支付方式类型');
                $table->boolean('status')->nullable()->comment('状态')->default('0');
                $table->json('config')->nullable()->comment('配置');
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
