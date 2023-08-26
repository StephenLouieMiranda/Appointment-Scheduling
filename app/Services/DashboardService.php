<?php

namespace App\Services;

use Illuminate\Support\Collection;

class DashboardService
{
    // Sets the fill property of the chart
    public $fill = false;

    // Sets the tension property of the chart
    public $tension = 0.5;

    // Sets the border width property of the chart
    public $borderColor = 'rgb(75, 192, 192)';

    // Sets the border width property of the chart
    public $localDatasetLabel = 'On-site';


    /**
     * =============================== Noli me tangkudasai ===============================
     */
    private $dataSets = [];

    private $span = 'day';

    private $initialData = [];

    private $labels = [];

    private $pivotColumn = 'created_at';

    /**
     * Initialize the dashboard service
     * @param string $span The span of the chart
     * @param Collection $data The collective data without filters for dataset
     * @param string $pivotColumn The column to be used for labels and dataset
     */
    public function __construct(
        $span = 'day',
        Collection $data,
        $pivotColumn = 'created_at',
        $startDate,
        $endDate,
    ) {
        $this->span = $span;
        $this->initialData = $data;
        $this->pivotColumn = $pivotColumn;
        $this->generateLabels($pivotColumn, $startDate, $endDate);
    }

    /**
     * adds a dataset to the chart
     */
    public function addDataSet($data)
    {
        $dataSet = [];
        switch ($this->span) {
            case 'day':
                $dataSet = $this->parseDay($data);
                break;
            case 'month':
                // add parser for month
                break;
            default: break;
        }

        $this->dataSets[] = $dataSet;
    }

    /**
     * generates the final dataset chart
     */
    public function generateChart()
    {
        return [
            'labels' => $this->labels,
            'datasets' => $this->dataSets
        ];
    }

    /**
     * Parse the data for the day span
     */
    private function parseDay($data)
    {
        $dataSet = $data->groupBy($this->pivotColumn)->map(function ($item, $key) {
            return $item->count();
        });

        $keys = $dataSet->keys()->all();
        // if a label is missing in the dataset, add it with a value of 0
        foreach ($this->labels as $label) {
            if (!in_array($label, $keys)) {
                $dataSet->put($label, 0);
            }
        }

        $dataSet = [];
        foreach ($this->labels as $label) {
            $dataSet[$label] = $data->where($this->pivotColumn, $label)->count();
        }

        return [
            'label' => $this->localDatasetLabel,
            'fill' => $this->fill,
            'tension' => $this->tension,
            'borderColor' => $this->borderColor,
            'data' => array_values($dataSet)
        ];
    }

    private function generateLabels($labelColumn, $startDate, $endDate)
    {
        $dates = [];
        $start = strtotime($startDate);
        $end = strtotime($endDate);

        while ($start <= $end) {
            $dates[] = date('Y-m-d', $start);
            $start = strtotime("+1 day", $start);
        }

        $this->labels = $dates;
    }

    /**
     * LOCAL DATASET MODIFIERS
     */

    public function setLocalLabel($label)
    {
        $this->localDatasetLabel = $label;
        return $this;
    }

    public function setFill($fill)
    {
        $this->fill = $fill;
        return $this;
    }

    public function setTension($tension)
    {
        $this->tension = $tension;
        return $this;
    }

    public function setBorderColor($borderColor)
    {
        $this->borderColor = $borderColor;
        return $this;
    }
}