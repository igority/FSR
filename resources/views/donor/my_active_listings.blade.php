@extends('layouts.master')
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header active-listings-content-header">
    <h1><i class="fa fa-cutlery"></i>
      <span>Активни донации</span>
      @if ($active_listings_no > 0)
        <span> ({{$active_listings_no}})</span>
      @endif
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Примател</a></li>
      <li><a href="/{{Auth::user()->type()}}/active_listings"><i class="fa fa-cutlery"></i> Активни донации</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content active-listings-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif
{{--
  @if ($errors->any())
      <div class="alert alert-danger">
        Донацијата не беше прифатена успешно. Корегирајте ги грешките и обидете се повторно
        <a href="javascript:document.getElementById('listingbox{{ old('listing_id') }}').scrollIntoView();">
          <button type="button" class="btn btn-default">Иди до донацијата</button>
        </a>
      </div>
  @endif --}}

  @foreach ($active_listings->get() as $active_listing)

        <div id="listingbox{{$active_listing->id}}" name="listingbox{{$active_listing->id}}"></div>
          <!-- Default box -->
          <div class="box listing-box listing-box-{{$active_listing->id}} {{($active_listing->id == old('listing_id')) ? 'box-error' : 'collapsed-box' }}">
            <div class="box-header with-border listing-box-header">
              <a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">
                <div class="listing-image">
                  {{-- <img src="../img/avatar5.png" /> --}}
                  @if ($active_listing->image_id)
                    <img class="img-rounded" alt="{{$active_listing->product->food_type->name}}" src="../../storage{{config('app.upload_path') . '/' . FSR\File::find($active_listing->image_id)->filename}}" />
                  @elseif ($active_listing->product->food_type->default_image)
                    <img class="img-rounded" alt="{{$active_listing->product->food_type->name}}" src="{{$active_listing->product->food_type->default_image}}" />
                  @else
                    <img class="img-rounded" alt="{{$active_listing->product->food_type->name}}" src="../img/food_types/food-general.jpg" />
                  @endif

                </div>
                <div class="header-wrapper">
                  <div id="listing-title-{{$active_listing->id}}" class="listing-title col-xs-12 panel">
                    <strong>
                      {{$active_listing->title}}
                    </strong>
                  </div>
                  <div class="header-elements-wrapper">

                    <div class="col-md-4 col-sm-4 col-xs-12">
                      <span class="col-xs-12">Количина:</span>
                      <span class="col-xs-12" id="quantity-offered-{{$active_listing->id}}"><strong>{{$active_listing->quantity}} {{$active_listing->quantity_type->description}}</strong></span>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <span class="col-xs-12">Прифатени:</span>

                      @switch($active_listing->listing_offers_count)
                          @case(0)
                          <span class="col-xs-12" id="accepted-quantity-{{$active_listing->id}}"><strong>Нема</strong></span>
                              @break
                          @case(1)
                          <span class="col-xs-12" id="accepted-quantity-{{$active_listing->id}}"><strong>{{$active_listing->listing_offers[0]->quantity}} {{$active_listing->quantity_type->description}} од {{$active_listing->listing_offers[0]->cso->first_name}} {{$active_listing->listing_offers[0]->cso->last_name}} | {{$active_listing->listing_offers[0]->cso->organization->name}}</strong></span>
                              @break
                          @default
                          <?php
                            $total_quantity = 0;
                            foreach ($active_listing->listing_offers as $listing_offer) {
                                $total_quantity += $listing_offer->quantity;
                            }
                          ?>
                          <span class="col-xs-12" id="accepted-quantity-{{$active_listing->id}}"><strong>{{$total_quantity}} {{$active_listing->quantity_type->description}} од {{$active_listing->listing_offers_count}} приматели</strong></span>
                      @endswitch

                    </div>

                  </div>
                </div>
                <div class="box-tools pull-right">
                    <i class="fa fa-caret-down"></i></button>
                </div>
              </a>
            </div>
            <div class="listing-box-body-wrapper">
              <div class="box-body">
                <div class="row">
                  <div class="col-md-3 col-sm-6 listing-pick-up-time ">
                    <span class="col-xs-12">Време за подигнување:</span>
                    <span class="col-xs-12" id="pickup-time-{{$active_listing->id}}"><strong>од {{Carbon::parse($active_listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($active_listing->pickup_time_to)->format('H:i')}} часот</strong></span>
                  </div>
                  <div class="col-md-3 col-sm-6 valid-from-up-time ">
                    <span class="col-xs-12">Важи од:</span>
                    <span class="col-xs-12" id="valid-from-{{$active_listing->id}}"><strong>{{Carbon::parse($active_listing->date_listed)->diffForHumans()}}</strong></span>
                  </div>
                  <div class="col-md-3 col-sm-6 expires-in-up-time ">
                    <span class="col-xs-12">Истекува за:</span>
                    <span class="col-xs-12" id="expires-in-{{$active_listing->id}}"><strong>{{Carbon::parse($active_listing->date_expires)->diffForHumans()}}</strong></span>
                  </div>
                  <div class="col-md-3 col-sm-6 listing-food-type ">
                    <span class="col-xs-12">Тип на храна:</span>
                    <span class="col-xs-12" id="food-type-{{$active_listing->id}}"><strong>{{$active_listing->product->food_type->name}}</strong></span>
                  </div>
                  {{-- <div class="col-md-5 col-sm-12 listing-description">
                    @if ($active_listing->description)
                      <span class="col-xs-12">Опис:</span>
                      <span class="col-xs-12" id="description-{{$active_listing->id}}"><strong>{{$active_listing->description}}</strong></span>
                    @endif
                  </div> --}}
                </div>
                @if ($active_listing->description)
                <hr>
                <div class="row">
                  <div class="col-xs-12 listing-description">
                      <span class="col-xs-12">Опис:</span>
                      <span class="col-xs-12" id="description-{{$active_listing->id}}"><strong>{{$active_listing->description}}</strong></span>
                  </div>
                </div>
                @endif
              </div>
              <div class="box-footer text-center">
                {{-- <button type="button" id="delete-offer-button-{{$listing_offer->id}}" name="delete-offer-button-{{$listing_offer->id}}"
                          class="btn btn-danger btn-lg delete-offer-button" data-toggle="modal" data-target="#delete-offer-popup">Избриши ја донацијата</button> --}}
                </div>
            </div>

            <!-- /.box-footer-->
          </div>
          <!-- /.box -->

  @endforeach

  <!-- Modal -->
  <div id="confirm-listing-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form id="listing-confirm-form" class="listing-confirm-form" action="{{ route('cso.active_listings') }}" method="post">
          {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 id="popup-title" class="modal-title popup-title"></h4>
          </div>
          <div id="listing-confirm-body" class="modal-body listing-confirm-body">
            <!-- Form content-->
            <h5 id="popup-info" class="popup-info row italic">
              Проверете ги добро податоците и ако е во ред потврдете:
            </h5>
            <div id="popup-quantity-needed" class="popup-quantity-needed popup-element row">
              <div class="popup-quantity-needed-label col-xs-6">
                <span class="pull-right popup-element-label">Потребна количина:</span>
              </div>
              <div id="popup-quantity-needed-value" class="popup-quantity-needed-value popup-element-value col-xs-6">
              </div>
            </div>

            <div id="popup-beneficiaries-no" class="popup-beneficiaries-no popup-element row">
              <div class="popup-beneficiaries-no-label col-xs-6">
                <span class="pull-right popup-element-label">За колку лица:</span>
              </div>
              <div id="popup-beneficiaries-no-value" class="popup-beneficiaries-no-value popup-element-value col-xs-6">
              </div>
            </div>

            <div id="popup-expires-in" class="popup-expires-in popup-element row">
              <div class="popup-expires-in-label col-xs-6">
                <span class="pull-right popup-element-label">Истекува за:</span>
              </div>
              <div id="popup-expires-in-value" class="popup-expires-in-value popup-element-value col-xs-6">
              </div>
            </div>

            <div id="popup-pickup-time" class="popup-pickup-time popup-element row">
              <div class="popup-pickup-time-label col-xs-6">
                <span class="pull-right popup-element-label">Време на подигнување:</span>
              </div>
              <div id="popup-pickup-time-value" class="popup-pickup-time-value popup-element-value col-xs-6">
              </div>
            </div>

            <div id="popup-location" class="popup-location popup-element row">
              <div class="popup-location-label col-xs-6">
                <span class="pull-right popup-element-label">Локација:</span>
              </div>
              <div id="popup-location-value" class="popup-location-value popup-element-value col-xs-6">
              </div>
            </div>

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
            <input type="submit" name="submit-listing-popup" class="btn btn-primary" value="Прифати" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->

@endsection
