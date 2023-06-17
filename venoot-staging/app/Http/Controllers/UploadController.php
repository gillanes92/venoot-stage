<?php

namespace App\Http\Controllers;

use App\Database;
use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;

class UploadController extends Controller
{

  public function uploadStandFile(Request $request)
  {
    try {
      $file = $request->file('file')->store('public');
      return response()->json(['name' => basename($file)], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e], 500);
    }
  }

  public function uploadLogo(Request $request)
  {
    try {
      $image = $request->file('logo')->store('public');
      return response()->json(['name' => basename($image)], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e], 500);
    }
  }

  public function uploadPhoto(Request $request)
  {
    try {
      $image = $request->file('photo')->store('public');
      return response()->json(['name' => basename($image)], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e], 500);
    }
  }

  public function uploadLogoSponsor(Request $request)
  {
    try {
      $image = $request->file('logo')->store('public');
      return response()->json(['name' => basename($image)], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e], 500);
    }
  }

  public function uploadLogoEvent(Request $request)
  {
    try {
      $image = $request->file('logo')->store('public');
      return response()->json(['name' => basename($image)], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e], 500);
    }
  }

  public function uploadBannerEvent(Request $request)
  {
    try {
      $image = $request->file('banner')->store('public');
      return response()->json(['name' => basename($image)], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e], 500);
    }
  }

  public function uploadLandingImages(Request $request)
  {
    try {
      $image = $request->file('image')->store('public');
      return response()->json(['name' => basename($image)], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e], 500);
    }
  }

  public function uploadLocationEvent(Request $request)
  {
    try {
      $image = $request->file('location')->store('public');
      return response()->json(['name' => basename($image)], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e], 500);
    }
  }

  public function uploadExtraImages(Request $request)
  {
    try {
      $image = $request->file('image')->store('public');
      $basename = basename($image);
      $imageSize = getImageSize(Storage::url($basename));
      return response()->json(['name' => $basename, 'type' => 'image', 'src' => Storage::url($basename), 'width' => $imageSize[0], 'height' => $imageSize[1]], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e], 500);
    }
  }


  public function getPublicImage($imageName)
  {
    try {
      $image = Storage::get('public/' . $imageName);
      $type = Storage::mimeType('public/' . $imageName);
      return response($image, 200)->header('Content-Type', $type)->header('Content-Disposition', "inline; filename='" . $imageName . "'");
    } catch (\Exception $e) {
      return response()->json(['error' => $e], 500);
    }
  }

  public function uploadFormImage(Request $request)
  {
    try {
      $key = array_keys($request->all())[0];
      $image = $request->file($key)->store('public');
      $basename = basename($image);
      return response()->json(['name' => $basename], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  public function uploadFormPDF(Request $request)
  {
    try {
      $key = array_keys($request->all())[0];
      $pdf = $request->file($key)->store('public');
      $basename = basename($pdf);
      return response()->json(['name' => $basename], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  public function uploadDatabase(Database $database, Request $request)
  {
    try {
      $excel = $request->file('database')->store('public');
      $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('database')->getRealPath());
      $worksheet = $spreadsheet->getActiveSheet();
      $database_names = collect($database->fields)->pluck('name')->toArray();
      $database_keys = collect($database->fields)->pluck('key')->toArray();
      $value = $worksheet->getCellByColumnAndRow(1, 1)->getValue();

      for ($col = 1; $col < 10000; ++$col) {
        $value = $worksheet->getCellByColumnAndRow($col, 1)->getValue();

        if (!$value) {
          break;
        }

        if (($key = array_search($value, $database_keys)) !== false) {

          if ($col < 3 && ['name', 'lastname', 'email'][$col - 1] != $value) {
            return response()->json(['error' => 'incorrect_format', 'keys' => [$database_names[$col - 1]]], 500);
          }
          unset($database_names[$key]);
          unset($database_keys[$key]);
        } else {
          return response()->json(['error' => 'unknow_key', 'keys' => [$value]], 500);
        }
      }
      if (count($database_keys) > 0) {
        return response()->json(['error' => 'missing_key', 'keys' => $database_names], 500);
      }

      $database_keys = collect($database->fields)->pluck('key')->toArray();
      $participants = [];
      for ($row = 2; $row < 1000000; ++$row) {
        $participant = [];
        for ($col = 1; $col <= count($database_keys); ++$col) {
          $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
          if ($value == '&&&' && $col == 1) {
            $row = 1000000;
            break;
          } else if (!$value && ($col == 1 || $col == 2 || $col == 3)) {
            return response()->json(['error' => 'missing_data', 'keys' => [(chr($col  + 64)) . $row]], 500);
          }

          $participant['data'][$database_keys[$col - 1]] = $value;
        }

        if ($participant != []) {
          array_push($participants, $participant);
        }
      }
      foreach ($participants as $participant) {
        $database->participants()->updateOrCreate(['data->email' => $participant['data']['email']], $participant);
      }

      return response()->json(['participants' => $database->participants], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => (array) $e], 500);
    }
  }

  public function uploadActivities(Event $event, Request $request)
  {
    $event->activities()->delete();

    try {
      $excel = $request->file('activities')->store('public');
      $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('activities')->getRealPath());
      $worksheet = $spreadsheet->getActiveSheet();
      $actors = $event->company->actors;
      $not_found = [];


      for ($row = 2; $row < 1048576; ++$row) {
        $current_activity = array();

        for ($col = 1; $col < 8; ++$col) {
          $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

          if ($value == '&&&' && $col == 1) {
            $row = 1048576;
            break;
          } else if (!$value) {
            $current_activity[$col] = null;
          } else {
            $current_activity[$col] = $value;
          }
        }

        $activity = null;
        if ($row < 1048576) {
          $activity = $event->activities()->create([
            'name' => $current_activity[1],
            'location' => $current_activity[2],
            'date' => Carbon::parse($current_activity[3]),
            'start_time' => Carbon::parse($current_activity[4]),
            'end_time' => Carbon::parse($current_activity[5]),
            'description' => $current_activity[6],
          ]);
        }


        if ($activity && $current_activity[7]) {
          $speaker = $actors->first(function ($actor) use ($current_activity, $not_found) {
            return Str::containsAll($current_activity[7], [$actor->name, $actor->lastname]);
          });

          if ($speaker) {
            $activity->actors()->sync([$speaker->id]);
          } else {
            array_push($not_found, $activity);
          }
        }
      }

      return response()->json(['activities' => $event->activities, 'not_found' => $not_found], 200);
    } catch (\Exception $e) {
      return response()->json(['object' => $current_activity], 500);
    }
  }

  public function uploadActors(Event $event, Request $request)
  {
    try {
      $excel = $request->file('actors')->store('public');
      $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('actors')->getRealPath());
      $worksheet = $spreadsheet->getActiveSheet();

      for ($row = 2; $row < 1048576; ++$row) {
        $current_activity = array();

        for ($col = 1; $col < 8; ++$col) {
          $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

          if ($value == '&&&' && $col == 1) {
            $row = 1048576;
            break;
          } else if (!$value) {
            $current_activity[$col] = null;
          } else {
            $current_activity[$col] = $value;
          }
        }

        if ($row < 1048576) {
          $actor = $event->company->actors()->create([
            'prefix' => $current_activity[1],
            'name' => $current_activity[2],
            'lastname' => $current_activity[3],
            'category' => $current_activity[4],
            'job' => $current_activity[5],
            'organization' => $current_activity[6],
            'description' => $current_activity[7],
            'photo' => 'YMWiYzmRPgz1XJSiQXo5khnJhyaE0UStguJ5PA70.jpeg',
          ]);

          $event->actors()->attach($actor);
        }
      }

      return response()->json(['actors' => $event->actors], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => (array) $e, 'object' => $current_activity], 500);
    }
  }

  public function uploadKeys(Request $request)
  {
    try {
      $excel = $request->file('keys')->store('public');
      return response()->json(['name' => basename($excel)], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e], 500);
    }
  }

  public function uploadTemplateImage(Request $request)
  {
    switch ($request->type) {
      case 'upload':
        try {
          $image = $request->file('file')->store('public');
          $basename = basename($image);
          return response()->json(['url' => Storage::disk('public')->url($basename)], 200);
        } catch (\Exception $e) {
          return response()->json(['error' => $e->getMessage()], 500);
        }
        break;

      case 'url':

        $request->validate([
          'url' => 'required|url'
        ]);

        try {
          return response()->json(['url' => $request->url], 200);
        } catch (\Exception $e) {
          return response()->json(['error' => $e->getMessage()], 500);
        }
        break;
    }
  }
}
