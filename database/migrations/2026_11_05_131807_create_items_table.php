
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            // Nomor kode barang sesuai klasifikasi inventaris
            $table->string('code', 80)->index();

            $table->string('name', 180);
            $table->string('brand', 150)->nullable();
            $table->string('size', 100)->nullable();
            $table->string('material', 100)->nullable();
            $table->string('unit', 30)->default('unit');
            $table->text('specification')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
