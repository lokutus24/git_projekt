<?php
	
 /**
  * include WP requirements
  */

if ( ! defined('ABSPATH') ) {
  
  require_once( dirname( __FILE__ ) . '/../../../wp-load.php' );
  //require_once( dirname( __FILE__ ) . '/../../../wp-includes/query.php' );


  /*
  use Inc\Base\CreateFeed;

  if (class_exists('Inc\\Base\\CreateFeed')) {
  	
    $feed = new CreateFeed();
    $feed->getProducts();

  }
  */

}


function getProducts(){

      $productDatas = array();

      $args = array(
          'post_type'      => 'product',
          'posts_per_page' => 999999999999
      );

      $loop = new \WP_Query( $args );

      while ( $loop->have_posts() ) : $loop->the_post();

          global $product;

          $categories = get_the_terms( $product->get_id(), 'product_cat' );
          $stock_status = $product->get_stock_status();

          if ( $stock_status == 'onbackorder' or $stock_status == 'in_stock') {

              $data = array(

                  'Id' => $product->get_sku(),
                  'Descriptions' => [
                      'Name' => $product->get_name(),
                      'Description' => $product->get_description()
                  ],
                  'Prices' => [
                      'Currency' => 'HUF',
                      'Price' => (int)$product->get_regular_price()*1.12,
                  ],
                  'ProductUrl' => $product->get_permalink(),
                  'Categories' => getPepitaCategoriePair($categories),
                  'Availability' => [
                      'Available' => ( $product->get_stock_quantity() ) ? 'true' : 'false',
                      'Quantity' => $product->get_stock_quantity()
                  ]
              );

              
              $ShippingPrice = 990;
              $DiscountedPrice = (int)$product->get_sale_price()*1.12;


              $eanCode = getEanCode($product->get_attributes());
              if ( !empty($eanCode) ) {
                  $data = array_merge($data, ['StructuredId' => $eanCode ]);
              }

              
              if ($ShippingPrice!=0) {
                  $data['Prices'] = array_merge($data['Prices'], ['ShippingPrice' => $ShippingPrice]);
              }


              if ($DiscountedPrice!=0) {
                  $data['Prices'] = array_merge($data['Prices'], ['DiscountedPrice' => $DiscountedPrice]);
              }
              
              $productDatas[] = array_merge( $data,  getProductImage($product->get_id()) );

          }

      endwhile;

      wp_reset_query();

      $orerXml = getUnasOrderXml('Catalog', $productDatas);
          
  }


  function generateXML($obj, $array ) {

    foreach ($array as $key => $value){

          if(is_numeric($key))
            

            if (key($value) == 'Id' and isset($array[$key]['Name'])){

              $key = 'Category';

            }elseif(key($value) == 'Url'){

              $key = 'Photo';

            }else{

              $key = 'Product';
            }

          if (is_array($value)){

              $node = $obj->addChild($key);

              generateXML($node, $value);
          }

          else{
              
              $obj->addChild($key, htmlspecialchars($value));
          }
      }
  }

  function getUnasOrderXml($root, $array){

    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><'.$root.' xmlns="https://pepita.hu/feed/1.0"/>');

    generateXML($xml, $array );

    $xml->asXML( dirname(__FILE__)."/products.xml" );

    formatXML();

  }

  function formatXML(){

    $xml = file_get_contents(dirname(__FILE__)."/products.xml");
    $dom = new \DOMDocument;
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($xml);
    $dom->formatOutput = TRUE;
    file_put_contents( dirname(__FILE__) ."/products.xml", $dom->saveXML());
  }


  function getEanCode($attributes){

          $eanCode = '';
          foreach ($attributes as $attribute) {

             $datas = $attribute->get_data();
             if ( $datas['name'] == 'pa_vonalkod') {
                 $eanCode = $datas['options'][0];
                 break;
             }
          }

          return $eanCode;
      }

  function getProductImage($post_id){

      $product_meta = get_post_meta($post_id);

      return setImages( wp_get_attachment_url( $product_meta['_thumbnail_id'][0], 'full' ) );
  }

  function setImages($images){


      //for ($i=0; $i<count($img) ; $i++) { 

          $photoUrl['Photos'][] = [
              //'Photo' => [
                  'Url' => $images,
                  'IsPrimary' => 'true'
              //]
          ];
      //}

      return $photoUrl;
  }

  function getPepitaCategoriePair($datas){

      if (!empty($datas)) {
          
          for ($i=0; $i <count($datas) ; $i++) { 
          
              $categories[] = [

                  'Id' => $datas[$i]->term_id,
                  'Name' => $datas[$i]->name
              ];
          }

      }
      //print_r($categories);

      return $categories;
  }


  getProducts();