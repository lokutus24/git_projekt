<?php 
/**
 * @package  AlecadddPlugin
 */
namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

use Inc\Base\WebsiteScrape;

class ManagerCallbacks extends BaseController
{

	private $categories = array();

	public function checkboxSanitize( $input ){

		// return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
		return ( isset($input) ? true : false );
	}

	public function adminSectionManager(){

		echo 'Az infó gomb alatt található oldalak közül illessz be egy linket és feltölti az oldalon lévő tartalmat!';
	}

	public function checkboxField( $args ){

		/* link letöltés */
		if ( isset($_POST['url_scrape']) ) {

			if (!isset($_POST['categories']) and !empty($this->categories)) {
				echo "Nem adtál meg kategóriát!\n";
				die();
			}

			$scraper = new WebsiteScrape($_POST['url_scrape'], $_POST['categories']);

			echo $scraper->checkUrl();
		}

		$name = $args['label_for'];
		//$classes = $args['class'];
		//$checkbox = get_option( $name );
		//echo '<input type="checkbox" name="' . $name . '" value="1" class="' . $classes . '" ' . ($checkbox ? 'checked' : '') . '>';
		echo "<div class='row' style='margin-bottom: 10px;'>
				<input class='scrapeurl' type='url' name='".$name."' style='width:600px;'>
			</div>";

	}

	public function categoriesField(){

		$this->categories = get_categories();
		foreach($this->categories as $category) {
		   $checked = '';
		   if ($category->name=='Képek') {
		   		$checked = 'checked';
		   }
		   echo '<div class="col-md-4">
		   			<input type="checkbox" name="categories[]" value="'.$category->term_id.'" '.$checked.'>'.$category->name.'
		   		</div>';
		}
	}
}