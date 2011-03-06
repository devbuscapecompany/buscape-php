<?php
require_once 'XMLTest.php';

/**
 * Apiki_Buscape_API::viewProductDetails test case.
 * @author	neto
 */
class ViewProductDetailsTest extends XMLTest {
	/**
	 * Tests Apiki_Buscape_API::viewProductDetails with empty array
	 * @expectedException UnexpectedValueException
	 */
	public function testViewProductDetailsWithEmptyArray() {
		$this->buscapeWrapper->viewProductDetails( array() );
	}

	/**
	 * Tests Apiki_Buscape_API::viewProductDetails with invalid productId
	 * @expectedException UnexpectedValueException
	 */
	public function testViewProductDetailsWithInvalidProductId() {
		$this->buscapeWrapper->viewProductDetails( array( 'productId' => -1 ) );
	}

	/**
	 * Tests Apiki_Buscape_API::viewProductDetails with productId
	 */
	public function testViewProductDetailsWithProductId() {
		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->viewProductDetails( array( 'productId' => 299606 ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}
}