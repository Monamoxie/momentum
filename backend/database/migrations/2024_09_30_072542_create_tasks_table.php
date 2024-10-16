<?php

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->string('name');
            $table->string('label')->nullable();
            $table->string('priority')->nullable()->default(TaskPriorityEnum::LOW->value);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->nullable()->default(TaskStatusEnum::PENDING->value);

            $table->foreign('parent_id')
                ->references('id')
                ->on('tasks')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
