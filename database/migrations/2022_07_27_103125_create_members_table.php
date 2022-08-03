<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('line_user_id', 255)->nullable()->commet('Line認証ID');
            $table->string("name", 255);
            $table->string("mail", 128);
            $table->string('password', 64);
            $table->string("api_token", 80)->unique()->nullable()->default(null)->comment('トークン');
            $table->tinyInteger("status")->unsigned()->comment("0:仮登録 1:通常 2:退会 3:停止");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
