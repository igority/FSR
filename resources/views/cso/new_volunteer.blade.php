@extends('layouts.master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header new-volunteer-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Додади волонтер</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Донор</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/volunteers">
				<i class="fa fa-user-circle"></i> Волонтери</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/volunteers/new">Додади нов</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content new-volunteer-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="new-volunteer-box box">
		<!--
		<div class="box-header with-border">

			<div id="new-volunteer-title" class="new-volunteer-title col-xs-12">
				{{-- <span class="pull-right"><strong>{{$user->email}} | {{$user->organization->name}}</strong></span> --}}
			</div>

		</div>
	-->
	<form id="new-volunteer-form" class="" action="{{ route('cso.new_volunteer') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
		<div class="volunteer-box-body-wrapper">
			<div class="box-body">
				<div id="new-volunteer-image" class="col-md-4 col-xs-12 new-volunteer-image">
					<div class="col-xs-12 form-group">
							<img class="img-rounded" alt="Слика за волонтер" src="{{url('img/avatar5.png')}}" />
					</div>
					<div class="col-xs-12 form-group {{ ($errors->has('new-volunteer-image')) ? ' has-error' : '' }}">
							<label for="new-volunteer-image">Внеси слика:</label>
              <input id="new-volunteer-image" type="file" class="form-control" name="volunteer-image" value="{{ old('new-volunteer-image') }}">
							@if ($errors->has('new-volunteer-image'))
						 <span class="help-block">
								 <strong>{{ $errors->first('new-volunteer-image') }}</strong>
						 </span>
		 				 @endif
					</div>
				</div>

				<div id="new-volunteer-info" class="col-md-8 col-xs-12 new-volunteer-info">

					<!-- First name -->
					<div class="row form-group{{ ($errors->has('new-volunteer-first-name') || $errors->has('new-volunteer-first-name')) ? ' has-error' : '' }}">
						<div class="new-volunteer-first-name-label col-sm-4 col-xs-12">
							<label for="new-volunteer-first-name">Име:</label>
						</div>
						<div class="new-volunteer-first-name-value col-sm-8 col-xs-12">
							<input type="text" name="volunteer-first-name" class="form-control" value="{{ (old('new-volunteer-first-name')) ? old('new-volunteer-first-name') : '' }}" required>
							@if ($errors->has('new-volunteer-first-name'))
								<span class="help-block">
									<strong>{{ $errors->first('new-volunteer-first-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Last name -->
					<div class="row  form-group{{ ($errors->has('new-volunteer-last-name') || $errors->has('new-volunteer-last-name')) ? ' has-error' : '' }}">
						<div class="new-volunteer-last-name-label col-sm-4 col-xs-12">
							<label for="new-volunteer-last-name">Презиме:</label>
						</div>
						<div class="new-volunteer-last-name-value col-sm-8 col-xs-12">
							<input type="text" name="volunteer-last-name" class="form-control" value="{{ (old('new-volunteer-last-name')) ? old('new-volunteer-last-name') : '' }}" required>
							@if ($errors->has('new-volunteer-last-name'))
								<span class="help-block">
									<strong>{{ $errors->first('new-volunteer-last-name') }}</strong>
								</span>
							@endif
						</div>
					</div>


					<div class="row form-group{{ ($errors->has('new-volunteer-email') || $errors->has('new-volunteer-email')) ? ' has-error' : '' }}">
						<div class="new-volunteer-email-label col-sm-4 col-xs-12">
							<label for="new-volunteer-email">Емаил:</label>
						</div>
						<div class="new-volunteer-email-value col-sm-8 col-xs-12">
							<input type="email" name="volunteer-email" class="form-control" value="{{ (old('new-volunteer-email')) ? old('new-volunteer-email') : '' }}" required>
							@if ($errors->has('new-volunteer-email'))
								<span class="help-block">
									<strong>{{ $errors->first('new-volunteer-email') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="row form-group{{ ($errors->has('new-volunteer-phone') || $errors->has('new-volunteer-phone')) ? ' has-error' : '' }}">
						<div class="new-volunteer-phone-label col-sm-4 col-xs-12">
							<label for="new-volunteer-phone">Телефон:</label>
						</div>
						<div class="new-volunteer-phone-value col-sm-8 col-xs-12">
							<input type="text" name="volunteer-phone" class="form-control" value="{{ (old('new-volunteer-phone')) ? old('new-volunteer-phone') : '' }}" required>
							@if ($errors->has('new-volunteer-phone'))
								<span class="help-block">
									<strong>{{ $errors->first('new-volunteer-phone') }}</strong>
								</span>
							@endif
						</div>
					</div>


				</div>

			</div>
		</div>
		<div class="box-footer text-center">
			<div class="pull-right">
				<button id="new-volunteer-submit" type="submit" name="new-volunteer-submit" class="btn btn-primary" >Потврди</button>
				<a href="{{route('cso.volunteers')}}" id="cancel-new-volunteer" name="cancel-new-volunteer"
				class="btn btn-default">Откажи</a>
			</div>
		</div>
	</form>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->


</section>
<!-- /.content -->

@endsection
