<?php

namespace Concrete\Package\News;

use Asset;
use AssetList;
use CollectionAttributeKey;
use Config;
use Package;
use Page;
use SinglePage;
use PageType;
use PageTemplate;
use Loader;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use \Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Attribute\Select\Option as SelectAttributeTypeOption;


defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{

    protected $pkgHandle = 'news';
    protected $appVersionRequired = '5.9';
    protected $pkgVersion = '0.0.3';

    public function getPackageDescription()
    {
        return t("To manage news (5.9)");
    }
    public function getPackageName()
    {
        return t("News");
    }
    public function uninstall()
    {
        parent::uninstall();
        Config::save('concrete.news_page_type_id', null);
        Config::save('concrete.news_page_template_id', null);
        Config::save('concrete.news_attribute_set_id', null);
    }
    public function install()
    {
        $pkg = parent::install();
        $this->install_dp_singlepages($pkg);
        $this->install_dp_attributes($pkg);
        $this->createParentPage();
        $this->addPageType($pkg);
        $this->addPageTemplate($pkg);
    }

    public function upgrade()
    {
        $pkg = parent::upgrade();
        $this->upgradeEvents($pkg);
    }

    public function install_dp_singlepages($pkg)
    {
        if (Page::getByPath("/dashboard/news")) {
            SinglePage::add("/dashboard/news", $pkg);
        }
        if (Page::getByPath("/dashboard/news/news_list")) {
            SinglePage::add("/dashboard/news/news_list", $pkg);
        }
        if (Page::getByPath("/dashboard/news/add_edit")) {
            SinglePage::add("/dashboard/news/add_edit", $pkg);
        }
        if (Page::getByPath("/dashboard/news/settings")) {
            SinglePage::add("/dashboard/news/settings", $pkg);
        }
    }
    public function install_dp_attributes($pkg)
    {
        $checkn = AttributeType::getByHandle('boolean');
        $eaku = AttributeKeyCategory::getByHandle('collection');
        $eaku->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_SINGLE);
        $pkgset = $eaku->addSet('news_attrs', t('News'), $pkg);
        Config::save('concrete.news_attribute_set_id', $pkgset->getAttributeSetID());
        $image_file = AttributeType::getByHandle('image_file');
        $textarea = AttributeType::getByHandle('textarea');
        $select = AttributeType::getByHandle('select');

        $property_thumbnail = CollectionAttributeKey::getByHandle('news_thumbnail');
        if (!is_object($property_thumbnail)) {
            $property_thumbnail = CollectionAttributeKey::add(
                $image_file,
                array('akHandle' => 'news_thumbnail', 'akName' => 'Thumbnail'),
                $pkg
            )->setAttributeSet($pkgset);
        }
        $news_content = CollectionAttributeKey::getByHandle('news_description');
        if (!is_object($news_content)) {
            $news_content = CollectionAttributeKey::add(
                $textarea,
                array(
                    'akHandle' => 'news_description',
                    'akName' => 'Description',
                    'akIsSearchableIndexed' => '1', 'akIsSearchable' => '1', 'akTextareaDisplayMode' => 'rich_text'
                ),
                $pkg
            )->setAttributeSet($pkgset);
        }
        $at = CollectionAttributeKey::getByHandle('news_category');
        if (!is_object($at)) {
            CollectionAttributeKey::add(
                $select,
                array(
                    'akHandle' => 'news_category',
                    'akName' => t('Category'),
                    'akIsSearchable' => 1,
                    'akIsSearchableIndexed' => 1,
                    'akIsRequired' => 1,
                    'akSelectAllowMultipleValues' => false,
                    'akSelectAllowOtherValues' => false,
                ),
                $pkg
            )->setAttributeSet($pkgset);
        }

        $news_management_section = CollectionAttributeKey::getByHandle('news_section');
        if (!is_object($news_management_section)) {
            CollectionAttributeKey::add(
                $checkn,
                array(
                    'akHandle' => 'news_section',
                    'akName' => t('News Section'),
                    'akIsSearchable' => 1,
                    'akIsSearchableIndexed' => 1,
                ),
                $pkg
            );
        }
    }
    public function addPageTemplate($pkg)
    {


        $pTemplateHandle = 'news_details';
        $pTemplateName = 'News Details';
        $pTemplateIcon = 'two_column.png';


        $pTemp = PageTemplate::getByHandle('news_details');
        if (!is_object($pTemp)) {

            PageTemplate::add($pTemplateHandle, $pTemplateName, $pTemplateIcon, $pkg);

        }
    }

    public function addPageType($pkg)
    {
        $data = array(
            'handle' => 'news',
            'name' => 'News',

        );
        $pType = PageType::getByHandle('news');
        if (!is_object($pType)) {
            PageType::add($data, $pkg);
        }
    }
    public function createParentPage()
    {
        //ADD parent page and set section
        $pageObj = Page::getByPath('/news');
        if (!is_object($pageObj) || $pageObj->cID == null) {

            $pType = PageType::getByHandle('page');
            $pTemp = PageTemplate::getByHandle('full');
            $data = array('cName' => 'News', 'cDatePublic' => Loader::helper('form/date_time')->translate('page_date_time'));
            $parent = Page::getByID(1);
            $p = $parent->add($pType, $data, $pTemp);
            $p->setAttribute('news_section', 1);
        }
    }

    public function defaultSettings($pkg)
    {



        $pType = PageType::getByHandle('news');
        $pTemp = PageTemplate::getByHandle('news_details');
        $atType = CollectionAttributeKey::getByHandle('news_attrs');

        if (is_object($pType) && empty(Config::get('concrete.news_page_type_id'))) {

            Config::save('concrete.news_page_type_id', $pType->getPageTypeID());
        }

        if (is_object($pTemp) && empty(Config::get('concrete.news_page_template_id'))) {

            Config::save('concrete.news_page_template_id', $pTemp->getPageTemplateID());
        }
        if (is_object($atType)) {

            Config::save('concrete.news_attribute_set_id', $atType->getAttributeKeyID());
        }


        //SET ATTRIBUTE VALUES
        $pageObj = Page::getByPath('/news');
        if (is_object($pageObj) || $pageObj->cID != null) {
            $pageObj->setAttribute('news_section', 1);
        }
    }

    public function addOptionValues()
    {
        $bop = CollectionAttributeKey::getByHandle('news_category');
        if (is_object($bop)) {
            $status = $this->checkOptions($bop);
            if(!$status){
            SelectAttributeTypeOption::add($bop, t('Media Releases'));
            SelectAttributeTypeOption::add($bop, t('Community Patnerships'));
            SelectAttributeTypeOption::add($bop, t('Events'));
            }
        }

    }
    public function upgradeEvents($pkg)
    {

        $this->defaultSettings($pkg);
        //$this->addOptionValues();
    }
    public function on_start()
    {
    }
}
