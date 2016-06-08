<?php


use tad\WPBrowser\Services\WP\Bootstrapper;

class NonceGenerationCest {

	/**
	 * @var array
	 */
	protected $config;

	/**
	 * @var Bootstrapper
	 */
	protected $bootstrapper;

	public function _before( FunctionalTester $I ) {
		$this->config       = Codeception\Configuration::config();
		$this->bootstrapper = new Bootstrapper( $this->config['settings']['wpLoadPath'] );
	}

	public function _after( FunctionalTester $I ) {
	}

	/**
	 * @test
	 * it should generate a nonce for the user 0
	 */
	public function it_should_generate_a_nonce_for_the_user_0( FunctionalTester $I ) {
		$nonce = $this->bootstrapper->createNonce( 'some_action', 0 );

		$I->assertInternalType( 'string', $nonce );
	}

	/**
	 * @test
	 * it should allow generating a nonce for user 1
	 */
	public function it_should_allow_generating_a_nonce_for_user_1( FunctionalTester $I ) {
		$nonce = $this->bootstrapper->createNonce( 'some_action', 1 );

		$I->assertInternalType( 'string', $nonce );
	}

	/**
	 * @test
	 * it should generate different nonces for different actions
	 */
	public function it_should_generate_different_nonces_for_different_actions( FunctionalTester $I ) {
		$nonceOneUserZero = $this->bootstrapper->createNonce( 'some_action', 0 );
		$nonceTwoUserZero = $this->bootstrapper->createNonce( 'another_action', 0 );
		$nonceOneUserOne  = $this->bootstrapper->createNonce( 'some_action', 1 );
		$nonceTwoUserOne  = $this->bootstrapper->createNonce( 'another_action', 1 );

		$I->assertNotEquals( $nonceOneUserZero, $nonceTwoUserZero );
		$I->assertNotEquals( $nonceOneUserOne, $nonceTwoUserOne );
	}

	/**
	 * @test
	 * it should generate difference nonces for different users and same actions
	 */
	public function it_should_generate_difference_nonces_for_different_users_and_same_actions( FunctionalTester $I ) {

		$nonceUserZero = $this->bootstrapper->createNonce( 'some_action', 0 );
		$nonceUserOne  = $this->bootstrapper->createNonce( 'some_action', 1 );

		$I->assertNotEquals( $nonceUserZero, $nonceUserOne );
	}

	/**
	 * @test
	 * it should allow verifying nonces
	 */
	public function it_should_allow_verifying_nonces( FunctionalTester $I ) {
		$nonceUserZero = $this->bootstrapper->createNonce( 'some_action', 0 );
		$verifiedZero  = $this->bootstrapper->verifyNonce( $nonceUserZero, 'some_action', 0 );
		$nonceUserOne  = $this->bootstrapper->createNonce( 'some_action', 1 );
		$verifiedOne   = $this->bootstrapper->verifyNonce( $nonceUserOne, 'some_action', 1 );

		$I->assertTrue( $verifiedZero );
		$I->assertTrue( $verifiedOne );
	}

	/**
	 * @test
	 * it should verify subsequent nonces generated for the same action and user
	 */
	public function it_should_verify_subsequent_nonces_generated_for_the_same_action_and_user( FunctionalTester $I ) {
		$nonceOne   = $this->bootstrapper->createNonce( 'some_action', 1 );
		$nonceTwo   = $this->bootstrapper->createNonce( 'some_action', 1 );
		$nonceThree = $this->bootstrapper->createNonce( 'some_action', 1 );

		$I->assertTrue( $this->bootstrapper->verifyNonce( $nonceOne, 'some_action', 1 ) );
		$I->assertTrue( $this->bootstrapper->verifyNonce( $nonceTwo, 'some_action', 1 ) );
		$I->assertTrue( $this->bootstrapper->verifyNonce( $nonceThree, 'some_action', 1 ) );
	}
}
