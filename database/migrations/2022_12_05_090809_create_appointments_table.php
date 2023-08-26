<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_code');
            $table->string('schedule_code');
            $table->string('patient_code');
            $table->string('user_code');
            $table->string('type')->nullable();
            $table->dateTime('date')->default(now()->format('Y-m-d'));
            $table->string('time')->default(now()->format('H:i:s'));
            $table->string('complaints')->nullable();
            $table->string('link')->default(null)->nullable();
            $table->string('status')->default('pending');
            $table->string('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['appointment_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
