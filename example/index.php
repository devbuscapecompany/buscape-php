<?php
/**
 * Este arquivo está com as configurações necessárias para testar a integração
 * com a API do BuscaPé. É apenas um exemplo. Teste uma possível integração aqui
 * e depois desenvolva em sua aplicação.
 *
 * Abaixo temos todos os métodos disponíveis pela classe comentados, descomente
 * o que quiser testar. O valor retornado será exibido na tela para que você possa
 * verificar.
 */
require_once '../Apiki_Buscape_API.php';

/*
 * Application ID usado para testes na API do BuscaPé. O mesmo não é válido para
 * o ambiente de produção.
 */
$applicationID  = '564771466d477a4458664d3d';

$sourceID       = '';

$objBuscaPeApi = new Apiki_Buscape_API( $applicationID, $sourceID );
$objBuscaPeApi->setSandbox();

// Busca uma lista de categorias
//echo $objBuscaPeApi->findCategoryList();

// Busca uma lista de produtos por palavras-chave
//echo $objBuscaPeApi->findProductList( array( 'keyword' => 'Celular,Nokia' ) );

// Busca ofertas a partir de palavras-chave
// echo $objBuscaPeApi->findOfferList( array( 'keyword' => 'iPhone 5' ) );

// Busca os dados de uma oferta a partir do seu ID
// echo $objBuscaPeApi->findOfferList( array( 'offerId' => 126733147 ) );

// Busca ofertas a partir de um código de barras
// echo $objBuscaPeApi->findOfferList( array( 'barcode' => 9788575222379 ) );

// Busca os produtos mais clicados da última semana
//echo $objBuscaPeApi->topProducts();

// Busca as avaliações dos usuários através do ID do produto
//echo $objBuscaPeApi->viewUserRatings( array( 'productId' => 240493 ) );

// Busca os detalhes de um produto a partir de seu ID
//echo $objBuscaPeApi->viewProductDetails( array( 'productId' => 232685 ) );

// Busca os detalhes de uma loja a partir de seu ID
//echo $objBuscaPeApi->viewSellerDetails( array( 'sellerId' => 335525 ) );
