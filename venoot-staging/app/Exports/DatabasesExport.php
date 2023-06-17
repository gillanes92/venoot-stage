<?php

namespace App\Exports;

use App\Database;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DatabasesExport implements FromView, WithDrawings
{
    use Exportable;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function drawings()
    {
        $drawing = [];
        try {
            $image_file = Storage::disk('local')->get('public/' . $this->database->company->logo);
        } catch (FileNotFoundException $e) {
            $contents = file_get_contents(Storage::url($this->database->company->logo));
            Storage::disk('local')->put('public/' . $this->database->company->logo, $contents);
        }

        $drawing = new Drawing();
        $drawing->setName('image');
        $drawing->setDescription('image');
        $drawing->setPath(public_path('storage/' . $this->database->company->logo));
        $drawing->setWidthAndHeight(180, 180);
        $drawing->setCoordinates('F1');

        return $drawing;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        $events = $this->database->events;

        return view('exports.database', [
            'database' => $this->database,
            'events' => $events
        ]);
    }
}
