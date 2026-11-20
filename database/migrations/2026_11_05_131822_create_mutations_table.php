
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_inventory_id')
                ->constrained('room_inventories')
                ->restrictOnDelete();

            $table->foreignId('from_room_id')
                ->constrained('rooms')
                ->restrictOnDelete();

            $table->foreignId('to_room_id')
                ->constrained('rooms')
                ->restrictOnDelete();

            $table->date('mutation_date');

            $table->unsignedInteger('quantity_good')->default(0);
            $table->unsignedInteger('quantity_minor')->default(0);
            $table->unsignedInteger('quantity_severe')->default(0);

            $table->text('notes')->nullable();

            $table->string('status', 30)->default('pending');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutations');
    }
};
