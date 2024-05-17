<?php

use App\Models\Zeroloss\Engineer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::dropIfExists('zeroloss_reviews');

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Engineer::class)->nullable()->constrained('zeroloss_engineers')->cascadeOnDelete();
            $table->morphs('commentable');
            $table->text('title')->nullable();
            $table->text('content')->nullable();
            $table->boolean('is_visible')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
