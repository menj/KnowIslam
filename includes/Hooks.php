<?php
namespace MediaWiki\Skins\KnowIslam;

use MediaWiki\MediaWikiServices;

class Hooks {
	private const SCHEMES = [ 'auto', 'light', 'dark', 'sepia' ];
	private const ACCENTS = [ 'emerald', 'azure', 'plum', 'bronze' ];
	private const SIZES = [ 'small', 'medium', 'large' ];
	private const WIDTHS = [ 'standard', 'wide' ];

	/** Two tabs in Special:Preferences: Colour and Reading. */
	public function onGetPreferences( $user, &$preferences ) {
		$preferences['knowislam-scheme'] = [
			'type' => 'select',
			'section' => 'knowislam-colour/knowislam-scheme-section',
			'label-message' => 'knowislam-pref-scheme',
			'options-messages' => [
				'knowislam-scheme-auto' => 'auto',
				'knowislam-scheme-light' => 'light',
				'knowislam-scheme-dark' => 'dark',
				'knowislam-scheme-sepia' => 'sepia',
			],
		];
		$preferences['knowislam-accent'] = [
			'type' => 'select',
			'section' => 'knowislam-colour/knowislam-scheme-section',
			'label-message' => 'knowislam-pref-accent',
			'options-messages' => [
				'knowislam-accent-emerald' => 'emerald',
				'knowislam-accent-azure' => 'azure',
				'knowislam-accent-plum' => 'plum',
				'knowislam-accent-bronze' => 'bronze',
			],
		];
		$preferences['knowislam-size'] = [
			'type' => 'select',
			'section' => 'knowislam-reading/knowislam-layout-section',
			'label-message' => 'knowislam-pref-size',
			'options-messages' => [
				'knowislam-size-small' => 'small',
				'knowislam-size-medium' => 'medium',
				'knowislam-size-large' => 'large',
			],
		];
		$preferences['knowislam-width'] = [
			'type' => 'select',
			'section' => 'knowislam-reading/knowislam-layout-section',
			'label-message' => 'knowislam-pref-width',
			'options-messages' => [
				'knowislam-width-standard' => 'standard',
				'knowislam-width-wide' => 'wide',
			],
		];
	}

	/** Adds one class per preference to the body element. */
	public function onOutputPageBodyAttributes( $out, $skin, &$bodyAttrs ) {
		if ( $skin->getSkinName() !== 'knowislam' ) {
			return;
		}
		$lookup = MediaWikiServices::getInstance()->getUserOptionsLookup();
		$user = $out->getUser();
		$pick = static function ( $key, $allowed ) use ( $lookup, $user ) {
			$value = $lookup->getOption( $user, $key );
			return in_array( $value, $allowed, true ) ? $value : $allowed[0];
		};
		if ( $out->getTitle() && $out->getTitle()->isMainPage()
			&& $out->getRequest()->getVal( 'action', 'view' ) === 'view'
		) {
			$bodyAttrs['class'] = ( $bodyAttrs['class'] ?? '' ) . ' ki-home';
		}
		$bodyAttrs['class'] = ( $bodyAttrs['class'] ?? '' ) . ' ki-site-root'
			. ' ki-scheme-' . $pick( 'knowislam-scheme', self::SCHEMES )
			. ' ki-accent-' . $pick( 'knowislam-accent', self::ACCENTS )
			. ' ki-size-' . $pick( 'knowislam-size', self::SIZES )
			. ' ki-width-' . $pick( 'knowislam-width', self::WIDTHS );
	}

	/**
	 * Applies a visitor's saved scheme before first paint, and
	 * $wgKnowIslamCustomColors as site-wide CSS variables.
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		if ( $skin->getSkinName() !== 'knowislam' ) {
			return;
		}
		if ( !$out->getUser()->isRegistered() ) {
			$out->addHeadItem( 'knowislam-scheme',
				'<script>try{var s=localStorage.getItem("knowislam-scheme");'
				. 'if(/^(auto|light|dark|sepia)$/.test(s)){document.documentElement.setAttribute("data-ki-scheme",s);}'
				. '}catch(e){}</script>'
			);
		}
		$custom = $out->getConfig()->get( 'KnowIslamCustomColors' );
		if ( !is_array( $custom ) || !$custom ) {
			return;
		}
		$rules = [];
		foreach ( $custom as $name => $value ) {
			if ( preg_match( '/^--ki-[a-z-]+$/', $name )
				&& preg_match( '/^(#[0-9a-fA-F]{3,8}|rgba?\([\d\s.,%]+\))$/', $value )
			) {
				$rules[] = "$name:$value";
			}
		}
		if ( $rules ) {
			$out->addInlineStyle( 'body.ki-site-root{' . implode( ';', $rules ) . '}' );
		}
	}
}
