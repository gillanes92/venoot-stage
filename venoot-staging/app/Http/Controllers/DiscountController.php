<?php

namespace App\Http\Controllers;

use App\Discount;
use App\DiscountKeyValue;
use App\Http\Requests\DiscountRequest;
use App\Participant;
use App\ParticipantTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return response()->json(['discounts' => $user->company->discounts], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscountRequest $request)
    {
        $company = Auth::user()->company;
        $discount = $company->discounts()->create($request->only(['name', 'trigger', 'trigger_value', 'trigger_quantity', 'type', 'type_quantity', 'quota']));
        if ($discount) {
            try {
                $discount->tickets()->sync(collect($request->tickets)->pluck('id'));

                if ($request->excelFile) {

                    $discount->discount_key_values()->delete();

                    $excel = Storage::disk('public')->path($request->excelFile);
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($excel);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = $worksheet->getHighestRow();

                    $values = [];
                    for ($row = 1; $row <= $highestRow; ++$row) {
                        $value = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                        if ($value) {
                            array_push($values, $row);
                            $discount->discount_key_values()->create(['value' => $value]);
                        }
                    }
                }

                return response()->json(['discounts' => $company->discounts], 200);
            } catch (\Exception $e) {
                return response()->json((array) $e, 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(DiscountRequest $request, Discount $discount)
    {
        $company = Auth::user()->company;
        try {
            $discount->update($request->only(['name', 'trigger', 'trigger_value', 'trigger_quantity', 'type', 'type_quantity', 'quota']));
            $discount->tickets()->sync(collect($request->tickets)->pluck('id'));

            if ($request->excelFile) {

                $discount->discount_key_values()->delete();

                $excel = Storage::disk('public')->path($request->excelFile);
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($excel);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();

                $values = [];
                for ($row = 1; $row <= $highestRow; ++$row) {
                    $value = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                    if ($value) {
                        array_push($values, $row);
                        $discount->discount_key_values()->create(['value' => $value]);
                    }
                }
            }
            return response()->json(['discounts' => $company->discounts], 200);
        } catch (\Exception $e) {
            return response()->json((array) $e, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $company = Auth::user()->company;
        try {
            $discount->delete();
            return response()->json(['discounts' => $company->discounts], 200);
        } catch (\Exception $e) {
            return response()->json((array) $e, 500);
        }
    }

    public function checkCupon(Discount $discount, Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:16'
        ]);

        $status = true;
        $reason = '';

        if ($discount->trigger_value != $request->value) {
            $status = false;
            $reason = 'mismatch';
        } else if ($discount->quota && $discount->quota <= $discount->participant_tickets()->count()) {
            $status = false;
            $reason = 'full';
        }

        return response()->json(['status' => $status, 'reason' => $reason], 200);
    }

    public function checkKeyValue(Discount $discount, Request $request)
    {
        $request->validate([
            'value' => 'required|string'
        ]);

        return response()->json(['status' => $discount->discount_key_values()->where('value', $request->value)->exists()], 200);
    }
}
