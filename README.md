Classe Apiki_Buscape_API
========================

Descrição
---------

A classe __Apiki_Buscape_API__ foi criada para ajudar no desenvolvimento de aplicações usando os webservices disponibilizados pela __API do BuscaPé__.

Como funciona ?
---------------

A classe Apiki_Buscape_API __pré-configura__ sua aplicação para uma integração com a API do BuscaPé, ou seja, ela __facilita a integração e agiliza o desenvolvimento__ das aplicações.

Como Usar ?
-----------

. Defina um diretório para ela em sua aplicação.

> mkdir buscape-php

. Faça um clone do repositório

> git clone https://github.com/buscapedev/buscape-php.git buscape-php

. Instancie a classe.
. Use sua instância criada para chamar os métodos da classe.

	require_once 'Apiki_Buscape_API.php';

	$applicationID  = '564771466d477a4458664d3d';
	$sourceID       = '';

	$objBuscaPeApi = new Apiki_Buscape_API( $applicationID, $sourceID );
	$objBuscaPeApi->findCategoryList();

Para mais informações acesse o (guia do desenvolvedor BuscaPé)[http://developer.buscape.com/api/]

Métodos de Consulta Disponíveis
===============================

string Apiki_Buscape_API::findCategoryList( array $args )
---------------------------------------------------------

Método faz busca de categorias, permite que você exiba informações relativas às categorias.
É possível obter categorias, produtos ou ofertas informando apenas um ID de categoria.

Todos os parâmetros necessários para a busca são informados em um array que deve ser passado para o método, são eles:

* __categoryId__ Id da categoria.
* __keyword__ Palavra-chave buscada entre as categorias.
* __callback__ Função de retorno a ser executada caso esteja usando o método __JSON__ como retorno.

Se não for informado nenhum dos parâmetros, a função retornará por padrão uma lista de categorias raiz, de id 0.

string Apiki_Buscape_API::findOfferList( array $args )
------------------------------------------------------

Método busca uma lista de ofertas.
É possível obter a lista de ofertas informando o ID do produto.

Todos os parâmetros necessários para a busca são informados em um array que deve ser passado para o método, são eles:

* __categoryId__ Id da categoria.
* __keyword__ Palavra-chave buscada entre as categorias.
* __productId__ Id do produto.
* __barcode__ Código de barras do produto.
* __callback__ Função de retorno a ser executada caso esteja usando o método __JSON__ como retorno.

Pelo menos um dos parâmetros de pesquisa devem ser informados para o retorno da função. Os parâmetros __categoryId__ e __keyword__ podem ser usados em conjunto.

string Apiki_Buscape_API::findProductList( array $args )
--------------------------------------------------------

Método permite que você busque uma lista de produtos únicos utilizando o id da categoria final ou um conjunto de palavras-chaves ou ambos.
Todos os parâmetros necessários para a busca são informados em um array que deve ser passado para o método, são eles:

* __categoryId__ Id da categoria.
* __keyword__ Palavra-chave buscada entre as categorias.
* __callback__ Função de retorno a ser executada caso esteja usando o método __JSON__ como retorno.

Pelo menos um dos parâmetros, __categoryID__ ou __keyword__ são requeridos para funcionamento desta função. Os dois também podem ser usados em conjunto.
Ou seja, podemos buscar uma palavra-chave em apenas uma determinada categoria.

string Apiki_Buscape_API::topProducts( array $args )
----------------------------------------------------

Método retorna os produtos mais populares do BuscaPé.
Todos os parâmetros necessários para a busca são informados em um array que deve ser passado para o método, são eles:

* __callback__ Função de retorno a ser executada caso esteja usando o método __JSON__ como retorno.

string Apiki_Buscape_API::viewProductDetails( array $args )
-----------------------------------------------------------

Função retorna os detalhes técnicos de um determinado produto.
Todos os parâmetros necessários para a busca são informados em um array que deve ser passado para o método, são eles:

* __productId__ Id do produto _(requerido)_.
* __callback__ Função de retorno a ser executada caso esteja usando o método json como retorno.

string Apiki_Buscape_API::viewSellerDetails( array $args )
----------------------------------------------------------

Função retorna os detalhes da loja/empresa como: endereços, telefones de contato etc...
Todos os parâmetros necessários para a busca são informados em um array que deve ser passado para o método, são eles:

* __sellerId__ Id da loja/empresa _(requerido)_.
* __callback__ Função de retorno a ser executada caso esteja usando o método __JSON__ como retorno.

string Apiki_Buscape_API::viewUserRatings( array $args )
--------------------------------------------------------

Método retorna as avaliações dos usuários sobre um determinado produto.
Todos os parâmetros necessários para a busca são informados em um array que deve ser passado para o método, são eles:

* __productId__ Id do produto _(requerido)_.
* __callback__ Função de retorno a ser executada caso esteja usando o método __JSON__ como retorno.

Getters, Setters e Métodos auxiliares
=====================================

string Apiki_Buscape_API::getApplicationId()
--------------------------------------------

Retorna o Id da aplicação.

Veja também Apiki_Buscape_API::setApplicationId( string $applicationId )

string Apiki_Buscape_API::getCountryCode()
------------------------------------------

Retorna o código do país.

Veja também Apiki_Buscape_API::setCountryCode( string $countryCode )

string Apiki_Buscape_API::getEnvironment()
------------------------------------------

Retorna o ambiente de integração (_bws_ para produção e _sandbox_ para testes).

Veja também Apiki_Buscape_API::setSandbox()

string Apiki_Buscape_API::getFormat()
-------------------------------------

Retorna o formato de retorno (_xml_ ou _json_).

Veja também Apiki_Buscape_API::setFormat( string $format )

string Apiki_Buscape_API::getSourceId()
---------------------------------------

Retorna o Source ID.

Veja também Apiki_Buscape_API::setSourceId( string $sourceId )

void Apiki_Buscape_API::setApplicationId( string $applicationId )
-----------------------------------------------------------------

Define o Id da aplicação.

* _string_ __$applicationId__ ID da aplicação registrado no BuscaPé.

Para obter um ID de aplicação você precisará fazer seu (registro)[http://developer.buscape.com/admin/registration.html]

Veja também Apiki_Buscape_API::getApplicationId()

void Apiki_Buscape_API::setCountryCode( string $countryCode )
-------------------------------------------------------------

Define o código do país.

* _string_ __$countryCode_ Código do país, pode ser um dos abaixo:
** __AR__ Para Argentina
** __BR__ Para Brasil
** __CL__ Para Chile
** __CO__ Para Colômbia
** __MX__ Para México
** __PE__ Para Peru
** __VE__ Para Venezuela

Veja também Apiki_Buscape_API::getCountryCode()

void Apiki_Buscape_API::setFormat( string $format )
---------------------------------------------------

Define o formato de retorno.

* _string_ __$format__ Formato do retorno, pode ser __xml__ ou __json__

Veja também Apiki_Buscape_API::getFormat()

void Apiki_Buscape_API::setSandbox( void )
------------------------------------------

Define se a integração vai ser feita no sandbox ou no ambiente de produção.

Veja também Apiki_Buscape_API::getEnvironment()

void Apiki_Buscape_API::setSourceId( string $sourceId )
-------------------------------------------------------

Define o sourceId

* _string_ __$sourceId__ O sourceId

Veja também Apiki_Buscape_API::getSourceId()