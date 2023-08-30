<?php
namespace App\Charts;

use App\Models\Agent;
use App\Models\Jamaah;
use App\Models\Packet;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class JamaahChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function buildPie()
    {
        $jamaahUmroh = Jamaah::whereHas('categories',function($query){
            $query->where('name','Umroh');
        })->count();
        $jamaahHaji = Jamaah::whereHas('categories',function($query){
            $query->where('name','Haji');
        })->count();
        $dataset = [
            $jamaahUmroh,
            $jamaahHaji
        ];
        $labels = [
            'Jamaah Umroh',
            'Jamaah Haji'
        ];
        return $this->chart->pieChart()
            ->setTitle('Grafik Jamaah')
            // ->setSubtitle('Tahun '.date('Y'))
            ->setDataset($dataset)
            ->setLabels($labels);
    }

    public function buildRadial()
    {
        $jamaahUmroh = Jamaah::whereHas('categories',function($query){
            $query->where('name','Umroh');
        })->count();
        $jamaahHaji = Jamaah::whereHas('categories',function($query){
            $query->where('name','Haji');
        })->count();
        $dataset = [
            $jamaahUmroh,
            $jamaahHaji
        ];
        $labels = [
            'Jamaah Umroh',
            'Jamaah Haji'
        ];
        return $this->chart->radialChart()
            ->setTitle('Grafik Jamaah')
            // ->setSubtitle('Tahun '.date('Y'))
            ->setDataset($dataset)
            ->setWidth(500)
            ->setHeight(360)
            ->setLabels($labels);
    }
}