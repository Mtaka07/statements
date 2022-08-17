<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailVerifiedAtToEmailVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_verification', function (Blueprint $table) {
            $table->dateTime('email_verified_at')->nullable()->after("expiration_datetime")->comment("有効期限");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_verification', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });
    }
}
