<?php

namespace App\Http\Controllers;

use App\Constant;

use Illuminate\Http\Request;

class ConstantController extends Controller
{
    public function show($name)
    {
        $constant = Constant::where('name', $name)->first();
        if ($constant) {
            return response()->json([$name => $constant->object], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }
}
