<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module', function (Blueprint $table) {
            $table->bigIncrements('modid');
            $table->bigInteger('parent_id')->default(0);
            $table->string('mod_name');
            $table->string('mod_alias');
            $table->string('mod_permalink')->nullable();
            $table->string('mod_icon')->nullable();
            $table->string('mod_order')->nullable();
            $table->enum('published', ['y', 'n'])->default('n');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module');
    }
}
