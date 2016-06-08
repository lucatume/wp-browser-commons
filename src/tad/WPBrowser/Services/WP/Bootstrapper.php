<?php

namespace tad\WPBrowser\Services\WP;
use tad\WPBrowser\Environment\System;

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
	protected $bootstrapScriptFilePath;

	/**
	 * @var string
	 */
	private $wpLoadPath;

	/**
	 * @var System
	 */
	private $system;

	/**
	 * Bootstrapper constructor.
	 *
	 * @param string $wpLoadPath
	 */
	public function __construct( $wpLoadPath = null, System $system = null ) {
		$this->wpLoadPath              = $wpLoadPath;
		$this->bootstrapScriptFilePath = dirname( dirname( __DIR__ ) ) . '/support/wpBootstrap.php';
		$this->system                  = $system ? $system : new System();
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
	public function bootstrapWpAndExec( array $actions, $unserializeOutput = true ) {
		$command = PHP_BINARY . ' ' . escapeshellarg( $this->bootstrapScriptFilePath ) . ' ' . escapeshellarg( $this->wpLoadPath ) . ' ' . escapeshellarg( serialize( $actions ) );
		$output = $this->system->system( $command );
		
		return $unserializeOutput ? unserialize( $output ) : $output;
	}

	/**
	 * @param string $wpLoadPath
	 */
	public function setWpLoadPath( $wpLoadPath ) {
		$this->wpLoadPath = $wpLoadPath;
	}

	public function getWpLoadPath() {
		return $this->wpLoadPath;
	}

	public function setBootstrapScriptFilePath( $bootsrapScriptFilePath ) {
		$this->bootstrapScriptFilePath = $bootsrapScriptFilePath;
	}

	public function getBootstrapScriptFilePath() {
		return $this->bootstrapScriptFilePath;
	}

	public function createNonces( array $actionsAndUsers ) {
		$nonces = [ ];

		foreach ( $actionsAndUsers as $actionsAndUser ) {
			$nonces[] = [ 'action' => $actionsAndUser[0], 'user' => $actionsAndUser[1] ];
		}

		$actions = [ 'nonces' => $nonces ];
		$output  = $this->bootstrapWpAndExec( $actions );

		return ! empty( $output['nonces'] ) ? $output['nonces'] : false;
	}

	public function verifyNonce( $nonce, $action, $user ) {
		$actions = [ 'nonces_verification' => [ 'nonce' => $nonce, 'action' => $action, 'user' => $user ] ];

		return (bool)$this->bootstrapWpAndExec( $actions, false );
	}
}