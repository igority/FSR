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

  @if ($errors->any())
      <div class="alert alert-danger">
        Донацијата не беше прифатена успешно. Корегирајте ги грешките и обидете се повторно
        <a href="javascript:document.getElementById('listingbox{{ old('listing_id') }}').scrollIntoView();">
          <button type="button" class="btn btn-default">Иди до донацијата</button>
        </a>
      </div>
  @endif

  @foreach ($active_listings->get() as $active_listing)
    <?php  $quantity_counter = 0;  ?>
    @foreach ($active_listing->listing_offers as $listing_offer)
      <?php if ($listing_offer->offer_status == 'active') {
    $quantity_counter += $listing_offer->quantity;
}  ?>
    @endforeach
      @if ($active_listing->quantity > $quantity_counter)
          <div id="listingbox{{$active_listing->id}}" name="listingbox{{$active_listing->id}}"></div>
          <!-- Default box -->
          <div class="box listing-box listing-box-{{$active_listing->id}} {{($active_listing->id == old('listing_id')) ? 'box-error' : 'collapsed-box' }}">
            <div class="box-header with-border listing-box-header">
              <a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">
                <div class="listing-image">
                  {{-- <img src="../img/avatar5.png" /> --}}
                  @if ($active_listing->image_id)
                    <img class="img-circle" alt="{{$active_listing->product->food_type->name}}" src="../../storage{{config('app.upload_path') . '/' . FSR\File::find($active_listing->image_id)->filename}}" />
                  @elseif ($active_listing->product->food_type->default_image)
                    <img class="img-circle" alt="{{$active_listing->product->food_type->name}}" src="{{$active_listing->product->food_type->default_image}}" />
                  @else
                    <img class="img-circle" alt="{{$active_listing->product->food_type->name}}" src="../img/food_types/food-general.jpg" />
                  @endif

                </div>
                <div class="header-wrapper">
                  <div id="listing-title-{{$active_listing->id}}" class="listing-title col-xs-12 panel">
                    <strong>
                      {{$active_listing->product->name}}
                    </strong>
                  </div>
                  <div class="header-elements-wrapper">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Истекува за:</span>

                      <span class="col-xs-12" id="expires-in-{{$active_listing->id}}"><strong>{{Carbon::parse($active_listing->date_expires)->diffForHumans()}}</strong></span>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Количина:</span>
                      <span class="col-xs-12" id="quantity-offered-{{$active_listing->id}}"><strong>{{$active_listing->quantity - $quantity_counter}} {{$active_listing->quantity_type->description}}</strong></span>

                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Локација:</span>
                      <span class="col-xs-12" id="donor-location-{{$active_listing->id}}"><strong>{{$active_listing->donor->location->name}}</strong></span>

                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Донирано од:</span>
                      <span class="col-xs-12" id="donor-info-{{$active_listing->id}}"><strong>{{$active_listing->donor->first_name}} {{$active_listing->donor->last_name}} | {{$active_listing->donor->organization->name}}</strong></span>

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
                    <span class="col-xs-12" id="pickup-time-{{$active_listing->id}}"><strong>од {{Carbon::parse($active_listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($active_listing->pickup_time_to)->format('H:i')}} часот</strong></span>
                  </div>
                  <div class="col-md-3 col-sm-6 listing-food-type ">
                    <span class="col-xs-12">Тип на храна:</span>
                    <span class="col-xs-12" id="food-type-{{$active_listing->id}}"><strong>{{$active_listing->product->food_type->name}}</strong></span>
                  </div>
                  <div class="col-md-5 col-sm-12 listing-description">
                    @if ($active_listing->description)
                      <span class="col-xs-12">Опис:</span>
                      <span class="col-xs-12" id="description-{{$active_listing->id}}"><strong>{{$active_listing->description}}</strong></span>
                    @endif
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-6 listing-input-wrapper">
                    <div class="panel col-xs-12" style="text-align: center;">Пополнете точно и кликнете на Внеси</div>
                    <div class="col-xs-12 form-group {{ ((old('listing_id') == $active_listing->id) && ($errors->has('quantity'))) ? 'has-error' : '' }} row">
                      {{-- <span class="col-xs-12">Потребна количина:</span> --}}
                      <label class="col-sm-6" for="quantity-needed">Потребна количина:</label>
                      <span class="col-sm-6">
                        <input id="quantity-needed-{{$active_listing->id}}" type="number"
                                min="0" max="{{$active_listing->quantity - $quantity_counter}}" step="0.1" name="quantity-needed"
                                class="form-control quantity-needed-input"
                                value="{{ ($active_listing->id == old('listing_id')) ? old('quantity') : $active_listing->quantity - $quantity_counter }}" >
                        <span id="quantity-type-inside-{{$active_listing->id}}" class="quantity-type-inside">{{$active_listing->quantity_type->description}}</span>
                      </span>
                      @if ((old('listing_id') == $active_listing->id) && ($errors->has('quantity')))
                     <span class="help-block listing-input-help-block pull-right">
                         <strong>{{ $errors->first('quantity') }}</strong>
                     </span>
                     @endif
                    </div>
                    <div class="col-xs-12 form-group {{ ((old('listing_id') == $active_listing->id) && ($errors->has('beneficiaries'))) ? 'has-error' : '' }} row">
                      {{-- <span class="quantity-type-inside">За</span> --}}
                      <label class="col-sm-6" for="beneficiaries-no">Предвидена за:</label>
                      <span class="col-sm-6">
                        <div id="portion-size-{{$active_listing->id}}" class="hidden">{{$active_listing->quantity_type->portion_size}}</div>
                        <input id="beneficiaries-no-{{$active_listing->id}}" type="number" min="0" max="99999999" step="1"
                                name="beneficiaries-no" class="form-control beneficiaries-no-input"
                                value="{{($active_listing->id == old('listing_id'))
                                            ? old('beneficiaries')
                                            : (($active_listing->quantity_type->portion_size)
                                                ? (int)(($active_listing->quantity - $quantity_counter) / $active_listing->quantity_type->portion_size)
                                                : 0)}}">
                        <span class="beneficiaries-no-inside">луѓе</span>
                      </span>
                      @if ((old('listing_id') == $active_listing->id) && ($errors->has('beneficiaries')))
                     <span class="help-block listing-input-help-block pull-right">
                         <strong>{{ $errors->first('beneficiaries') }}</strong>
                     </span>
                     @endif
                    </div>
                  </div>
                  <div class="col-md-6 listing-pickup-volunteer">
                    <div class="panel col-xs-12" style="text-align: center;">Волонтер за подигнување</div>
                    <div class="col-xs-12 form-group {{ ((old('listing_id') == $active_listing->id) && ($errors->has('volunteer_name'))) ? 'has-error' : '' }} row">
                      <label class="col-sm-6" for="pickup-volunteer-name">Име:</label>
                      <span class="col-sm-6">
                        <input type="text" id="pickup-volunteer-name-{{$active_listing->id}}" name="pickup-volunteer-name"
                                class="pickup-volunteer-name form-control"
                                value="{{($active_listing->id == old('listing_id'))
                                            ? old('volunteer_name')
                                            : Auth::user()->first_name . ' ' . Auth::user()->last_name }}">
                      </span>
                      @if ((old('listing_id') == $active_listing->id) && ($errors->has('volunteer_name')))
                     <span class="help-block listing-input-help-block pull-right">
                         <strong>{{ $errors->first('volunteer_name') }}</strong>
                     </span>
                     @endif
                    </div>
                    <div class="col-xs-12 form-group {{ ((old('listing_id') == $active_listing->id) && ($errors->has('volunteer_phone'))) ? 'has-error' : '' }} row">
                      <label class="col-sm-6" for="pickup-volunteer-phone">Број за контакт:</label>
                      <span class="col-sm-6">
                        <input type="text" id="pickup-volunteer-phone-{{$active_listing->id}}" name="pickup-volunteer-phone"
                              class="pickup-volunteer-phone form-control"
                              value="{{($active_listing->id == old('listing_id'))
                                          ? old('volunteer_phone')
                                          : Auth::user()->phone }}">
                      </span>
                      @if ((old('listing_id') == $active_listing->id) && ($errors->has('volunteer_phone')))
                     <span class="help-block listing-input-help-block pull-right">
                         <strong>{{ $errors->first('volunteer_phone') }}</strong>
                     </span>
                     @endif
                    </div>
                  </div>
                </div>
              </div>
              <div class="box-footer text-center">
                  <button type="button" id="listing-submit-button-{{$active_listing->id}}" name="listing-submit-button-{{$active_listing->id}}"
                            class="btn btn-primary btn-lg listing-submit-button" data-toggle="modal" data-target="#confirm-listing-popup">Внеси</button>
                </div>
            </div>

            <!-- /.box-footer-->
          </div>
          <!-- /.box -->



      @endif
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
