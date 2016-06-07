<?php
$wpLoadPath = $argv[1];
$actions    = unserialize($argv[2]);

include $wpLoadPath;

$output = [ ];

// Nonces generation
if ( isset( $actions['nonces'] ) ) {
	$output['nonces'] = [ ];
	foreach ( $actions['nonces'] as $nonceInput ) {
		$user   = $nonceInput['user'];
		$action = $nonce['action'];
		if ( empty( $output['nonces'][ $user ] ) ) {
			$output['nonces'][ $user ] = [ ];
		}
		wp_set_current_user( $user );
		$nonce = wp_create_nonce( $action );
		$output['nonces'][ $user ][ $action ] = $nonce;
	}
}

$output = serialize( $output );

return $output;
