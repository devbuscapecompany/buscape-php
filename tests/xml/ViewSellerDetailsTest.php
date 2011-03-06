<?php
require_once 'XMLTest.php';

/**
 * Apiki_Buscape_API::viewSellerDetails test case.
 * @author	neto
 */
class ViewSellerDetailsTest extends XMLTest {
	/**
	 * Tests Apiki_Buscape_API::viewSellerDetails with empty array
	 * @expectedException UnexpectedValueException
	 */
	public function testViewSellerDetailsWithEmptyArray() {
		$this->buscapeWrapper->viewSellerDetails( array() );
	}

	/**
	 * Tests Apiki_Buscape_API::viewSellerDetails with invalid sellerId
	 * @expectedException UnexpectedValueException
	 */
	public function testViewSellerDetailsWithInvalidSellerId() {
		$this->buscapeWrapper->viewSellerDetails( array( 'sellerId' => -1 ) );
	}

	/**
	 * Tests Apiki_Buscape_API::viewSellerDetails with sellerId
	 */
	public function testViewSellerDetailsWithSellerId() {
		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->viewSellerDetails( array( 'sellerId' => 155 ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}
}