<?php

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
        Schema::table('user_settings', function (Blueprint $table) {
            $table->boolean('rain_enabled')->default(false);
            $table->boolean('snow_enabled')->default(false);
            $table->boolean('uvi_enabled')->default(false);
            $table->integer('rain_value')->default(0);
            $table->integer('snow_value')->default(0);
            $table->integer('uvi_value')->default(0);

            $table->dropColumn('city_name');
            $table->dropColumn('city_country');
            $table->dropColumn('city_state');
            $table->dropColumn('city_lat');
            $table->dropColumn('city_lon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn('rain_enabled');
            $table->dropColumn('snow_enabled');
            $table->dropColumn('uvi_enabled');
            $table->dropColumn('rain_value');
            $table->dropColumn('snow_value');
            $table->dropColumn('uvi_value');

            $table->char('city_name')->nullable();
            $table->char('city_country')->nullable();
            $table->char('city_state')->nullable();
            $table->char('city_lat')->nullable();
            $table->char('city_lon')->nullable();
        });
    }
};
