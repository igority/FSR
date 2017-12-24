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
                    <img class="img-circle" alt="{{$listing_offer->listing->product->food_type->name}}" src="../../storage{{config('app.upload_path') . '/' . FSR\File::find($listing_offer->listing->image_id)->filename}}" />
                  @elseif ($listing_offer->listing->product->food_type->default_image_id)
                    <img class="img-circle" alt="{{$listing_offer->listing->product->food_type->name}}" src="{{$listing_offer->listing->product->food_type->default_image_id}}" />
                  @else
                    <img class="img-circle" alt="{{$listing_offer->listing->product->food_type->name}}" src="../img/food_types/food-general.jpg" />
                  @endif

                </div>
                <div class="header-wrapper">
                  <div id="listing-title-{{$listing_offer->id}}" class="listing-title col-xs-12 panel">
                    <strong>
                      {{$listing_offer->listing->product->name}}
                    </strong>
                  </div>
                  <div class="header-elements-wrapper">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Истекува за:</span>

                      <span class="col-xs-12" id="expires-in-{{$listing_offer->id}}"><strong>{{Carbon::parse($listing_offer->listing->date_expires)->diffForHumans()}}</strong></span>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Количина:</span>
                      <span class="col-xs-12" id="quantity-offered-{{$listing_offer->id}}"><strong>{{$listing_offer->quantity}} {{$listing_offer->listing->quantity_type->description}} за {{$listing_offer->beneficiaries_no}} луѓе</strong></span>

                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Локација:</span>
                      <span class="col-xs-12" id="donor-location-{{$listing_offer->id}}"><strong>{{$listing_offer->listing->donor->location->name}}</strong></span>

                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Донирано од:</span>
                      <span class="col-xs-12" id="donor-info-{{$listing_offer->id}}"><strong>{{$listing_offer->listing->donor->first_name}} {{$listing_offer->listing->donor->last_name}} | {{$listing_offer->listing->donor->organization->name}}</strong></span>

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
                  <div class="col-md-4 col-sm-6 listing-pick-up-time ">
                    <span class="col-xs-12">Време за подигнување:</span>
                    <span class="col-xs-12" id="pickup-time-{{$listing_offer->id}}"><strong>од {{Carbon::parse($listing_offer->listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($listing_offer->listing->pickup_time_to)->format('H:i')}} часот</strong></span>
                  </div>
                  <div class="col-md-3 col-sm-6 listing-food-type ">
                    <span class="col-xs-12">Тип на храна:</span>
                    <span class="col-xs-12" id="food-type-{{$listing_offer->id}}"><strong>{{$listing_offer->listing->product->food_type->name}}</strong></span>
                  </div>
                  <div class="col-md-5 col-sm-12 listing-description">
                    @if ($listing_offer->listing->description)
                      <span class="col-xs-12">Опис:</span>
                      <span class="col-xs-12" id="description-{{$listing_offer->id}}"><strong>{{$listing_offer->listing->description}}</strong></span>
                    @endif
                  </div>
                </div>
                <hr>
                <div class="row">
                  <!--
                  <div class="col-md-12 listing-input-wrapper">
                    <div class="panel col-xs-12" style="text-align: center;">Волонтер за подигнување</div>
                    <div class="col-md-5 form-group {{ ((old('lising_offer_id') == $listing_offer->id) && ($errors->has('volunteer'))) ? 'has-error' : '' }}">
                      <label class="col-sm-6" for="pickup-volunteer-name">Име:</label>
                      <span class="col-sm-6">
                        <input type="text" id="pickup-volunteer-name-{{$listing_offer->id}}" name="pickup-volunteer-name"
                                class="pickup-volunteer-name form-control"
                                value="{{($listing_offer->id == old('lising_offer_id'))
                                            ? old('volunteer_name')
                                            : $listing_offer->volunteer->first_name . ' ' . $listing_offer->volunteer->last_name }}">
                      </span>
                      @if ((old('lising_offer_id') == $listing_offer->id) && ($errors->has('volunteer_name')))
                     <span class="help-block listing-input-help-block pull-right">
                         <strong>{{ $errors->first('volunteer_name') }}</strong>
                     </span>
                     @endif
                    </div>
                    <div class="col-md-5 form-group {{ ((old('lising_offer_id') == $listing_offer->id) && ($errors->has('volunteer_phone'))) ? 'has-error' : '' }}">
                      <label class="col-sm-6" for="pickup-volunteer-phone">Број за контакт:</label>
                      <span class="col-sm-6">
                        <input type="text" id="pickup-volunteer-phone-{{$listing_offer->id}}" name="pickup-volunteer-phone"
                              class="pickup-volunteer-phone form-control"
                              value="{{($listing_offer->id == old('lising_offer_id'))
                                          ? old('volunteer_phone')
                                          : $listing_offer->volunteer_pickup_phone }}">
                      </span>
                      @if ((old('lising_offer_id') == $listing_offer->id) && ($errors->has('volunteer_phone')))
                     <span class="help-block listing-input-help-block pull-right">
                         <strong>{{ $errors->first('volunteer_phone') }}</strong>
                     </span>
                     @endif
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="update-volunteer-button-{{$listing_offer->id}}" name="update-volunteer-button-{{$listing_offer->id}}"
                        class="btn btn-default btn-primary update-volunteer-button"
                        data-toggle="modal" data-target="#update-volunteer-popup" update-volunteer-popup>Промени</button>
                    </div>
                  -->
                </div>
              </div>
              <div class="box-footer text-center">
                  <button type="button" id="delete-offer-button-{{$listing_offer->id}}" name="delete-offer-button-{{$listing_offer->id}}"
                            class="btn btn-danger btn-lg delete-offer-button" data-toggle="modal" data-target="#delete-offer-popup">Избриши ја донацијата</button>
                </div>
              </div>
            </div>
            <!-- /.box-footer-->
          </div>
          <!-- /.box -->




  @endforeach

  <!-- Update Volunteer Modal  -->
  <div id="update-volunteer-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form id="update-volunteer-form" class="update-volunteer-form" action="{{ route('cso.accepted_listings') }}" method="post">
          {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 id="popup-title" class="modal-title popup-title">Промени го волонтерот</h4>
          </div>
          <div id="update-volunteer-body" class="modal-body update-volunteer-body">
            <!-- Form content-->
            <h5 id="popup-info" class="popup-info row italic">
              Проверете ги добро податоците и ако е во ред потврдете:
            </h5>

            <div id="popup-volunteer-name" class="popup-volunteer-name popup-element row">
              <div class="popup-volunteer-name-label col-xs-6">
                <span class="pull-right popup-element-label">Име на волонтер:</span>
              </div>
              <div id="popup-volunteer-name-value" class="popup-volunteer-name-value popup-element-value col-xs-6">
              </div>
            </div>

            <div id="popup-volunteer-phone" class="popup-volunteer-phone popup-element row">
              <div class="popup-volunteer-phone-label col-xs-6">
                <span class="pull-right popup-element-label">Број за контакт:</span>
              </div>
              <div id="popup-volunteer-phone-value" class="popup-volunteer-phone-value popup-element-value col-xs-6">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" name="update-volunteer-popup" class="btn btn-primary" value="Прифати" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Modal  -->
  <div id="delete-offer-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form id="delete-offer-form" class="delete-offer-form" action="{{ route('cso.accepted_listings') }}" method="post">
          {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 id="popup-title" class="modal-title popup-title">Избриши ја донацијата</h4>
          </div>
          <div id="delete-offer-body" class="modal-body delete-offer-body">
            <!-- Form content-->
            <h5 id="popup-info" class="popup-info row italic">
              Дали сте сигурни дека сакате да ја избришите прифатената донација?
            </h5>
          </div>
          <div class="modal-footer">
            <input type="submit" name="delete-offer-popup" class="btn btn-danger" value="Избриши" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->

@endsection


@section('cso_accepted_listings_no')
  @if ($listing_offers_no)
    <span class="pull-right-container">
      <small class="label pull-right bg-blue">{{$listing_offers_no}}</small>
    </span>
  @endif
@endsection
