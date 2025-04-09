<?php namespace Concrete\Package\Prime;
use Route;
use Package;
use Concrete\Core\Support\Facade\Facade;
use Concrete\Package\Prime\Src\Page\Theme\GridFramework\Type\Bootstrap5 as Bootstrap5GridFramework;
defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package {
    protected $pkgHandle = 'prime';
    protected $appVersionRequired = '5.8.5';
    protected $pkgVersion = '0.0.0';
    protected $pkgDescription = "A complete Digital Solution";
    protected $pkgName = "Prime:Prime Advertising";

    public function install()
    {
        parent::install();
        $this->installContentFile('install.xml');
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile('update.xml');
    }

    public function on_start()
    {
      $app = Facade::getFacadeApplication();
      $manager = $app->make('manager/grid_framework');
      $manager->extend('bootstrap5', function ($app) {
          return new Bootstrap5GridFramework();
      });
        $this->registerRoutes();
    }
    public function registerRoutes()
       {
    Route::register('/submit/boatFilter', '\Application\Block\BoatList\Controller::boatFilter');
       }
}
