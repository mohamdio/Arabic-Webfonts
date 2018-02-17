/**
 * This file adds some LIVE to the Theme Customizer live preview.
 */
( function( $ ) {
    
    /**
    *****************************
    * Body Styles section
    *****************************
    */
    
    // Update body font family in real time...
	wp.customize( 'awf_body_font_family', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl, body.rtl header, body.rtl footer, body.rtl .content, body.rtl .sidebar, body.rtl p, body.rtl h1, body.rtl h2, body.rtl h3, body.rtl h4, body.rtl h5, body.rtl h6, body.rtl ul, body.rtl li, body.rtl div, body.rtl nav, body.rtl nav a, body.rtl nav ul li, body.rtl input, body.rtl button, body.rtl label, body.rtl textarea, body.rtl input::placeholder').css('font-family', newval );
		} );
	} );
    
    // Update body font size in real time...
	wp.customize( 'awf_body_font_size', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl, body.rtl header, body.rtl footer, body.rtl .content, body.rtl .sidebar, body.rtl p, body.rtl h1, body.rtl h2, body.rtl h3, body.rtl h4, body.rtl h5, body.rtl h6, body.rtl ul, body.rtl li, body.rtl div, body.rtl nav, body.rtl nav a, body.rtl nav ul li, body.rtl input, body.rtl button, body.rtl label, body.rtl textarea, body.rtl input::placeholder').css('font-size', newval+ 'px' );
		} );
	} );
    
    // Update body line height in real time...
	wp.customize( 'awf_body_line_height', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl, body.rtl header, body.rtl footer, body.rtl .content, body.rtl .sidebar, body.rtl p, body.rtl h1, body.rtl h2, body.rtl h3, body.rtl h4, body.rtl h5, body.rtl h6, body.rtl ul, body.rtl li, body.rtl div, body.rtl nav, body.rtl nav a, body.rtl nav ul li, body.rtl input, body.rtl button, body.rtl label, body.rtl textarea, body.rtl input::placeholder').css('line-height', newval );
		} );
	} );
    
    
    /**
    *****************************
    * Paragraphs Styles section
    *****************************
    */

	// Update paragraphs font family in real time...
	wp.customize( 'awf_paragraphs_font_family', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl p').css('font-family', newval );
		} );
	} );
    
    // Update paragraphs font size in real time...
	wp.customize( 'awf_paragraphs_font_size', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl p').css('font-size', newval+ 'px' );
		} );
	} );
    
    // Update paragraphs line height in real time...
	wp.customize( 'awf_paragraphs_line_height', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl p').css('line-height', newval );
		} );
	} );
    
    // Update paragraphs text decoration in real time...
	wp.customize( 'awf_paragraphs_text_decoration', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl p').css('text-decoration', newval );
		} );
	} );
    
    /**
    *****************************
    * Headings Styles section
    *****************************
    */
    
    /**
    ******** H1 ********
    */
    
    // Update font family in real time...
	wp.customize( 'awf_h1_font_family', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h1').css('font-family', newval );
		} );
	} );
    
    // Update font size in real time...
	wp.customize( 'awf_h1_font_size', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h1').css('font-size', newval+ 'px' );
		} );
	} );
    
    // Update line height in real time...
	wp.customize( 'awf_h1_line_height', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h1').css('line-height', newval );
		} );
	} );
    
    // Update text decoration in real time...
	wp.customize( 'awf_h1_text_decoration', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h1').css('text-decoration', newval );
		} );
	} );
    
    /**
    ******** H2 ********
    */
    
    // Update font family in real time...
	wp.customize( 'awf_h2_font_family', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h2').css('font-family', newval );
		} );
	} );
    
    // Update font size in real time...
	wp.customize( 'awf_h2_font_size', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h2').css('font-size', newval+ 'px' );
		} );
	} );
    
    // Update line height in real time...
	wp.customize( 'awf_h2_line_height', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h2').css('line-height', newval );
		} );
	} );
    
    // Update text decoration in real time...
	wp.customize( 'awf_h2_text_decoration', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h2').css('text-decoration', newval );
		} );
	} );
    
    /**
    ******** H3 ********
    */
    
    // Update font family in real time...
	wp.customize( 'awf_h3_font_family', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h3').css('font-family', newval );
		} );
	} );
    
    // Update font size in real time...
	wp.customize( 'awf_h3_font_size', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h3').css('font-size', newval+ 'px' );
		} );
	} );
    
    // Update line height in real time...
	wp.customize( 'awf_h3_line_height', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h3').css('line-height', newval );
		} );
	} );
    
    // Update text decoration in real time...
	wp.customize( 'awf_h3_text_decoration', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h3').css('text-decoration', newval );
		} );
	} );
    
    /**
    ******** H4 ********
    */
    
    // Update font family in real time...
	wp.customize( 'awf_h4_font_family', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h4').css('font-family', newval );
		} );
	} );
    
    // Update font size in real time...
	wp.customize( 'awf_h4_font_size', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h4').css('font-size', newval+ 'px' );
		} );
	} );
    
    // Update line height in real time...
	wp.customize( 'awf_h4_line_height', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h4').css('line-height', newval );
		} );
	} );
    
    // Update text decoration in real time...
	wp.customize( 'awf_h4_text_decoration', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h4').css('text-decoration', newval );
		} );
	} );
    
    /**
    ******** H5 ********
    */
    
    // Update font family in real time...
	wp.customize( 'awf_h5_font_family', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h5').css('font-family', newval );
		} );
	} );
    
    // Update font size in real time...
	wp.customize( 'awf_h5_font_size', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h5').css('font-size', newval+ 'px' );
		} );
	} );
    
    // Update line height in real time...
	wp.customize( 'awf_h5_line_height', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h5').css('line-height', newval );
		} );
	} );
    
    // Update text decoration in real time...
	wp.customize( 'awf_h5_text_decoration', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h5').css('text-decoration', newval );
		} );
	} );
    
    /**
    ******** H6 ********
    */
    
    // Update font family in real time...
	wp.customize( 'awf_h6_font_family', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h6').css('font-family', newval );
		} );
	} );
    
    // Update font size in real time...
	wp.customize( 'awf_h6_font_size', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h6').css('font-size', newval+ 'px' );
		} );
	} );
    
    // Update line height in real time...
	wp.customize( 'awf_h6_line_height', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h6').css('line-height', newval );
		} );
	} );
    
    // Update text decoration in real time...
	wp.customize( 'awf_h6_text_decoration', function( value ) {
		value.bind( function( newval ) {
			$('body.rtl h6').css('text-decoration', newval );
		} );
	} );

	
	
} )( jQuery );