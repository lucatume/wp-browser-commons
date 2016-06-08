<?php
$wpLoadPath = $argv[1];
$actions    = unserialize( $argv[2] );

include $wpLoadPath;

$output = [ ];

// Nonces generation
if ( isset( $actions['nonces'] ) ) {
	$output['nonces'] = [ ];
	foreach ( $actions['nonces'] as $nonceInput ) {
		$user   = $nonceInput['user'];
		$action = $nonceInput['action'];
		if ( empty( $output['nonces'][ $user ] ) ) {
			$output['nonces'][ $user ] = [ ];
		}
		wp_set_current_user( $user );
		$nonce                                = wp_create_nonce( $action );
		$output['nonces'][ $user ][ $action ] = $nonce;
	}
}

// Nonces verification
if ( isset( $actions['nonces_verification'] ) ) {
	$nonce       = $actions['nonces_verification'];
	$user        = $nonce['user'];
	$action      = $nonce['action'];
	$nonceString = $nonce['nonce'];
	wp_set_current_user( $user );

	$verified = wp_verify_nonce( $nonceString, $action );
	
	die( (bool)$verified ? true : false );
}

die( serialize( $output ) );
