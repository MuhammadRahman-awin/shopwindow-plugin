var SW = {
    init: function () {
        jQuery("#next").click(function () {
            jQuery.ajax({
                //url: shopwindow_params.ajaxurl,
                url: 'http://localhost/wp/',
                success: function( data ) {
                    console.log(this);
                }
            })
        });
    }
};

jQuery(document).ready(function() { SW.init(); });
