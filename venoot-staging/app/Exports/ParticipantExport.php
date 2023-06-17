<?php

namespace App\Exports;

use App\Participant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ParticipantExport implements FromView, WithDrawings
{
    use Exportable;

    public function __construct(Participant $participant)
    {
        $this->participant = $participant;

        $company = $participant->database->company;

        $this->databases = $company->databases;
        $this->events = $company->events;
    }

    public function drawings()
    {
        $drawing = [];
        try {
            $image_file = Storage::disk('local')->get('public/' . $this->participant->database->company->logo);
        } catch (FileNotFoundException $e) {
            $contents = file_get_contents(Storage::url($this->participant->database->company->logo));
            Storage::disk('local')->put('public/' . $this->participant->database->company->logo, $contents);
        }

        $drawing = new Drawing();
        $drawing->setName('image');
        $drawing->setDescription('image');
        $drawing->setPath(public_path('storage/' . $this->participant->database->company->logo));
        $drawing->setWidthAndHeight(180, 180);
        $drawing->setCoordinates('H1');

        return $drawing;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        return view('exports.participant', [
            'databases' => $this->databases, 'participant' => $this->participant, 'events' => $this->events
        ]);
    }
}
