<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_accounts')->onDelete('cascade');
            $table->string('file');
            $table->timestamp('posted_date')->useCurrent();
            $table->boolean('comment_enable')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('user_accounts');
            $table->foreignId('modified_by')->nullable()->constrained('user_accounts');
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
        Schema::dropIfExists('posts');
    }
}
