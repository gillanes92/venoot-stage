<?php

namespace App\Exports;

use App\Event;
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
use Maatwebsite\Excel\Events\BeforeSheet;
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
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Chart\Axis;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class EventTicketSaleExport implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithColumnFormatting, WithCharts, WithDrawings
{
    use Exportable;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->participant_tickets = [];
        $this->participants = $event->profile->participants_collection->with([
            'participations' => function ($query) use ($event) {
                $query->where('event_id', $event->id);
            },
            'participations.sent_mails',
        ])->get();

        $this->sent_mails = $this->participants->flatMap(function ($participant) {
            $participation = count($participant['participations']) > 0 ? $participant['participations'][0] : null;

            if (!$participation) return [];
            return $participation['sent_mails'];
        });

        $this->registered = $this->participants->filter(function ($participant) {
            $participation = count($participant['participations']) > 0 ? $participant['participations'][0] : null;
            if (!$participation) return false;
            return !!$participation->registered_at;
        });
    }

    public function title(): string
    {
        return 'EventoTicketSale';
    }

    public function columnFormats(): array
    {
        return [
            'B25' => NumberFormat::FORMAT_PERCENTAGE_00,
            'E30:E33' => NumberFormat::FORMAT_PERCENTAGE_00,
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
        $drawing->setCoordinates('H1');

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                foreach ($this->event->tickets as $index => $ticket) {
                    $event->sheet->setCellValue('U' . ($index + 1), $ticket->name);
                    $event->sheet->setCellValue('V' . ($index + 1), $ticket->quantity - $ticket->left);
                    $event->sheet->setCellValue('W' . ($index + 1), $ticket->quantity);
                }

                $this->participant_tickets = $this->event->participant_orders->flatMap(function ($participant_order) {
                    return $participant_order->participant_tickets;
                })->groupBy(function ($participant_ticket) {
                    return $participant_ticket->ticket->name;
                })->transform(function ($grouped_tickets, $ticket_name) {
                    return $grouped_tickets->groupBy(function ($participant_ticket) {
                        return Carbon::parse($participant_ticket->created_at_at)->format("Y-m-d");
                    });
                });

                $column_index = 24;
                foreach ($this->participant_tickets as $ticket_name => $dates) {
                    $first_column = Coordinate::stringFromColumnIndex($column_index);
                    $middle_column = Coordinate::stringFromColumnIndex($column_index + 1);
                    $last_column = Coordinate::stringFromColumnIndex($column_index + 2);
                    $column_index += 3;
                    $days = $dates->keys()->toArray();

                    $event->sheet->setCellValue($first_column . '1', $ticket_name);

                    if (count($days) > 0) {
                        $min_date = new DateTime(min($days));
                        $max_date = new DateTime(max($days));

                        if ($min_date == $max_date) {
                            $event->sheet->setCellValue($middle_column . '1', $min_date->format('Y-m-d'));
                            $event->sheet->setCellValue($last_column . '1', count($dates[$min_date->format('Y-m-d')] ?? []));
                        } else {
                            $interval = DateInterval::createFromDateString('1 day');
                            $period = new DatePeriod($min_date, $interval, $max_date);

                            foreach ($period as $index => $day) {
                                $event->sheet->setCellValue($middle_column . ($index + 1), $day->format('Y-m-d'));
                                $event->sheet->setCellValue($last_column . ($index + 1), count($dates[$day->format('Y-m-d')] ?? []));
                            }
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

                $event->sheet->styleCells('A1:H61', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->styleCells('A63:N63', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->mergeCells('A1:C4');
                $event->sheet->mergeCells('D1:D4');
                $event->sheet->mergeCells('E1:E4');
                $event->sheet->mergeCells('F1:G4');
                $event->sheet->mergeCells('H1:H4');

                $event->sheet->setCellValue('B25', '=B25/B22');

                $event->sheet->setCellValue('E30', '=C30/D30');
                $event->sheet->setCellValue('E31', '=C31/B22');
                $event->sheet->setCellValue('E32', '=C32/B22');
                $event->sheet->setCellValue('E33', '=C33/B22');
            }
        ];
    }

    public function charts()
    {

        $layout1 = new Layout();
        $layout1->setShowVal(true);
        $layout1->setShowPercent(true);

        $data_series = [];
        $initial_index = 24;
        foreach ($this->participant_tickets as $ticket_name => $dates) {
            $first_column = Coordinate::stringFromColumnIndex($initial_index);
            $middle_column = Coordinate::stringFromColumnIndex($initial_index + 1);
            $last_column = Coordinate::stringFromColumnIndex($initial_index + 2);
            $initial_index += 3;

            $labels     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'EventoTicketSale!$' . $first_column . '$1', null, 1)];
            $categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'EventoTicketSale!$' . $middle_column . '$1:$' . $middle_column . '$' . count($dates), null, count($dates))];
            $values     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'EventoTicketSale!$' . $last_column . '$1:$' . $last_column . '$' . count($dates), null, count($dates))];
            array_push(
                $data_series,
                new DataSeries(DataSeries::TYPE_LINECHART, null, range(0, count($values) - 1), $labels, $categories, $values)
            );
        }

        $chart1 = new Chart(
            'chart',
            new Title('Venta de Entradas'),
            new Legend(),
            new PlotArea($layout1, $data_series)
        );

        $chart1->setTopLeftPosition('B36');
        $chart1->setBottomRightPosition('H55');

        $layout2 = new Layout();
        $layout2->setShowVal(true);
        $layout2->setShowPercent(true);

        $labels2     = [];
        $categories2 = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'EventoTicketSale!$U$1:$U$' . $this->event->tickets->count(), null, $this->event->tickets->count())];
        $values2     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'EventoTicketSale!$V$1:$V$' . $this->event->tickets->count(), null, $this->event->tickets->count())];

        $series2 = new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_STACKED, range(0, count($values2) - 1), $labels2, $categories2, $values2);
        $series2->setPlotDirection(DataSeries::DIRECTION_HORIZONTAL);

        $xaxis = new Axis();
        $max_tickets = $this->event->tickets->max('quantity');
        $xaxis->setAxisOptionsProperties('low', null, null, null, null, null, 0, $max_tickets, null, null);

        $chart2 = new Chart(
            'chart',
            new Title('Entradas'),
            null,
            new PlotArea($layout2, [$series2]),
            true,
            DataSeries::EMPTY_AS_GAP,
            null,
            null,
            $xaxis
        );

        $chart2->setTopLeftPosition('B6');
        $chart2->setBottomRightPosition('H19');

        return [$chart1, $chart2];
    }

    public function view(): View
    {
        return view('exports.event-ticketsales', [
            'event' => $this->event,
            'participants' => $this->participants,
            'registered' => $this->registered,
            'sent_mails' => $this->sent_mails,
        ]);
    }
}
