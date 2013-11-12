<?php
/**
 * A classe BlackfridayLomadee foi criada para ajudar no desenvolvimento de
 * aplicações usando os webservices disponibilizados pela API do Buscapé para o Blackfriday.
 * 
 * Documentação: http://developer.buscape.com
 *
 * @author Daniel Freire
 * @version 1.0.0
 * @license Creative Commons Atribuição 3.0 Brasil. http://creativecommons.org/licenses/by/3.0/br/
 */

class BlackfridayLomadee {
	/**
	 * Id da aplicação
	 *
	 * @var string
	 */
	protected $_applicationId = '';
	
	/**
	 * Source ID
	 *
	 * @var string
	 */
	protected $_sourceId = '';
	
	/**
	 * Ambiente bws
	 * 
	 * Homologação: sandbox
	 * Produção: bws
	 *
	 * @var string
	 */
	protected $_environment = 'sandbox';
	
	/**
	 * A cada instância criada deverá ser passado como parâmetro obrigatório o
	 * id da aplicação. O Source ID não é obrigatório
	 *
	 * @param string $applicationId
	 * @param string $sourceId
	 * @throws InvalidArgumentException Se o ID da aplicação não for passado
	 */
	public function __construct( $applicationId , $sourceId = '' ) {
		$this->setApplicationId( $applicationId );
		$this->setSourceId( $sourceId );
	}
	
	/**
	 * Método busca retorna os dados da url requisitada
	 *
	 * @param	array $args Parâmetros que serão enviados
	 * @throws	RuntimeException Se a extensão CURL do PHP estiver desabilitada
	 */
	protected function getOffers( $categoryId = '' ) {
		// @codeCoverageIgnoreStart
		if (  !function_exists( 'curl_init' ) ){
			throw new RuntimeException( 'A extensão CURL do PHP está desabilitada. Habilite-a para o funcionamento da classe.' );
		}
	
		if ( !empty( $this->_sourceId ) ){
			$args[ 'sourceId' ] = $this->_sourceId;
		}
		
		if ( !empty( $categoryId ) ){
			$args[ 'categoryId' ] = $categoryId;
		}
	
		if ( $this->_environment == 'bws' && ( isset( $_SERVER[ 'REMOTE_ADDR' ] ) || ( $xip = isset( $_SERVER[ 'HTTP_X_IP' ] ) ) != false ) ) {
			$args[ 'clientIp' ] = preg_replace( '/[^0-9., ]/' , '' , $_SERVER[ isset($xip) && $xip ? 'HTTP_X_IP' : 'REMOTE_ADDR' ] );
		}
	
		$args['format'] = 'json';
	
		$url = sprintf( 'http://%s.buscape.com/service/blackfriday/buscape/%s/BR?format=json&%s' , $this->_environment , $this->_applicationId , http_build_query( $args ) );
	
		$curl = curl_init();
		curl_setopt( $curl , CURLOPT_URL , $url );
		curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , false );
		curl_setopt( $curl , CURLOPT_USERAGENT , isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) ? $_SERVER[ 'HTTP_USER_AGENT' ] : "Mozilla/4.0" );
		curl_setopt( $curl , CURLOPT_RETURNTRANSFER , true );
		$retorno = curl_exec( $curl );
		curl_close( $curl );
	
		return $retorno;
	}
	
	/**
	 * Define se a integração vai ser feita no sandbox ou não
	 *
	 * @param bool $is
	 */
	public function setSandbox() {
		$this->_setEnvironment( 'sandbox' );
	}
	
	/**
	 * Retorna o Id da aplicação
	 *
	 * @return string
	 */
	public function getApplicationId() {
		return $this->_applicationId;
	}
	
	/**
	 * Define o Id da aplicação
	 *
	 * @param string $applicationId
	 * @throws InvalidArgumentException Se o ID da aplicação não for passado
	 */
	public function setApplicationId( $applicationId ) {
		if ( empty( $applicationId ) ){
			throw new InvalidArgumentException( 'ID da aplicação não pode ser vazio.' );
		}
	
		$this->_applicationId = $applicationId;
	}
	
	/**
	 * Retorna o Source ID
	 *
	 * @return string
	 */
	public function getSourceId() {
		return $this->_sourceId;
	}
	
	/**
	 * Define o Source Id
	 *
	 * @param string $sourceId
	 */
	public function setSourceId( $sourceId ) {
		$this->_sourceId = $sourceId;
	}
	
	/**
	 * Retorna o ambiente de integração
	 *
	 * @return string
	 */
	public function getEnvironment() {
		return $this->_environment;
	}
	
	/**
	 * Define o ambiente de integração
	 *
	 * @param string $environment (bws|sandbox)
	 */
	private function _setEnvironment( $environment ) {
		$this->_environment = $environment;
	}
}