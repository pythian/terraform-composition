<?php namespace Application\Block\StepHighlight;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{

    protected $btTable = 'btStepHighlight';
    protected $btExportTables = ['btStepHighlight', 'btStepHighlightEntries'];
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
    protected $content;

    private $uniqueID;

    public function getBlockTypeName() {
        return t('Step Highlight');
    }

    public function getBlockTypeDescription() {
        return t('');
    }

    public function getSearchableContent() {

        $content = [];
        $content[] = $this->title;
        $content[] = $this->content;

        $entries = $this->getEntries('edit');
        foreach ($entries as $entry) {
            $content[] = $entry['title'];
            $content[] = $entry['description'];
        }

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

        // Wysiwyg editors
        $this->set('content', LinkAbstractor::translateFromEditMode($this->content));

        // Get entries
        $entries = $this->getEntries('edit');
        $this->set('entries', $entries);

    }

    public function addEdit() {

        $this->set('title', $this->title);
        $this->set('content', $this->content);

        // Get entry column names
        $entryColumnNames = $this->getEntryColumnNames();

        $this->set('entryColumnNames', $entryColumnNames);

        // Load form.css
        $al = AssetList::getInstance();
        $al->register('css', 'step-highlight/form', 'blocks/step_highlight/css_files/form.css', [], false);
        $this->requireAsset('css', 'step-highlight/form');

        // Make $app available in view
        $this->set('app', $this->app);

    }

    public function view() {

        // Make $app available in view
        $this->set('app', $this->app);

        // Wysiwyg editors
        $this->set('content', LinkAbstractor::translateFrom($this->content));

        // Get entries
        $entries = $this->getEntries();
        $this->set('entries', $entries);

    }

    public function save($args) {

        // Basic fields
        $args['title']   = !empty($args['title']) ? trim($args['title']) : '';
        $args['content'] = !empty($args['content']) ? LinkAbstractor::translateTo($args['content']) : '';

        parent::save($args);

        $db = $this->app->make('database')->connection();

        // Delete existing entries of current block's version
        $db->delete('btStepHighlightEntries', ['bID' => $this->bID]);

        if (isset($args['entry']) AND is_array($args['entry']) AND count($args['entry'])) {

            $i = 1;

            foreach ($args['entry'] as $entry) {

                // Prepare data for insert
                $data = [];
                $data['position']    = $i;
                $data['bID']         = $this->bID;
                $data['title']       = trim($entry['title']);
                $data['description'] = LinkAbstractor::translateTo($entry['description']);

                $db->insert('btStepHighlightEntries', $data);

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
                btStepHighlightEntries.*
            FROM
                btStepHighlightEntries
            WHERE
                btStepHighlightEntries.bID = :bID
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
                $db->insert('btStepHighlightEntries', $data);
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
        $al->register('javascript', 'step-highlight/auto-js', 'blocks/step_highlight/auto.js', [], false);
        $this->requireAsset('javascript', 'step-highlight/auto-js');

        $this->edit();

    }

    public function scrapbook() {

        $this->edit();

    }

    private function getEntries($outputMethod = 'view') {

        $db = $this->app->make('database')->connection();

        $sql = '
            SELECT
                btStepHighlightEntries.*
            FROM
                btStepHighlightEntries
            WHERE
                btStepHighlightEntries.bID = :bID
            ORDER BY
                btStepHighlightEntries.position ASC
        ';
        $parameters = [];
        $parameters['bID'] = $this->bID;

        $entries = $db->fetchAll($sql, $parameters);

        $modifiedEntries = [];

        foreach ($entries as $entry) {

            $entry['description'] = ($outputMethod=='edit') ? LinkAbstractor::translateFromEditMode($entry['description']) : LinkAbstractor::translateFrom($entry['description']);

            $modifiedEntries[] = $entry;

        }

        return $modifiedEntries;

    }

     private function getEntryColumnNames() {

        $db = $this->app->make('database')->connection();

        $columns = $db->getSchemaManager()->listTableColumns('btStepHighlightEntries');

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

}
