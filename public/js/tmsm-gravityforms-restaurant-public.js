(function( $ ) {
	'use strict';

  $(document).on('gform_post_render', function(event, form_id, current_page){

    $('.tmsm-gravityforms-restaurant-date .ginput_container *').datepicker('destroy');

    if(($('.tmsm-gravityforms-restaurant-result').html().indexOf('tmsm-gravityforms-restaurant-full') > -1)){
      $('.tmsm-gravityforms-restaurant-availability .ginput_container *').val(0);
    }
    else{
      $('.tmsm-gravityforms-restaurant-availability .ginput_container *').val(1);
    }

  });

})( jQuery );
