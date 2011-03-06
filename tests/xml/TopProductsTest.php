<?php
require_once 'XMLTest.php';

/**
 * Apiki_Buscape_API::topProducts test case.
 * @author	neto
 */
class TopProductsTest extends XMLTest {
	/**
	 * Tests Apiki_Buscape_API::topProducts with Empty array
	 */
	public function testTopProducts() {
		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->topProducts( array() ) ) );
		$this->assertThatXMLIsValid( $dom );
	}

	/**
	 * Tests Apiki_Buscape_API::topProducts with invalid categoryId
	 * @expectedException UnexpectedValueException
	 */
	public function testTopProductsWithInvalidCategoryId() {
		$this->buscapeWrapper->topProducts( array( 'categoryId' => -1 ) );
	}

	/**
	 * Tests Apiki_Buscape_API::topProducts with categoryId
	 */
	public function testTopProductsWithCategoryId() {
		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->topProducts( array( 'categoryId' => 10232 ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}
}