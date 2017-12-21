@extends('layouts.master')


@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header accepted-listings-content-header">
    <h1><i class="fa fa-bookmark"></i>
      <span>Прифатени донации</span>
      @if ($listing_offers_no > 0)
        <span> ({{$listing_offers_no}})</span>
      @endif
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Примател</a></li>
      <li><a href="/{{Auth::user()->type()}}/accepted_listings"><i class="fa fa-bookmark"></i> Прифатени донации</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content accepted-listings-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif

  @if ($errors->any())
      <div class="alert alert-danger">
        Измените не се прифатени! Корегирајте ги грешките и обидете се повторно.
        <a href="javascript:document.getElementById('listingbox{{ old('lising_offer_id') }}').scrollIntoView();">
          <button type="button" class="btn btn-default">Иди до донацијата</button>
        </a>
      </div>
  @endif

  @foreach ($listing_offers->get() as $listing_offer)

          <div id="listingbox{{$listing_offer->id}}" name="listingbox{{$listing_offer->id}}"></div>
          <!-- Default box -->
          <div class="box listing-box listing-box-{{$listing_offer->id}} {{($listing_offer->id == old('listing_offer_id')) ? 'box-error' : 'collapsed-box' }}">
            <div class="box-header with-border listing-box-header">
              <a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">
                <div class="listing-image">
                  {{-- <img src="../img/avatar5.png" /> --}}
                  @if ($listing_offer->listing->image_id)
                    <img class="img-circle" alt="{{$listing_offer->listing->food_type->name}}" src="../../storage{{config('app.upload_path') . '/' . FSR\File::find($listing_offer->listing->image_id)->filename}}" />
                  @elseif ($listing_offer->listing->food_type->default_image_id)
                    <img class="img-circle" alt="{{$listing_offer->listing->food_type->name}}" src="{{$listing_offer->listing->food_type->default_image_id}}" />
                  @else
                    <img class="img-circle" alt="{{$listing_offer->listing->food_type->name}}" src="../img/food_types/food-general.jpg" />
                  @endif

                </div>
                <div class="header-wrapper">
                  <div id="listing-title-{{$listing_offer->id}}" class="listing-title col-xs-12 panel">
                    <strong>
                      {{$listing_offer->listing->title}}
                    </strong>
                  </div>
                  <div class="header-elements-wrapper">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Прифатена Количина:</span>
                      <span class="col-xs-12" id="quantity-offered-{{$listing_offer->id}}"><strong>{{$listing_offer->quantity}} {{$listing_offer->listing->quantity_type->description}} за {{$listing_offer->beneficiaries_no}} луѓе</strong></span>
                    </div>
                    {{-- <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Истекува за:</span>
                      <span class="col-xs-12" id="expires-in-{{$listing_offer->id}}"><strong>{{Carbon::parse($listing_offer->listing->date_expires)->diffForHumans()}}</strong></span>
                    </div> --}}
                    <div class="col-md-5 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Прифатена од:</span>
                      <span class="col-xs-12" id="cso-info-{{$listing_offer->id}}"><strong>{{$listing_offer->cso->first_name}} {{$listing_offer->cso->last_name}} | {{$listing_offer->cso->phone}}  | {{$listing_offer->cso->organization->name}}</strong></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Волонтер за подигнување:</span>
                      <span class="col-xs-12" id="cso-info-{{$listing_offer->id}}"><strong>{{$listing_offer->volunteer_pickup_name}}  | {{$listing_offer->volunteer_pickup_phone}}</strong></span>
                    </div>
                  </div>
                </div>
                <div class="box-tools pull-right">
                  {{-- <button type="button" class="btn btn-box-tool"
                          title="Collapse"> --}}
                    <i class="fa fa-caret-down"></i></button>
                </div>
              </a>
            </div>
            <div class="listing-box-body-wrapper">
              <div class="box-body">
                <div class="row">
                  <div class="col-md-4 col-sm-12 listing-pick-up-time ">
                    <span class="col-xs-12">Време за подигнување:</span>
                    <span class="col-xs-12" id="pickup-time-{{$listing_offer->id}}"><strong>од {{Carbon::parse($listing_offer->listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($listing_offer->listing->pickup_time_to)->format('H:i')}} часот</strong></span>
                  </div>
                  <div class="col-md-4 col-sm-6 listing-food-type ">
                    <span class="col-xs-12">Истекува за:</span>
                      <span class="col-xs-12" id="expires-in-{{$listing_offer->id}}"><strong>{{Carbon::parse($listing_offer->listing->date_expires)->diffForHumans()}}</strong></span>                  </div>
                  <div class="col-md-4 col-sm-6 listing-food-type ">
                    <span class="col-xs-12">Тип на храна:</span>
                    <span class="col-xs-12" id="food-type-{{$listing_offer->id}}"><strong>{{$listing_offer->listing->food_type->name}}</strong></span>
                  </div>
                </div>
                @if ($listing_offer->listing->description)
                <hr>
                <div class="row">
                  <div class="col-xs-12 listing-description">
                      <span class="col-xs-12">Опис:</span>
                      <span class="col-xs-12" id="description-{{$listing_offer->id}}"><strong>{{$listing_offer->listing->description}}</strong></span>
                  </div>
                </div>
                @endif
              <div class="box-footer text-center">

                </div>
              </div>
            </div>
            <!-- /.box-footer-->
          </div>
          <!-- /.box -->

  @endforeach

</section>
<!-- /.content -->

@endsection
