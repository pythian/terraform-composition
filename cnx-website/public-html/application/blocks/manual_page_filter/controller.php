<?php namespace Application\Block\ManualPageFilter;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Page\Page;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{

    protected $btTable = 'btManualPageFilter';
    protected $btExportTables = ['btManualPageFilter', 'btManualPageFilterEntries'];
    protected $btExportPageColumns = ['select_page'];
    protected $btInterfaceWidth = '1000';
    protected $btInterfaceHeight = '650';
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = 0;

    protected $btDefaultSet = ''; // basic, navigation, form, express, social, multimedia

    protected $title;

    private $uniqueID;

    public function getBlockTypeName() {
        return t('Manual Page Filter');
    }

    public function getBlockTypeDescription() {
        return t('');
    }

    public function getSearchableContent() {

        $content = [];
        $content[] = $this->title;

        return implode(' ', $content);

    }

    public function on_start() {

        // Unique identifier
        $this->uniqueID = $this->app->make('helper/validation/identifier')->getString(18);
        $this->set('uniqueID', $this->uniqueID);

    }

    public function add() {

        $this->addEdit();
        $this->set('entries', []);

    }

    public function edit() {

        $this->addEdit();

        // Get entries
        $entries = $this->getEntries('edit');
        $this->set('entries', $entries);

    }

    public function addEdit() {

        $this->set('title', $this->title);

        // Load assets for repeatable entries
        $this->requireAsset('core/sitemap');

        // Get entry column names
        $entryColumnNames = $this->getEntryColumnNames();

        $this->set('entryColumnNames', $entryColumnNames);

        // Load form.css
        $al = AssetList::getInstance();
        $al->register('css', 'manual-page-filter/form', 'blocks/manual_page_filter/css_files/form.css', [], false);
        $this->requireAsset('css', 'manual-page-filter/form');

        // Make $app available in view
        $this->set('app', $this->app);

    }

    public function view() {

        // Make $app available in view
        $this->set('app', $this->app);

        // Get entries
        $entries = $this->getEntries();
        $entries = $this->prepareEntriesForView($entries);
        $this->set('entries', $entries);

    }

    public function save($args) {

        // Basic fields
        $args['title'] = !empty($args['title']) ? trim($args['title']) : '';

        parent::save($args);

        $db = $this->app->make('database')->connection();

        // Delete existing entries of current block's version
        $db->delete('btManualPageFilterEntries', ['bID' => $this->bID]);

        if (isset($args['entry']) AND is_array($args['entry']) AND count($args['entry'])) {

            $i = 1;

            foreach ($args['entry'] as $entry) {

                // Prepare data for insert
                $data = [];
                $data['position']               = $i;
                $data['bID']                    = $this->bID;
                $data['select_page']            = intval($entry['select_page']);
                $data['select_page_ending']     = trim($entry['select_page_ending']);
                $data['select_page_text']       = trim($entry['select_page_text']);
                $data['select_page_title']      = trim($entry['select_page_title']);
                $data['select_page_new_window'] = intval($entry['select_page_new_window']);

                $db->insert('btManualPageFilterEntries', $data);

                $i++;

            }

        }

    }

    public function duplicate($newBlockID) {

        parent::duplicate($newBlockID);

        $db = $this->app->make('database')->connection();

        // Get latest entry...
        $sql = '
            SELECT
                btManualPageFilterEntries.*
            FROM
                btManualPageFilterEntries
            WHERE
                btManualPageFilterEntries.bID = :bID
        ';
        $parameters = [];
        $parameters['bID'] = $this->bID;

        $entries = $db->fetchAll($sql, $parameters);

        // ... and copy it
        if (is_array($entries) AND count($entries)) {
            foreach ($entries as $entry) {
                $data = [];
                foreach ($entry as $columnName => $value) {
                    $data[$columnName] = $value;
                }
                unset($data['id']);
                $data['bID'] = $newBlockID;
                $db->insert('btManualPageFilterEntries', $data);
            }
        }

    }

    public function delete() {

    }

    public function validate($args) {

        $error = $this->app->make('helper/validation/error');

        return $error;

    }

    public function composer() {

        $al = AssetList::getInstance();
        $al->register('javascript', 'manual-page-filter/auto-js', 'blocks/manual_page_filter/auto.js', [], false);
        $this->requireAsset('javascript', 'manual-page-filter/auto-js');

        $this->edit();

    }

    public function scrapbook() {

        $this->edit();

    }

    private function getEntries($outputMethod = 'view') {

        $db = $this->app->make('database')->connection();

        $sql = '
            SELECT
                btManualPageFilterEntries.*
            FROM
                btManualPageFilterEntries
            WHERE
                btManualPageFilterEntries.bID = :bID
            ORDER BY
                btManualPageFilterEntries.position ASC
        ';
        $parameters = [];
        $parameters['bID'] = $this->bID;

        $entries = $db->fetchAll($sql, $parameters);

        $modifiedEntries = [];

        foreach ($entries as $entry) {


            $modifiedEntries[] = $entry;

        }

        return $modifiedEntries;

    }

     private function getEntryColumnNames() {

        $db = $this->app->make('database')->connection();

        $columns = $db->getSchemaManager()->listTableColumns('btManualPageFilterEntries');

        $columnNames = [];

        foreach($columns as $column) {
            $columnNames[] = $column->getName();
        }

        $key1 = array_search('id', $columnNames);
        unset($columnNames[$key1]);
        $key2 = array_search('bID', $columnNames);
        unset($columnNames[$key2]);
        $key3 = array_search('position', $columnNames);
        unset($columnNames[$key3]);

        return $columnNames;

    }

    private function prepareForViewLinkFromSitemap($type, $fields) {

        $keys = array_keys($fields);
        $pageIDFieldName    = $keys[0];
        $endingFieldName    = $keys[1];
        $textFieldName      = $keys[2];
        $titleFieldName     = $keys[3];
        $newWindowFieldName = $keys[4];

        $pageID    = $fields[$pageIDFieldName];
        $ending    = $fields[$endingFieldName];
        $text      = $fields[$textFieldName];
        $title     = $fields[$titleFieldName];
        $newWindow = !empty($fields[$newWindowFieldName]) ? 'target="_blank" rel="noopener"' : '';

        $pageObject = false;
        $name       = '';
        $link       = '';

        if (!empty($pageID)) {

            $pageObject = Page::getByID($pageID);

            if (!$pageObject->isError() AND !$pageObject->isInTrash()) {

                $link = $pageObject->getCollectionLink();
                $name = $pageObject->getCollectionName();

            }
        }

        if ($type == 'view') {

            // Fields from database
            $this->set($pageIDFieldName, $pageID);
            $this->set($endingFieldName, $ending);
            $this->set($textFieldName, $text);
            $this->set($titleFieldName, $title);
            $this->set($newWindowFieldName, $newWindow);

            // Additional data
            $this->set($pageIDFieldName.'_object', $pageObject);
            $this->set($pageIDFieldName.'_name', $name);
            $this->set($pageIDFieldName.'_link', $link);
            $this->set($pageIDFieldName.'_link_type', 'link_from_sitemap');

        } elseif ($type == 'entry') {

            $entry = [];

            // Fields from database
            $entry[$pageIDFieldName]    = $pageID;
            $entry[$endingFieldName]    = $ending;
            $entry[$textFieldName]      = $text;
            $entry[$titleFieldName]     = $title;
            $entry[$newWindowFieldName] = $newWindow;

            // Additional data
            // $entry[$pageIDFieldName.'_object']    = $pageObject;
            $entry[$pageIDFieldName.'_name']      = $name;
            $entry[$pageIDFieldName.'_link']      = $link;
            $entry[$pageIDFieldName.'_link_type'] = 'link_from_sitemap';

            return $entry;

        }

    }

    private function prepareEntriesForView($entries) {

        $entriesForView = [];

        if (is_array($entries) AND count($entries)) {

            foreach ($entries as $key => $entry) {

                // Select Page (select_page) - Link from Sitemap
                $modifiedEntry = $this->prepareForViewLinkFromSitemap('entry', [
                    'select_page'            => $entry['select_page'],
                    'select_page_ending'     => $entry['select_page_ending'],
                    'select_page_text'       => $entry['select_page_text'],
                    'select_page_title'      => $entry['select_page_title'],
                    'select_page_new_window' => $entry['select_page_new_window']
                ]);
                $entry = array_merge($entry, $modifiedEntry);

                $entriesForView[] = $entry;

            }

        }

        return $entriesForView;

    }

}
