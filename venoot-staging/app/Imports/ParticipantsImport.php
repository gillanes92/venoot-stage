<?php

namespace App\Imports;

use App\Participant;
use Maatwebsite\Excel\Concerns\ToModel;

class ParticipantsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Participant([
            //'data'
        ]);
    }
}
