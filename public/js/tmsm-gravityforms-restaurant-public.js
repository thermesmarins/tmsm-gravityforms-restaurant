(function( $ ) {
	'use strict';

  $(document).on('gform_post_render', function(event, form_id, current_page){

    if ($.isFunction( $('.tmsm-gravityforms-restaurant-date .ginput_container *').datepicker)) {
      $('.tmsm-gravityforms-restaurant-date .ginput_container *').datepicker('destroy');
    } else {
    }

    if( $('.tmsm-gravityforms-restaurant-result').length > 0 && ($('.tmsm-gravityforms-restaurant-result').html().indexOf('tmsm-gravityforms-restaurant-full') > -1)){
      $('.tmsm-gravityforms-restaurant-availability .ginput_container *').val(0);
    }
    else{
      $('.tmsm-gravityforms-restaurant-availability .ginput_container *').val(1);
    }

  });

})( jQuery );
