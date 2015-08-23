/**
 * This file working for customizer controls.
 */
jQuery(document).ready(function($) {
    
    /**
    *****************************
    * Reset button
    *****************************
    */
    $('.awf-reset-button').on('click', function () {

        var section = $(this).attr('id');

        var msg = confirm(AWF_Customizer_Reset.confirm);

        if (!msg) return;

        var data = {
            wp_customize: 'on',
            action: 'reset_customizer_settings',
            reset_section: section,
        };

        $.post(ajaxurl, data, function () {
            wp.customize.state('saved').set(true);
            location.reload();
        });

    });
    
    
    /**
    *****************************
    * Fields group
    *****************************
    */
    
    // check if group opened
    $('*[id*=fields_group].customize-control').click(function() {
        
       if ( $(this).find('.awf-fields-group').hasClass('open')) {
          $(this).find('.awf-fields-group').removeClass('open');
          return false;
       } else {
          $(this).find('.awf-fields-group').addClass('open');
          return false;
       }
        
    });
    
    // get number from id
    var getNumericPart = function(id) {
        
        var $num = id.replace(/\D+/g, '');
        return $num;
        
    }
    
    // Heading section
    $('[id$=awf_headings_styles] .awf-fields-group').each(function() {
        
        var num = getNumericPart($(this).attr('id'));
        
        $('[id$=awf_headings_styles] .accordion-section-title').click(function() {
           $('[id$=awf_headings_styles] .customize-control').hide();
           $('[id$=awf_headings_styles] *[id*=fields_group].customize-control').show();
        });
        
        $('[id$=awf_headings_styles] *[id*='+num+'_fields_group].customize-control').click(function() {
           $('[id$=awf_headings_styles] .customize-control').not($('[id$=awf_headings_styles] *[id*='+num+'].customize-control')).hide().find('.awf-fields-group').removeClass('open');
           $('[id$=awf_headings_styles] *[id*='+num+'].customize-control').toggle();
           $('[id$=awf_headings_styles] *[id*=fields_group].customize-control').show();
        });
        
    });
    
    // Custom controls section
    if ( $('[id$=awf_custom_controls_styles]')[0] ) {
        
        $('[id$=awf_custom_controls_styles] .awf-fields-group').each(function() {

            var num = getNumericPart($(this).attr('id'));

            $('[id$=awf_custom_controls_styles] .accordion-section-title').click(function() {
               $('[id$=awf_custom_controls_styles] .customize-control').hide();
               $('[id$=awf_custom_controls_styles] *[id*=fields_group].customize-control').show();
            });

            $('[id$=awf_custom_controls_styles] *[id*='+num+'_fields_group].customize-control').click(function() {
               $('[id$=awf_custom_controls_styles] .customize-control').not($('[id$=awf_custom_controls_styles] *[id*='+num+'].customize-control')).hide().find('.awf-fields-group').removeClass('open');
               $('[id$=awf_custom_controls_styles] *[id*='+num+'].customize-control').toggle();
               $('[id$=awf_custom_controls_styles] *[id*=fields_group].customize-control').show();
            });

        });
        
    }
       
});