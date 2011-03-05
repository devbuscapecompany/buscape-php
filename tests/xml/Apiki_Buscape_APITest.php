<?php
require_once 'Apiki_Buscape_API.php';
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Apiki_Buscape_API test case.
 * @author	neto
 */
class Apiki_Buscape_APITest extends PHPUnit_Framework_TestCase {
	const SANDBOX_APPLICATION_ID = '564771466d477a4458664d3d';

	/**
	 * @var	string
	 */
	private $applicationId = Apiki_Buscape_APITest::SANDBOX_APPLICATION_ID;

	/**
	 * @var	string
	 */
	private $sourceId;

	/**
	 * @var Apiki_Buscape_API
	 */
	private $buscapeWrapper;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp();

		$this->applicationId = self::SANDBOX_APPLICATION_ID;

		$this->buscapeWrapper = new Apiki_Buscape_API( $this->applicationId , $this->sourceId );
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->buscapeWrapper = null;

		parent::tearDown();
	}

	/**
	 * Tests Apiki_Buscape_API->setCountryCode()
	 */
	public function testCountryCode() {
		$this->buscapeWrapper->setCountryCode( 'BR' );

		$this->assertEquals( 'BR' , $this->buscapeWrapper->getCountryCode() );
	}

	/**
	 * Tests Apiki_Buscape_API->setCountryCode() with an invalid country code
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidCountryCode() {
		$this->buscapeWrapper->setCountryCode( 'JP' );
	}

	/**
	 * Tests Apiki_Buscape_API->setFormat() with xml format
	 */
	public function testXMLFormat() {
		$this->buscapeWrapper->setFormat( 'xml' );

		$this->assertEquals( 'xml' , $this->buscapeWrapper->getFormat() );
	}

	/**
	 * Tests Apiki_Buscape_API->setFormat() with json format
	 */
	public function testJSONFormat() {
		$this->buscapeWrapper->setFormat( 'json' );

		$this->assertEquals( 'json' , $this->buscapeWrapper->getFormat() );
	}

	/**
	 * Tests Apiki_Buscape_API->setFormat() with an invalid format
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidFormat() {
		$this->buscapeWrapper->setFormat( 'html' );
	}

	/**
	 * Tests Apiki_Buscape_API->setApplicationId()
	 */
	public function testApplicationId() {
		$this->buscapeWrapper->setApplicationId( self::SANDBOX_APPLICATION_ID );

		$this->assertEquals( $this->buscapeWrapper->getApplicationId() , self::SANDBOX_APPLICATION_ID );
	}

	/**
	 * Tests Apiki_Buscape_API->setApplicationId() with empty string
	 * @expectedException	InvalidArgumentException
	 */
	public function testEmptyApplicationId() {
		$this->buscapeWrapper->setApplicationId( '' );

		$this->fail( 'applicationId não pode ser uma string vazia' );
	}

	/**
	 * Tests Apiki_Buscape_API->setApplicationId() with empty string
	 * @expectedException	InvalidArgumentException
	 */
	public function testNULLApplicationId() {
		$this->buscapeWrapper->setApplicationId( null );

		$this->fail( 'applicationId não pode ser null' );
	}

	/**
	 * Tests Apiki_Buscape_API->getEnvironment()
	 */
	public function testProductionEnvironment() {
		$this->assertEquals( 'bws' , $this->buscapeWrapper->getEnvironment() );
	}

	/**
	 * Tests Apiki_Buscape_API->getEnvironment()
	 */
	public function testSandboxEnvironment() {
		$this->buscapeWrapper->setSandbox();
		$this->assertEquals( 'sandbox' , $this->buscapeWrapper->getEnvironment() );
	}

	/**
	 * Tests Apiki_Buscape_API->getSourceId()
	 */
	public function testSourceId() {
		$this->buscapeWrapper->setSourceId( '92544' );

		$this->assertEquals( '92544' , $this->buscapeWrapper->getSourceId() );
	}
}