var SW = {
    init: function () {
        this.loadVerticalFeed();
        this.loadHorizontalFeed();
    },

    loadHorizontalFeed: function(){
        jQuery("#nextHorizontal").on('click', function () {
            jQuery.ajax({
                url: shopwindow_params.ajaxurl,
                data:
                    jQuery('form#swFeedHorizontal').serialize(),
                success: function(response){
                    jQuery('#ajaxResponseHorizontal').html(response);
                    jQuery("#nextHorizontal").text('next >>')
                },
                error: function(errorThrown){
                    alert(JSON.stringify(errorThrown));
                }
            })
        });

        jQuery('#nextHorizontal').trigger('click');
    },

    loadVerticalFeed: function() {
        jQuery("#nextVertical").on('click', function () {
            jQuery.ajax({
                url: shopwindow_params.ajaxurl,
                data:
                    jQuery('form#swFeedVertical').serialize(),
                success: function(response){
                    jQuery('#ajaxResponseVertical').html(response);
                    jQuery("#nextVertical").text('next >>')
                },
                error: function(errorThrown){
                    alert(JSON.stringify(errorThrown));
                }
            })
        });

        jQuery('#nextVertical').trigger('click');

    }
};

jQuery(document).ready(function() { SW.init(); });
