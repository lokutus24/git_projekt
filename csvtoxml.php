<?php

if (file_exists(dirname(__FILE__)."/vendor/autoload.php")) {

	/**
	* @return PSR-4 PHP
	*/
	 require_once dirname(__FILE__)."/vendor/autoload.php";
}

use GreenCape\Xml\Converter;


function convertXml($data){

	$xml = new Converter($data);

	return $xml;

}

function setPageContent($token, $xml){

       $headers = array();
       $headers[] = "Authorization: Bearer ".$token;
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_HEADER, false);
       curl_setopt($curl, CURLOPT_POST, TRUE);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

       $request = (string)$xml;  

       //echo $request;
       //die();

       curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($curl, CURLOPT_VERBOSE, true);
       curl_setopt($curl, CURLOPT_URL, "https://api.unas.eu/shop/setPageContent");
       curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
       $response = curl_exec($curl);

       //Storage::put('butor_blog.txt', print_r($response, true));

       //$xml = simplexml_load_string($response);

       //dd($xml);

       return $response;
}


function loginWithUnasApiKey($apiKey){

   /**
   * login with CURL
   * @return $token
   */
   $curl = curl_init();
   curl_setopt($curl, CURLOPT_HEADER, false);
   curl_setopt($curl, CURLOPT_POST, TRUE);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

   $request = '<?xml version="1.0" encoding="UTF-8" ?>
            <Params>
               <ApiKey>'.$apiKey.'</ApiKey>
            </Params>';
            
   curl_setopt($curl, CURLOPT_URL, "https://api.unas.eu/shop/login");
   curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
   $response = curl_exec($curl);
   $xml = simplexml_load_string($response);
   $token = ( (string)$xml->Token!==null ) ? (string)$xml->Token : '';
   
   return $token;
}


function getAllImageFromText($txt, $blodid){

	preg_match_all('/https:\/\/(.*jpg)/', $txt, $images);

	for ($i=0; $i <count($images[0]) ; $i++) { 
		
		$tempImg = '';
		$imgExp = explode('/', $images[0][$i]);
		$imgName = $imgExp[count($imgExp)-1];

		$newImg = "https://shop.unas.hu/shop_ordered/{$blodid}/pic/Bloghoz_kepek/{$imgName}";

		$txt = str_replace($images[0][$i], $newImg, $txt);
	}

	$txt = str_replace('https://www.geminiduo.hu/', 'https://homelux.hu/', $txt);

	return $txt;
}



$apiKey = "bae777b0e1754a34976156b4611445c1e7c9519d";
$blodid = "46113"; 
/*                       
$data = [];
$fileLoc = __DIR__."/files/convertposts.csv";
$separator = ',';
if (($feedData = fopen($fileLoc, "r") )!==FALSE) {

	$i = 0;
	while ( ($datas = fgetcsv($feedData, 0, $separator) )!==FALSE) {

		//print_r($datas);
		
		if ($i!=0) {

			//if ($datas[1] =='Gyermek érkezik - így alakítsd át a fürdőt aquaparkká') {
				
				$datas[2] = getAllImageFromText($datas[2], $blodid);

				$lead = substr($datas[2], 0, 1000);

				$sefEx = explode('/', $datas[6]);

				$imageName = explode('/', $datas[7]);

				$img = explode('.', $imageName[count($imageName)-1]);

				$data["PageContents"][] = [

					"PageContent" => [
						"Title" => "{$datas[1]}",
						"Type" => "blog",
						"Lang" => "hu",
						/*"Author" => [
							"Name" => "<![CDATA[]]>"
						],
						"Published" => "yes",
						"Explicit" => "no",
						"CommentAllowed" => "no",
						"SefUrl" => "{$datas[22]}",
						/*"Image" => [
							"Lead" => "https://shop.unas.hu/shop_ordered/{$blodid}/pic/Bloghoz_kepek/{$imageName[count($imageName)-1]}"
						],
						
						"Meta" => [
							//"Keywords" => "<![CDATA[]]>",
							"Description" => "{$datas[3]}",
							"Title" => "{$datas[1]}",
							//"Robots" => "<![CDATA[]]>",
						],
						"AutomaticMeta" => [
							"Keywords" => "{$datas[11]}, [page]"
						],
						"Dates" => [
							"Publication" => "".str_replace("-", ".", $datas[30])." 08:00"
						],
						"BlogContent" => [
							"Lead" => "<!HTML KOD>{$lead}",
							"Text" => "<!HTML KOD>{$datas[2]}"
						]
					],
				];


			//}

		}
		
		$i++;

	}//end while

}//end if


$xml = convertXml($data);
$token = loginWithUnasApiKey($apiKey);

$result = setPageContent($token, $xml);

echo $result;
*/

$xml = file_get_contents(__DIR__."/files/butor_blog.xml");

$token = loginWithUnasApiKey($apiKey);

$result = setPageContent($token, $xml);

echo $result;
