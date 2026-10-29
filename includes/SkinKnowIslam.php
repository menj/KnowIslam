<?php
namespace MediaWiki\Skins\KnowIslam;

use SkinMustache;
use SpecialPage;

/**
 * Adds the data the Know Islam templates need on top of SkinMustache:
 * main-page detection, search and special-page URLs, and the landing topics.
 * Landing text comes from interface messages, so it can be edited on-wiki
 * through the MediaWiki namespace.
 */
class SkinKnowIslam extends SkinMustache {
	private const TOPICS = 4;

	public function getTemplateData() {
		$data = parent::getTemplateData();
		$isMain = $this->getTitle() && $this->getTitle()->isMainPage()
			&& $this->getRequest()->getVal( 'action', 'view' ) === 'view';

		$data['ki-search-action'] = $this->getConfig()->get( 'Script' );
		$data['ki-allpages-url'] = SpecialPage::getTitleFor( 'Allpages' )->getLocalURL();
		$data['ki-random-url'] = SpecialPage::getTitleFor( 'Randompage' )->getLocalURL();
		$data['is-mainpage'] = $isMain;

		// The site's own logo from $wgLogos / $wgLogo: icon first, then svg, 2x, 1x.
		$logos = $data['data-logos'] ?? [];
		$logo = $logos['icon'] ?? $logos['svg'] ?? $logos['2x'] ?? $logos['1x'] ?? null;
		$data['ki-logo-url'] = is_string( $logo ) ? $logo : null;

		if ( $isMain ) {
			$search = SpecialPage::getTitleFor( 'Search' );
			$topics = [];
			for ( $i = 1; $i <= self::TOPICS; $i++ ) {
				$title = $this->msg( "knowislam-topic-$i-title" );
				if ( $title->isDisabled() ) {
					continue;
				}
				$topics[] = [
					'number' => $this->msg( "knowislam-topic-$i-label" )->text(),
					'symbol' => $this->msg( "knowislam-topic-$i-symbol" )->text(),
					'title' => $title->text(),
					'text' => $this->msg( "knowislam-topic-$i-text" )->text(),
					'href' => $search->getLocalURL( [
						'search' => $this->msg( "knowislam-topic-$i-query" )->text(),
					] ),
				];
			}
			$data['array-ki-topics'] = $topics;
			$data['has-ki-topics'] = (bool)$topics;
		}
		return $data;
	}
}
