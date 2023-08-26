<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_code')->index('schedule_code');
            $table->string('appointment_code')->index('appointment_code')->nullable();
            $table->string('doctor_id')->index('doctor_id');
            $table->string('day')->index('day');
            $table->string('start_time_slot')->index('start_time_slot');
            $table->string('end_time_slot')->index('end_time_slot');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
