<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('phoneno')->unique();
            $table->date('dob');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('firebase_id')->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->text('about')->nullable();
            $table->timestamps();
            $table->boolean('active')->default(true);  // Inherited from BaseModel
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_accounts');
    }
}