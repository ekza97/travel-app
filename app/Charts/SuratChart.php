<?php
namespace App\Charts;

use App\Models\Agent;
use App\Models\Packet;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class SuratChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function buildPie()
    {
        $suratMasuk = Packet::count();
        $suratKeluar = Agent::count();
        $dataset = [
            $suratMasuk,
            $suratKeluar
        ];
        $labels = [
            'Surat Masuk',
            'Surat Keluar'
        ];
        return $this->chart->pieChart()
            ->setTitle('Grafik Surat Masuk & Surat Keluar')
            ->setSubtitle('Tahun '.date('Y'))
            ->setDataset($dataset)
            ->setLabels($labels);
    }

    public function buildRadial()
    {
        $suratMasuk = Packet::count();
        $suratKeluar = Agent::count();
        $dataset = [
            $suratMasuk,
            $suratKeluar
        ];
        $labels = [
            'Surat Masuk',
            'Surat Keluar'
        ];
        return $this->chart->radialChart()
            ->setTitle('Grafik Surat Masuk & Surat Keluar')
            ->setSubtitle('Tahun '.date('Y'))
            ->setDataset($dataset)
            ->setWidth(500)
            ->setHeight(360)
            ->setLabels($labels);
    }
}