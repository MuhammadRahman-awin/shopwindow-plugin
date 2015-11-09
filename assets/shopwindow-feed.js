var SW = {
    init: function () {
        this.loadVerticalFeed();
        this.loadHorizontalFeed();
        this.togglePrceRangeInout();
        this.resetForm();
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
                    SW.processAnalytics();
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
                    SW.processAnalytics();
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            })
        });

        jQuery('#nextVertical').trigger('click');
    },

    togglePrceRangeInout: function() {
        if(jQuery("#maxPriceRange").is(':checked')) {
            jQuery(".range").attr("readonly", false);
        };
        jQuery("#maxPriceRange").focus(function () {
            jQuery(".range").attr("readonly", false);
        });
        jQuery(".maxPriceRadio").focus(function () {
            jQuery(".range").attr("readonly", true);
        });
    },

    processAnalytics: function () {
        jQuery("a[class^='track-'],a[class^='trackImage']").on('click', function(e){
            var feedId = getFeedId(this.className);
            jQuery.ajax({
                url: shopwindow_params.ajaxurl,
                data: {
                    'action': 'track_user_click',
                    'feedId': feedId
                },
                success: function(response){
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            })
        });

        var getFeedId = function (classNameString) {
            var id = classNameString.split("-");
            return id[1];
        }
    },

    resetForm: function() {
        jQuery("#resetFilters").on('click', function () {
            var form = jQuery('form#swFilters');
            form.find('input:text, input:password, input:file, select, textarea').val('');
            form.find('input:radio, input:checkbox')
                .removeAttr('checked').removeAttr('selected');
        });
    }
};

jQuery(document).ready(function() { SW.init(); });
