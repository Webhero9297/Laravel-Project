<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSheetsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheet_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('script_cell');
            $table->string('status_cell');
            $table->string('case_sep');
            $table->string('fb_cell');
            $table->string('ignore_list');
            $table->string('additional_columns');
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
        Schema::dropIfExists('sheet_settings');
    }
}
