<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('url')->nullable();
            $table->string('model_id')->nullable();
            $table->string('model')->nullable();
            $table->string('style')->nullable();
            $table->string('location')->default('header'); // header, footer, sidebar, etc.
            $table->string('type')->nullable();  
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
