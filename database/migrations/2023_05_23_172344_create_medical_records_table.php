<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->string('record_code');
            $table->string('patient_code');
            $table->string('doctor_id');
            $table->date('visit_date');
            $table->string('symptoms');
            $table->string('diagnosis');
            $table->longText('findings');
            $table->longText('medications');
            $table->longText('treatment_notes');
            $table->date('next_appointment')->nullable();
            $table->date('date_issued')->nullable();
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
        Schema::dropIfExists('medical_records');
    }
}
