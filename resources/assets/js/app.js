
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});

$( document ).ready(function() {
  //$( "#registerTypeSelect" ).trigger( "change" );
});



$('#register_type_select').on('change', function() {

  //enable disable fields
  if (this.value) {
      $("input").prop('disabled', false);
      $("#register_organization_select").prop('disabled', false);
      $("#register_location_select").prop('disabled', false);
      $("#register_donor_type_select").prop('disabled', false);
      $("button").prop('disabled', false);
      //show/hide Donor Types select box
      if (this.value == 'donor') {
        $("#register_donor_type_wrapper").removeClass('hidden');
      } else {
        $("#register_donor_type_wrapper").addClass('hidden');
      }

  } else {
      $("input").prop('disabled', true);
      $("#register_organization_select").prop('disabled', true);
      $("#register_location_select").prop('disabled', true);
      $("#register_donor_type_select").prop('disabled', true);
      $("button").prop('disabled', true);
      //hide (if not hidden) Donor Types select box
      $("#register_donor_type_wrapper").addClass('hidden');
  }

  //remove all from organizations select box (except the default one)
  $('#register_organization_select').children('option:not(:first)').remove();
  //get the organizations retrieved from the database with ajax
  $.post('register/organizations', {'type': this.value, '_token':$('meta[name="csrf-token"]').attr('content')}, function(data) {
    if (data) {
      //append the other options retrieved from database
      $.each(data,function(key, value) {
        $('#register_organization_select').append('<option value=' + value.id + '>' + value.name + '</option>');
      });
    }
  });
});
