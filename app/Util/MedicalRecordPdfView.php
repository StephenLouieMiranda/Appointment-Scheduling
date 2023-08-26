<?php

namespace App\Util;

use App\Contracts\PdfViewInterface;

class MedicalRecordPdfView implements PdfViewInterface
{
    private $view;

    private $medicalRecord;

    private $patient;

    private $doctor;

    /**
     * Required Data for an endorsement pdf to be generated
     * @param MedicalRecord $medicalRecord
     * @param Patient $patient
     * @param Doctor $doctor
     */
    public function __construct(
        $medicalRecord,
        $patient,
        $doctor,
    ) {
        $this->medicalRecord = $medicalRecord;
        $this->patient = $patient;
        $this->doctor = $doctor;
    }

    public function setView(string $view)
    {
        $this->view = $view;
        return $this;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function getData(): array
    {
        return [
            'medical_record' => $this->medicalRecord,
            'patient' => $this->patient,
            'doctor' => $this->doctor,
        ];
    }
}