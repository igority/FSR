<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//welcome
Route::get('/', 'WelcomeController@index')->name('welcome');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
  //get organizations with ajax
Route::post('register/organizations', 'Auth\RegisterController@getOrganizations')->name('register.organizations');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

//donor routes
Route::get('donor/home', 'Donor\HomeController@index')->name('donor.home');
Route::get('donor/new_listing', 'Donor\NewListingController@index')->name('donor.new_listing');
Route::post('donor/new_listing', 'Donor\NewListingController@handle_post');


//cso routes
Route::get('cso/home', 'Cso\HomeController@index')->name('cso.home');
Route::get('cso/active_listings', 'Cso\ActiveListingsController@index')->name('cso.active_listings');
