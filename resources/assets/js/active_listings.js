/*  If there is error in server validation, scroll to the listing with the error */
$(document).ready(function(){
  if (document.getElementById(window.location.hash.substring(1))) {
      document.getElementById(window.location.hash.substring(1)).scrollIntoView();
  }
});


  /* On quantity-needed input field change:
      - limit the value to the max_quantity
      - auto update the beneficiaries number input field in regards with the portion size
   */
$('.quantity-needed-input').on('input', function() {

  var id = this.id.replace('quantity-needed-', '');
  var max_quantity = $('#quantity-offered-' + id).text().split(' ')[0];
  console.log(max_quantity);
  var isnum = /^\d+$/.test(this.value);
  console.log(this.value);
  if ((this.value != '') && (parseInt(this.value) > max_quantity || !$.isNumeric(this.value))) {
    $(this).val(max_quantity);
  }

  var portion_size = $('#portion-size-' + id).text();
  $('#beneficiaries-no-' + id).val(parseInt(this.value / portion_size));
});


/* When a listing is accepted, fill in the data in a popup for appearence, and fill in the form with hidden fields for sending */
$('.listing-submit-button').on('click', function() {
  var id = this.id.replace("listing-submit-button-", "");

  /* Find elements and extract values */
  var title = $('#listing-title-' + id).text().trim();
  var quantity_number = $('#quantity-needed-' + id).val();
  var quantity_description = $('#quantity-needed-' + id).val() + " " + $('#quantity-type-inside-' + id).text().trim();
  var beneficiaries = $('#beneficiaries-no-' + id).val();
  var expires_in = $('#expires-in-' + id).text().trim();
  var pickup_time = $('#pickup-time-' + id).text().trim();
  var location = $('#donor-location-' + id).text().trim();
  var volunteer_name = $('#pickup-volunteer-name-' + id).val();
  var volunteer_phone = $('#pickup-volunteer-phone-' + id).val();

  /* Fill popup for appearence */
  $('#popup-title').text(title);
  $('#popup-quantity-needed-value').text(quantity_description);
  $('#popup-beneficiaries-no-value').text(beneficiaries);
  $('#popup-expires-in-value').text(expires_in);
  $('#popup-pickup-time-value').text(pickup_time);
  $('#popup-location-value').text(location);
  $('#popup-volunteer-name-value').text(volunteer_name);
  $('#popup-volunteer-phone-value').text(volunteer_phone);

  /* Fill form with hidden elements  */
  $( "#listing-confirm-form" ).append( "<input class='input-element-popup' type='hidden' name='listing_id' value='" + id  + "'>" );
  $( "#listing-confirm-form" ).append( "<input class='input-element-popup' type='hidden' name='quantity' value='" + quantity_number  + "'>" );
  $( "#listing-confirm-form" ).append( "<input class='input-element-popup' type='hidden' name='beneficiaries' value='" + beneficiaries  + "'>" );
  $( "#listing-confirm-form" ).append( "<input class='input-element-popup' type='hidden' name='volunteer_name' value='" + volunteer_name  + "'>" );
  $( "#listing-confirm-form" ).append( "<input class='input-element-popup' type='hidden' name='volunteer_phone' value='" + volunteer_phone  + "'>" );

});
/* On click update volunteer (in Accepted Listings) fill in the data in the update-volunteer-popup popup */
$('.update-volunteer-button').on('click', function() {
  var id = this.id.replace("update-volunteer-button-", "");

  /* Find elements and extract values */
  var volunteer_name = $('#pickup-volunteer-name-' + id).val();
  var volunteer_phone = $('#pickup-volunteer-phone-' + id).val();

  /* Fill popup for appearence */
  $('#popup-volunteer-name-value').text(volunteer_name);
  $('#popup-volunteer-phone-value').text(volunteer_phone);

  /* Fill form with hidden elements  */
  $( "#update-volunteer-form" ).append( "<input class='input-element-popup' type='hidden' name='listing_offer_id' value='" + id  + "'>" );
  $( "#update-volunteer-form" ).append( "<input class='input-element-popup' type='hidden' name='volunteer_name' value='" + volunteer_name  + "'>" );
  $( "#update-volunteer-form" ).append( "<input class='input-element-popup' type='hidden' name='volunteer_phone' value='" + volunteer_phone  + "'>" );

});
//on dismiss, remove all dynamic input elements from popup
$('.modal').on('hide.bs.modal', function () {
  $('.input-element-popup').remove();
})
