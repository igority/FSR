@extends('layouts.master')
@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header active-listings-content-header">
    <h1><i class="fa fa-cutlery"></i>
      <span>Активни понуди</span>
      @if ($active_listings->get()->count())
        <span> ({{$active_listings->get()->count()}})</span>
      @endif
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Примател</a></li>
      <li><a href="/{{Auth::user()->type()}}/active_listings"><i class="fa fa-cutlery"></i> Активни понуди</a></li>
    </ol>
  </section>



<!-- Main content -->
<section class="content active-listings-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif

  @foreach ($active_listings->get() as $active_listing)
    <?php  $quantity_counter = 0;  ?>
    @foreach ($active_listing->listing_offers as $listing_offer)
      <?php  $quantity_counter += $listing_offer->quantity;  ?>
    @endforeach
      @if ($active_listing->quantity > $quantity_counter)


          <!-- Default box -->
          <div class="box listing-box collapsed-box">
            <div class="box-header with-border listing-box-header">
              <a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">
                <div class="listing-image">
                  {{-- <img src="../img/avatar5.png" /> --}}
                  @if ($active_listing->image_id)
                    <img class="img-circle" alt="{{$active_listing->food_type->name}}" src="../../storage{{config('app.upload_path') . '/' . FSR\File::find($active_listing->image_id)->filename}}" />
                  @elseif ($active_listing->food_type->default_image_id)
                    <img class="img-circle" alt="{{$active_listing->food_type->name}}" src="{{$active_listing->food_type->default_image_id}}" />
                  @else
                    <img class="img-circle" alt="{{$active_listing->food_type->name}}" src="../img/food_types/food-general.jpg" />
                  @endif

                </div>
                <div class="header-wrapper">
                  <div class="listing-title col-xs-12 panel">
                    <strong>
                      {{$active_listing->title}}
                    </strong>
                  </div>
                  <div class="header-elements-wrapper">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Истекува за:</span>
                      <span class="col-xs-12"><strong>{{Carbon::parse($active_listing->date_expires)->diffForHumans()}}</strong></span>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Количина:</span>
                      <span class="col-xs-12"><strong>{{$active_listing->quantity - $quantity_counter}} {{$active_listing->quantity_type->description}}</strong></span>

                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Локација:</span>
                      <span class="col-xs-12"><strong>{{$active_listing->donor->location->name}}</strong></span>

                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <span class="col-xs-12">Донирано од:</span>
                      <span class="col-xs-12"><strong>{{$active_listing->donor->first_name}} {{$active_listing->donor->last_name}} | {{$active_listing->donor->organization->name}}</strong></span>

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
                    <span class="col-xs-12"><strong>од {{Carbon::parse($active_listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($active_listing->pickup_time_to)->format('H:i')}} часот</strong></span>
                  </div>
                  <div class="col-md-3 col-sm-6 listing-food-type ">
                    <span class="col-xs-12">Тип на храна:</span>
                    <span class="col-xs-12"><strong>{{$active_listing->food_type->name}}</strong></span>
                  </div>
                  <div class="col-md-5 col-sm-12 listing-description">
                    @if ($active_listing->description)
                      <span class="col-xs-12">Опис:</span>
                      <span class="col-xs-12"><strong>{{$active_listing->description}}</strong></span>
                    @endif
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-6 listing-input-wrapper">
                    <div class="panel col-xs-12" style="text-align: center;">Пополнете точно и кликнете на Внеси</div>
                    <div class="col-xs-12 row">
                      {{-- <span class="col-xs-12">Потребна количина:</span> --}}
                      <label class="col-sm-6" for="quantity-needed">Потребна количина:</label>
                      <span class="col-sm-6">
                        <input type="number" min="0" max="{{$active_listing->quantity - $quantity_counter}}" step="0.1" name="quantity-needed"
                                class="form-control quantity-needed-input" value="{{$active_listing->quantity - $quantity_counter}}" >
                        <span class="quantity-type-inside">{{$active_listing->quantity_type->description}}</span>
                      </span>
                    </div>
                    <div class="col-xs-12 row">
                      {{-- <span class="quantity-type-inside">За</span> --}}
                      <label class="col-sm-6" for="beneficiaries-no">Предвидена за:</label>
                      <span class="col-sm-6">
                        <input type="number" min="0" max="99999999" step="1" name="beneficiaries-no" class="form-control beneficiaries-no-input" value="{{(int)($active_listing->quantity / $active_listing->quantity_type->portion_size)}}">
                        <span class="beneficiaries-no-inside">луѓе</span>
                      </span>
                    </div>
                  </div>
                  <div class="col-md-6 listing-pickup-volunteer">
                    <div class="panel col-xs-12" style="text-align: center;">Волонтер за подигнување</div>
                    <div class="col-xs-12 row">
                      <label class="col-sm-6" for="pickup-volunteer-name">Име:</label>
                      <span class="col-sm-6">
                        <input type="text" name="pickup-volunteer-name" class="pickup-volunteer-name form-control" value="{{Auth::user()->first_name}} {{Auth::user()->last_name}}">
                      </span>
                    </div>
                    <div class="col-xs-12 row">
                      <label class="col-sm-6" for="pickup-volunteer-phone">Број за контакт:</label>
                      <span class="col-sm-6">
                        <input type="text" name="pickup-volunteer-phone" class="pickup-volunteer-phone form-control" value="{{Auth::user()->phone}}">
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="box-footer text-center">
                  <button type="button" name="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#confirm-listing-popup">Внеси</button>
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
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->

@endsection
