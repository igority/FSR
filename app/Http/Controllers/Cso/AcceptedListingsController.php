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
        // if ($request->has('update-volunteer-popup')) {
        //     return $this->handle_update_volunteer($request);
        // } elseif ($request->has('delete-offer-popup')) {
        return $this->handle_delete_offer($request);
        // }
        // return 0;

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
    public function update_volunteer(Request $request)
    {
        // $validation = $this->validator($request->all());
        //
        // if ($validation->fails()) {
        //     return redirect($route)->withErrors($validation->errors())
        //                          ->withInput();
        // }
        $data = $request->all();
        $listing_offer = ListingOffer::find($data['listing_offer_id']);
        $listing_offer->volunteer_id = $data['volunteer'];
        $listing_offer->save();

        return response()->json(['listing-offer-id' => $listing_offer->id]);
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

    /**
     * Open a single listing offer page
     *
     * @param  Request  $request
     * @param  int  $listing_offer_id
     * @return \Illuminate\Http\Response
     */
    public function single_accepted_listing(Request $request, int $listing_offer_id)
    {
        $listing_offer = ListingOffer::where('offer_status', 'active')
                                   ->where('cso_id', Auth::user()->id)
                                   ->whereHas('listing', function ($query) {
                                       $query->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
                                            ->where('date_listed', '<=', Carbon::now()->format('Y-m-d H:i'))
                                            ->where('listing_status', 'active');
                                   })->find($listing_offer_id);

        return view('cso.single_accepted_listing')->with([
        'listing_offer' => $listing_offer,
      ]);
    }
}
