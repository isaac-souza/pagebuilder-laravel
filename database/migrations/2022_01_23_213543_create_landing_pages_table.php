<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandingPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->index()->primary();
            $table->uuid('account_uuid')->index();

            $table->string('name');
            $table->string('slug');
            $table->string('type');

            $table->json('pages')->nullable();
            $table->json('draft')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('account_uuid')->references('uuid')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('landing_pages');
    }
}
