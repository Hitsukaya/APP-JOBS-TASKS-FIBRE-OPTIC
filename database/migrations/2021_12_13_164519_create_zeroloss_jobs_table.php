<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zeroloss_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zeroloss_brand_id')->nullable()->constrained()->nullOnDelete();
            //$table->enum('status', ['readyforservice', 'design', 'trrcomplete', 'a55', 'subducted','cabled'])->default('readyforservice');
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->string('document')->nullable(); //Document PDF
            $table->boolean('featured')->default(false);
            $table->boolean('is_visible')->default(false);
            $table->boolean('backorder')->default(false);
            $table->date('published_at')->nullable();
            $table->string('seo_title', 60)->nullable();
            $table->string('seo_description', 160)->nullable();
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
        Schema::dropIfExists('zeroloss_jobs');
    }
};
