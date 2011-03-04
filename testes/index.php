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

//echo $objBuscaPeApi->findCategoryList();
//echo $objBuscaPeApi->findProductList( array( 'keyword' => 'Celular,Nokia' ) );
//echo $objBuscaPeApi->findOfferList( array( 'productId' => 240493 ) );
echo $objBuscaPeApi->topProducts();
//echo $objBuscaPeApi->viewUserRatings( array( 'productId' => 240493 ) );
//echo $objBuscaPeApi->viewProductDetails( array( 'productId' => 23348 ) );
//echo $objBuscaPeApi->viewSellerDetails( array( 'sellerId' => 335525 ) );
?>
