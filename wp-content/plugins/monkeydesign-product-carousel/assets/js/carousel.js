/* global Swiper */

( function () {
	'use strict';

	const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function initCarousel( el ) {
		const config = JSON.parse( el.dataset.swiperConfig || '{}' );

		const swiperEl      = el.querySelector( '.pc-carousel-wrapper .swiper' );
		const thumbsSwiperEl = config.thumbs ? el.querySelector( '.pc-carousel__thumbs-swiper' ) : null;

		if ( ! swiperEl || swiperEl._swiperInstance ) {
			return;
		}

		// Init thumbnail Swiper first so we can pass it to the main Swiper
		let thumbsSwiper = null;
		if ( thumbsSwiperEl && ! thumbsSwiperEl._swiperInstance ) {
			thumbsSwiper = new Swiper( thumbsSwiperEl, {
				slidesPerView:       'auto',
				spaceBetween:        config.thumbs_gap || 8,
				freeMode:            true,
				watchSlidesProgress: true,
			} );
			thumbsSwiperEl._swiperInstance = thumbsSwiper;
		}

		const swiperConfig = {
			slidesPerView:  config.columns?.mobile  || 1,
			spaceBetween:   config.space_between    || 20,
			speed:          reducedMotion ? 0 : ( config.speed || 400 ),
			watchOverflow:  true,

			breakpoints: {
				768:  { slidesPerView: config.columns?.tablet  || 2 },
				1024: { slidesPerView: config.columns?.desktop || 3 },
			},

			a11y: {
				enabled:          true,
				prevSlideMessage: 'Previous slide',
				nextSlideMessage: 'Next slide',
			},

			keyboard: {
				enabled:        true,
				onlyInViewport: true,
			},

			loop:     ! reducedMotion && !! config.loop,
			autoplay: ( ! reducedMotion && config.autoplay )
				? { delay: config.delay || 3000, pauseOnMouseEnter: true, disableOnInteraction: false }
				: false,

			navigation: config.arrows ? {
				nextEl: el.querySelector( '.pc-carousel__next' ),
				prevEl: el.querySelector( '.pc-carousel__prev' ),
			} : false,

			pagination: config.dots ? {
				el:        el.querySelector( '.pc-carousel__dots' ),
				clickable: true,
			} : false,

			thumbs: thumbsSwiper ? { swiper: thumbsSwiper } : false,

			scrollbar: config.scrollbar ? {
				el:              el.querySelector( '.pc-carousel__scrollbar' ),
				draggable:       true,
				snapOnRelease:   true,
				dragSize:        'auto',
			} : false,
		};

		const swiper = new Swiper( swiperEl, swiperConfig );
		swiperEl._swiperInstance = swiper;

		// Sync slider
		if ( config.sync_id ) {
			window._mdpcSync = window._mdpcSync || {};
			const syncGroup = window._mdpcSync[ config.sync_id ] || [];
			syncGroup.push( swiper );
			window._mdpcSync[ config.sync_id ] = syncGroup;

			swiper._mdpcSyncing = false;
			swiper.on( 'slideChange', () => {
				if ( swiper._mdpcSyncing ) return;
				( window._mdpcSync[ config.sync_id ] || [] ).forEach( peer => {
					if ( peer === swiper ) return;
					peer._mdpcSyncing = true;
					peer.slideTo( swiper.realIndex );
					setTimeout( () => { peer._mdpcSyncing = false; }, 50 );
				} );
			} );
		}

		// Fraction
		if ( config.fraction ) {
			const fractionEl  = el.querySelector( '.pc-carousel__fraction' );
			const currentEl   = fractionEl?.querySelector( '.pc-carousel__fraction-current' );
			const totalEl     = fractionEl?.querySelector( '.pc-carousel__fraction-total' );
			if ( currentEl && totalEl ) {
				const updateFraction = () => {
					currentEl.textContent = swiper.realIndex + 1;
					totalEl.textContent   = swiper.slides.length;
				};
				swiper.on( 'slideChange', updateFraction );
				updateFraction();
			}
		}

		// Wishlist (PRO)
		el.querySelectorAll( '.pc-wishlist-btn' ).forEach( btn => {
			const id = btn.dataset.productId;
			if ( mdpcWishlistHas( id ) ) mdpcWishlistMark( btn, true );
			btn.addEventListener( 'click', () => {
				const active = mdpcWishlistToggle( id );
				mdpcWishlistMark( btn, active );
			} );
		} );

		// Sale countdown (PRO)
		el.querySelectorAll( '.pc-countdown[data-expires]' ).forEach( countdown => {
			const expires = parseInt( countdown.dataset.expires, 10 ) * 1000;

			function updateCountdown() {
				const remaining = Math.max( 0, expires - Date.now() );
				if ( remaining === 0 ) {
					countdown.hidden = true;
					clearInterval( countdown._cdTimer );
					return;
				}
				const d = Math.floor( remaining / 86400000 );
				const h = Math.floor( ( remaining % 86400000 ) / 3600000 );
				const m = Math.floor( ( remaining % 3600000 ) / 60000 );
				const s = Math.floor( ( remaining % 60000 ) / 1000 );
				countdown.querySelector( '[data-unit="d"]' ).textContent = String( d ).padStart( 2, '0' );
				countdown.querySelector( '[data-unit="h"]' ).textContent = String( h ).padStart( 2, '0' );
				countdown.querySelector( '[data-unit="m"]' ).textContent = String( m ).padStart( 2, '0' );
				countdown.querySelector( '[data-unit="s"]' ).textContent = String( s ).padStart( 2, '0' );
			}

			updateCountdown();
			countdown._cdTimer = setInterval( updateCountdown, 1000 );
		} );

		// Quick view (PRO)
		if ( window.mdpcAjax && el.querySelector( '.pc-qv-trigger' ) ) {
			el.addEventListener( 'click', e => {
				const trigger = e.target.closest( '.pc-qv-trigger' );
				if ( trigger ) {
					e.preventDefault();
					mdpcOpenModal( trigger.dataset.productId, el );
				}
			} );
		}

		// AJAX cart enhanced (PRO)
		if ( config.ajax_cart ) {
			el.classList.add( 'pc-carousel--ajax-cart' );
			el.querySelectorAll( '.add_to_cart_button' ).forEach( btn => {
				new MutationObserver( () => {
					if ( btn.classList.contains( 'added' ) ) {
						mdpcShowCartToast( el, config );
						setTimeout( () => btn.classList.remove( 'added' ), 3000 );
					}
				} ).observe( btn, { attributes: true, attributeFilter: [ 'class' ] } );
			} );
		}

		// Progress bar
		if ( config.progress ) {
			const progressTrack = el.querySelector( '.pc-carousel__progress' );
			const progressBar   = progressTrack?.querySelector( '.pc-carousel__progress-bar' );
			if ( progressBar ) {
				const updateProgress = () => {
					const pct = Math.round( swiper.progress * 100 );
					progressBar.style.width = Math.min( 100, Math.max( 0, pct ) ) + '%';
					progressTrack.setAttribute( 'aria-valuenow', pct );
				};
				swiper.on( 'slideChange', updateProgress );
				updateProgress();
			}
		}
	}

	// Quick view modal (PRO) — single instance, reused across all carousels
	let _qvModal = null;

	function mdpcGetModal() {
		if ( _qvModal ) return _qvModal;

		const backdrop = document.createElement( 'div' );
		backdrop.className = 'pc-qv-backdrop';
		backdrop.setAttribute( 'aria-hidden', 'true' );

		const dialog = document.createElement( 'div' );
		dialog.className = 'pc-qv-modal';
		dialog.setAttribute( 'role', 'dialog' );
		dialog.setAttribute( 'aria-modal', 'true' );
		dialog.setAttribute( 'tabindex', '-1' );
		dialog.innerHTML =
			'<button class="pc-qv-close" aria-label="Close">' +
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
			'</button>' +
			'<div class="pc-qv-content"></div>';

		document.body.appendChild( backdrop );
		document.body.appendChild( dialog );

		backdrop.addEventListener( 'click', mdpcCloseModal );
		dialog.querySelector( '.pc-qv-close' ).addEventListener( 'click', mdpcCloseModal );

		document.addEventListener( 'keydown', e => {
			if ( e.key === 'Escape' && dialog.classList.contains( 'is-open' ) ) {
				mdpcCloseModal();
			}
			if ( e.key === 'Tab' && dialog.classList.contains( 'is-open' ) ) {
				const focusable = [ ...dialog.querySelectorAll( 'a,button,input,select,textarea,[tabindex]:not([tabindex="-1"])' ) ].filter( el => ! el.disabled );
				if ( ! focusable.length ) return;
				const first = focusable[ 0 ];
				const last  = focusable[ focusable.length - 1 ];
				if ( e.shiftKey && document.activeElement === first ) {
					e.preventDefault();
					last.focus();
				} else if ( ! e.shiftKey && document.activeElement === last ) {
					e.preventDefault();
					first.focus();
				}
			}
		} );

		_qvModal = { backdrop, dialog };
		return _qvModal;
	}

	// Wishlist helpers
	const WISHLIST_KEY = 'mdpc_wishlist';

	function mdpcWishlistGet() {
		try { return JSON.parse( localStorage.getItem( WISHLIST_KEY ) || '[]' ); } catch ( e ) { return []; }
	}

	function mdpcWishlistHas( id ) {
		return mdpcWishlistGet().includes( String( id ) );
	}

	function mdpcWishlistToggle( id ) {
		const list = mdpcWishlistGet();
		const idx  = list.indexOf( String( id ) );
		if ( idx === -1 ) { list.push( String( id ) ); } else { list.splice( idx, 1 ); }
		localStorage.setItem( WISHLIST_KEY, JSON.stringify( list ) );
		return idx === -1;
	}

	function mdpcWishlistMark( btn, active ) {
		btn.classList.toggle( 'is-active', active );
		btn.setAttribute( 'aria-pressed', String( active ) );
		const label = btn.getAttribute( 'aria-label' ) || '';
		btn.setAttribute( 'aria-label', active
			? label.replace( 'Add to', 'Remove from' )
			: label.replace( 'Remove from', 'Add to' )
		);
	}

	const _qvVars = [
		'--mdpc-qv-modal-bg', '--mdpc-qv-modal-radius',
		'--mdpc-qv-title-color', '--mdpc-qv-excerpt-color', '--mdpc-qv-sale-color',
		'--mdpc-qv-btn-bg', '--mdpc-qv-btn-color', '--mdpc-qv-btn-bg-hover', '--mdpc-qv-btn-color-hover',
		'--mdpc-qv-link-color', '--mdpc-qv-link-color-hover',
		'--mdpc-qv-close-size', '--mdpc-qv-close-radius',
		'--mdpc-qv-close-bg', '--mdpc-qv-close-color', '--mdpc-qv-close-bg-hover', '--mdpc-qv-close-color-hover',
	];

	function mdpcQvResync( carouselEl ) {
		if ( ! _qvModal || ! carouselEl ) return;
		const cs = getComputedStyle( carouselEl );
		_qvVars.forEach( v => {
			_qvModal.dialog.style.setProperty( v, cs.getPropertyValue( v ) );
		} );
	}

	window.mdpcQvResync = mdpcQvResync;

	function mdpcOpenModal( productId, carouselEl ) {
		if ( ! window.mdpcAjax ) return;
		const { backdrop, dialog } = mdpcGetModal();
		const content = dialog.querySelector( '.pc-qv-content' );

		_qvModal._sourceEl = carouselEl || null;
		mdpcQvResync( carouselEl );

		content.innerHTML = '<div class="pc-qv-spinner"></div>';
		backdrop.classList.add( 'is-visible' );
		dialog.classList.add( 'is-open' );
		document.body.classList.add( 'pc-qv-open' );
		dialog.focus();

		fetch( window.mdpcAjax.url, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: new URLSearchParams( {
				action:     'mdpc_quick_view',
				product_id: productId,
				nonce:      window.mdpcAjax.nonce,
			} ),
		} )
			.then( r => r.json() )
			.then( data => {
				if ( data.success ) {
					content.innerHTML = data.data.html;
					dialog.focus();
				} else {
					content.innerHTML = '<p class="pc-qv-error">Could not load product.</p>';
				}
			} )
			.catch( () => {
				content.innerHTML = '<p class="pc-qv-error">Could not load product.</p>';
			} );
	}

	function mdpcCloseModal() {
		if ( ! _qvModal ) return;
		_qvModal.backdrop.classList.remove( 'is-visible' );
		_qvModal.dialog.classList.remove( 'is-open' );
		document.body.classList.remove( 'pc-qv-open' );
	}

	function mdpcShowCartToast( carouselEl, config ) {
		const position = ( config && config.toast_position ) || 'widget';
		const offsetX  = ( config && config.toast_offset_x ) || 20;
		const offsetY  = ( config && config.toast_offset_y ) || 20;

		// Page-relative toasts are position:fixed and shared across the whole
		// page (not per-carousel), so clicking add-to-cart on any carousel
		// re-uses the same element instead of stacking multiple toasts.
		const isPageRelative = position.indexOf( 'page-' ) === 0;
		let toast = isPageRelative
			? document.body.querySelector( '.pc-cart-toast--page' )
			: carouselEl.querySelector( '.pc-cart-toast' );

		if ( ! toast ) {
			toast = document.createElement( 'div' );
			toast.setAttribute( 'aria-live', 'polite' );
			( isPageRelative ? document.body : carouselEl ).appendChild( toast );
		}

		toast.className = 'pc-cart-toast pc-cart-toast--' + position + ( isPageRelative ? ' pc-cart-toast--page' : '' );
		if ( isPageRelative ) {
			toast.style.setProperty( '--mdpc-toast-offset-x', offsetX + 'px' );
			toast.style.setProperty( '--mdpc-toast-offset-y', offsetY + 'px' );
		}
		toast.textContent = ( config && config.toast_text ) || '✓ Added to cart';
		toast.classList.add( 'is-visible' );
		clearTimeout( toast._t );
		toast._t = setTimeout( () => toast.classList.remove( 'is-visible' ), 2500 );
	}

	function initAll( scope ) {
		const root = scope || document;
		root.querySelectorAll( '.pc-carousel' ).forEach( initCarousel );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', () => initAll() );
	} else {
		initAll();
	}

	window.mdpcCarouselInit = initAll;
} )();
