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
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedBigInteger('bevy_id')->nullable()->unique()->after('id');
            $table->text('bevy_url')->nullable()->after('image');
            $table->dateTime('end_date')->nullable()->after('time');
            $table->string('location')->nullable()->after('end_date');
            $table->string('event_type')->nullable()->after('location');
            $table->text('tags')->nullable()->after('event_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['bevy_id', 'bevy_url', 'end_date', 'location', 'event_type', 'tags']);
        });
    }
};

