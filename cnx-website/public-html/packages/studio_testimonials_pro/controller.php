<?php
namespace Concrete\Package\StudioTestimonialsPro;
use Package;
use BlockType;
use SinglePage;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package {

	protected $pkgHandle = 'studio_testimonials_pro';
	protected $appVersionRequired = '5.9';
	protected $pkgVersion = '0.9.9.1';

	public function getPackageDescription() {
		return t("Manage and display testmonials.");
	}

	public function getPackageName() {
		return t("Studio Testimonials Pro for Concrete Version 9 and PHP version 8 ");
	}

	public function install() {
		$pkg = parent::install();

		SinglePage::add('/dashboard/studio_testimonials_pro', $pkg);

		// install block
		BlockType::installBlockTypeFromPackage('studio_testimonials_pro', $pkg);
	}
	public function on_start(){
		\Route::register('/tools/select_testimonials','\Concrete\Package\StudioTestimonialsPro\Src\Testimonial::select_testimonials');
		\Route::register('/tools/submit_testimonial','\Concrete\Package\StudioTestimonialsPro\Src\Testimonial::submit_testimonial');
	}

}
