<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use ZipArchive;

class SalesReportController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.reports.sales', $this->reportData($request));
    }

    public function export(Request $request)
    {
        $report = $this->reportData($request);
        $rows = [
            ['BSR sales report'],
            ['Report type', ucfirst($report['periodType'])],
            ['Period', $report['periodLabel']],
            ['Generated at', CarbonImmutable::now('Asia/Dhaka')->format('Y-m-d H:i:s').' Asia/Dhaka'],
            [],
            ['Summary'],
            ['Orders', (int) $report['summary']->order_count],
            ['Product subtotal (BDT)', (float) $report['summary']->product_subtotal],
            ['Shipping charges (BDT)', (float) $report['summary']->shipping_total],
            ['Gross sales (BDT)', (float) $report['summary']->sales_total],
            [],
        ];

        if ($report['periodType'] === 'daily') {
            $rows[] = ['Order number', 'Order time', 'Customer', 'Phone', 'District', 'Area', 'Product subtotal (BDT)', 'Shipping (BDT)', 'Total (BDT)', 'Status'];
            foreach ($report['rows'] as $order) {
                $rows[] = [
                    $order->order_number,
                    $order->created_at->timezone('Asia/Dhaka')->format('Y-m-d H:i:s'),
                    $order->customer_name,
                    $order->phone,
                    $order->district,
                    $order->area,
                    (float) $order->subtotal,
                    (float) $order->delivery_charge,
                    (float) $order->total,
                    ucfirst($order->status),
                ];
            }
        } else {
            $rows[] = [$report['periodType'] === 'monthly' ? 'Date' : 'Month', 'Orders', 'Product subtotal (BDT)', 'Shipping (BDT)', 'Gross sales (BDT)'];
            foreach ($report['rows'] as $row) {
                $rows[] = [
                    $row->report_period,
                    (int) $row->order_count,
                    (float) $row->product_subtotal,
                    (float) $row->shipping_total,
                    (float) $row->sales_total,
                ];
            }
        }

        $fileName = 'bsr-sales-'.$report['periodType'].'-'.$report['filePeriod'].'.xlsx';
        $path = tempnam(sys_get_temp_dir(), 'bsr-sales-');
        if ($path === false) {
            abort(500, 'Unable to prepare the sales report download.');
        }
        $this->writeWorkbook($path, $rows);

        return response()->streamDownload(function () use ($path) {
            readfile($path);
            @unlink($path);
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function reportData(Request $request): array
    {
        $validated = $request->validate([
            'period' => ['nullable', Rule::in(['daily', 'monthly', 'yearly'])],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'month' => ['nullable', 'date_format:Y-m'],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        $periodType = $validated['period'] ?? 'daily';
        $now = CarbonImmutable::now('Asia/Dhaka');
        [$start, $end, $periodLabel, $filePeriod] = match ($periodType) {
            'monthly' => $this->monthRange($validated['month'] ?? $now->format('Y-m')),
            'yearly' => $this->yearRange((int) ($validated['year'] ?? $now->year)),
            default => $this->dayRange($validated['date'] ?? $now->format('Y-m-d')),
        };

        $fromUtc = $start->setTimezone('UTC')->format('Y-m-d H:i:s');
        $toUtc = $end->setTimezone('UTC')->format('Y-m-d H:i:s');
        $orders = Order::query()
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$fromUtc, $toUtc]);

        $summary = (clone $orders)->selectRaw(
            'COUNT(*) AS order_count, COALESCE(SUM(subtotal), 0) AS product_subtotal, COALESCE(SUM(delivery_charge), 0) AS shipping_total, COALESCE(SUM(total), 0) AS sales_total'
        )->first();

        if ($periodType === 'daily') {
            $rows = (clone $orders)->orderBy('created_at')->get();
        } elseif ($periodType === 'monthly') {
            $rows = (clone $orders)->selectRaw(
                'DATE(DATE_ADD(created_at, INTERVAL 6 HOUR)) AS report_period, COUNT(*) AS order_count, SUM(subtotal) AS product_subtotal, SUM(delivery_charge) AS shipping_total, SUM(total) AS sales_total'
            )->groupByRaw('DATE(DATE_ADD(created_at, INTERVAL 6 HOUR))')->orderBy('report_period')->get();
        } else {
            $rows = (clone $orders)->selectRaw(
                "DATE_FORMAT(DATE_ADD(created_at, INTERVAL 6 HOUR), '%Y-%m') AS report_period, COUNT(*) AS order_count, SUM(subtotal) AS product_subtotal, SUM(delivery_charge) AS shipping_total, SUM(total) AS sales_total"
            )->groupByRaw("DATE_FORMAT(DATE_ADD(created_at, INTERVAL 6 HOUR), '%Y-%m')")->orderBy('report_period')->get();
        }

        return compact('periodType', 'periodLabel', 'filePeriod', 'summary', 'rows') + [
            'dateValue' => $periodType === 'daily' ? $filePeriod : $now->format('Y-m-d'),
            'monthValue' => $periodType === 'monthly' ? $filePeriod : $now->format('Y-m'),
            'yearValue' => $periodType === 'yearly' ? $filePeriod : (string) $now->year,
        ];
    }

    private function dayRange(string $date): array
    {
        $start = CarbonImmutable::createFromFormat('!Y-m-d', $date, 'Asia/Dhaka');

        return [$start, $start->endOfDay(), $start->format('d M Y'), $start->format('Y-m-d')];
    }

    private function monthRange(string $month): array
    {
        $start = CarbonImmutable::createFromFormat('!Y-m', $month, 'Asia/Dhaka')->startOfMonth();

        return [$start, $start->endOfMonth(), $start->format('F Y'), $start->format('Y-m')];
    }

    private function yearRange(int $year): array
    {
        $start = CarbonImmutable::create($year, 1, 1, 0, 0, 0, 'Asia/Dhaka');

        return [$start, $start->endOfYear(), (string) $year, (string) $year];
    }

    private function writeWorkbook(string $path, array $rows): void
    {
        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::OVERWRITE) !== true) {
            @unlink($path);
            abort(500, 'Unable to create the Excel report.');
        }
        $worksheetRows = '';

        foreach ($rows as $rowIndex => $row) {
            $cells = '';
            foreach (array_values($row) as $columnIndex => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                $reference = $this->columnName($columnIndex + 1).($rowIndex + 1);
                if (is_int($value) || is_float($value)) {
                    $cells .= '<c r="'.$reference.'"><v>'.$value.'</v></c>';
                } else {
                    $text = htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
                    $cells .= '<c r="'.$reference.'" t="inlineStr"><is><t xml:space="preserve">'.$text.'</t></is></c>';
                }
            }
            $worksheetRows .= '<row r="'.($rowIndex + 1).'">'.$cells.'</row>';
        }

        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Sales report" sheetId="1" r:id="rId1"/></sheets></workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>');
        $zip->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>'.$worksheetRows.'</sheetData></worksheet>');
        $zip->close();
    }

    private function columnName(int $number): string
    {
        $name = '';
        while ($number > 0) {
            $number--;
            $name = chr(65 + ($number % 26)).$name;
            $number = intdiv($number, 26);
        }

        return $name;
    }
}
