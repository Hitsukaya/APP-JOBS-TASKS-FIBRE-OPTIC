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
        Schema::create('zeroloss_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('zeroloss_engineer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('zeroloss_job_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number', 32)->unique();
            //$table->enum('status', ['readyforservice', 'design', 'trrcomplete', 'a55', 'subducted','cabled'])->default('readyforservice');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('zeroloss_tasks');
    }
};
