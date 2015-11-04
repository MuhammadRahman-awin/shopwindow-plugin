var SW = {
    init: function () {
        jQuery("#next").on('click', function () {
            jQuery.ajax({
                url: shopwindow_params.ajaxurl,
                'action':'get_sw_product',

                data:
                    jQuery('form#swFeed').serialize(),

                success: function(response){
                    console.log(response);
                    jQuery('#ajaxResponse').html(response);
                },
                error: function(errorThrown){
                    alert(JSON.stringify(errorThrown));
                }
            })
        });

        jQuery('#next').trigger('click');
    }
};

jQuery(document).ready(function() { SW.init(); });
