//Implement datatables
jQuery(document).ready(function() {
  jQuery('table.data_table').DataTable({
    paging: false,
    dom: 'Bfrtip',
    buttons: [
      'copy', 'excel', 'csv', 'print'
    ]
  });
});

//Implement toggle containers
jQuery(document).ready(function(){
  
  //  Summary: JQuery subroutine to support toggle containers
  //  Details: This script will first hide any elements with a 'toggleContiner' class. It will then find any elements with a class of 'toggleContinerTrigger', and attach a function to them which will toggle the following element (should be the toggleContainer) on or off when clicked.

  //Hide any elements with a 'toggleContainer' class
    jQuery(".toggleContainer").hide();
  //Attach a function to any elements with a class of 'toggleContainerTrigger', which will toggle the following element (should be the toggleContainer) on or off when clicked.
    jQuery(".toggleContainerTrigger").click(function(){
      jQuery(this).toggleClass("active").next().slideToggle("fast");
    });
});

//implement people finder customizations
jQuery(document).ready(function(){

  //establish click handler for people finder field approval all button
  jQuery('.user-edit-php a.pf_approve_all, .profile-php a.pf_approve_all').on('click', function(e) {
    e.preventDefault();
    //alert('You clicked the approve all button.');
    jQuery('.user-edit-php a.pf_approve, .profile-php a.pf_approve').each(function( index ) {
      //console.log( index + ": " + jQuery( this ).text() );
      jQuery(this).trigger('click');
    });
  });

  //establish click handler for people finder field approval buttons
  jQuery('.user-edit-php a.pf_approve, .profile-php a.pf_approve').on('click', function(e) {
    e.preventDefault();
    //get field name
    pf_field_name=jQuery(this).attr('data-pf-field');
    //get field value
    pf_field_value=jQuery('#'+pf_field_name).val();
    //set approved field value to user-entered field value
    jQuery('input#'+pf_field_name+'_approved').val(pf_field_value);
    //change the input field style to indicate that it is ready to be saved
    jQuery('#'+pf_field_name).css('color','green').css('font-weight','bold');
    //message about saving
    //alert('This field is marked for approval; now you must save.');
  });

  //establish click handler for people finder field disapproval all button
  jQuery('.user-edit-php a.pf_disapprove_all, .profile-php a.pf_disapprove_all').on('click', function(e) {
    e.preventDefault();
    alert('You clicked the disapprove all button.');
    jQuery('.user-edit-php a.pf_disapprove, .profile-php a.pf_disapprove').each(function( index ) {
      //console.log( index + ": " + jQuery( this ).text() );
      jQuery(this).trigger('click');
    });
  });

  //establish click handler for people finder field disapprove buttons
  jQuery('.user-edit-php a.pf_disapprove, .profile-php a.pf_disapprove').on('click', function(e) {
    e.preventDefault();
    //get field name
    pf_field_name=jQuery(this).attr('data-pf-field');
    //get field value
    pf_field_value=jQuery('input#'+pf_field_name).val();
    //set approved field value to blank
    jQuery('input#'+pf_field_name+'_approved').val('');
    //change the input field style to indicate that it is ready to be saved
    jQuery('input#'+pf_field_name).css('text-decoration','line-through');
    //message about saving
    //alert('This field is marked for disapproval; now you must save.');
  });

});
