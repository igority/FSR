<?php

namespace FSR\Http\Controllers\Cso;

use FSR\Listing;
use FSR\ListingOffer;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AcceptedListingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:cso');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //default settings:
        //  dd(config('default_settings.new_listing_in_location_email'));

        //  $active_listings = Listing::where('listing_status', 'active');
        // $active_listings = Listing::where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
        //                           ->where('listing_status', 'active')
        //                           ->orderBy('date_expires', 'ASC');


        $listing_offers = ListingOffer::where('offer_status', 'active')
                                      ->where('cso_id', Auth::user()->id)
                                      ->whereHas('listing', function ($query) {
                                          $query->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'));
                                      });


        //$listing_offers = ListingOffer::all();
        $listing_offers_no = 0;
        foreach ($listing_offers->get() as $listing_offer) {
            //     $quantity_counter = 0;
            //     foreach ($active_listing->listing_offers as $listing_offer) {
            //         $quantity_counter += $listing_offer->quantity;
            //     }
            //     if ($active_listing->quantity > $quantity_counter) {
            $listing_offers_no++;
            //     }
        }

        return view('cso.accepted_listings')->with([
          'listing_offers' => $listing_offers,
          'listing_offers_no' => $listing_offers_no,
        ]);
    }

    /**
     * Handle post request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {

    //
        if ($request->has('update-volunteer-popup')) {
            return $this->handle_update_volunteer($request);
        } elseif ($request->has('delete-offer-popup')) {
            return $this->handle_delete_offer($request);
        }
        return 0;

        // $validation = $this->validator($request->all());
        //
        // //  http://fsr.test/cso/active_listings#listingbox6
        // $route = route('cso.active_listings') . '#listingbox' . $request->all()['listing_id'];
        //
        // if ($validation->fails()) {
        //     return redirect($route)->withErrors($validation->errors())
        //                            ->withInput();
        // }
        // $listing_offer = $this->create($request->all());
        // return back()->with('status', "Донацијата е успешно прифатена!");
    }

    /**
     * Handle volunteer update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_update_volunteer(Request $request)
    {
        $validation = $this->validator($request->all());

        $route = route('cso.accepted_listings') . '#listingbox' . $request->all()['listing_offer_id'];

        if ($validation->fails()) {
            return redirect($route)->withErrors($validation->errors())
                                 ->withInput();
        }
        $listing_offer = $this->update($request->all());
        return back()->with('status', "Волонтерот е успешно променет!");
    }

    /**
     * Updates the selected listing offer
     *
     * @param  array  $data
     * @return \FSR\ListingOffer
     */
    protected function update(array $data)
    {
        $listing_offer = ListingOffer::find($data['listing_offer_id']);
        $listing_offer->volunteer_pickup_name = $data['volunteer_name'];
        $listing_offer->volunteer_pickup_phone = $data['volunteer_phone'];
        $listing_offer->save();
        return $listing_offer;
    }

    /**
     * Get a validator for an incoming listing offer input request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
            'volunteer_name'     => 'required',
            'volunteer_phone'    => 'required',
        ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * Handle offer listing "delete". (it is actually update)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_delete_offer(Request $request)
    {
        $listing_offer = $this->delete($request->all());
        return back()->with('status', "Донацијата е успешно избришана!");
    }

    /**
     * Mark the selected listing offer as cancelled
     *
     * @param  array  $data
     * @return \FSR\ListingOffer
     */
    protected function delete(array $data)
    {
        $listing_offer = ListingOffer::find($data['listing_offer_id']);
        $listing_offer->offer_status = 'cancelled';
        $listing_offer->save();
        return $listing_offer;
    }
}
