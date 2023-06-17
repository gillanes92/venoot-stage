<?php

namespace App\Exports;

use App\Event;
use App\Poll;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
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

class EventPollExport implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithColumnFormatting, WithCharts, WithDrawings
{
    use Exportable;

    public function __construct(Event $event, Poll $poll)
    {
        $this->event = $event;
        $this->poll = $poll;
        $this->period = null;
        $this->questions = 0;
    }

    public function title(): string
    {
        return 'EventoEncuesta';
    }

    public function columnFormats(): array
    {
        return [
            'G10:G11' => NumberFormat::FORMAT_PERCENTAGE_00,
            'E16:E19' => NumberFormat::FORMAT_PERCENTAGE_00,
        ];
    }

    public function drawings()
    {
        $drawing = [];

        if (!Storage::disk('local')->exists('public/' . $this->event->company->logo)) {
            $contents = file_get_contents(Storage::url($this->event->company->logo));
            Storage::disk('local')->put('public/' . $this->event->company->logo, $contents);
        }

        $drawing = new Drawing();
        $drawing->setName('image');
        $drawing->setDescription('image');
        $drawing->setPath(public_path('storage/' . $this->event->company->logo));
        $drawing->setWidthAndHeight(80, 80);
        $drawing->setCoordinates('H1');

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->questions = 0;
                $this->titles = [];
                $this->alternatives = [];
                $startingColumn = 12;
                $startingRow = 2;
                $i = 0;

                foreach ($this->poll->questions as $question) {
                    if ($question->type == 2) continue;
                    $this->questions++;
                    $this->titles[$i] = $question->question;
                    $event->sheet->setCellValueByColumnAndRow($startingColumn, $startingRow, $question->question);

                    $offset = 1;
                    $this->alternatives[$i] = $question->alternatives->count();
                    foreach ($question->alternatives as $alternativa) {
                        $event->sheet->setCellValueByColumnAndRow($startingColumn, $startingRow + $offset, $alternativa->alternative);

                        if ($question->type == 0) {
                            $event->sheet->setCellValueByColumnAndRow($startingColumn + 1, $startingRow + $offset, $question->answers()->where('alternative_id', $alternativa->id)->count());
                        } else if ($question->type == 1) {
                            $event->sheet->setCellValueByColumnAndRow($startingColumn + 1, $startingRow + $offset, $question->answers()->whereJsonContains('alternative_ids', $alternativa->id)->count());
                        }

                        $offset++;
                    }

                    $startingColumn += 2;
                    $i += 1;
                }
            },

            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->styleCells('A1:B4', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->styleCells('C1:C4', [
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

                $event->sheet->styleCells('F1:G4', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->styleCells('H1:H4', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->styleCells('A1:H20', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->mergeCells('A1:B4');
                $event->sheet->mergeCells('C1:C4');
                $event->sheet->mergeCells('D1:D4');
                $event->sheet->mergeCells('E1:E4');
                $event->sheet->mergeCells('F1:G4');
                $event->sheet->mergeCells('H1:H4');


                $event->sheet->setCellValue('E15', '=C15/D15');
                $event->sheet->setCellValue('E16', '=C16/C15');
                $event->sheet->setCellValue('E17', '=C17/C15');
                $event->sheet->setCellValue('E18', '=C18/C15');
            }
        ];
    }

    public function charts()
    {
        $columnChart = "A";
        $rowChart = 22;
        $column = "L";
        $charts = [];

        for ($i = 0; $i < $this->questions; $i++) {

            $labels     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'EventoEncuesta!$' . $column . '$2', null, 1)];
            $categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'EventoEncuesta!$' . $column . '$3:$' . $column . '$' . ($this->alternatives[$i] + 2), null, $this->alternatives[$i])];

            ++$column;

            $values     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'EventoEncuesta!$' . $column . '$3:$' . $column . '$' . ($this->alternatives[$i] + 2), null, $this->alternatives[$i])];

            ++$column;

            $layout = new Layout();

            // Show value on pie chart
            $layout->setShowVal(true);

            // Or show pct
            $layout->setShowPercent(true);

            $chart1 = new Chart(
                'chart',
                new Title($this->titles[$i]),
                new Legend(),
                new PlotArea($layout, [
                    new DataSeries(DataSeries::TYPE_PIECHART, null, range(0, count($values) - 1), $labels, $categories, $values)
                ])
            );

            $chart1->setTopLeftPosition($columnChart . $rowChart);
            ++$columnChart;
            ++$columnChart;
            $chart1->setBottomRightPosition($columnChart . ($rowChart + 20));

            $charts[$i] = $chart1;

            ++$columnChart;

            if ((($i + 1) % 3) == 0) {
                $columnChart = 'A';
                $rowChart += 21;
            }
        }

        $this->endRowChart = $rowChart + 20;

        return $charts;
    }

    public function view(): View
    {
        return view('exports.event-poll', [
            'event' => $this->event,
            'poll' => $this->poll,
            'endRowChart' => $this->endRowChart,
        ]);
    }
}
