<?php
/**
 * A classe Apiki_Buscape_API foi criada para ajudar no desenvolvimento de
 * aplicações usando os webservices disponibilizados pela API do BuscaPé.
 *
 * As funções desta classe tem os mesmos nomes dos serviços disponibilizados pelo
 * BuscaPé.
 *
 * @author Apiki
 * @version 2.0
 * @license Creative Commons Atribuição 3.0 Brasil. http://creativecommons.org/licenses/by/3.0/br/
 */
class Apiki_Buscape_API {

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
	 * Código do país
	 *
	 * @var string
	 */
	protected $_countryCode = 'BR';

	/**
	 * Formato de retorno padrão
	 *
	 * @var string
	 */
	protected $_format = 'xml';

	/**
	 * Ambiente bws (produção)
	 *
	 * @var string
	 */
	protected $_environment = 'bws';

	/**
	 * A cada instância criada deverá ser passado como parâmetro obrigatório o
	 * id da aplicação. O Source ID não é obrigatório
	 *
	 * @param string $applicationId
	 * @param string $sourceId
	 * @throws InvalidArgumentException Se o ID da aplicação não for passado
	 */
	public function __construct( $applicationId , $sourceId = '' ) {
		if ( empty( $applicationId ) ){
			throw new InvalidArgumentException( 'ID da aplicação requerido.' );
		}

		$this->setApplicationId( $applicationId );
		$this->setSourceId( $sourceId );
	}

	/**
	 * Define o Id da aplicação
	 *
	 * @param string $applicationId
	 */
	public function setApplicationId( $applicationId ) {
		$this->_applicationId = $applicationId;
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
	 * Define o código do país
	 *
	 * @param string $countryCode (AR|BR|CL|CO|MX|PE|VE)
	 * @throws InvalidArgumentException Se o código do país não existir
	 */
	public function setCountryCode( $countryCode ) {
		if ( !in_array( strtoupper( $countryCode ) , array( 'AR' , 'BR' , 'CL' , 'CO' , 'MX' , 'PE' , 'VE' ) ) ){
			throw new InvalidArgumentException( sprintf( 'O código do país <b>%s</b> não existe.' , $countryCode ) );
		}

		$this->_countryCode = $countryCode;
	}

	/**
	 * Define o formato de retorno
	 *
	 * @param string $format (xml|json)
	 * @throws InvalidArgumentException Se o formato não existir
	 */
	public function setFormat( $format ) {
		if ( !in_array( strtolower( $format ) , array( 'xml' , 'json' ) ) ){
			throw new InvalidArgumentException( sprintf( 'O formato de retorno <b>%s</b> não existe.' , $format ) );
		}

		$this->_format = $format;
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
	 * Retorna o Source ID
	 *
	 * @return string
	 */
	public function getSourceId() {
		return $this->_sourceId;
	}

	/**
	 * Retorna o código do país
	 *
	 * @return string
	 */
	public function getCountryCode() {
		return $this->_countryCode;
	}

	/**
	 * Retorna o formato de retorno
	 *
	 * @return string
	 */
	public function getFormat() {
		return $this->_format;
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
	 * Método faz busca de categorias, permite que você exiba informações
	 * relativas às categorias. É possível obter categorias, produtos ou ofertas
	 * informando apenas um ID de categoria.
	 *
	 * Todos os parâmetros necessários para a busca são informados em um array
	 * que deve ser passado para o método, são eles:
	 *
	 * <ul>
	 * <li>categoryId   = Id da categoria</li>
	 * <li>keyword      = Palavra-chave buscada entre as categorias</li>
	 * <li>callback     = Função de retorno a ser executada caso esteja usando o método
	 * json como retorno.</li>
	 * </ul>
	 *
	 * Se não for informado nenhum dos parâmetros, a função retornará por padrão
	 * uma lista de categorias raiz, de id 0.
	 *
	 * @param   array   $args Parâmetros passados para gerar a url de requisição
	 * @return  string  Retorno da pesquisa feita no BuscaPé, no formato requerido.
	 */
	public function findCategoryList( $args = array() ) {
		$serviceName = 'findCategoryList';
		$default = array( 'categoryId' => 0 );
		$args = array_merge( $default , $args );

		if ( !empty( $args[ 'categoryId' ] ) or $args[ 'categoryId' ] == 0 ){
			$param = '?categoryId=' . (int) $args[ 'categoryId' ];
		}

		if ( !empty( $args[ 'keyword' ] ) ){
			$param = '?keyword=' . (string) $args[ 'keyword' ];
		}

		$callback = !empty( $args[ 'callback' ] ) ? '&callback=' . $args[ 'callback' ] : '';
		$url = sprintf( 'http://%s.buscape.com/service/%s/%s/%s/%s%s%s%s' , $this->getEnvironment() , $serviceName , $this->getApplicationId() , $this->getCountryCode() , $param , $this->_jsonParam() , $callback , $this->_sourceIdParam() );

		return $this->_getContent( $url );
	}

	/**
	 * Método permite que você busque uma lista de produtos únicos
	 * utilizando o id da categoria final ou um conjunto de palavras-chaves
	 * ou ambos.
	 *
	 * Todos os parâmetros necessários para a busca são informados em um array
	 * que deve ser passado para o método, são eles:
	 *
	 * <ul>
	 * <li>categoryId   = Id da categoria</li>
	 * <li>keyword      = Palavra-chave buscada entre as categorias</li>
	 * <li>callback     = Função de retorno a ser executada caso esteja usando o método
	 * json como retorno.</li>
	 * </ul>
	 *
	 * Pelo menos um dos parâmetros, <categoryID> ou <keyword> são requeridos para
	 * funcionamento desta função. Os dois também podem ser usados em conjunto.
	 * Ou seja, podemos buscar uma palavra-chave em apenas uma determinada categoria.
	 *
	 * @param   array   $args Parâmetros para gerar a url de requisição
	 * @return  string  Retorno da pesquisa feita no BuscaPé, no formato requerido.
	 * @throws	UnexpectedValueException Se nenhum parâmetro for passado
	 */
	public function findProductList( $args = array() ) {
		$serviceName = 'findProductList';
		$default = array();
		$args = array_merge( $default , $args );

		if ( !empty( $args[ 'categoryId' ] ) ){
			$param = '?categoryId=' . (int) $args[ 'categoryId' ];
		}

		if ( isset( $param ) and  !empty( $args[ 'keyword' ] ) ){
			$param = $param . '&keyword=' . (string) $args[ 'keyword' ];
		}

		if ( !isset( $param ) and  !empty( $args[ 'keyword' ] ) ){
			$param = '?keyword=' . (string) $args[ 'keyword' ];
		}

		if ( empty( $param ) ){
			throw new UnexpectedValueException( sprintf( 'Pelo menos um parâmetro de pesquisa é requerido na função <b>%s</b>.' , $serviceName ) );
		}

		$callback = !empty( $args[ 'callback' ] ) ? '&callback=' . $args[ 'callback' ] : '';
		$url = sprintf( 'http://%s.buscape.com/service/%s/%s/%s/%s%s%s%s' , $this->getEnvironment() , $serviceName , $this->getApplicationId() , $this->getCountryCode() , $param , $this->_jsonParam() , $callback , $this->_sourceIdParam() );

		return $this->_getContent( $url );
	}

	/**
	 * Método busca uma lista de ofertas. É possível obter a lista de ofertas
	 * informando o ID do produto.
	 *
	 * Todos os parâmetros necessários para a busca são informados em um array
	 * que deve ser passado para o método, são eles:
	 *
	 * <ul>
	 * <li>categoryId   = Id da categoria</li>
	 * <li>keyword      = Palavra-chave buscada entre as categorias</li>
	 * <li>productId    = Id do produto</li>
	 * <li>barcode      = Código de barras do produto</li>
	 * <li>callback     = Função de retorno a ser executada caso esteja usando o método
	 * json como retorno.</li>
	 * </ul>
	 *
	 * Pelo menos um dos parâmetros de pesquisa devem ser informados para o retorno
	 * da função. Os parâmetros <categoryId> e <keyword> podem ser usados em conjunto.
	 *
	 * @param   array   $args Parâmetros passados para gerar a url de requisição.
	 * @return  string  Retorno da pesquisa feita no BuscaPé, no formato requerido.
	 * @throws	UnexpectedValueException Se nenhum parâmetro for passado
	 */
	public function findOfferList( $args = array() ) {
		$serviceName = 'findOfferList';
		$default = array();
		$args = array_merge( $default , $args );

		if ( !empty( $args[ 'categoryId' ] ) ){
			$param = '?categoryId=' . $args[ 'categoryId' ];
		}

		if ( isset( $param ) and  !empty( $args[ 'keyword' ] ) ){
			$param = $param . '&keyword=' . $args[ 'keyword' ];
		} elseif ( !isset( $param ) and  !empty( $args[ 'keyword' ] ) ){
			$param = '?keyword=' . $args[ 'keyword' ];
		}

		if ( !empty( $args[ 'productId' ] ) ){
			$param = '?productId=' . $args[ 'productId' ];
		}

		if ( !empty( $args[ 'barcode' ] ) ){
			$param = '?barcode=' . $args[ 'barcode' ];
		}

		if ( empty( $param ) ){
			throw new UnexpectedValueException( sprintf( 'Pelo menos um parâmetro de pesquisa é requerido na função <b>%s</b>.' , $serviceName ) );
		}

		$callback = !empty( $args[ 'callback' ] ) ? '&callback=' . $args[ 'callback' ] : '';
		$url = sprintf( 'http://%s.buscape.com/service/%s/%s/%s/%s%s%s%s' , $this->getEnvironment() , $serviceName , $this->getApplicationId() , $this->getCountryCode() , $param , $this->_jsonParam() , $callback , $this->_sourceIdParam() );

		return $this->_getContent( $url );
	}

	/**
	 * Método retorna os produtos mais populares do BuscaPé
	 *
	 * Todos os parâmetros necessários para a busca são informados em um array
	 * que deve ser passado para o método, são eles:
	 *
	 * <ul>
	 * <li>callback = Função de retorno a ser executada caso esteja usando o método
	 * json como retorno.</li>
	 * </ul>
	 *
	 * @param   array   $args Parâmetros passados para gerar a url de requisição.
	 * @return  string  Retorno da pesquisa feita no BuscaPé, no formato requerido.
	 */
	public function topProducts( $args = array() ) {
		$serviceName = 'topProducts';
		$callback = !empty( $args[ 'callback' ] ) ? '&callback=' . $args[ 'callback' ] : '';
		$url = sprintf( 'http://%s.buscape.com/service/%s/%s/%s/%s%s%s' , $this->getEnvironment() , $serviceName , $this->getApplicationId() , $this->getCountryCode() , $this->_jsonParam() , $callback , $this->_sourceIdParam() );

		return $this->_getContent( $url );
	}

	/**
	 * Método retorna as avaliações dos usuários sobre um determinado produto
	 *
	 * Todos os parâmetros necessários para a busca são informados em um array
	 * que deve ser passado para o método, são eles:
	 *
	 * <ul>
	 * <li>productId    = Id do produto (requerido)</li>
	 * <li>callback     = Função de retorno a ser executada caso esteja usando o método
	 * json como retorno.</li>
	 * </ul>
	 *
	 * @param   args    $args Parâmetros passados para gerar a url de requisição.
	 * @return  string  Retorno da pesquisa feita no BuscaPé, no formato requerido.
	 * @throws	UnexpectedValueException Se o ID do produto não for passado
	 */
	public function viewUserRatings( $args = array() ) {
		$serviceName = 'viewUserRatings';
		$default = array();
		$args = array_merge( $default , $args );

		if ( !empty( $args[ 'productId' ] ) ){
			$param = '?productId=' . $args[ 'productId' ];
		}

		if ( empty( $param ) ){
			throw new UnexpectedValueException( sprintf( 'ID do produto requerido na função <b>%s</b>.' , $serviceName ) );
		}

		$callback = (  !empty( $args[ 'callback' ] ) ) ? '&callback=' . $args[ 'callback' ] : '';
		$url = sprintf( 'http://%s.buscape.com/service/%s/%s/%s/%s%s%s%s' , $this->getEnvironment() , $serviceName , $this->getApplicationId() , $this->getCountryCode() , $param , $this->_jsonParam() , $callback , $this->_sourceIdParam() );

		return $this->_getContent( $url );
	}

	/**
	 * Função retorna os detalhes técnicos de um determinado produto.
	 *
	 * Todos os parâmetros necessários para a busca são informados em um array
	 * que deve ser passado para o método, são eles:
	 *
	 * <ul>
	 * <li>productId    = Id do produto (requerido)</li>
	 * <li>callback     = Função de retorno a ser executada caso esteja usando o método
	 * json como retorno.</li>
	 * </ul>
	 *
	 * @param	array    $args Parâmetros passados para gerar a url de requisição.
	 * @return	string   Função de retorno a ser executada caso esteja usando o método
	 * @throws	UnexpectedValueException Se o ID do produto não for passado
	 */
	public function viewProductDetails( $args = array() ) {
		$serviceName = 'viewProductDetails';
		$default = array();
		$args = array_merge( $default , $args );

		if ( !empty( $args[ 'productId' ] ) ){
			$param = '?productId=' . $args[ 'productId' ];
		}

		if ( empty( $param ) ){
			throw new UnexpectedValueException( sprintf( 'ID do produto requerido na função <b>%s</b>.' , $serviceName ) );
		}

		$callback = !empty( $args[ 'callback' ] ) ? '&callback=' . $args[ 'callback' ] : '';
		$url = sprintf( 'http://%s.buscape.com/service/%s/%s/%s/%s%s%s%s' , $this->getEnvironment() , $serviceName , $this->getApplicationId() , $this->getCountryCode() , $param , $this->_jsonParam() , $callback , $this->_sourceIdParam() );

		return $this->_getContent( $url );
	}

	/**
	 * Função retorna os detalhes da loja/empresa como: endereços, telefones de
	 * contato etc...
	 *
	 * Todos os parâmetros necessários para a busca são informados em um array
	 * que deve ser passado para o método, são eles:
	 *
	 * <ul>
	 * <li>sallerId = Id da loja/empresa (requerido)</li>
	 * <li>callback = Função de retorno a ser executada caso esteja usando o método
	 * json como retorno.</li>
	 * </ul>
	 *
	 * @param   array   $args Parâmetros passados para gerar a url de requisição.
	 * @return  string  Função de retorno a ser executada caso esteja usando o método.
	 * @throws	UnexpectedValueException Se o ID da loja não for passado
	 */
	public function viewSellerDetails( $args = array() ) {
		$serviceName = 'viewSellerDetails';
		$default = array();
		$args = array_merge( $default , $args );

		if ( !empty( $args[ 'sellerId' ] ) ){
			$param = '?sellerId=' . $args[ 'sellerId' ];
		}

		if ( empty( $param ) ){
			throw new UnexpectedValueException( sprintf( 'ID da loja/empresa requerido na função <b>%s</b>.' , $serviceName ) );
		}

		$callback = !empty( $args[ 'callback' ] ) ? '&callback=' . $args[ 'callback' ] : '';
		$url = sprintf( 'http://%s.buscape.com/service/%s/%s/%s/%s%s%s%s' , $this->getEnvironment() , $serviceName , $this->getApplicationId() , $this->getCountryCode() , $param , $this->_jsonParam() , $callback , $this->_sourceIdParam() );

		return $this->_getContent( $url );
	}

	/**
	 * Define o ambiente de integração
	 *
	 * @param string $environment (bws|sandbox)
	 */
	private function _setEnvironment( $environment ) {
		$this->_environment = $environment;
	}

	/**
	 * Verifia se o formato de retorno configurado é JSON
	 *
	 * @return bool
	 */
	private function _isFormatJson() {
		if ( $this->getFormat() == 'json' ){
			return true;
		}

		return false;
	}

	/**
	 * Retorna o formato do parâmetro para uso na url de requisição à API do
	 * BuscaPé
	 *
	 * @return string
	 */
	private function _jsonParam() {
		if ( $this->_isFormatJson() ){
			return '&format=json';
		}

		return '';
	}

	/**
	 * Retorna o formato do parâmetro para uso na url de requisição à API do
	 * BuscaPé.
	 *
	 * @return string
	 */
	private function _sourceIdParam() {
		return '&sourceId=' . $this->getSourceId();
	}

	/**
	 * Método exibe os erros
	 *
	 * @deprecated Exibição de erros substituída por exceções
	 * @param string $error
	 */
	protected function _showErrors( $error ) {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
		echo $error;

		trigger_error( 'O método _showErrors foi marcado como deprecated e será removido em futuras implementações' , E_USER_DEPRECATED );
		exit();
	}

	/**
	 * Método busca retorna os dados da url requisitada
	 *
	 * @param   str $url URL de acesso via CURL
	 * @return  str Dados de retorno da URL requisitada
	 * @throws	RuntimeException Se a extensão CURL do PHP estiver desabilitada
	 */
	protected function _getContent( $url ) {
		if (  !function_exists( 'curl_init' ) ){
			throw new RuntimeException( 'A extensão CURL do PHP está desabilitada. Habilite-a para o funcionamento da classe.' );
		}

		if ( $this->getEnvironment() == 'bws' ){
			$url = $url . '&clientIp=' . preg_replace( '/[^0-9., ]/' , '' , $_SERVER[ 'REMOTE_ADDR' ] );
		}

		$curl = curl_init();
		curl_setopt( $curl , CURLOPT_URL , $url );
		curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , false );
		curl_setopt( $curl , CURLOPT_USERAGENT , isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) ? $_SERVER[ 'HTTP_USER_AGENT' ] : "Mozilla/4.0" );
		curl_setopt( $curl , CURLOPT_RETURNTRANSFER , true );
		$retorno = curl_exec( $curl );
		curl_close( $curl );

		return $retorno;
	}
}