<?php

use App\Models\User;
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
        Schema::create('user_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->char('name')->nullable();
            $table->char('country')->nullable();
            $table->char('state')->nullable();
            $table->char('lat')->nullable();
            $table->char('lon')->nullable();
            $table->timestamps();
            $table->unique(['name', 'country', 'state', 'lat', 'lon']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cities');
    }
};
