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
        Schema::create('zeroloss_task_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zeroloss_task_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('zeroloss_job_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('zeroloss_build_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('zeroloss_brand_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('status', ['readyforservice', 'design', 'trrcomplete', 'a55', 'subducted','cabled'])->default('readyforservice');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zeroloss_task_items');
    }
};
