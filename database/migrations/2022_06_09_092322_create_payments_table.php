<?php

use App\Models\Zeroloss\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('zeroloss_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Task::class);

            $table->string('reference');

            $table->string('provider');

            $table->string('method');

            $table->decimal('amount');

            $table->string('currency');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('zeroloss_payments');
    }
};
