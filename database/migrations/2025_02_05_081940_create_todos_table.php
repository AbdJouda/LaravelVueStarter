<?php

use App\Enums\TodoPriorities;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title')->index();
            $table->text('description')->nullable()->index();
            $table->boolean('is_completed')->default(false);
            $table->date('due_date')->nullable();
            $table->enum('priority', TodoPriorities::getAllValues())->default(TodoPriorities::MEDIUM->value);
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
