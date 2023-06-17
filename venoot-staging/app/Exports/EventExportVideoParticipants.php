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
use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use DateTime;
use DateInterval;
use DatePeriod;
use Maatwebsite\Excel\Events\BeforeSheet;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class EventExportVideoParticipants implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithDrawings //, WithColumnFormatting
{
    use Exportable;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->rowAmount = 0;
        $this->participant_chat_activity_times = $event->chat_activity_times()->with(['participant', 'activity'])->get()->groupBy('participation_id');

        // $chat_times_consolidated = [];
        // foreach ($participant_chat_activity_times as $participant_id => $chat_activity_times) {
        //     if (!Arr::exists($chat_times_consolidated, $participant_id)) {
        //         $chat_times_consolidated[$participant_id] = [];
        //     }

        //     foreach ($chat_activity_times as $chat_activity_time) {
        //         $this->rowAmount += 1;

        //         if ($chat_activity_time->activity_id == null) {
        //             $chat_activity_time->activity_id = 0;
        //         }

        //         if (!Arr::exists($chat_times_consolidated[$participant_id], $chat_activity_time->activity_id)) {
        //             $chat_times_consolidated[$participant_id][$chat_activity_time->activity_id] = [
        //                 'name' => $chat_activity_time->participant->data['name'],
        //                 'lastname' => $chat_activity_time->participant->data['lastname'],
        //                 'email' => $chat_activity_time->participant->data['email'],
        //                 'activity' =>  $chat_activity_time->activity_id == 0 ? "General" : $chat_activity_time->activity->name,
        //                 'last_seen' => null,
        //                 'time' => 0
        //             ];
        //         }

        //         $created_at = Carbon::parse($chat_activity_time->created_at);
        //         $updated_at = Carbon::parse($chat_activity_time->updated_at);

        //         $deltaTime = $created_at->diffInSeconds($updated_at);
        //         $chat_times_consolidated[$participant_id][$chat_activity_time->activity_id]['time'] += $deltaTime;
        //         $chat_times_consolidated[$participant_id][$chat_activity_time->activity_id]['last_seen'] = $updated_at;
        //     }
        // }

        // $this->chat_times_consolidated = $chat_times_consolidated;
    }

    public function title(): string
    {
        return 'Evento Video Participación';
    }

    // public function columnFormats(): array
    // {
    //     return [
    //         'E10:F' . ($this->rowAmount + 9) => NumberFormat::FORMAT_DATE_DATETIME,
    //     ];
    // }

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
        $drawing->setCoordinates('F5');

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
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

                $event->sheet->mergeCells('A1:C4');
                $event->sheet->mergeCells('D1:D4');
                $event->sheet->mergeCells('E1:E4');
                $event->sheet->mergeCells('F1:F4');
            }
        ];
    }

    public function view(): View
    {
        return view('exports.event-video-times', [
            'event' => $this->event,
            'participant_chat_activity_times' => $this->participant_chat_activity_times,
            // 'chat_times_consolidated' => $this->chat_times_consolidated,
        ]);
    }
}
