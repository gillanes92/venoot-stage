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
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
use DatePeriod;
use Maatwebsite\Excel\Events\BeforeSheet;

use Illuminate\Support\Facades\Log;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class EventConfirmationExport implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithColumnFormatting, WithCharts, WithDrawings
{
    use Exportable;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->sent_mails = null;
        $this->opens = null;
        $this->bounced = null;
        $this->confirmed_at = null;
        $this->registered_at = null;
        $this->days = 0;
        $this->days1 = 0;
        $this->days2 = 0;

        Log::info('Started');

        /*$this->participants = $event->profile->participants_collection->with([
            'participations' => function ($query) use ($event) {
                $query->where('event_id', $event->id);
            },
            'participations.sent_mails',
        ])->get();*/

        $this->participants = DB::table('participations')
                  ->selectRaw("participations.id as participation_id,
                              participations.uuid,
                              participations.invitation_sent_at, participations.confirmed_status, participations.confirmed_sent_at,
                              participations.confirmed_at, participations.registered_at,
                              participants.*")
                  ->join('participants', 'participants.id', '=', 'participations.participant_id')
                  ->where('participations.event_id', $event->id)->get();

        $this->participants = $this->participants->transform(function ($participant) use ($event) {
          $participant = json_decode(json_encode($participant), true);
          $participant = array_merge($participant, json_decode($participant['data'], true));

          unset($participant['data']);
          return $participant;
        });

        $this->opens = DB::table('sent_emails')
                  ->selectRaw("sent_emails.recipient")
                  ->join('participations', 'sent_emails.participation_id', '=', 'participations.id')
                  ->where('participations.event_id', $event->id)
                  ->where('sent_emails.opens', '>', 0)
                  ->groupBy('sent_emails.recipient')->get();

        $this->bounced = DB::table('sent_emails')
                  ->selectRaw("sent_emails.recipient")
                  ->join('participations', 'sent_emails.participation_id', '=', 'participations.id')
                  ->where('participations.event_id', $event->id)
                  ->where('sent_emails.bounced', '=', true)
                  ->groupBy('sent_emails.recipient')->get();

        $this->fields = DB::table('databases')
                  ->selectRaw("databases.fields")
                  ->whereRaw("databases.id = (select database_id from profiles where id = (select profile_id from events where id = ?))", $event->id)->get();

        $this->fields = $this->fields->transform(function ($field) use ($event) {
          $field = json_decode(json_encode($field), true);
          $field = array_merge($field, json_decode($field['fields'], true));

          unset($field['fields']);
          return $field;
        });

        Log::info($this->participants);

        Log::debug('Participants Gotten');
    }

    public function title(): string
    {
        return 'EventoConfirmacion';
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

                $event->sheet->setCellValue('U1', 'Envíos');
                $event->sheet->setCellValue('X1', 'Confirmados');
                $event->sheet->setCellValue('AA1', 'Acreditados');

                Log::info('registerEvents');

                $this->sent_mails = DB::table('sent_emails')
                          ->selectRaw("sent_emails.*")
                          ->join('participations', 'sent_emails.participation_id', '=', 'participations.id')
                          ->where('participations.event_id', $this->event->id)->get();

                Log::debug('$this->sent_mails Gotten');

                $this->confirmed_at = DB::table('participations')
                          ->selectRaw("participations.id as participation_id,
                                      participations.uuid,
                                      participations.invitation_sent_at, participations.confirmed_status, participations.confirmed_sent_at,
                                      participations.confirmed_at, participations.registered_at,
                                      participants.*")
                          ->join('participants', 'participants.id', '=', 'participations.participant_id')
                          ->where('participations.event_id', $this->event->id)
                          ->where('participations.confirmed_status', '=', true)->get();

                Log::debug('$this->confirmed_at Gotten');

                $this->registered_at = DB::table('participations')
                          ->selectRaw("participations.id as participation_id,
                                      participations.uuid,
                                      participations.invitation_sent_at, participations.confirmed_status, participations.confirmed_sent_at,
                                      participations.confirmed_at, participations.registered_at,
                                      participants.*")
                          ->join('participants', 'participants.id', '=', 'participations.participant_id')
                          ->where('participations.event_id', $this->event->id)
                          ->where('participations.registered_at', '!=', null)->get();

                Log::debug('$this->registered_at Gotten');

                $sent_at = $this->sent_mails->groupBy(function ($email) {
                    return Carbon::parse($email->created_at)->format("Y-m-d");
                });

                Log::debug('$this->sent_at Gotten');

                $days = $sent_at->keys()->toArray();

                Log::debug('$this->days Gotten');

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

                Log::debug('165 Gotten');

                $confirmed_at = $this->confirmed_at->groupBy(function ($participant) {
                    return Carbon::parse($participant->confirmed_at)->format("Y-m-d");
                });

                $days = $confirmed_at->keys()->toArray();
                if (count($days) == 0) {
                    $this->days1 = 0;
                } else {
                    $min_date = new DateTime(min($days));
                    $max_date = new DateTime(max($days));
                    $max_date = $max_date->modify('1 day');

                    if ($min_date == $max_date) {
                        $event->sheet->setCellValue('Y1', $min_date->format('Y-m-d'));
                        $event->sheet->setCellValue('Z1', count($confirmed_at[$min_date->format('Y-m-d')] ?? []));
                        $this->days1 = 1;
                    } else {
                        $interval = DateInterval::createFromDateString('1 day');
                        $period = new DatePeriod($min_date, $interval, $max_date);

                        foreach ($period as $index => $day) {
                            $event->sheet->setCellValue('Y' . ($index + 1), $day->format('Y-m-d'));
                            $event->sheet->setCellValue('Z' . ($index + 1), count($confirmed_at[$day->format('Y-m-d')] ?? []));
                            $this->days1 = $index + 1;
                        }
                    }
                }

                Log::debug('195 Gotten');

                $registered_at = $this->registered_at->groupBy(function ($participant) {
                    return Carbon::parse($participant->registered_at)->format("Y-m-d");
                });

                $days = $registered_at->keys()->toArray();
                if (count($days) == 0) {
                    $this->days2 = 0;
                } else {
                    $min_date = new DateTime(min($days));
                    $max_date = new DateTime(max($days));
                    $max_date = $max_date->modify('1 day');

                    if ($min_date == $max_date) {
                        $event->sheet->setCellValue('AB1', $min_date->format('Y-m-d'));
                        $event->sheet->setCellValue('AC1', count($registered_at[$min_date->format('Y-m-d')] ?? []));
                        $this->days2 = 1;
                    } else {
                        $interval = DateInterval::createFromDateString('1 day');
                        $period = new DatePeriod($min_date, $interval, $max_date);

                        foreach ($period as $index => $day) {
                            $event->sheet->setCellValue('AB' . ($index + 1), $day->format('Y-m-d'));
                            $event->sheet->setCellValue('AC' . ($index + 1), count($registered_at[$day->format('Y-m-d')] ?? []));
                            $this->days2 = $index + 1;
                        }
                    }
                }

                Log::debug('225 Gotten');

            },

            AfterSheet::class    => function (AfterSheet $event) {

                Log::debug('AfterSheet');

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

                $event->sheet->styleCells('A1:H46', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                $event->sheet->styleCells('A48:N48', [
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

                $event->sheet->setCellValue('G10', '=B10/E7');
                $event->sheet->setCellValue('G11', '=B10/B7');

                $event->sheet->setCellValue('E16', '=C16/D16');
                $event->sheet->setCellValue('E17', '=C17/B7');
                $event->sheet->setCellValue('E18', '=C18/B7');
                $event->sheet->setCellValue('E19', '=C19/B7');

                Log::debug('AfterSheet Gotten');

            }
        ];
    }

    public function charts()
    {
        Log::debug('charts');

        $labels     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'EventoConfirmacion!$U$1', null, 1)];
        $categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'EventoConfirmacion!$V$1:$V$' . $this->days, null, $this->days)];
        $values     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'EventoConfirmacion!$W$1:$W$' . $this->days, null, $this->days)];

        $labels2     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'EventoConfirmacion!$X$1', null, 1)];
        $categories2 = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'EventoConfirmacion!$Y$1:$Y$' . $this->days1, null, $this->days1)];
        $values2     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'EventoConfirmacion!$Z$1:$Z$' . $this->days1, null, $this->days1)];

        $labels3     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'EventoConfirmacion!$AA$1', null, 1)];
        $categories3 = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'EventoConfirmacion!$AB$1:$AB$' . $this->days2, null, $this->days2)];
        $values3     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'EventoConfirmacion!$AC$1:$AC$' . $this->days2, null, $this->days2)];

        $layout = new Layout();

        // Show value on pie chart
        $layout->setShowVal(true);

        // Or show pct
        $layout->setShowPercent(true);

        $chart1 = new Chart(
            'chart',
            new Title('Envios, Confirmaciones y Aceptados'),
            new Legend(),
            new PlotArea($layout, [
                new DataSeries(DataSeries::TYPE_LINECHART, null, range(0, count($values) - 1), $labels, $categories, $values),
                new DataSeries(DataSeries::TYPE_LINECHART, null, range(0, count($values2) - 1), $labels2, $categories2, $values2),
                new DataSeries(DataSeries::TYPE_LINECHART, null, range(0, count($values3) - 1), $labels3, $categories3, $values3)
            ])
        );

        $chart1->setTopLeftPosition('B21');
        $chart1->setBottomRightPosition('G40');

        Log::debug('charts Gotten');

        return $chart1;
    }

    public function view(): View
    {
        return view('exports.event-confirmation', [
            'event' => $this->event,
            'participants' => $this->participants,
            'fields' => $this->fields[0],
            'sent_mails' => $this->sent_mails,
            'opens' => $this->opens,
            'bounced' => $this->bounced,
            'confirmed' => $this->confirmed_at,
            'registered' => $this->registered_at,
        ]);
    }
}
