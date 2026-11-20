
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_inventories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->restrictOnDelete();

            $table->foreignId('item_id')
                ->constrained('items')
                ->restrictOnDelete();

            // Tahun pembuatan atau pembelian
            $table->unsignedSmallInteger('acquisition_year')->nullable();

            // Kondisi barang sesuai kolom KIR
            $table->unsignedInteger('quantity_good')->default(0);
            $table->unsignedInteger('quantity_minor')->default(0);
            $table->unsignedInteger('quantity_severe')->default(0);

            // Keterangan perpindahan atau mutasi
            $table->text('mutation_notes')->nullable();

            $table->timestamps();

            $table->index(['room_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_inventories');
    }
};
