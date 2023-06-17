<?php

namespace App\Exports;

use App\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use \Maatwebsite\Excel\Sheet;
use Illuminate\Support\Carbon;
use DateTime;
use DateInterval;
use DatePeriod;
use Maatwebsite\Excel\Events\BeforeSheet;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class EventExport implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithColumnFormatting, WithCharts, WithDrawings //, ShouldQueue
{
    use Exportable;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->sent_mails = null;
        $this->days = 0;
        $this->days2 = 0;

        $this->participants = $event->profile->participants_collection->with([
            'participations' => function ($query) use ($event) {
                $query->where('event_id', $event->id);
            },
            'participations.sent_mails',
        ])->get();
    }

    public function title(): string
    {
        return 'Evento';
    }

    public function columnFormats(): array
    {
        return [
            'E12:E15' => NumberFormat::FORMAT_PERCENTAGE_00,
        ];
    }

    public function drawings()
    {
        if (!Storage::disk('local')->exists('public/' . $this->event->company->logo)) {
            $contents = file_get_contents(Storage::url($this->event->company->logo));
            Storage::disk('local')->put('public/' . $this->event->company->logo, $contents);
        }

        $drawing = new Drawing();
        $drawing->setName('image');
        $drawing->setDescription('image');
        $drawing->setPath(public_path('storage/' . $this->event->company->logo));
        $drawing->setWidthAndHeight(80, 80);
        $drawing->setCoordinates('F1');

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {

                $event->sheet->setCellValue('U1', 'Envios');
                $event->sheet->setCellValue('X1', 'Vistos');

                $this->sent_mails = $this->participants->flatMap(function ($participant) {
                    $participation = count($participant['participations']) > 0 ? $participant['participations'][0] : null;

                    if (!$participation) return [];
                    return $participation['sent_mails'];
                });

                $seen_at = $this->sent_mails->filter(function ($email) {
                    return $email->opens > 0;
                })->groupBy(function ($email) {
                    return Carbon::parse($email->updated_at)->format("Y-m-d");
                });

                $days = $seen_at->keys()->toArray();
                if (count($days) == 0) {
                    $this->days2 = 0;
                } else {
                    $min_date = new DateTime(min($days));
                    $max_date = new DateTime(max($days));
                    $max_date = $max_date->modify('1 day');

                    if ($min_date == $max_date) {
                        $event->sheet->setCellValue('Y1', $min_date->format('Y-m-d'));
                        $event->sheet->setCellValue('Z1', count($seen_at[$min_date->format('Y-m-d')] ?? []));
                        $this->days2 = 1;
                    } else {
                        $interval = DateInterval::createFromDateString('1 day');
                        $period = new DatePeriod($min_date, $interval, $max_date);

                        foreach ($period as $index => $day) {
                            $event->sheet->setCellValue('Y' . ($index + 1), $day->format('Y-m-d'));
                            $event->sheet->setCellValue('Z' . ($index + 1), count($seen_at[$day->format('Y-m-d')] ?? []));
                            $this->days2 = $index + 1;
                        }
                    }
                }

                $sent_at = $this->sent_mails->groupBy(function ($email) {
                    return Carbon::parse($email->created_at)->format("Y-m-d");
                });

                $days = $sent_at->keys()->toArray();
                if (count($days) == 0) {
                    $this->days = 0;
                } else {
                    $min_date = new DateTime(min($days));
                    $max_date = new DateTime(max($days));
                    $max_date = $max_date->modify('1 day');

                    if ($min_date == $max_date) {
                        $event->sheet->setCellValue('V1', $min_date->format('Y-m-d'));
                        $event->sheet->setCellValue('W1', count($sent_at[$min_date->format('Y-m-d')] ?? []));
                        $this->days = 1;
                    } else {
                        $interval = DateInterval::createFromDateString('1 day');
                        $period = new DatePeriod($min_date, $interval, $max_date);

                        foreach ($period as $index => $day) {
                            $event->sheet->setCellValue('V' . ($index + 1), $day->format('Y-m-d'));
                            $event->sheet->setCellValue('W' . ($index + 1), count($sent_at[$day->format('Y-m-d')] ?? []));
                            $this->days = $index + 1;
                        }
                    }
                }
            },

            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->styleCells('A1:C4', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->styleCells('D1:D4', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->styleCells('E1:E4', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->styleCells('F1:F4', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->styleCells('A1:F38', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->styleCells('B6:E7', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->mergeCells('A1:C4');
                $event->sheet->mergeCells('D1:D4');
                $event->sheet->mergeCells('E1:E4');
                $event->sheet->mergeCells('F1:F4');

                $event->sheet->setCellValue('E12', '=C12/D12');
                $event->sheet->setCellValue('E13', '=C13/C12');
                $event->sheet->setCellValue('E14', '=C14/C12');
                $event->sheet->setCellValue('E15', '=C15/C12');
            }
        ];
    }

    public function charts()
    {
        $labelsA    = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Evento!$U$1', null, 1)];
        $categoriesA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Evento!$V$1:$V$' . $this->days, null, $this->days)];
        $valuesA     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Evento!$W$1:$W$' . $this->days, null, $this->days)];

        $labelsB    = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Evento!$X$1', null, 1)];
        $categoriesB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Evento!$Y$1:$Y$' . $this->days2, null, $this->days2)];
        $valuesB     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Evento!$Z$1:$Z$' . $this->days2, null, $this->days2)];

        $layout = new Layout();

        // Show value on pie chart
        $layout->setShowVal(true);

        // Or show pct
        $layout->setShowPercent(true);

        $chartA = new Chart(
            'chart',
            new Title('Envios y Vistos'),
            new Legend(),
            new PlotArea($layout, [
                new DataSeries(DataSeries::TYPE_LINECHART, null, range(0, count($valuesA) - 1), $labelsA, $categoriesA, $valuesA),
                new DataSeries(DataSeries::TYPE_LINECHART, null, range(0, count($valuesB) - 1), $labelsB, $categoriesB, $valuesB)
            ])
        );

        $chartA->setTopLeftPosition('B18');
        $chartA->setBottomRightPosition('F37');

        return $chartA;
    }

    public function view(): View
    {
        return view('exports.event', [
            'event' => $this->event,
            'participants' => $this->participants,
            'sent_mails' => $this->sent_mails,
        ]);
    }
}
