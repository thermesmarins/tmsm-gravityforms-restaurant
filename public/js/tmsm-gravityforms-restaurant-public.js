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

 
gform.addFilter( 'gform_datepicker_options_pre_init', function( optionsObj, formId, fieldId ) {

  if ( formId == 1 && fieldId == 10 ) { 
 
    // Jours Fériés pour 2026 (Exemple : 1er Janvier, Lundi de Pâques, 1er Mai)
    var disabledDates = ["10/11/2025", "04/06/2026", "05/01/2026"]; 
    optionsObj.beforeShowDay = function(date) {
        // 1. Vérification des week-ends (0 = Dimanche, 6 = Samedi)
        // var day = date.getDay();
        // if (day == 0 || day == 6) {
        //     return [false, 'weekend']; // Désactive les week-ends
        // }
        console.log(time_limit.time_limit);
        // 2. Vérification des jours spécifiques
      var checkdate = jQuery.datepicker.formatDate('dd/mm/yy', date);
      var tl = (typeof time_limit.time_limit === 'string' && /^\d{2}:\d{2}$/.test(time_limit.time_limit)) ? time_limit.time_limit : '15:00';
      var m = tl.match(/^(\d{2}):(\d{2})$/);
      var now = new Date();
      var nowHM = now.getHours() * 100 + now.getMinutes();
      var cutoffHM = m ? (parseInt(m[1], 10) * 100 + parseInt(m[2], 10)) : 1500;

      if (nowHM >= cutoffHM) {
        var todayDate = now.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' });
        disabledDates.push(todayDate);
      }
        if (disabledDates.indexOf(checkdate) != -1) {
            return [false, 'holiday']; // Désactive les jours fériés
        }
        
        // 3. Si aucune condition n'est remplie, la date est sélectionnable
        return [true, ''];
    };
}
return optionsObj;
} );
})( jQuery );
