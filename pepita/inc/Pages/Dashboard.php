<?php

/**
 * @package Admin menü class
 */

namespace Inc\Pages;

use \Inc\Base\BaseController;
use \Inc\Api\SettingsApi;
use \Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ManagerCallbacks;

class Dashboard extends BaseController
{
	
	public $SettingsApiClass = array();

	public $adminMenuPages = array();

	public $callBacks = array();

	public $managerCallbacks = array();

	public $setSettings = array();

	public $setSections = array();

	public $setFields = array();


	public function registerFunction(){

		$this->callBacks = new AdminCallbacks();

		$this->SettingsApiClass = new SettingsApi();

		$this->managerCallbacks = new ManagerCallbacks();

		$this->setAdminPages();

		//menühoz tartozó beállítások, szekciók, mezők
		$this->setSettings()->setSections()->setFields();
		//add_action('admin_menu', array($this, 'createMenu'));
		$this->SettingsApiClass->addPages($this->adminMenuPages)->setSubPagesTitle('Dashboard')->addSettings($this->setSettings)->addSections($this->setSections)->addFields($this->setFields)->register();
	}


	public function setAdminPages(){

		$this->adminMenuPages = array(

			[
				'page_title' => "Pepita Feed Creator",
				'menu_title' => "Pepita Feed Creator",
				'capability' => "manage_options",
				'menu_slug' => "pepita_plugin",
				'callback' => array($this->callBacks, 'adminDashboard'),
				'icon_url' => 'dashicons-cart',
				'position' => 110
			]
		);
	}

	public function setSettings(){
		
		//foreach ( $this->managers as $key => $value ) {
			$this->setSettings[] = array(
				'option_group' => 'pepita_plugin_settings',
				'option_name' => 'pepita_feed',
				'callback' => array( $this->managerCallbacks, 'checkboxSanitize' )
			);
		//}

		return $this;
	}

	public function setSections(){
		
		$this->setSections = array(

			[
				'id' => 'pepita_admin', //akarmi
				'title' => "Link hozzáadás",
				'callback' => array($this->managerCallbacks, 'adminSectionManager' ),
				'page' => "pepita_plugin"
			]
		);

		return $this;
	}

	public function setFields(){
		
		$this->setFields = [
			array(
				'id' => 'pepita_feed',
				'title' => 'Link',
				'callback' => array( $this->managerCallbacks, 'checkboxField' ),
				'page' => 'pepita_plugin',
				'section' => 'pepita_admin',
				'args' => array(
					'label_for' => 'pepita_feed',
					'class' => 'ui-toggle'
				)
			),
			array(
				'id' => 'teszt',
				'title' => 'Kategóriák',
				'callback' => array( $this->managerCallbacks, 'categoriesField' ),
				'page' => 'pepita_plugin',
				'section' => 'pepita_admin',
				'args' => array(
					'label_for' => 'category',
					'class' => 'ui-toggle'
				)
			)
		];

		return $this;
	}

}