<?php

namespace Inc\Base;

/**
 * XML Convert
 */
class XmlConvert extends BaseController
{
	
	
	public static function arrayToXml($datas = array(), $elementName = ''){

		$xml = new \SimpleXMLElement('<'.$elementName.'/>');

		array_walk_recursive($datas, array ($xml, 'addChild'));

		return $xml->asXML();
	}

	public static function generateXML($obj, $array ) {

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

	            self::generateXML($node, $value);
	        }

	        else{
	            
	            $obj->addChild($key, htmlspecialchars($value));
	        }
	    }
	}

	public static function getUnasOrderXml($root, $array){

		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><'.$root.' xmlns="https://pepita.hu/feed/1.0"/>');

		self::generateXML($xml, $array );

		$xml->asXML( dirname(__FILE__, 3)."/products.xml" );

		self::formatXML();

	}

	public static function formatXML(){

		$xml = file_get_contents(dirname(__FILE__, 3)."/products.xml");
		$dom = new \DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		$dom->loadXML($xml);
		$dom->formatOutput = TRUE;
		file_put_contents( dirname(__FILE__, 3) ."/products.xml", $dom->saveXML());
	}
}