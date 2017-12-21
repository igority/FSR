
/* On click delete offer (in Accepted Listings) fill the popup with hidden id field */
$('.delete-offer-button').on('click', function() {
  var id = this.id.replace("delete-offer-button-", "");
alert('test');
  /* Fill form with hidden elements  */
  $( "#delete-offer-form" ).append( "<input class='input-element-popup' type='hidden' name='listing_offer_id' value='" + id  + "'>" );
});
