<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId("website_id")->constrained('websites')->onUpdate('cascade')->onDelete('restrict');
            $table->integer('attivita_id');
            $table->integer('allegato_id')->nullable();
            $table->text('extra');
            $table->integer('published_admin_id');
            $table->integer('status');
            $table->string('message')->nullable();
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
        Schema::dropIfExists('request_shares');
    }
}
