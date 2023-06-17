<?php

namespace App\Exports;

use App\Database;
use App\Participant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class VerifyMailExport implements FromCollection
{
    use Exportable;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function collection()
    {
        $participants = $this->database->participants()->where('blocked', false)->where('allow_mailing', true)->get(); //->where('verified', false);
        return $participants->map(function (Participant $participant) {
            return [$participant->data['email']];
        });
    }
}
