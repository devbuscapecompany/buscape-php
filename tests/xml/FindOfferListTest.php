<?php
require_once 'Apiki_Buscape_API.php';
require_once 'XMLTest.php';

/**
 * Apiki_Buscape_API::findOfferList test case.
 * @author	neto
 */
class FindOfferListTest extends XMLTest {
	/**
	 * Tests Apiki_Buscape_API::findOfferList with an empty array
	 * @expectedException UnexpectedValueException
	 */
	public function testFindOfferListWithEmptyArray() {
		$this->buscapeWrapper->findOfferList( array() );
	}

	/**
	 * Tests Apiki_Buscape_API::findOfferList with invalid categoryId
	 * @expectedException UnexpectedValueException
	 */
	public function testFindOfferListWithInvalidCategoryId() {
		$this->buscapeWrapper->findOfferList( array( 'categoryId' => -1 ) );
	}

	/**
	 * Tests Apiki_Buscape_API::findOfferList with invalid productId
	 * @expectedException UnexpectedValueException
	 */
	public function testFindOfferListWithInvalidProductId() {
		$this->buscapeWrapper->findOfferList( array( 'productId' => -1 ) );
	}

	/**
	 * Tests Apiki_Buscape_API::findOfferList with empty keyword
	 * @expectedException UnexpectedValueException
	 */
	public function testFindOfferListWithEmptyKeyword() {
		$this->buscapeWrapper->findOfferList( array( 'keyword' => '   ' ) );
	}

	/**
	 * Tests Apiki_Buscape_API::findOfferList with categoryId
	 */
	public function testFindOfferListWithCategoryId() {
		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->findOfferList( array( 'categoryId' => 10232 ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}

	/**
	 * Tests the XML response of Apiki_Buscape_API::findOfferList with categoryId
	 * @depends	testFindOfferListWithCategoryId
	 * @param	string $xml
	 */
	public function testXMLFindOfferListWithCategoryId( $xml ) {
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
	 * Tests Apiki_Buscape_API::findOfferList with keyword
	 */
	public function testFindOfferListWithKeyword() {
		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->findOfferList( array( 'keyword' => 'tablet' ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}

	/**
	 * Tests the XML response of Apiki_Buscape_API::findOfferList with keyword
	 * @depends	testFindOfferListWithKeyword
	 * @param	string $xml
	 */
	public function testXMLFindOfferListWithKeyword( $xml ) {
		$this->assertTag( array(
			'tag'		=>	'details',
			'parent'	=>	array(
							'tag'		=>	'Result',
							'child'		=>	array(
											'tag'			=>	'category',
						)
			),
			'child'		=>	array(
							'tag'		=>	'status',
							'content'	=>	'success'
			)
		), $xml );
	}

	/**
	 * Tests Apiki_Buscape_API::findOfferList with productId
	 */
	public function testFindOfferListWithProductId() {
		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->findOfferList( array( 'productId' => 238192 ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}

	/**
	 * Tests the XML response of Apiki_Buscape_API::findOfferList with keyword
	 * @depends	testFindOfferListWithProductId
	 * @param	string $xml
	 */
	public function testXMLFindOfferListWithProductId( $xml ) {
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
}