<?php

use App\Models\Category;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->float('price')->unsigned();
            $table->boolean('exists')->default(true);
            $table->integer('count')->default(0);
            
            //Forgin Kye
            // $table->foreignId('category_id');
            // $table->foreign('category_id')->on('categories')->references('id');

            $table->foreignId('category_id')->constrained()->restrictOnDelete();

            // $table->foreignIdFor(Category::class)->constrained();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
