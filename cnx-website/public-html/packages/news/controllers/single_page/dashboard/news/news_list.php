<?php



namespace Concrete\Package\News\Controller\SinglePage\Dashboard\News;



use \Concrete\Core\Page\Controller\DashboardPageController;

use Loader;

use Page;

use PageList;

use Core;

use PageTemplate;

use Concrete\Core\Multilingual\Page\Section\Section;





defined('C5_EXECUTE') or die(_("Access Denied."));

class NewsList extends DashboardPageController

{

	public $itemsPerPage = 1;

	public $num = 1;

	public $helpers = array('html', 'form');

	public function on_start()

	{

		$this->error = Loader::helper('validation/error');

		$this->set('pageTitle', 'News List');

		$this->requireAsset('dashboard_assets');

	}



	public function setLanguage()

	{

		$service = \Core::make('site');

		if (isset($_REQUEST['siteTreeID']) && $_REQUEST['siteTreeID'] > 0) {

			$tree = $service->getSiteTreeByID($_REQUEST['siteTreeID']);

		} else {

			$tree = $service->getActiveSiteForEditing()->getSiteTreeObject();

		}

		$provider = \Core::make('\Concrete\Core\Application\UserInterface\Sitemap\StandardSitemapProvider');

		$collection = $provider->getTreeCollection($tree);

		$collectionEntries = $collection->getEntries();

		$allowedSiteTreeIDs = array();

		if (is_array($collectionEntries) && count($collectionEntries) > 0) {

			foreach ($collectionEntries as $collectionEntry) {

				$allowedSiteTreeIDs[] = $collectionEntry->getSiteTreeID();

			}

		}

		$ml = Section::getList();

		$c = \Page::getCurrentPage();

		$this->set('languageSections', $ml);

		$this->set('allowedSiteTreeIDs', $allowedSiteTreeIDs);

		$this->set('cID', $c->getCollectionID());

	}

	public function approvethis($cIDd, $name)
    {
        $p = Page::getByID($cIDd);
        $p->getVersionObject()->approve();
        $p->activate();
        $this->set('message', t('"' . base64_decode($name) . '" has been approved and is now public'));
        $this->view();
    }

	public static function getOptions($handle)

	{



		$opts = array();



		$ak = \CollectionAttributeKey::getByHandle($handle);

		$o = $ak->getController()->getOptions();

		$opts[] = 'All';

		if (sizeof($o) > 0) {

			foreach ($o as $opt) {

				if (!empty($opt)) {

					$opts[$opt->getSelectAttributeOptionValue()] = $opt->getSelectAttributeOptionValue();

				}

			}

		}





		return $opts;

	}

	public function view()

	{

		$news_category = $this->getOptions('news_category');

        $this->set('category', $news_category);

		$this->loadNewsSections();

		$this->setLanguage();

		$pageList = new PageList();



		if (isset($_COOKIE['currentActiveLocale'])) {

			$currentActiveLocale = $_COOKIE['currentActiveLocale'];

		}

		if (isset($_GET['siteTreeID']) > 0) {

			$currentActiveLocale = $_GET['siteTreeID'];

		}

		if (isset($_GET['category']) > 0) {

			$pageList->filterByNewsCategory($_GET['category']);

		}



		$em = \ORM::entityManager();

		if (isset($currentActiveLocale) && $currentActiveLocale > 0 && in_array($currentActiveLocale, $this->get('allowedSiteTreeIDs'))) {

			$pageList->setSiteTreeObject($em->find('\Concrete\Core\Entity\Site\Tree', $currentActiveLocale));

		} else {

			if (is_array($this->get('allowedSiteTreeIDs')) && count($this->get('allowedSiteTreeIDs')) > 0) {

				$pageList->setSiteTreeObject($em->find('\Concrete\Core\Entity\Site\Tree', $this->get('allowedSiteTreeIDs')[0]));

			} else {

				$pageList->setSiteTreeToCurrent();

			}

		}



		$itemsperpage = 10;



		/*STATUS FILTER*/

		if (isset($_GET['ccm_order_dir']) == 'asc') {

			$ccm_order_dir = 'desc';

		} else {

			$ccm_order_dir = 'asc';

		}

		$this->set('ccm_order_dir', $ccm_order_dir);

		/*STATUS FILTER*/

		if (isset($_GET['ccm_order_by']) && isset($_GET['ccm_order_dir'])) {

			$pageList->sortBy('ak_' . $_GET['ccm_order_by'], $_GET['ccm_order_dir']);

		} else {

			$pageList->sortBy('cDateAdded', 'desc');

			//$pageList->sortBy('cID', 'asc');

		}

		if (isset($_GET['cParentID']) && $_GET['cParentID'] > 0) {

			$pageList->filterByParentID($_GET['cParentID']);

		} else {

			$sections = $this->get('sections');

			$keys = array_keys($sections);

			$keys[] = -1;

			$pageList->filterByParentID($keys);

		}

		if (!empty(isset($_GET['like']))) {

			$pageList->filterByName($_GET['like']);

		}

		//	print_r($pageList);

		/*	CATEGORY FILTER */

		if (isset($_GET['ccm_order_dir_cat']) == 'asc') {

			$ccm_order_dir_cat = 'desc';

			$pageList->sortBy('pp.cPath', 'asc');

			$this->set('ccm_order_dir_cat', $ccm_order_dir_cat);

		} elseif (isset($_GET['ccm_order_dir_cat']) == 'desc') {

			$ccm_order_dir_cat = 'asc';

			$pageList->sortBy('pp.cPath', 'desc');

			$this->set('ccm_order_dir_cat', $ccm_order_dir_cat);

		}



		/*	CATEGORY FILTER */

		/*	NAME FILTER*/

		if (isset($_GET['ccm_order_dir_name']) == 'asc') {

			$ccm_order_dir_name = 'desc';

			$pageList->sortBy($_GET['ccm_order_by_name'], $_GET['ccm_order_dir_name']);

			$this->set('ccm_order_dir_name', $ccm_order_dir_name);

		} elseif (isset($_GET['ccm_order_dir_name']) == 'desc') {

			$ccm_order_dir_name = 'asc';

			$pageList->sortBy($_GET['ccm_order_by_name'], $_GET['ccm_order_dir_name']);

			$this->set('ccm_order_dir_name', $ccm_order_dir_name);

		}







		$pageList->includeInactivePages(true);

		$pageList->displayUnapprovedPages(true);



		/*NAME*/

		/*DATE	FILTER*/

		if (isset($_GET['ccm_order_dir_date']) == 'asc') {

			$ccm_order_dir_date = 'desc';

			$pageList->sortBy($_GET['ccm_order_by_date'], $_GET['ccm_order_dir_date']);

			$this->set('ccm_order_dir_date', $ccm_order_dir_date);

		} elseif (isset($_GET['ccm_order_dir_date']) == 'desc') {

			$ccm_order_dir_date = 'asc';

			$pageList->sortBy($_GET['ccm_order_by_date'], $_GET['ccm_order_dir_date']);

			$this->set('ccm_order_dir_date', $ccm_order_dir_date);

		}



		/*DATE	*/

		if (isset($_GET['numResults']) > 0) {

			$pageList->setItemsPerPage($_GET['numResults']);

			$numResults = $_GET['numResults'];

		} else {

			$pageList->setItemsPerPage($itemsperpage);

			$numResults = $itemsperpage;

		}

		$totalPagesCount = count($pageList->getResults());



		if (isset($_GET['pageStatus']) == "unpublished") {

			$pageList->filter(false, "p.cIsActive != 1");

		}



		if (isset($_GET['pageStatus']) == "published") {



			$pageList->filter(false, "p.cIsActive = 1");

		}



		$pageList->sortByPublicDateDescending();



		$totalPages = $pageList->get();



		$showPagination = false;

		$pagination = $pageList->getPagination();

		$pages = $pagination->getCurrentPageResults();

		$paginationList = $pagination->renderDefaultView();



		if ($totalPagesCount > $numResults || isset($_REQUEST['ccm_paging_p'])) {

			$showPagination = true;

			$this->set('pagination', $paginationList);

		}

		if ($showPagination) {

			$this->requireAsset('css', 'core/frontend/pagination');

		}

		$this->set('pageList', $pages);

		$this->set('pageLists', $pageList);

		$this->set('showPagination', $showPagination);





		$this->set('totalPages', $totalPages);

	}





	protected function loadNewsSections()

	{



		$pageSectionList = new PageList();

		$pageSectionList->filterByNewsSection(1);

		if (isset($_COOKIE['currentActiveLocale'])) {

			$currentActiveLocale = $_COOKIE['currentActiveLocale'];

		}

		if (isset($_GET['siteTreeID']) > 0) {

			$currentActiveLocale = $_GET['siteTreeID'];

		}

		$em = \ORM::entityManager();

    if (isset($currentActiveLocale) && $currentActiveLocale > 0) {

      //echo $currentActiveLocale;die();

      $pageSectionList->setSiteTreeObject($em->find('\Concrete\Core\Entity\Site\Tree', $currentActiveLocale));

  }

 else {

			if (is_array($this->get('allowedSiteTreeIDs')) && count($this->get('allowedSiteTreeIDs')) > 0) {

				$pageSectionList->setSiteTreeObject($em->find('\Concrete\Core\Entity\Site\Tree', $this->get('allowedSiteTreeIDs')[0]));

			} else {

				$pageSectionList->setSiteTreeToCurrent();

			}

		}

		$pageSectionList->sortBy('cvName', 'asc');

		$tmpSections = $pageSectionList->get();

		$sections = array();

		foreach ($tmpSections as $_c) {

			$sections[$_c->getCollectionID()] = $_c->getCollectionName() . ' - ' . $_c->getSiteTreeObject()->getSiteHomePageObject()->getCollectionName();

		}

		$this->set('sections', $sections);

	}



	public function delete_check($cIDd, $name)

	{

		$this->set('remove_name', $name);

		$this->set('remove_cid', $cIDd);

		$this->view();

	}

	public function delete($cIDd, $name)

	{

		$c = Page::getByID($cIDd);

		$db = Loader::db();

		$c->delete();

		$this->set('message', t('"' . $name . '" has been deleted'));

		$this->set('remove_name', '');

		$this->set('remove_cid', '');

		$this->view();

	}

	public function duplicate($cIDd)

	{

		$c = Page::getByID($cIDd);

		$cpID = $c->getCollectionParentID();

		$cp = Page::getByID($cpID);

		$c->duplicate($cp);

		$this->view();

	}

	public function clear_warning()

	{

		$this->set('remove_name', '');

		$this->set('remove_cid', '');

		$this->view();

	}

	protected function validate()

	{

		$vt = Loader::helper('validation/strings');

		$vn = Loader::Helper('validation/numbers');

		$dt = Loader::helper("form/date_time");

		if (!$vn->integer($this->post('cParentID'))) {

			$this->error->add(t('You must choose a parent page for this Page entry.'));

		}

		if (!$vn->integer($this->post('ctID'))) {

			$this->error->add(t('You must choose a page type for this Page entry.'));

		}

		if (!$vt->notempty($this->post('page_title'))) {

			$this->error->add(t('Title is required'));

		}

		if (!$this->error->has()) {

			Loader::model('collection_types');

			$ct = CollectionType::getByID($this->post('ctID'));

			$parent = Page::getByID($this->post('cParentID'));

			$parentPermissions = new Permissions($parent);

			if (!$parentPermissions->canAddSubCollection($ct)) {

				$this->error->add(t('You do not have permission to add a page of that type to that area of the site.'));

			}

		}

	}

	private function saveData($p)

	{

		Loader::model("attribute/categories/collection");

		$set = AttributeSet::getByHandle('page');

		$setAttribs = $set->getAttributeKeys();

		if ($setAttribs) {

			foreach ($setAttribs as $ak) {

				$aksv = CollectionAttributeKey::getByHandle($ak->akHandle);

				$aksv->saveAttributeForm($p);

			}

		}

	}

	public function page_added()

	{

		$this->set('message', t('News added.'));

		$this->view();

	}

	public function page_updated()

	{

		$this->set('message', t('News updated.'));

		$this->view();

	}

	public function page_deleted()

	{

		$this->set('message', t('News deleted.'));

		$this->view();

	}

	public function on_before_render()

	{

		$this->set('error', $this->error);

	}





	public function changeStatus()

	{



		$pageID = $_POST['cID'];

		$status = $_POST['status'];

		$p = Page::getByID($pageID);

		if ($this->post('status') == 0) {

			$p->deactivate();

			$p->getVersionObject()->deny();

			echo "Post has been UnPublished";

			exit();

		} else {

			$p->getVersionObject()->approve();

			$p->activate();

			echo "Post has been Published";

			exit();

		}

	}

}
