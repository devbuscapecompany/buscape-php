<?php
require_once 'Apiki_Buscape_API.php';
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Apiki_Buscape_API test case.
 * @author	neto
 */
class XMLTest extends PHPUnit_Framework_TestCase {
	const XML_SCHEMA_URI = '../schema/buscape.xsd';
	const SANDBOX_APPLICATION_ID = '564771466d477a4458664d3d';
	const PRODUCTION_APPLICATION_ID = '6a46486e764a51354753343d';

	/**
	 * @var	string
	 */
	protected  $applicationId = self::SANDBOX_APPLICATION_ID;

	/**
	 * @var	string
	 */
	protected $sourceId;

	/**
	 * @var Apiki_Buscape_API
	 */
	protected $buscapeWrapper;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp();

		if ( !function_exists( 'curl_init' ) ){
			$this->markTestIncomplete( 'A extensão CURL do PHP está desabilitada' );
		}

		libxml_use_internal_errors( true );

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
	 * Asserts that the XML is valid
	 * @param	DOMDocument $dom
	 * @param	string $message
	 * @param	string $schemaURI
	 */
	public static function assertThatXMLIsValid( DOMDocument $dom , $message = '' , $schemaURI = self::XML_SCHEMA_URI ){
		self::assertTrue( $dom->schemaValidate( $schemaURI ) , $message );
	}
}