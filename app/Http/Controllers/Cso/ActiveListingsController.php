<?php

namespace FSR\Http\Controllers\Cso;

use FSR\Listing;
use FSR\ListingOffer;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ActiveListingsController extends Controller
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


        //  $active_listings = Listing::where('listing_status', 'active');
        $active_listings = Listing::where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
                                  ->where('listing_status', 'active')
                                  ->orderBy('date_expires', 'ASC');

        $listing_offers = ListingOffer::all();

        return view('cso.active_listings')->with([
          'active_listings' => $active_listings,
          'listing_offers' => $listing_offers,
        ]);
    }
}
