var SW = {
    init: function () {
        this.loadVerticalFeed();
        this.loadHorizontalFeed();
        this.loadHorizontalFeedShortCode();
        this.togglePrceRangeInout();
        this.displayReport();
        this.resetForm();
        this.displayAnalytics();
    },

    loadHorizontalFeed: function(){
        jQuery("#nextHorizontal").on('click', function () {
            jQuery.ajax({
                url: awindatafeed_params.ajaxurl,
                data:
                    jQuery('form#swFeedHorizontal').serialize(),
                success: function(response){
                    jQuery('#ajaxResponseHorizontal').html(response);
                    jQuery("#nextHorizontal").show();
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
                url: awindatafeed_params.ajaxurl,
                data:
                    jQuery('form#swFeedVertical').serialize(),
                success: function(response){
                    jQuery('#ajaxResponseVertical').html(response);
                    jQuery("#nextVertical").show();
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

    loadHorizontalFeedShortCode: function() {
        jQuery("#nextHorizontalSc").on('click', function () {
            console.log('foox');

            jQuery.ajax({
                url: awindatafeed_params.ajaxurl,
                data:
                    jQuery('form#swFeedHorizontalSc').serialize(),
                success: function(response){
                    jQuery('#ajaxResponseHorizontalSc').html(response);
                    jQuery("#nextHorizontalSc").show();
                    jQuery("#nextHorizontalSc").html("&raquo;");
                    SW.processAnalytics();
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            })
        });

        jQuery('#nextHorizontalSc').trigger('click');
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
        jQuery("a[class^='trackImage']").on('click', function(e){
            var feedId = getFeedId(this.className);
            jQuery.ajax({
                url: awindatafeed_params.ajaxurl,
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

    displayReport: function() {
        jQuery("#reportButton").on('click', function () {
            jQuery('section.analytics').show();
        });
    },

    resetForm: function() {
        jQuery("#resetFilters").on('click', function () {
            var form = jQuery('form#swFilters');
            form.find('input:text, input:password, input:file, select, textarea').val('');
            form.find('input:radio, input:checkbox')
                .removeAttr('checked').removeAttr('selected');
        });
    },

    displayAnalytics: function() {
        jQuery( "#reportButton" ).click(function() {
            jQuery("#reportButton").hide();
            jQuery( "#analytics" ).show('slow');
        });
    }
};

jQuery(document).ready(function() { SW.init(); });
