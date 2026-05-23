<?php

namespace App\Services;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanExcelExporter
{
    private string $startDate;
    private string $endDate;
    private array $revenue;
    private array $topProducts;

    public function __construct(
        string $startDate,
        string $endDate,
        array $revenue,
        array $topProducts
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->revenue = $revenue;
        $this->topProducts = $topProducts;
    }

    public function download(): StreamedResponse
    {
        $fileName = 'Laporan_Keuangan_' . $this->startDate . '_' . $this->endDate . '.xls';

        return response()->streamDownload(function () {
            echo $this->buildXml();
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private function buildXml(): string
    {
        $periodLabel = Carbon::parse($this->startDate)->translatedFormat('d F Y')
            . ' — ' . Carbon::parse($this->endDate)->translatedFormat('d F Y');
        $generatedAt = now()->translatedFormat('d F Y, H:i') . ' WIB';
        $exportedBy = session('user.name', 'Administrator');

        $totalRevenue = collect($this->revenue)->sum(fn ($r) => (float) ($r['total_revenue'] ?? 0));
        $totalOrders = collect($this->revenue)->sum(fn ($r) => (int) ($r['total_orders'] ?? 0));
        $activeDays = count($this->revenue);
        $avgDaily = $activeDays > 0 ? $totalRevenue / $activeDays : 0;
        $topProductRevenue = collect($this->topProducts)->sum(fn ($p) => (float) ($p['total_revenue'] ?? 0));
        $topProductSold = collect($this->topProducts)->sum(fn ($p) => (int) ($p['total_sold'] ?? 0));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
        $xml .= ' xmlns:o="urn:schemas-microsoft-com:office:office"';
        $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel"';
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";

        $xml .= $this->styles();

        // Sheet 1: Ringkasan Keuangan
        $xml .= '<Worksheet ss:Name="Ringkasan">';
        $xml .= '<Table>';
        $xml .= $this->colWidths([280, 220, 220]);
        $xml .= $this->row(['JAYA MANDIRI — DIGITAL PRINTING'], 'Title');
        $xml .= $this->row(['LAPORAN KEUANGAN'], 'Subtitle');
        $xml .= $this->row(['Periode: ' . $periodLabel], 'Meta');
        $xml .= $this->row(['Diekspor: ' . $generatedAt], 'Meta');
        $xml .= $this->row(['Oleh: ' . $exportedBy], 'Meta');
        $xml .= $this->emptyRow();
        $xml .= $this->row(['RINGKASAN KEUANGAN'], 'Section');
        $xml .= $this->row(['Indikator', 'Nilai', 'Keterangan'], 'Header');
        $xml .= $this->row(['Total Pendapatan', $totalRevenue, 'Rp (periode filter)'], 'Money', [1]);
        $xml .= $this->row(['Total Pesanan', $totalOrders, 'Jumlah transaksi selesai'], 'Data');
        $xml .= $this->row(['Hari Aktif', $activeDays, 'Hari dengan transaksi'], 'Data');
        $xml .= $this->row(['Rata-rata / Hari', round($avgDaily), 'Rp per hari aktif'], 'Money', [1]);
        $xml .= $this->emptyRow();
        $xml .= $this->row(['KONTRIBUSI PRODUK (TOP 10)'], 'Section');
        $xml .= $this->row(['Total Omzet Top Produk', $topProductRevenue, 'Rp dari 10 produk terlaris'], 'Money', [1]);
        $xml .= $this->row(['Total Terjual', $topProductSold, 'Pcs dari 10 produk terlaris'], 'Data');
        $xml .= '</Table></Worksheet>';

        // Sheet 2: Pendapatan Harian
        $xml .= '<Worksheet ss:Name="Pendapatan Harian">';
        $xml .= '<Table>';
        $xml .= $this->colWidths([120, 140, 180]);
        $xml .= $this->row(['PENDAPATAN HARIAN — ' . $periodLabel], 'Section');
        $xml .= $this->emptyRow();
        $xml .= $this->row(['Tanggal', 'Jumlah Pesanan', 'Total Pendapatan (Rp)'], 'Header');
        foreach ($this->revenue as $row) {
            $xml .= $this->row([
                $this->formatDate($row['date'] ?? ''),
                (int) ($row['total_orders'] ?? 0),
                (float) ($row['total_revenue'] ?? 0),
            ], 'Data', [2]);
        }
        if (count($this->revenue) > 0) {
            $xml .= $this->row(['TOTAL KESELURUHAN', $totalOrders, $totalRevenue], 'Total', [2]);
        }
        $xml .= '</Table></Worksheet>';

        // Sheet 3: Produk Terlaris (omzet)
        $xml .= '<Worksheet ss:Name="Produk Terlaris">';
        $xml .= '<Table>';
        $xml .= $this->colWidths([50, 280, 120, 160]);
        $xml .= $this->row(['10 PRODUK TERLARIS — OMZET'], 'Section');
        $xml .= $this->emptyRow();
        $xml .= $this->row(['#', 'Nama Produk', 'Terjual (pcs)', 'Total Omzet (Rp)'], 'Header');
        foreach ($this->topProducts as $i => $prod) {
            $xml .= $this->row([
                $i + 1,
                $prod['product_name'] ?? '-',
                (int) ($prod['total_sold'] ?? 0),
                (float) ($prod['total_revenue'] ?? 0),
            ], 'Data', [3]);
        }
        if (count($this->topProducts) > 0) {
            $xml .= $this->row(['TOTAL', $topProductSold, $topProductRevenue], 'Total', [2]);
        }
        $xml .= '</Table></Worksheet>';

        $xml .= '</Workbook>';

        return $xml;
    }

    private function styles(): string
    {
        return <<<'STYLES'
<Styles>
    <Style ss:ID="Title">
        <Font ss:Bold="1" ss:Size="16" ss:Color="#1E293B"/>
    </Style>
    <Style ss:ID="Subtitle">
        <Font ss:Bold="1" ss:Size="12" ss:Color="#2563EB"/>
    </Style>
    <Style ss:ID="Meta">
        <Font ss:Size="10" ss:Color="#64748B"/>
    </Style>
    <Style ss:ID="Section">
        <Font ss:Bold="1" ss:Size="11" ss:Color="#FFFFFF"/>
        <Interior ss:Color="#1E293B" ss:Pattern="Solid"/>
        <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
    </Style>
    <Style ss:ID="Header">
        <Font ss:Bold="1" ss:Size="10" ss:Color="#FFFFFF"/>
        <Interior ss:Color="#2563EB" ss:Pattern="Solid"/>
        <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
        <Borders>
            <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1D4ED8"/>
        </Borders>
    </Style>
    <Style ss:ID="Data">
        <Font ss:Size="10" ss:Color="#334155"/>
        <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
        <Borders>
            <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/>
            <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/>
            <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/>
        </Borders>
        <Alignment ss:Vertical="Center" ss:WrapText="1"/>
    </Style>
    <Style ss:ID="Money">
        <NumberFormat ss:Format="#,##0"/>
        <Font ss:Size="10" ss:Color="#059669" ss:Bold="1"/>
        <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
        <Borders>
            <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/>
        </Borders>
        <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
    </Style>
    <Style ss:ID="Total">
        <Font ss:Bold="1" ss:Size="10" ss:Color="#FFFFFF"/>
        <Interior ss:Color="#0F172A" ss:Pattern="Solid"/>
        <Borders>
            <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#0F172A"/>
        </Borders>
        <Alignment ss:Vertical="Center"/>
    </Style>
</Styles>
STYLES;
    }

    private function colWidths(array $widths): string
    {
        $xml = '';
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>';
        }
        return $xml;
    }

    private function emptyRow(): string
    {
        return '<Row ss:Height="12"><Cell><Data ss:Type="String"></Data></Cell></Row>';
    }

    private function row(array $values, string $styleId, array $moneyCols = []): string
    {
        $xml = '<Row ss:Height="22">';
        foreach ($values as $idx => $value) {
            $style = in_array($idx, $moneyCols, true) ? 'Money' : $styleId;
            if ($styleId === 'Total' && $idx > 0 && is_numeric($value)) {
                $style = 'Total';
            }
            $xml .= $this->cell($value, $style);
        }
        $xml .= '</Row>';
        return $xml;
    }

    private function cell(mixed $value, string $styleId): string
    {
        $style = ' ss:StyleID="' . $styleId . '"';
        if (is_numeric($value) && $styleId !== 'Meta') {
            $num = str_contains((string) $value, '.') ? (float) $value : (int) $value;
            return '<Cell' . $style . '><Data ss:Type="Number">' . $num . '</Data></Cell>';
        }
        return '<Cell' . $style . '><Data ss:Type="String">' . $this->escape((string) $value) . '</Data></Cell>';
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function formatDate(?string $date): string
    {
        if (!$date) {
            return '-';
        }
        try {
            return Carbon::parse($date)->translatedFormat('d M Y');
        } catch (\Exception) {
            return $date;
        }
    }
}
