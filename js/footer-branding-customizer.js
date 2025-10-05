(function( wp, $ ) {
	if ( ! wp || ! wp.customize ) { return; }

	function updateWrapperVisibility( enabled ) {
		var $wrap = $( '[data-footer-branding-wrapper]' );
		if ( ! $wrap.length ) { return; }
		if ( enabled ) { $wrap.show(); } else { $wrap.hide(); }
	}
	function updateTaglineVisibility( enabled, text ) {
		var $tag = $( '[data-footer-tagline]' );
		if ( ! $tag.length ) { return; }
		if ( enabled && text ) { $tag.text( text ).show(); } else { $tag.hide(); }
	}
	function updatePosition( pos ) {
		var $wrap = $( '[data-footer-branding-wrapper]' );
		if ( ! $wrap.length ) { return; }
		var map = { left: 'flex-start', center: 'center', right: 'flex-end' };
		$wrap.css( 'align-items', map[ pos ] || 'flex-start' );
	}
	function updateTransform() {
		var $wrap = $( '[data-footer-branding-wrapper]' );
		if ( ! $wrap.length ) { return; }
		var x = parseInt( wp.customize.value( 'footer_branding_offset_x' )() || 0, 10 );
		var y = parseInt( wp.customize.value( 'footer_branding_offset_y' )() || 0, 10 );
		$wrap.css( 'transform', 'translate(' + x + '%,' + y + '%)' );
	}
	function updateMargin( val ) {
		var $wrap = $( '[data-footer-branding-wrapper]' );
		if ( ! $wrap.length ) { return; }
		val = parseInt( val || 0, 10 );
		$wrap.css( 'margin', val + '%' );
	}
	function updateImage( attachmentId ) {
		var $wrap = $( '[data-footer-branding-wrapper]' );
		if ( ! $wrap.length ) { return; }
		var $img = $wrap.find( 'img.footer-branding-image' );
		if ( attachmentId ) {
			wp.media.attachment( attachmentId ).fetch().then( function() {
				var url = wp.media.attachment( attachmentId ).get( 'url' );
				if ( $img.length ) { $img.attr( 'src', url ); }
				else { $wrap.prepend( $('<img>', { 'class':'footer-branding-image', src:url }) ); }
			});
		} else {
			// If cleared, we could fallback to existing logo; for simplicity just remove override img
			if ( $img.length ) { $img.remove(); }
		}
	}

	wp.customize( 'footer_branding_enable', function( value ) { value.bind( updateWrapperVisibility ); } );
	wp.customize( 'footer_branding_tagline_enable', function( value ) { value.bind( function( v ){ updateTaglineVisibility( v, wp.customize.value( 'footer_branding_tagline' )() ); } ); } );
	wp.customize( 'footer_branding_tagline', function( value ) { value.bind( function( text ){ updateTaglineVisibility( wp.customize.value( 'footer_branding_tagline_enable' )(), text ); } ); } );
	wp.customize( 'footer_branding_position', function( value ) { value.bind( updatePosition ); } );
	wp.customize( 'footer_branding_margin', function( value ) { value.bind( updateMargin ); } );
	wp.customize( 'footer_branding_offset_x', function( value ) { value.bind( updateTransform ); } );
	wp.customize( 'footer_branding_offset_y', function( value ) { value.bind( updateTransform ); } );
	wp.customize( 'footer_branding_image', function( value ) { value.bind( updateImage ); } );

	// Logo/tagline layout
	wp.customize( 'footer_branding_logo_layout', function( value ) { value.bind( function( layout ) {
		var $wrap = $( '[data-footer-branding-wrapper]' );
		if( ! $wrap.length ) return;
		var dir = 'column';
		var $tag = $wrap.find('[data-footer-tagline]');
		$wrap.removeClass('flex-row');
		$tag.css({margin:'', 'margin-top':'', 'margin-bottom':'', 'margin-left':'', 'margin-right':''});
		if ( layout === 'logo-bottom' ) {
			dir = 'column-reverse';
			$tag.css('margin-bottom','.5rem');
		} else if ( layout === 'logo-left' ) {
			dir = 'row';
			$wrap.addClass('flex-row');
			$tag.css('margin-left','.75rem');
		} else if ( layout === 'logo-right' ) {
			dir = 'row-reverse';
			$wrap.addClass('flex-row');
			$tag.css('margin-right','.75rem');
		} else { // logo-top
			$tag.css('margin-top','.5rem');
		}
		$wrap.css('flex-direction', dir );
	}); });

	// Logo width/height
	wp.customize( 'footer_branding_logo_width', function( value ) { value.bind( function( v ){ var $img = $( '[data-footer-branding-wrapper] img.footer-branding-image, [data-footer-branding-wrapper] .footer-logo img' ); if($img.length){ if(parseInt(v,10)>0){ $img.css('width', parseInt(v,10)+'px'); } else { $img.css('width',''); } } }); } );
	wp.customize( 'footer_branding_logo_height', function( value ) { value.bind( function( v ){ var $img = $( '[data-footer-branding-wrapper] img.footer-branding-image, [data-footer-branding-wrapper] .footer-logo img' ); if($img.length){ if(parseInt(v,10)>0){ $img.css({height: parseInt(v,10)+'px', 'object-fit':'contain'}); } else { $img.css({height:'', 'object-fit':''}); } } }); } );

})( window.wp, window.jQuery );