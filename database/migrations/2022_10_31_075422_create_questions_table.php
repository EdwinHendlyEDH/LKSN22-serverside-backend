<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id');
            $table->string('name');
            $table->enum('choice_type', ['short answer', 'paragraph', 'date', 'time', 'multiple choices', 'dropdown', 'checkboxes']);
            $table->string('choices')->nullable();
            $table->tinyInteger('is_required')->default(0);
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
        Schema::dropIfExists('questions');
    }
};
