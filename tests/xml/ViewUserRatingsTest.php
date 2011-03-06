<?php
require_once 'XMLTest.php';

/**
 * Apiki_Buscape_API::viewUserRatings test case.
 * @author	neto
 */
class ViewUserRatingsTest extends XMLTest {
	/**
	 * Tests Apiki_Buscape_API::viewUserRatings with empty array
	 * @expectedException UnexpectedValueException
	 */
	public function testViewUserRatingsWithEmptyArray() {
		$this->buscapeWrapper->viewUserRatings( array() );
	}

	/**
	 * Tests Apiki_Buscape_API::viewUserRatings with invalid productId
	 * @expectedException UnexpectedValueException
	 */
	public function testViewUserRatingsWithInvalidProductId() {
		$this->buscapeWrapper->viewUserRatings( array( 'productId' => -1 ) );
	}

	/**
	 * Tests Apiki_Buscape_API::viewUserRatings with productId
	 */
	public function testViewUserRatingsWithProductId() {
		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->viewUserRatings( array( 'productId' => 299606 ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}
}