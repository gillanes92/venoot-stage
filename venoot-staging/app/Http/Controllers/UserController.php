<?php

namespace App\Http\Controllers;

use App\User;
use App\Event;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Requests\UserUpdateRequest;
use App\Mail\WebinarPasswordChange;
use Illuminate\Support\Facades\Mail;
// use App\Http\Requests\UserSignUpRequest;

use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (Gate::allows('index-user')) {
            return response()->json(['users' => $user->company->users], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function indexWithRoles()
    {
        $user = Auth::user();
        if (Gate::allows('index-user')) {
            $users = $user->company->users()->without('roles')->get();
            $users->map(function ($user) {
                $roles = $user->getRoleNames();
                unset($user->roles);
                $user->roles = $roles;
                return $user;
            });

            return response()->json(['users' => $users, 'roles' => Role::all()->pluck('name')], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requested()
    {
        $user = Auth::user();
        if (Gate::allows('index-user')) {
            return response()->json(['usersRequested' => $user->company->companyRequests->pluck('user')], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Show the form for singning up a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function signup(UserSignUpRequest $request)
    // {
    //     $request->validated();

    //     return User::create([
    //         'name' => $request->name,
    //         'lastname' => $request->lastname,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);
    // }

    public function storeFromApi(Request $request)
    {
        if ($request->token != 'ukD32xcBdqC4avXM') {
            return response()->json(['status' => 'Acess Key Error'], 403);
        }

        // $r = json_decode($request->all()[0], true);

        $r = $request->all()[0];
        $company = Company::firstOrNew(
            ['rut' => $r['meta_data'][3]['value']],
            [
                'social_reason' => $r['meta_data'][4]['value'],
                'area' => 'FALTA ESTE VALOR',
                'address' => $r['meta_data'][0]['value'],
                'city' => 'FALTA ESTE VALOR',
                'phone' => $r['billing']['phone'],
                'logo' => 'NO-LOGO.jpg',
                'commune_id' => 109,
                'region_id' => 7
            ]
        );
        $company->save();

        // $hash = base64_encode(hash_hmac('sha256', $request->getContent(), '12345678', true));
        // if (false && $hash != $request->header('X-WC-Webhook-Signature')) {
        //     Log::alert("Woocommerce Signature Error");
        //     return response()->json(['error' => 'Woocommerce Signature Error'], 200);
        // }

        // $id = $request->id;
        // $client = new \GuzzleHttp\Client();

        // try {

        //     $response = $client->get('https://venoot.com/wp-json/wc/v3/orders/' . $id, [
        //         'auth' => [
        //             'ck_b8cfb2f2aa4ccff85bf6929a46678e68e015f0dd',
        //             'cs_f05774016245b0a63751d12f92707e5ea94f704d'
        //         ]
        //     ]);

        // $r = json_decode($response->getBody()->getContents());

        // $company = Company::firstOrNew(
        //     ['rut' => $request->meta_data[3]->value],
        //     [
        //         'social_reason' => $request->billing->company,
        //         'area' => $r->meta_data[2]->value,
        //         'address' => $r->meta_data[3]->value,
        //         'city' => 'FALTA ESTE VALOR',
        //         'phone' => $r->billing->phone,
        //         'logo' => 'NO-LOGO.jpg',
        //         'commune_id' => 109,
        //         'region_id' => 7
        //     ]
        // );
        // $company->save();

        $product_id = $r['line_items'][0]['product_id'];
        $variation_id = $r['line_items'][0]['variation_id'];

        $package = 'free';
        $expiration_date = Carbon::now();
        switch ($product_id) {
                // TESTING PRODUCT
            case 9519:
                $package = 'testing';
                $expiration_date = Carbon::now()->addDays(14);
                break;

            case 9198:
                $package = 'free';

                if ($variation_id == 9101) {
                    $expiration_date = Carbon::now()->addYear();
                } else if ($variation_id == 9102) {
                    $expiration_date = Carbon::now()->addMonth();
                }
                break;

            case 9317:
                $package = 'pro';

                if ($variation_id == 9327) {
                    $expiration_date = Carbon::now()->addYear();
                } else if ($variation_id == 9326) {
                    $expiration_date = Carbon::now()->addMonth();
                }
                break;

            case 9321:
                $package = 'enterprise';

                if ($variation_id == 9325) {
                    $expiration_date = Carbon::now()->addYear();
                } else if ($variation_id == 9324) {
                    $expiration_date = Carbon::now()->addMonth();
                }
                break;
        }

        $raw_password = Str::random(12);
        Log::debug($raw_password);
        $user = $company->users()->firstOrNew(
            ['email' => $r['billing']['email']],
            [
                'name' => $r['billing']['first_name'],
                'lastname' => $r['billing']['last_name'],
                'password' => Hash::make($raw_password),
                'package' => $package,
                'expiration_date' => $expiration_date,
                'customer_id' => $r['order_id'],
            ]
        );

        // if (!$user->email_verified_at) {
        //     $user->email_verified_at = Carbon::now();
        // }

        $user->category = 'webinar';
        $user->customer_id = $r['order_id'];
        $user->save();
        $user->assignRole("base");
        $user->assignRole("company-admin");

        Mail::to($user->email)->queue(new WebinarPasswordChange($user));

        return response()->json($user, 200);
        // } catch (\Exception $e) {
        //     Log::error($e->getMessage());
        //     return response()->json($e->getMessage(), 200);
        // }
    }

    public function webinarPasswordChange($order_id)
    {
        $user = User::where('customer_id', $order_id)->first();
        if ($user || !$user->email_verified_at) {
            return view('webinar-password-change', ['user' => $user]);
        } else {
            abort(404);
        }
    }

    public function webinarPasswordChangeRequest(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'repeat_password' => 'required|string|same:password',
            'order_id' => 'required|string'
        ]);

        $user = User::where('customer_id', $request->order_id)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->email_verified_at = Carbon::now();
            $user->save();

            return response()->json(null, 204);
        }

        return response()->json(null, 404);
    }

    public function webinarPasswordChanged($order_id)
    {
        $user = User::where('customer_id', $order_id)->first();
        if ($user || $user->email_verified_at) {
            return view('webinar-password-changed');
        } else {
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (Gate::allows('show-user', $user)) {
            return response()->json(['user' => $user], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $request->validated();

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        if ($user->save()) {
            return response()->json(['user' => $user], 200);
        }

        return response()->json(['error' => 'server_error'], 500);
    }

    public function accept(Request $request, User $user)
    {
        if (Gate::allows('accept-user', $user)) {
            $userRequest = $user->companyRequest;
            $user->company()->associate($userRequest->company);
            $user->assignRole('base');
            $user->save();
            $userRequest->delete();
            return response()->json(null, 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function reject(Request $request, User $user)
    {
        if (Gate::allows('accept-user', $user)) {
            $userRequest = $user->companyRequest->delete();
            return response()->json(null, 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function addRole(User $user, Request $request)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $current_user = Auth::user();
        if (Gate::allows('edit-permissions', $current_user, $user)) {
            $user->assignRole($request->role);
            return response()->json(['roles' => $user->getRoleNames()], 200);
        }

        return response()->json(['error' => 'unauthorized'], 403);
    }

    public function removeRole(User $user, Request $request)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $current_user = Auth::user();
        if (Gate::allows('edit-permissions', $current_user, $user)) {
            $user->removeRole($request->role);
            return response()->json(['roles' => $user->getRoleNames()], 200);
        }

        return response()->json(['error' => 'unauthorized'], 403);
    }

    public function collectorEvents(User $collector)
    {
        /** @var User $user */
        $user = Auth::user();
        $events = collect();
        if (in_array('company-admin', $user->getRoleNames()->toArray())) {
            $events = $events->merge($user->company->events);
        }

        if (in_array('super-admin', $user->getRoleNames()->toArray())) {
            $events = $events->merge(Event::where('published', true)->get());
        }

        $events = $events->merge($collector->events);

        $events = $events->unique();

        return response()->json(['events' => $events], 200);
    }

    public function changeLocale(Request $request)
    {
        $request->validate([
            'locale' => 'in:en,es'
        ]);

        /** @var User $user */
        $user = Auth::user();
        if ($user) {
            $user->locale = $request->locale;
            $user->save();
        } else {
            App::setLocale($request->locale);
        }

        return response()->json(null, 204);
    }

    public function resetPassword($order_token)
    {
        return view('temp_reset_password', ['order_token' => $order_token]);
    }
}
