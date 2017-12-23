<?php

namespace FSR\Http\Controllers\Donor;

use Carbon\Carbon;
use FSR\File;
use FSR\Cso;
use FSR\Listing;
use FSR\Product;
use FSR\FoodType;
use FSR\QuantityType;
use FSR\Custom\Methods;
use FSR\Notifications\Donor\NewListing;
use Illuminate\Http\Request;
use FSR\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Intervention\Image\ImageManagerStatic as Image;

class NewListingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:donor');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $food_type = old('food_type');
        if ($food_type) {
            $products = Product::where('food_type_id', $food_type)->get();
        } else {
            $products = Product::all();
        }

        $food_types = FoodType::all();
        $quantity_types = QuantityType::all();
        $now = Carbon::now()->format('Y-m-d') . 'T' . Carbon::now()->format('H:i');
        return view('donor.new_listing')->with([
          'quantity_types' => $quantity_types,
          'food_types' => $food_types,
          'products' => $products,
          'now' => $now,
        ]);
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
            'food_type'         => 'required',
            'product_id'        => 'required',
            'image'             => 'image|max:2048',
            'quantity'          => 'required|numeric',
            'quantity_type'     => 'required',
            'date_listed'       => 'required',
            'expires_in'        => 'required|numeric',
            'time_type'         => 'required',
            'pickup_time_from'  => 'required',
            'pickup_time_to'    => 'required',
        ];

        return Validator::make($data, $validatorArray);
    }


    /**
     * Handle a "add new listing" request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $validation = $this->validator($request->all());
        //$this->validator($request->all())->validate();
        if ($validation->fails()) {
            return redirect(route('donor.new_listing'))->withErrors($validation->errors())
                                                     ->withInput();
        }

        $file_id = $this->handleUpload($request);
        $listing = $this->create($request->all(), $file_id);

        $csos = Cso::where('location_id', Auth::user()->location_id)->get();
        //     ->where('notifications', 1)->get();

        Notification::send($csos, new NewListing($listing));

        return back()->with('status', "Донацијата е додадена успешно!");
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \FSR\User
     */
    protected function create(array $data, $file_id)
    {
        return  Listing::create([
                'donor_id' => Auth::user()->id,
                'product_id' => $data['product_id'],
                'description' => $data['description'],
                'food_type_id' => $data['food_type'],
                'quantity' => $data['quantity'],
                'quantity_type_id' => $data['quantity_type'],
                'date_listed' => Methods::convert_date_input_to_db($data['date_listed']),
                'date_expires' => $this->calculate_date_expires($data['date_listed'], $data['expires_in'], $data['time_type']),
                'image_id' => $file_id,
                'pickup_time_from' => $data['pickup_time_from'],
                'pickup_time_to' => $data['pickup_time_to'],
                'listing_status' => 'active',

            ]);
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
        if ($request->hasFile('image')) {

              //Methods::fitImage($request);
            $file = $request->file('image');
            $filename =$file->hashName();

            $directory_path = storage_path('app/public' . config('app.upload_path'));
            $file_path = $directory_path . '/' . $filename;

            if (!file_exists($directory_path)) {
                mkdir($directory_path, 666, true);
            }
            $img = Image::make($file);
            Methods::fitImage($img);
            $img->save($file_path);

            $file_id = File::create([
                  'path_to_file'  => config('app.upload_path'),
                  'filename'      => $filename,
                  'original_name' => $file->getClientOriginalName(),
                  'extension'     => $file->getClientOriginalExtension(),
                  'size'          => Storage::size('public' . config('app.upload_path') . '/' . $filename),
                  'last_modified' => Storage::lastModified('public' . config('app.upload_path') . '/' . $filename),
                  'purpose'       => 'listing image',
                  'for_user_type' => 'donor',
                  'description'   => 'An uploaded image for a listing.',
              ])->id;
            return $file_id;
        }
    }

    /**
     * Retrieve Products with ajax to populate the <select> control
     *
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function getProducts(Request $request)
    {
        return $products = Product::where('food_type_id', $request->input('food_type'))->get();
    }

    /**
     * Calculates the datetime when a listing expires, from the different input values
     *
     * @param string $date_listed is the starting datetime of the listing
     * @param int $time_value specifies how much of the $time_type the listing will stay as active
     * @param string $time_type can be hours, days or weeks
     * @return string
     */
    public function calculate_date_expires($date_listed, $time_value, $time_type)
    {
        $carbon_date = new Carbon($date_listed);

        switch ($time_type) {
        case 'hours':
          return $carbon_date->addHours($time_value)->format('Y-m-d H:i');
          break;
        case 'days':
                return $carbon_date->addDays($time_value)->format('Y-m-d H:i');
          break;
        case 'weeks':
                return $carbon_date->addWeeks($time_value)->format('Y-m-d H:i');
          break;

        default:
          return $carbon_date->addHours($time_value)->format('Y-m-d H:i');
          break;
      }
    }
}
