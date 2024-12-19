<?php

use App\Models\UserCity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hourly_weather', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UserCity::class, 'user_city_id')->constrained('user_cities')->onDelete('cascade');
            $table->timestamp('dt'); // Date and time of the weather data
            $table->float('temp')->nullable(); // Temperature
            $table->float('feels_like')->nullable(); // Feels like temperature
            $table->integer('pressure')->nullable(); // Atmospheric pressure
            $table->integer('humidity')->nullable(); // Humidity percentage
            $table->float('dew_point')->nullable(); // Dew point temperature
            $table->float('uvi')->nullable(); // UV index
            $table->integer('clouds')->nullable(); // Cloudiness percentage
            $table->integer('visibility')->nullable(); // Visibility in meters
            $table->float('wind_speed')->nullable(); // Wind speed
            $table->integer('wind_deg')->nullable(); // Wind direction in degrees
            $table->float('wind_gust')->nullable(); // Wind gust speed
            $table->float('pop')->default(0); // Probability of precipitation
            $table->float('rain_1h')->nullable(); // Rain volume for the last hour
            $table->float('snow_1h')->nullable(); // Snow volume for the last hour
            $table->timestamps();
            $table->unique(['user_city_id', 'dt']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hourly_weather');
    }
};
