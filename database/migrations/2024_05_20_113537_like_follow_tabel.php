<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow_tabel', function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedBigInteger("current_user_id");
            $table->unsignedBigInteger("targeted_user_id");
            $table->timestamps();

            $table->foreign('current_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('targeted_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['current_user_id', 'targeted_user_id']); // Ensure unique follow relationships
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_tabel');
    }
};

