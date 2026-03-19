<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('license_plate_number')->nullable()->after('image_path');
            $table->string('registration_number')->nullable()->after('license_plate_number');
            $table->string('fuel_type')->nullable()->after('registration_number');
            $table->string('transmission_type')->nullable()->after('fuel_type');
            $table->string('mileage')->nullable()->after('transmission_type');
            $table->integer('seating_capacity')->nullable()->after('mileage');
            $table->string('engine_or_chassis_number')->nullable()->after('seating_capacity');
            $table->boolean('has_air_conditioning')->default(false)->after('engine_or_chassis_number');
            $table->boolean('has_bluetooth')->default(false)->after('has_air_conditioning');
            $table->text('description')->nullable()->after('has_bluetooth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'license_plate_number', 'registration_number', 'fuel_type', 'transmission_type',
                'mileage', 'seating_capacity', 'engine_or_chassis_number', 'has_air_conditioning',
                'has_bluetooth', 'description'
            ]);
        });
    }
};
