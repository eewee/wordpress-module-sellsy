jQuery(document).ready(function($) {

    $("#contact_form_setting_opportunity_pipeline").change(function(){
       var id_pipeline = $(this).val();
       if (id_pipeline != 0) {

           // AJAX : https://codex.wordpress.org/AJAX_in_Plugins
           var data = {
               'action': 'eewee_my_backend_action',
               'contact_form_id': ajax_object.contact_form_id,  // Send data
               'id_pipeline': id_pipeline,                      // Send data
           };

           // We can also pass the url value separately from ajaxurl for front end AJAX implementations
           $.post(ajax_object.ajax_url, data, function(response) {
               var options = [];
               var j = JSON.parse( response );
               $.each(j, function(kJ, vJ){
                   options.push('<option value="'+kJ+'">'+vJ+'</option>');
               });
               $('#contact_form_setting_opportunity_step option').remove();
               $('#contact_form_setting_opportunity_step').append( options );
           });
       }
    });

    /*
     jQuery.ajax({
         type:"POST",
         url: "/wp-admin/admin-ajax.php",   // https://codex.wordpress.org/AJAX_in_Plugins
         data: {
             action: "my_test_action",
             form_data : newFormChange
         },
         success: function (data) {
             console.log(data);
         },
         error : function( data ) {
             console.log( 'Erreur…' );
         }
     });

    $.ajax({
        url : adminAjax,
        method : 'POST',
        data : {
            action : 'get_my_post',
            id : 1 // en vrai, récupérer l'id du contenu en variable ;-)
        },
        success : function( data ) {
            if ( data.success ) {
                var article = $( data.data.article );
                $( '#content' ).html( article );
            } else {
                console.log( data.data );
            }
        },
        error : function( data ) {
            console.log( 'Erreur…' );
        }
    });
*/

});
