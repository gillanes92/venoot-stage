<?php

namespace App\Http\Controllers;

use App\Sponsor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Requests\SponsorRequest;

class SponsorController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $user = Auth::user();
    if (Gate::allows('show-sponsor', $user)) {
      return response()->json(['sponsors' => $user->company->sponsors], 200);
    } else {
      return response()->json(['error' => 'unauthorized'], 403);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(SponsorRequest $request)
  {
    $user = Auth::user();
    if (Gate::allows('show-sponsor')) {
      $sponsor = Sponsor::create([
        'name' => $request->name,
        'description' => $request->description,
        'logo' => $request->logo,
        'category' => $request->category,
        'url' => $request->url,
        'company_id' => $user->company->id,
      ]);

      return response()->json(['sponsors' => $sponsor->company->sponsors], 200);
    } else {
      return response()->json(['error' => 'unauthorized'], 403);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Sponsor $sponsor)
  {
    if (Gate::allows('show-sponsor')) {
      return response()->json(['sponsor' => $sponsor], 200);
    } else {
      return response()->json(['error' => 'unauthorized'], 403);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(SponsorRequest $request, $id)
  {
    $sponsor = Sponsor::find($id);
    if (Gate::allows('edit-sponsor', $sponsor)) {

      $sponsor->name = $request->name;
      $sponsor->description = $request->description;
      $sponsor->logo = $request->logo;
      $sponsor->category = $request->category;
      $sponsor->company_id = $request->company_id;
      $sponsor->url = $request->url;
      $sponsor->save();

      return response()->json(['sponsors' => $sponsor->company->sponsors], 200);
    } else {
      return response()->json(['error' => 'unauthorized'], 403);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $user = Auth::user();
    $sponsor = Sponsor::find($id);
    if (Gate::allows('edit-sponsor', $sponsor)) {
      if ($sponsor->delete()) {
        return response()->json(['sponsors' => $user->company->sponsors], 200);
      } else {
        return response()->json(['sponsor' => $sponsor], 500);
      }
    } else {
      return response()->json(['error' => 'unauthorized'], 403);
    }
  }
}
