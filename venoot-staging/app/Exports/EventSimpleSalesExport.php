<?php

namespace App\Exports;

use App\Event;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use \Maatwebsite\Excel\Sheet;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class EventSimpleSalesExport implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithColumnFormatting
{
    use Exportable;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->sold_tickets = $event->paid_participant_orders->flatMap(function ($participant_order) { return $participant_order->participant_tickets; });
    }

    public function title(): string
    {
        return 'Evento Ventas';
    }

    public function columnFormats(): array
    {
        return [
            'G10:G11' => NumberFormat::FORMAT_PERCENTAGE_00,
            'E16:E19' => NumberFormat::FORMAT_PERCENTAGE_00,
        ];
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

                $event->sheet->styleCells('F1:G4', [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);

                // $event->sheet->styleCells('H1:H4', [
                //     'borders' => [
                //         'outline' => [
                //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //             'color' => ['argb' => 'FF000000'],
                //         ],
                //     ]
                // ]);

                // $event->sheet->styleCells('A1:H46', [
                //     'borders' => [
                //         'outline' => [
                //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                //             'color' => ['argb' => 'FF000000'],
                //         ],
                //     ]
                // ]);

                // $event->sheet->styleCells('A48:N48', [
                //     'borders' => [
                //         'outline' => [
                //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //             'color' => ['argb' => 'FF000000'],
                //         ],
                //     ]
                // ]);

                $event->sheet->mergeCells('A1:C4');
                $event->sheet->mergeCells('D1:D4');
                $event->sheet->mergeCells('E1:E4');
                $event->sheet->mergeCells('F1:G4');
                // $event->sheet->mergeCells('H1:H4');
            }
        ];
    }

    public function view(): View
    {
        return view('exports.event-simple-sales', [
            'event' => $this->event,
            'sold_tickets' => $this->sold_tickets,
            'fields' => $this->event->profile->database->fields,
        ]);
    }
}