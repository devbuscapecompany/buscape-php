<?php
/**
 * A classe Apiki_Buscape_API foi criada para ajudar no desenvolvimento de
 * aplicações usando os webservices disponibilizados pela API do BuscaPé.
 *
 * As funções desta classe tem os mesmos nomes dos serviços disponibilizados pelo
 * BuscaPé.
 *
 * @author Apiki
 * @author João Batista Neto
 * @version 2.0.7
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
		$this->setApplicationId( $applicationId );
		$this->setSourceId( $sourceId );
	}

	/**
	 * Método busca retorna os dados da url requisitada
	 *
	 * @param   string $serviceName Nome do serviço
	 * @param	array $args Parâmetros que serão enviados
	 * @return  string Dados de retorno da URL requisitada
	 * @throws	RuntimeException Se a extensão CURL do PHP estiver desabilitada
	 */
	protected function _getContent( $serviceName , array $args , $lomadee = false ) {
		// @codeCoverageIgnoreStart
		if (  !function_exists( 'curl_init' ) ){
			throw new RuntimeException( 'A extensão CURL do PHP está desabilitada. Habilite-a para o funcionamento da classe.' );
		}
		// @codeCoverageIgnoreEnd
		
		if ( (bool) $lomadee ) {
			$serviceName .= '/lomadee';
		}

		if ( !empty( $this->_sourceId ) ){
			$args[ 'sourceId' ] = $this->_sourceId;
		}

		if ( $this->_environment == 'bws' && ( isset( $_SERVER[ 'REMOTE_ADDR' ] ) || ( $xip = isset( $_SERVER[ 'HTTP_X_IP' ] ) ) != false ) ) {
			$args[ 'clientIp' ] = preg_replace( '/[^0-9., ]/' , '' , $_SERVER[ isset($xip) && $xip ? 'HTTP_X_IP' : 'REMOTE_ADDR' ] );
		}

		if ($this->_isFormatJson()) {
			$args['format'] = 'json';
		}

		$url = sprintf( 'http://%s.buscape.com/service/%s/%s/%s/?%s' , $this->_environment , $serviceName , $this->_applicationId , $this->_countryCode , http_build_query( $args ) );

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
	 * Verifia se o formato de retorno configurado é JSON
	 *
	 * @codeCoverageIgnore
	 * @return bool
	 */
	private function _isFormatJson() {
		if ( $this->getFormat() == 'json' ){
			return true;
		}

		return false;
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
	 * Serviço utilizado somente na integração do Aplicativo com o Lomadee.
	 * Dentro do fluxo de integração, o aplicativo utiliza esse serviço para
	 * criar sourceId (código) para o Publisher que deseja utiliza-lo.
	 * Os parâmetros necessários neste serviço são informados pelo próprio
	 * Lomadee ao aplicativo.
	 * No ambiente de homologação sandbox, os valores dos parâmetros podem ser
	 * fictícios pois neste ambiente este serviço retornará sempre o mesmo sourceId
	 * para os testes do Developer.
	 *
	 * Todos os parâmetros necessários para a busca são informados em um array
	 * que deve ser passado para o método, são eles:
	 *
	 * <ul>
	 * <li>sourceName   = Nome do código.</li>
	 * <li>publisherId  = ID do publisher.</li>
	 * <li>siteId       = ID do site selecionado pelo publisher.</li>
	 * <li>campaignList = Lista de IDs das campanhas separados por vírgula.</li>
	 * <li>token        = Token utilizado para validação da requisição.</li>
	 * </ul>
	 *
	 * @param   array $args
	 * @return  string  O sourceId
	 */
	public function createSourceId( array $args ) {
		return $this->_getContent( 'createSource' , $args , true );
	}

	/**
	 * Método faz busca de categorias, permite que você exiba informações
	 * relativas às categorias. É possível obter categorias, produtos ou ofertas
	 * informando apenas um ID de categoria.
	 *
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
	 * @throws	UnexpectedValueException Se a palavra chave for uma string vazia.
	 * @throws	UnexpectedValueException Se o id da categoria for menor que zero.
	 */
	public function findCategoryList( array $args = array() ) {
		return $this->_getContent( 'findCategoryList' , $this->validateParams( array_merge( array( 'categoryId' => 0 ) , $args ) ) );
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
	 * <li>offerId      = ID da oferta</li>
	 * <li>callback     = Função de retorno a ser executada caso esteja usando o método
	 * json como retorno.</li>
	 * </ul>
	 *
	 * Pelo menos um dos parâmetros de pesquisa devem ser informados para o retorno
	 * da função. Os parâmetros <categoryId> e <keyword> podem ser usados em conjunto.
	 *
	 * @param   array   $args Parâmetros passados para gerar a url de requisição.
	 * @param   boolean $lomadee Indica se deverá ser utilizada a API do Lomadee
	 * @return  string  Retorno da pesquisa feita no BuscaPé, no formato requerido.
	 * @throws	UnexpectedValueException Se nenhum parâmetro for passado
	 * @throws	UnexpectedValueException Se o id da categoria for menor que zero.
	 * @throws	UnexpectedValueException Se o id do produto for menor que zero.
	 * @throws	UnexpectedValueException Se a palavra chave for uma string vazia.
	 */
	public function findOfferList( array $args = array() , $lomadee = false ) {
		return $this->_getContent( 'findOfferList' , $this->validateParams( $args , array() , array( 'categoryId' , 'productId' , 'keyword', 'barcode', 'offerId' ) ) , $lomadee );
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
	 * @param   boolean $lomadee Indica se deverá ser utilizada a API do Lomadee
	 * @return  string  Retorno da pesquisa feita no BuscaPé, no formato requerido.
	 * @throws	UnexpectedValueException Se nenhum parâmetro for passado.
	 * @throws	UnexpectedValueException Se o id da categoria for menor que zero.
	 * @throws	UnexpectedValueException Se a palavra chave for uma string vazia.
	 */
	public function findProductList( array $args = array() , $lomadee = false ) {
		return $this->_getContent( 'findProductList' , $this->validateParams( $args , array() , array( 'categoryId' , 'keyword' ) ) , $lomadee );
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
	 * Retorna o código do país
	 *
	 * @return string
	 */
	public function getCountryCode() {
		return $this->_countryCode;
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
	 * Retorna o formato de retorno
	 *
	 * @return string
	 */
	public function getFormat() {
		return $this->_format;
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
	 * Define o Source Id
	 *
	 * @param string $sourceId
	 */
	public function setSourceId( $sourceId ) {
		$this->_sourceId = $sourceId;
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
	 * @throws	UnexpectedValueException Se o id da categoria for menor que zero.
	 */
	public function topProducts( array $args = array() ) {
		return $this->_getContent( 'topProducts' , $this->validateParams( $args ) );
	}

	/**
	 * Valida os parâmetros que serão enviados à API do BuscaPé
	 * @param	array $args Matriz com os parâmetros
	 * @param	integer $and Lista de parâmetros obrigatórios
	 * @param	integer $or Lista de parâmetros opcionais, mas que pelo menos 1 deve existir
	 * @throws	OutOfBoundsException Se o número de parâmetros passados for menor que
	 * os obrigatórios.
	 * @throws	UnexpectedValueException Se algum dos parâmetros forem inválidos
	 * @throws	UnexpectedValueException Se nenhum dos parâmetros opcionais forem passados
	 */
	private function validateParams( array $args , array $and = array() , array $or = array() ){
		if ( isset( $args[ 'keyword' ] ) ){
			$args[ 'keyword' ] = trim( (string) $args[ 'keyword' ] );

			if ( empty( $args[ 'keyword' ] ) ){
				throw new UnexpectedValueException( 'A palavra chave não pode ser uma string vazia' );
			}
		}

		if ( isset( $args[ 'categoryId' ] ) ){
			$args[ 'categoryId' ] = (int) $args[ 'categoryId' ];

			if ( $args[ 'categoryId' ] < 0 ){
				throw new UnexpectedValueException( 'O id da categoria deve ser maior ou igual a zero' );
			}
		}

		if ( isset( $args[ 'productId' ] ) ){
			$args[ 'productId' ] = (int) $args[ 'productId' ];

			if ( $args[ 'productId' ] < 0 ){
				throw new UnexpectedValueException( 'O id do produto deve ser maior ou igual a zero' );
			}
		}

		if ( isset( $args[ 'sellerId' ] ) ){
			$args[ 'sellerId' ] = (int) $args[ 'sellerId' ];

			if ( $args[ 'sellerId' ] < 0 ){
				throw new UnexpectedValueException( 'O id da loja/empresa deve ser maior ou igual a zero' );
			}
		}

		if ( isset( $args[ 'barcode' ] ) ){
			$args[ 'barcode' ] = trim( (string) $args[ 'barcode' ] );

			if ( empty( $args[ 'barcode' ] ) ){
				throw new UnexpectedValueException( 'O código de barras não pode ser uma string vazia' );
			}
		}

		if ( isset( $args[ 'offerId' ] ) ){
			$args[ 'offerId' ] = (int) $args[ 'offerId' ];

			if ( $args[ 'offerId' ] < 0 ){
				throw new UnexpectedValueException( 'O id da oferta deve ser maior ou igual a zero' );
			}
		}

		foreach ( $and as $param ){
			if ( !isset( $args[ $param ] ) ){
				throw new UnexpectedValueException( sprintf( 'O parâmetro %s é requerido' , $param ) );
			}
		}

		for ( $i = 0 , $t = count( $or ) , $f = 0 ; $i < $t ; ++$i ){
			if ( isset( $args[ $or[ $i ] ] ) ){
				++$f;
				break;
			}
		}

		if ( $t != 0 && $f == 0 ){
			throw new UnexpectedValueException( sprintf( 'Pelo menos um dos parâmetros: "%s", devem ser passados e "%s" foi passado.' , implode( '","' , $or ) , implode( '","' , array_keys( $args ) ) ) );
		}

		return $args;
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
	public function viewProductDetails( array $args = array() ) {
		return $this->_getContent( 'viewProductDetails' , $this->validateParams( $args , array( 'productId' ) ) );
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
	public function viewSellerDetails( array $args = array() ) {
		return $this->_getContent( 'viewSellerDetails' , $this->validateParams( $args , array( 'sellerId' ) ) );
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
	 * @throws	UnexpectedValueException Se o ID do produto não for passado.
	 * @throws	UnexpectedValueException Se o ID do produto for menor que zero.
	 */
	public function viewUserRatings( array $args = array() ) {
		return $this->_getContent( 'viewUserRatings' , $this->validateParams( $args , array( 'productId' ) ) );
	}
}
