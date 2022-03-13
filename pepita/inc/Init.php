<?php

/**
* @package init the config files OOP
* final classt a gyermek osztályok nem tudják felülírni! és nem is tudják kiterjeszteni, kiegészíteni!
*/

namespace Inc;

final class Init
{
	
	/**
	* tárolja azokat az osztályokat, amiket használ a plugin
	* @return összes használni kívánt class.
	*/
	
	public static function getService(){

		return [
			//Pages\Dashboard::class,
			//Base\Enqueue::class,
			//Base\SettingsLink::class,
			//Base\CustomPostTypeController::class,
			//Base\TaxonomyController::class
			//Base\CreateFeed::class,
		];
		
	}


	/**
	* Ha létezik Az admin classból objektumból az registerFunction function, akkor betölti azt!
	* 
	*/
	public static function serviceRegister(){

		foreach (self::getService() as $class) {
			$service = self::instantiate($class);
			if (method_exists($service, 'registerFunction')) {
				$service->registerFunction(); //minden osztályban, ha létezik ez a függvény, akkor meghívja!
			}
			
		}
	}

	/**
	* azok az objektum példányok, amik a get services-ben szerepelnek.
	* @return getServices-ben lévő objektumpéldányok.
	*/
	public static function instantiate($class){
		return $service = new $class();
	}
}

