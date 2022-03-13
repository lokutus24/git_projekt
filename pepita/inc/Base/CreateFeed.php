<?php

/**
 * BaseController
 */

namespace Inc\Base;

use Inc\Base\XmlConvert;

/**
 *  Visszaadja a termékeket
 */
class CreateFeed extends BaseController
{

    public function process(){

        $this->getProducts();
    }

    public function getProducts(){

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
                    'Categories' => $this->getPepitaCategoriePair($categories),
                    'Availability' => [
                        'Available' => ( $product->get_stock_quantity() ) ? 'true' : 'false',
                        'Quantity' => $product->get_stock_quantity()
                    ]
                );

                
                $ShippingPrice = 990;
                $DiscountedPrice = (int)$product->get_sale_price()*1.12;


                $eanCode = $this->getEanCode($product->get_attributes());
                if ( !empty($eanCode) ) {
                    $data = array_merge($data, ['StructuredId' => $eanCode ]);
                }

                
                if ($ShippingPrice!=0) {
                    $data['Prices'] = array_merge($data['Prices'], ['ShippingPrice' => $ShippingPrice]);
                }


                if ($DiscountedPrice!=0) {
                    $data['Prices'] = array_merge($data['Prices'], ['DiscountedPrice' => $DiscountedPrice]);
                }
                
                $productDatas[] = array_merge( $data,  $this->getProductImage($product->get_id()) );

            }

        endwhile;

        wp_reset_query();

        $orerXml = XmlConvert::getUnasOrderXml('Catalog', $productDatas);
        
    }

    public function test(){

        echo "szia";

        $query = new \WC_Product_Query(array(
            'limit' => 10,
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        $products = $query->get_products();

        if (!empty($products)) {
            foreach ($products as $product) {

                echo get_permalink($product->get_id());
            }
        }

        var_dump($products);
    }

    private function getPepitaCategoriePair($datas){

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

    private function getDeliveryCost($product){

        $shipping_methods = WC()->shipping->get_shipping_methods();

    }

    private function getEanCode($attributes){

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

    private function getProductImage($post_id){

        $product_meta = get_post_meta($post_id);

        return $this->setImages( wp_get_attachment_url( $product_meta['_thumbnail_id'][0], 'full' ) );
    }

    private function setImages($images){


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
}