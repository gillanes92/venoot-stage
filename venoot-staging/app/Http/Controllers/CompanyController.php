<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the response with limited informacion
     */

    public function indexLimited() {

        $companies = Company::all()->map(function($item, $key) {
            return ['id' => $item->id, 'name' => $item->social_reason];
        });
        return response()->json(['companies' => $companies], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $request->validated();

        $company = Company::create([
            'social_reason' => $request->social_reason,
            'area' => $request->area,
            'rut' => $request->rut,
            'address' => $request->address,
            'city' => $request->city,
            'commune_id' => $request->commune_id,
            'region_id' => $request->region_id,
            'phone' => $request->phone,
            'logo' => $request->logo,
            'payment_type' => $request->payment_type,
        ]);

        $user = Auth::user();
        $user->company()->associate($company);
        $user->save();

        return $user->company;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        if (Gate::allows('show-company', $company)) {
            return response()->json(['company' => $company], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $request->validated();

        $company->social_reason = $request->social_reason;
        $company->area = $request->area;
        $company->rut = $request->rut;
        $company->address = $request->address;
        $company->city = $request->city;
        $company->commune_id = $request->commune_id;
        $company->region_id = $request->region_id;
        $company->phone = $request->phone;
        $company->logo = $request->logo;
        $company->payment_type = $request->payment_type;
        $company->save();

        return $company;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
}
