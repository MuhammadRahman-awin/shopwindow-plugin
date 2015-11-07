var SW = {
    init: function () {
        this.loadVerticalFeed();
        this.loadHorizontalFeed();
        this.togglePrceRangeInout();
    },

    loadHorizontalFeed: function(){
        jQuery("#nextHorizontal").on('click', function () {
            jQuery.ajax({
                url: shopwindow_params.ajaxurl,
                data:
                    jQuery('form#swFeedHorizontal').serialize(),
                success: function(response){
                    jQuery('#ajaxResponseHorizontal').html(response);
                    jQuery("#nextHorizontal").html("&raquo;");

                },
                error: function(errorThrown){
                    console.log(errorThrown);
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
                    jQuery("#nextVertical").html('Next &raquo;')
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            })
        });

        jQuery('#nextVertical').trigger('click');
    },

    togglePrceRangeInout: function() {
        jQuery("#maxPriceRange").focus(function () {
            jQuery(".range").attr("readonly", false);
        });
        jQuery(".maxPriceRadio").focus(function () {
            jQuery(".range").attr("readonly", true);
        });
    }
};

jQuery(document).ready(function() { SW.init(); });
