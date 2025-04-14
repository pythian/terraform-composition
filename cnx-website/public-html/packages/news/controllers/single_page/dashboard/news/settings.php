<?php

namespace Concrete\Package\News\Controller\SinglePage\Dashboard\News;

use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use \Concrete\Core\Page\Controller\DashboardPageController;
use AttributeSet;
use Loader;
use Config;
use PageTemplate;
use PageType;

class Settings extends DashboardPageController
{

	public function view()
	{
		$category = AttributeKeyCategory::getByID(1);
		$sets = $category->getAttributeSets();
		$setsarr = array();
		foreach ($sets as $set) {
			$setsarr[$set->getAttributeSetID()] = $set->getAttributeSetName();
		}

		$this->set('attribute_sets', $setsarr);
		$ctArray = PageTemplate::getList();
		$PageTemplates = array('' => 'Select Page Template');
		foreach ($ctArray as $ct) {
			$PageTemplates[$ct->getPageTemplateID()] = $ct->getPageTemplateName();
		}
		$this->set('PageTemplates', $PageTemplates);
		$ctArray = PageType::getList();
		$PageTypes = array('' => 'Select Page Type');
		foreach ($ctArray as $ct) {
			$PageTypes[$ct->getPageTypeID()] = $ct->getPageTypeName();
		}
		$this->set('PageTypes', $PageTypes);
		$this->set('attribute_set_id', Config::get('concrete.news_attribute_set_id'));
		$this->set('page_type_id', Config::get('concrete.news_page_type_id'));
		$this->set('page_template_id', Config::get('concrete.news_page_template_id'));
	}
	public function save_settings()
	{
		if ($this->token->validate("save_settings")) {
			if ($this->isPost()) {
				if (isset($_POST['NEWS_ATTRIBUTE_SET_ID'])) {
					Config::save('concrete.news_attribute_set_id', $_POST['NEWS_ATTRIBUTE_SET_ID']);
				}
				if (isset($_POST['NEWS_PAGE_TYPE_ID'])) {
					Config::save('concrete.news_page_type_id', $_POST['NEWS_PAGE_TYPE_ID']);
				}
				if (isset($_POST['NEWS_PAGE_TEMPLATE_ID'])) {
					Config::save('concrete.news_page_template_id', $_POST['NEWS_PAGE_TEMPLATE_ID']);
				}
				$this->set('message', t('Settings has been saved.'));
				$this->view();
			}
		} else {
			$this->set('error', array($this->token->getErrorMessage()));
		}
	}
}
