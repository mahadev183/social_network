<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserAccountsTablePasswordLength extends Migration
{
    public function up()
    {
        Schema::table('user_accounts', function (Blueprint $table) {
            $table->string('password', 60)->change();  // Increase password length to 60
        });
    }

    public function down()
    {
        Schema::table('user_accounts', function (Blueprint $table) {
            $table->string('password')->change();  // Rollback if necessary
        });
    }
}
