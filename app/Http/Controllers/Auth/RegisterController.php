<?php

namespace FSR\Http\Controllers\Auth;

use FSR\User;
use FSR\Cso;
use FSR\Donor;
use FSR\Location;
use FSR\DonorType;
use FSR\Organization;
use FSR\File;
use FSR\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    protected $redirectTo = '/';
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $selectedType = old('type');
        if ($selectedType) {
            $organizations = Organization::where('type', '=', $selectedType)->get();
        } else {
            $organizations = Organization::all();
        }

        $locations = Location::all();
        $donor_types = DonorType::all();
        return view('auth.register')->with([
          'organizations' => $organizations,
          'locations' => $locations,
          'donor_types' => $donor_types,
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        //  DB::transaction(function(Request $request) {
        $file_id = $this->handleUpload($request);
        event(new Registered($user = $this->create($request->all(), $file_id)));
        //  });

        Auth::guard($user->type())->login($user);
        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Retrieve Organizations with ajax to populate the <select> control
     *
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function getOrganizations(Request $request)
    {
        return $organizations = Organization::where('type', '=', $request->input('type'))->get();
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
            'type'                  => 'required',
            'organization'          => 'required',
            'donor_type'            => '',
            'location'              => 'required',
            'email'                 => 'required|string|email|max:255|unique:donors|unique:csos',
            'password'              => 'required|string|min:6|confirmed',
            'profile_image'         => 'image|max:2048',
        ];
        if ($data['type'] == 'donor') {
            $validatorArray['donor_type'] = 'required';
        }
        return Validator::make($data, $validatorArray);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \FSR\User
     */
    protected function create(array $data, $file_id)
    {
        switch ($data['type']) {
          case 'donor':
          $redirectTo = '/donor/home';
          return  Donor::create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'organization_id' => $data['organization'],
                'location_id' => $data['location'],
                'donor_type_id' => $data['donor_type'],
                'profile_image_id' => $file_id,
                'notifications' => '1',
            ]);

          break;
          case 'cso':
          $redirectTo = '/cso/home';
          return  Cso::create([
              'email' => $data['email'],
              'password' => bcrypt($data['password']),
              'first_name' => $data['first_name'],
              'last_name' => $data['last_name'],
              'phone' => $data['phone'],
              'address' => $data['address'],
              'organization_id' => $data['organization'],
              'location_id' => $data['location'],
              'profile_image_id' => $file_id,
              'notifications' => '1',
            ]);
          break;

        default:
          # code...
          break;
      }
    }

    /**
     * handle the profile image upload.
     *
     * @param  Request $request
     * @return int id of the uploaded image in the Files table
     */
    public function handleUpload(Request $request)
    {
        /*
        show like this:
        http://fsr.test/storage/upload/qovEHC3FJ70FEKwWdp202jz2qjwelB8evnTgqrPg.jpeg

        */

        //$id = $this->create($data)->id;
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('public' . config('app.upload_path'));
            $file_id = File::create([
                  'path_to_file'  => config('app.upload_path'),
                  'filename'      => $request->profile_image->hashName(),
                  'original_name' => $request->file('profile_image')->getClientOriginalName(),
                  'extension'     => $request->file('profile_image')->getClientOriginalExtension(),
                  'size'          => Storage::size($path),
                  'last_modified' => Storage::lastModified($path),
                  'purpose'       => 'profile_image',
                  'for_user_type' => $request->all()['type'],
                  'description'   => 'Profile image for a ' . $request->all()['type'] . ' uploaded when registering.',
              ])->id;

            return $file_id;
        }
    }
}
