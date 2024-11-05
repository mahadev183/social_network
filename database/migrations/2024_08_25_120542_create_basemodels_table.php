<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasemodelsTable extends Migration
{
    public function up()
    {
        Schema::create('basemodels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('user_accounts');
            $table->foreignId('modified_by')->nullable()->constrained('user_accounts');
            $table->timestamps();  // This automatically creates `created_at` and `updated_at` columns
            $table->boolean('active')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('basemodels');
    }
}