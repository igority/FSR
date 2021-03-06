<?php

namespace FSR\Http\Controllers\Auth;

use FSR\User;
use FSR\Cso;
use FSR\Donor;
use FSR\Location;
use FSR\DonorType;
use FSR\Organization;
use FSR\File;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

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
    protected $redirectTo = '/login';
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
        $file_id = $this->register_handle_upload($request);
        event(new Registered($user = $this->create($request->all(), $file_id)));
        //  });
        $request->session()->put('status', Lang::get('login.not_approved'));
        //Auth::guard($user->type())->login($user);
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
     * set information for image upload for registering new user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function register_handle_upload(Request $request)
    {
        $input_name = 'profile_image';
        $purpose = 'profile image';
        $for_user_type = $request->all()['type'];
        $description = 'Profile image for a ' . $request->all()['type'] . ' uploaded when registering.';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }
}
