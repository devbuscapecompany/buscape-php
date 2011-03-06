<?php
require_once 'Apiki_Buscape_API.php';
require_once 'XMLTest.php';

/**
 * Apiki_Buscape_API::findCategoryList test case.
 * @author	neto
 */
class FindCategoryListTest extends XMLTest {
	/**
	 * 401 assert helper
	 * @param	DOMNodeList $nodeList
	 */
	private function assert401Content( DOMNodeList $nodeList ) {
		$h3Element = $nodeList->item( 0 );

		$this->assertNotNull( $h3Element );
		$this->assertEquals( 'The request requires user authentication' , $h3Element->nodeValue );
	}

	/**
	 * Tests Apiki_Buscape_API->findCategoryList with invalid applicationId in sandbox
	 */
	public function testSandBoxWithInvalidApplicationId() {
		$this->buscapeWrapper->setApplicationId( '1234' );
		$this->buscapeWrapper->setSandbox();

		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadHTML( $this->buscapeWrapper->findCategoryList() ) );
		$this->assert401Content( $dom->getElementsByTagName( 'h3' ) );
	}

	/**
	 * Tests Apiki_Buscape_API->findCategoryList with invalid applicationId in production
	 */
	public function testProductionWithInvalidApplicationId() {
		$this->buscapeWrapper->setApplicationId( '1234' );

		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadHTML( $this->buscapeWrapper->findCategoryList() ) );
		$this->assert401Content( $dom->getElementsByTagName( 'h3' ) );
	}

	/**
	 * Tests Apiki_Buscape_API->findCategoryList in sandbox with categoryId param
	 */
	public function testSandBoxFindCategoryListWithCategoryId() {
		$this->buscapeWrapper->setSandbox();

		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->findCategoryList( array( 'categoryId' => 1 ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}

	/**
	 * Tests the XML response of Apiki_Buscape_API->findCategoryList with categoryId = 1
	 * @depends testSandBoxFindCategoryListWithCategoryId
	 * @param string $xml
	 */
	public function testSandBoxXMLFindCategoryListWithCategoryId( $xml ){
		$this->assertTag( array(
			'tag'		=>	'details',
			'parent'	=>	array(
							'tag'		=>	'Result',
							'child'		=>	array(
											'tag'			=>	'category',
											'attributes'	=>	array(
																'parentCategoryId'	=> 0,
																'id'				=> 1
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
	 * Tests Apiki_Buscape_API->findCategoryList in sandbox with an invalid categoryId param
	 * @expectedException UnexpectedValueException
	 */
	public function testSandBoxFindCategoryListWithInvalidCategoryId() {
		$this->buscapeWrapper->setSandbox();
		$this->buscapeWrapper->findCategoryList( array( 'categoryId' => -1 ) );
	}

	/**
	 * Tests Apiki_Buscape_API->findCategoryList in sandbox with keyword
	 */
	public function testSandBoxFindCategoryListWithKeyword() {
		$this->buscapeWrapper->setSandbox();

		$dom = new DOMDocument();
		$this->assertTrue( $dom->loadXML( $this->buscapeWrapper->findCategoryList( array( 'keyword' => 'eletronicos' ) ) ) );
		$this->assertThatXMLIsValid( $dom );

		return $dom->saveXML();
	}

	/**
	 * Tests the XML response of Apiki_Buscape_API->findCategoryList with keyword = eletronicos
	 * @depends testSandBoxFindCategoryListWithKeyword
	 * @param string $xml
	 */
	public function testSandBoxXMLFindCategoryListWithKeyword( $xml ){
		$this->assertTag( array(
			'tag'		=>	'details',
			'child'		=>	array(
							'tag'		=>	'status',
							'content'	=>	'success'
			)
		), $xml );
	}

	/**
	 * Tests Apiki_Buscape_API->findCategoryList in sandbox with invalid keyword
	 * @expectedException UnexpectedValueException
	 */
	public function testSandBoxFindCategoryListWithInvalidKeyword() {
		$this->buscapeWrapper->setSandbox();
		$this->buscapeWrapper->findCategoryList( array( 'keyword' => '    ' ) );
	}
}