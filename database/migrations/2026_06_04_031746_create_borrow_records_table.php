<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
   public function up(): void
{
    Schema::create('borrow_records', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('book_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->date('borrow_date');

        $table->date('return_date')
            ->nullable();

        $table->string('status')
            ->default('borrowed');

        $table->timestamps();
    });
}

    
    public function down(): void
    {
        Schema::dropIfExists('borrow_records');
    }
};
