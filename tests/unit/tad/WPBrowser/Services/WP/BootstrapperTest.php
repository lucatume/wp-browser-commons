<?php
namespace tad\WPBrowser\Services\WP;


use org\bovigo\vfs\vfsStream;
use tad\WPBrowser\Environment\System;

class BootstrapperTest extends \Codeception\Test\Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var string
	 */
	protected $wpLoadPath;

	/**
	 * @var System
	 */
	protected $system;

	protected function _before() {
		$wp         = vfsStream::newDirectory( 'wp' );
		$wpLoadFile = vfsStream::newFile( 'wp-load.php' );
		$wpLoadFile->setContent( 'foo' );
		$wp->addChild( $wpLoadFile );
		$this->wpLoadPath = $wp->url() . '/wp-load.php';
		$this->system     = $this->prophesize( System::class );
	}

	protected function _after() {
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( 'tad\WPBrowser\Services\WP\Bootstrapper', $sut );
	}

	/**
	 * @test
	 * it should allow setting the wpLoadPath
	 */
	public function it_should_allow_setting_the_wp_load_path() {
		$sut = $this->make_instance();

		$sut->setWpLoadPath( 'foo' );

		$this->assertEquals( 'foo', $sut->getWpLoadPath() );
	}

	/**
	 * @test
	 * it should allow setting the bootstrap file path
	 */
	public function it_should_allow_setting_the_bootstrap_file_path() {
		$sut = $this->make_instance();

		$sut->setBootstrapScriptFilePath( 'foo' );

		$this->assertEquals( 'foo', $sut->getBootstrapScriptFilePath() );
	}

	/**
	 * @test
	 * it should exec bootstrap script with actions
	 */
	public function it_should_exec_bootstrap_script_with_actions() {
		$sut = $this->make_instance();
		$sut->setBootstrapScriptFilePath( 'foo' );
		$actions = [ 'some' => 'action' ];

		$this->system->system( PHP_BINARY . ' ' . escapeshellarg( 'foo' ) . ' ' . escapeshellarg( $this->wpLoadPath ) . ' ' . escapeshellarg( serialize( $actions ) ) )
		             ->willReturn( serialize( [ 'some' => 'output' ] ) );

		$sut->bootstrapWpAndExec( $actions );
	}

	/**
	 * @test
	 * it should exec bootstrap with proper parameters when requesting nonces
	 */
	public function it_should_exec_bootstrap_with_proper_parameters_when_requesting_nonces() {
		$sut = $this->make_instance();
		$sut->setBootstrapScriptFilePath( 'foo' );
		$nonces = [
			[ 'action' => 'some_action', 'user' => 1 ]
		];

		$this->system->system( PHP_BINARY . ' ' . escapeshellarg( 'foo' ) . ' ' . escapeshellarg( $this->wpLoadPath ) . ' ' . escapeshellarg( serialize( [ 'nonces' => $nonces ] ) ) )
		             ->willReturn( serialize( [ 'some' => 'output' ] ) );

		$sut->createNonce( 'some_action', 1 );
	}

	/**
	 * @test
	 * it should allow creating multiple nonces
	 */
	public function it_should_allow_creating_multiple_nonces() {
		$sut = $this->make_instance();
		$sut->setBootstrapScriptFilePath( 'foo' );
		$nonces = [
			[ 'action' => 'some_action', 'user' => 1 ],
			[ 'action' => 'another_action', 'user' => 2 ],
			[ 'action' => 'more_action', 'user' => 3 ]
		];

		$this->system->system( PHP_BINARY . ' ' . escapeshellarg( 'foo' ) . ' ' . escapeshellarg( $this->wpLoadPath ) . ' ' . escapeshellarg( serialize( [ 'nonces' => $nonces ] ) ) )
		             ->willReturn( serialize( [ 'some' => 'output' ] ) );

		$sut->createNonces( [ [ 'some_action', 1 ], [ 'another_action', 2 ], [ 'more_action', 3 ] ] );
	}

	/**
	 * @return Bootstrapper
	 */
	private function make_instance() {
		return new Bootstrapper( $this->wpLoadPath, $this->system->reveal() );
	}
}