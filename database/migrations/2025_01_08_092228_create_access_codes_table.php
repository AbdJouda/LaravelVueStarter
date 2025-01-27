<?php

use App\Models\AccessCode;
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
        Schema::create('access_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('code');
            $table->tinyInteger('target')->default(AccessCode::PASSWORD_RESET_CODE);
            $table->uuidMorphs('verifiable');
            $table->timestamp('expires_at');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_codes');
    }
};
