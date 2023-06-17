<?php

namespace App\Http\Controllers;

use App\Commune;
use App\Region;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public function communes ()
    {
        return Commune::all();
    }

    public function regions ()
    {
        return Region::all();
    }
}
