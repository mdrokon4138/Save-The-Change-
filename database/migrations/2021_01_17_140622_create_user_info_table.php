<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('question_answer')->nullable();
            $table->string('security_questions')->nullable();
            $table->string('user_type')->nullable();
            $table->string('account_type')->nullable();
            $table->string('account_type_2nd')->nullable();
            $table->string('saving_time')->nullable();
            $table->string('bonus')->nullable();
            $table->string('referral')->nullable();
            $table->string('secret_code')->nullable();
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
        Schema::dropIfExists('user_info');
    }
}
