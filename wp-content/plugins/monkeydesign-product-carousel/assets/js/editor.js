/* global elementorFrontend, mdpcCarouselInit, mdpcQvResync */

( function () {
	'use strict';

	window.addEventListener( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/mdpc_carousel.default',
			function ( $scope ) {
				mdpcCarouselInit( $scope[ 0 ] );
			}
		);

		// In editor mode: poll for QV modal var re-sync when the modal is open.
		// MutationObserver on <head> is unreliable because Elementor replaces <style>
		// textContent, which doesn't always fire subtree mutations consistently.
		if ( elementorFrontend.isEditMode() ) {
			setInterval( function () {
				if (
					typeof mdpcQvResync === 'function' &&
					window._qvModal &&
					window._qvModal.dialog.classList.contains( 'is-open' ) &&
					window._qvModal._sourceEl
				) {
					mdpcQvResync( window._qvModal._sourceEl );
				}
			}, 150 );
		}
	} );
} )();
