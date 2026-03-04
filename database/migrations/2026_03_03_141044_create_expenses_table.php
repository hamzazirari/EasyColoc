<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colocation_id')->constrained('colocations');
            $table->foreignId('user_id')->constrained('users');
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->enum('category', ['loyer', 'courses', 'electricite', 'internet', 'autre']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};