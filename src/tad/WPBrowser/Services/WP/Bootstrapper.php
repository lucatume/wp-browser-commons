<?php

namespace tad\WPBrowser\Services\WP;

/**
 * Class Bootstrapper
 *
 * Bootstraps WordPress from wp-load file and executes requests on it.
 *
 * @package tad\WPBrowser\Services\WP
 */
class Bootstrapper {

	/**
	 * @var string
	 */
	private $wpLoadPath;

	/**
	 * Bootstrapper constructor.
	 *
	 * @param string $wpLoadPath
	 */
	public function __construct( $wpLoadPath = null ) {
		$this->wpLoadPath      = $wpLoadPath;
		$this->wpBootstrapFile = dirname( dirname( __DIR__ ) ) . '/support/wpBootstrap.php';
	}

	/**
	 * Generates a nonce for an action for a user.
	 *
	 * @param string $action
	 * @param int    $user
	 *
	 * @return mixed
	 */
	public function createNonce( $action, $user = 0 ) {
		$actions = [ 'nonces' => [ [ 'action' => $action, 'user' => $user ] ] ];
		$output  = $this->bootstrapWpAndExec( $actions );

		return ! empty( $output['nonces'][ $user ][ $action ] ) ? $output['nonces'][ $user ][ $action ] : false;
	}

	/**
	 * @param array $actions
	 *
	 * @return array
	 */
	protected function bootstrapWpAndExec( array $actions ) {
		$actions          = serialize( $actions );
		$serializedOutput = system( PHP_BINARY . ' ' . escapeshellarg( $this->wpBootstrapFile ) . ' ' . escapeshellarg( $this->wpLoadPath ) . ' ' . escapeshellarg( $actions ) );
		$output           = unserialize( $serializedOutput );

		return $output;
	}

	/**
	 * @param string $wpLoadPath
	 */
	public function setLoadPath( $wpLoadPath ) {
		$this->wpLoadPath = $wpLoadPath;
	}
}