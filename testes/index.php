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

$objApikiBuscapeApi = new Apiki_Buscape_API( $applicationID, $sourceID );
$objApikiBuscapeApi->setSandbox();

echo $objApikiBuscapeApi->findCategoryList();
//echo $objApikiBuscapeApi->findProductList( array( 'keyword' => 'Celular,Nokia' ) );
//echo $objApikiBuscapeApi->findOfferList( array( 'productId' => 240493 ) );
//echo $objApikiBuscapeApi->topProducts();
//echo $objApikiBuscapeApi->viewUserRatings( array( 'productId' => 240493 ) );
//echo $objApikiBuscapeApi->viewProductDetails( array( 'productId' => 23348 ) );
//echo $objApikiBuscapeApi->viewSellerDetails( array( 'sellerId' => 335525 ) );
?>