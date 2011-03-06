<?php
require_once 'XMLTest.php';

/**
 * Apiki_Buscape_API::findProductList test case.
 * @author	neto
 */
class FindProductListTest extends XMLTest {
	/**
	 * Tests Apiki_Buscape_API::findProductList() with empty array
	 * @expectedException UnexpectedValueException
	 */
	public function testFindProductListWithEmptyArray() {
		$this->buscapeWrapper->findProductList( array() );
	}

	/**
	 * Tests Apiki_Buscape_API::findProductList() with invalid categoryId
	 * @expectedException UnexpectedValueException
	 */
	public function testFindProductListWithInvalidCategoryId() {
		$this->buscapeWrapper->findProductList( array( 'categoryId' => -1 ) );
	}

	/**
	 * Tests Apiki_Buscape_API::findProductList() with categoryId and sourceId
	 */
	public function testFindProductListWithCategoryIdAndSourceId() {
		$dom = new DOMDocument();
		$this->buscapeWrapper->setSourceId( 9262544 );
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->findProductList( array( 'categoryId' => 10232 ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}

	/**
	 * Tests Apiki_Buscape_API::findProductList() with categoryId
	 */
	public function testFindProductListWithCategoryId() {
		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->findProductList( array( 'categoryId' => 10232 ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}

	/**
	 * Tests the XML response of Apiki_Buscape_API->findProductList with categoryId = 10232
	 * @depends	testFindProductListWithCategoryId
	 * @param	string $xml
	 */
	public function testXMLFindProductListWithCategoryId( $xml ) {
		$this->assertTag( array(
			'tag'		=>	'details',
			'parent'	=>	array(
							'tag'		=>	'Result',
							'child'		=>	array(
											'tag'			=>	'category',
											'attributes'	=>	array(
																'id'				=> 10232
											)
						)
			),
			'child'		=>	array(
							'tag'		=>	'status',
							'content'	=>	'success'
			)
		), $xml );
	}

	/**
	 * Tests Apiki_Buscape_API::findProductList() with empty keyword
	 * @expectedException UnexpectedValueException
	 */
	public function testFindProductListWithEmptyKeyword() {
		$this->buscapeWrapper->findProductList( array( 'keyword' => '   ' ) );
	}

	/**
	 * Tests Apiki_Buscape_API::findProductList() with a keyword
	 */
	public function testFindProductListWithKeyword() {
		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->findProductList( array( 'keyword' => 'tablet' ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}

	/**
	 * Tests the XML response of Apiki_Buscape_API->findProductList with keyword = tablet
	 * @depends	testFindProductListWithKeyword
	 * @param	string $xml
	 */
	public function testXMLFindProductListWithKeyword( $xml ) {
		$this->assertTag( array(
			'tag'		=>	'details',
			'child'		=>	array(
							'tag'		=>	'status',
							'content'	=>	'success'
			)
		), $xml );
	}
}