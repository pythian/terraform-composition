<?php
namespace Concrete\Package\ServiceLocations;
use \Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionKey;
use \Concrete\Core\Support\Facade\Application;
use CollectionAttributeKey;
use Package;
use BlockType;
use SinglePage;
use Loader;
use Config;
use Page;
use PageTemplate;
use FileSet;
use Exception;
use AssetList;
use Asset;
use Core;
use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Package\PackageService;
use AttributeSet;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package {

	protected $pkgHandle = 'service_locations';
	protected $appVersionRequired = '5.6.0';
	protected $pkgVersion = '0.9.8.1';

	public function getPackageDescription() {
		return t("To Manage service locations");
	}

	public function getPackageName() {
		return t("Service Locations for Concrete Version 9 and PHP version 8");
	}

	public function uninstall(){

		$pkg = Package::getByHandle("service_locations");

		/*
		DELETE ALL ASSOCIATED ATTRIBUTES
		*/

		$app = Application::getFacadeApplication();
		$em = $app->make('database')->getEntityManager();
        $db = $app->make('database')->connection();


		/* $akIDs = $db->getAll('select akID from AttributeKeys where pkgID = ?',array($pkg->getPackageID()));

		foreach($akIDs as $akID){
			try{
				$at = CollectionKey::getByID($akID['akID']);
				$em->remove($at);
				$em->flush();
			}catch(\Exception $e){
				echo $e->getMessage();
			}
		} */
		$db->query("SET foreign_key_checks = 0");
		parent::uninstall();
	}
	public function upgrade() {
     	 $this->load_required_models();
	 	 parent::upgrade();
	  }

	public function install() {

		$this->load_required_models();
		$pkg = parent::install();
		$this->installXml();
		$this->install_dp_settings($pkg);
		/* $this->install_dp_singlepages($pkg);
		$this->install_dp_attributes($pkg);
		$this->install_dp_pages($pkg);
		$this->install_dp_settings($pkg); */

	}

	protected function installXml()
    {
        $pkg = Core::make(PackageService::class)->getByHandle($this->pkgHandle);
        $ci = new ContentImporter();
        $ci->importContentFile($pkg->getPackagePath() . '/install.xml');
		$ci->importContentFile($pkg->getPackagePath() . '/attributeset_update.xml');
    }

	function install_dp_settings(){
		$pkg = Package::getByHandle("service_locations");
		$as = AttributeSet::getByHandle('service_locations');
        if (isset($as) && is_object($as)) {
			$pkg->getConfig()->save('service.SERVICE_LOCATIONS_ATTRIBUTE_SET_ID',$as->getAttributeSetID());
        }


		}

	function load_required_models() {
	\Loader::model('attribute/categories/collection');
		}

	public function on_start(){
		$al = AssetList::getInstance();
		$al->register('css', 'lightbox', 'css/lightbox.css', array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), $this);
		$al->register('javascript', 'lightbox', 'js/lightbox.min.js', array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), $this);
		$al->register('css', 'taginput', 'css/bootstrap-tagsinput.css', array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), $this);
		$al->register('javascript', 'taginput', 'js/bootstrap-tagsinput.js', array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), $this);
	}



}
