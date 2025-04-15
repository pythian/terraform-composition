<?php



namespace Concrete\Package\News\Controller\SinglePage\Dashboard\News;



use AttributeSet;

use CollectionAttributeKey;

use Config;

use Loader;

use Page;

use PageList;

use PageTemplate;

use PageType;

use Permissions;

use \Concrete\Core\Multilingual\Page\Section\Section;

use \Concrete\Core\Page\Controller\DashboardPageController;



defined('C5_EXECUTE') or die(_("Access Denied."));

class AddEdit extends DashboardPageController

{

    public $num = 15;

    public $helpers = array('html', 'form');

    public function on_start()

    {

        //Loader::model('page_list');

        $this->error = Loader::helper('validation/error');

        $this->set('pageTitle', 'Add a News');

        $this->requireAsset('dashboard_assets');

    }

    public function view()

    {

        $this->setupForm();

        $pageList = new PageList();

        if (isset($_GET['ccm_order_by']) && isset($_GET['ccm_order_dir'])) {

            $pageList->sortBy('ak_' . $_GET['ccm_order_by'], $_GET['ccm_order_dir']);

        } else {

            $pageList->sortBy('cDateAdded', 'desc');

        }

        if (isset($_GET['cParentID']) && $_GET['cParentID'] > 0) {

            $pageList->filterByParentID($_GET['cParentID']);

        } else {

            $sections = $this->get('sections');

            $keys = array_keys($sections);

            $keys[] = -1;

            $pageList->filterByParentID($keys);

        }

        $this->set('pageList', $pageList);

        $this->set('pageResults', $pageList->getResults());

    }



    public function getLanguageSections()

    {

        //$ml = Section::getList();

        //$this->set('languageSections', $ml);



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

        if (sizeof($collectionEntries) > 0) {

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



    protected function loadNewsSections()

    {

        $pageSectionList = new PageList();

        $pageSectionList->filterByNewsSection(1);

        $pageSectionList->setSiteTreeToAll();

        $pageSectionList->sortBy('cvName', 'asc');

        $tmpSections = $pageSectionList->get();

        $sections = array();

        foreach ($tmpSections as $_c) {

            if (in_array($_c->getSiteTreeObject()->getSiteTreeID(), $this->get('allowedSiteTreeIDs'))) {

                $n = $_c->getCollectionName() . ' - ' . $_c->getSiteTreeObject()->getSiteHomePageObject()->getCollectionName();

                if ($_c->getCollectionName() . ' - ' . $_c->getSiteTreeObject()->getSiteHomePageObject()->getCollectionName() == 'News - Home') {



                    $n = 'News - English';

                }



                $sections[$_c->getCollectionID()] =  $n;

            }

        }

        $this->set('sections', $sections);

    }



    public function edit($cID)

    {



        $this->set('pageTitle', 'Edit News');

        $this->setupForm();

        $page = Page::getByID($cID);



        $sections = $this->get('sections');

        if (in_array($page->getCollectionParentID(), array_keys($sections))) {

            $this->set('page', $page);

            setcookie('currentActiveLocale', $page->getSiteTreeID(), time() + (86400 * 30), "/"); // 86400 = 1 day

        } else {

            $this->redirect('/dashboard/news/');

        }

    }

    public function delete($cID)

    {

        $this->setupForm();

        $page = Page::getByID($cID);

        $sections = $this->get('sections');

        if (in_array($page->getCollectionParentID(), array_keys($sections))) {

            $this->set('page', $page);

        } else {

            $this->redirect('/dashboard/news/news_list');

        }

    }

    protected function setupForm()

    {

        $this->getLanguageSections();

        $this->loadNewsSections();

        $ctArray = PageTemplate::getList();

        $PageTemplates = array();

        foreach ($ctArray as $ct) {

            if ($ct->getPageTemplateName() != 'Home') {

                $PageTemplates[$ct->getPageTemplateID()] = $ct->getPageTemplateName();

            }

        }

        $this->set('PageTemplates', $PageTemplates);

    }



    public function add()

    {



        $this->setupForm();

        if ($this->isPost()) {

            // echo "<pre>";print_r($this->post());die();

            $this->validate(true);

            if (!$this->error->has()) {

                $cParentIDsArray = $this->post('cParentIDs');



                $parent = Page::getByID($cParentIDsArray[0]);

                //echo "<pre>";print_r($cParentIDsArray[0]);die();

                $ct = PageType::getByID($this->post('ctID'));

                $pt = PageTemplate::getByID($this->post('ptID'));

                $data = array('uID' => $this->post('uID'),'cDescription' => $this->post('cDescription'), 'cName' => $this->post('page_title'), 'cDatePublic' => Loader::helper('form/date_time')->translate('page_date_time'));

                $p = $parent->add($ct, $data, $pt);
                if ($this->post('status') == 0) {
                    $p->deactivate();
                    $p->getVersionObject()->deny();
                }
                $this->saveData($p);





                $this->redirect('/dashboard/news/news_list', 'page_added');

            }

        }

    }



    public function update()

    {

        $this->edit($this->post('pageID'));

        if ($this->isPost()) {

            // echo "<pre>";print_r($this->post());die();

            $this->validate();

            if (!$this->error->has()) {

                $p = Page::getByID($this->post('pageID'));

                $parent = Page::getByID($this->post('cParentID'));

                $pt = PageTemplate::getByID($this->post('ptID'));

                $data = array('uID' => $this->post('uID'),'cDescription' => $this->post('cDescription'), 'pTemplateID' => $this->post('ptID'), 'cName' => $this->post('page_title'), 'cDatePublic' => Loader::helper('form/date_time')->translate('page_date_time'));

                $p->update($data);

                if ($this->post('status') == 0) {
                    $p->deactivate();
                    $p->getVersionObject()->deny();
                } else {
                    $p->getVersionObject()->approve();
                    $p->activate();
                }
                if ($p->getCollectionParentID() != $parent->getCollectionID()) {
                    $p->move($parent);
                }

                $this->saveData($p);

                $this->redirect('/dashboard/news/news_list', 'page_updated');

            }

        }

    }



    protected function validate($add = false)

    {

        $vt = Loader::helper('validation/strings');

        $vn = Loader::Helper('validation/numbers');

        $dt = Loader::helper("form/date_time");

        if ($add) {





            if (!$this->post('cParentIDs')) {

                //$this->error->add(t('You must choose a parent page for this Page entry.'));

            }

        } else {

            if (!$vn->integer($this->post('cParentID'))) {

                // $this->error->add(t('You must choose a parent page for this Page entry.'));

            }

        }

        if (!$vt->notempty($this->post('page_title'))) {

            $this->error->add(t('Title is required'));

        }

        if (empty($this->post('slug'))) {

            //$this->error->add(t('Slug is required.'));

        }





        if (!$vn->integer($this->post('ctID'))) {

            $this->error->add(t('You must choose a page Template from settings for this Page entry.'));

        }

        if (!$vn->integer($this->post('ptID'))) {

            $this->error->add(t('You must choose a page type   from settings for this Page entry.'));

        }







        if (!$this->error->has()) {



            $ct = PageType::getByID($this->post('ctID'));

            if ($add) {

                if (sizeof($this->post('cParentIDs')) < 1) {

                    foreach ($this->post('cParentIDs') as $cParentID) {

                        $parent = Page::getByID($cParentID);

                        $parentPermissions = new Permissions($parent);

                        if (!$parentPermissions->canAddSubCollection($ct)) {

                            $this->error->add(t('You do not have permission to add a page of that type to that area of the site.'));

                            break;

                        }

                    }

                }

            } else {

                $parent = Page::getByID($this->post('cParentID'));

                $parentPermissions = new Permissions($parent);

                if (!$parentPermissions->canAddSubCollection($ct)) {

                    $this->error->add(t('You do not have permission to add a page of that type to that area of the site.'));

                }

            }

        }

    }



    private function saveData($p)

    {

        $ca = CollectionAttributeKey::getByHandle('news_author');

        $p->setAttribute('exclude_nav', 1);

        $attributeset_id = Config::get('concrete.news_attribute_set_id');

        $set = AttributeSet::getByID($attributeset_id);

        if (is_object($set)) {

            $setAttribs = $set->getAttributeKeys();

            if ($setAttribs) {

                foreach ($setAttribs as $ak) {

                    $aksv = CollectionAttributeKey::getByHandle($ak->getAttributeKeyHandle());

                    $controller = $aksv->getController();

                    $value = $controller->createAttributeValueFromRequest();

                    $p->setAttribute($aksv, $value);

                }

            }

        }



        $cck = CollectionAttributeKey::getByHandle('meta_title');

        $controller = $cck->getController();

        $value = $controller->createAttributeValueFromRequest();

        $p->setAttribute($cck, $value);



        $cck = CollectionAttributeKey::getByHandle('meta_description');

        $controller = $cck->getController();

        $value = $controller->createAttributeValueFromRequest();

        $p->setAttribute($cck, $value);



        $cck = CollectionAttributeKey::getByHandle('meta_keywords');

        $controller = $cck->getController();

        $value = $controller->createAttributeValueFromRequest();

        $p->setAttribute($cck, $value);









        $p->reindex();

    }

    public function on_before_render()

    {

        $this->set('error', $this->error);

    }

}
