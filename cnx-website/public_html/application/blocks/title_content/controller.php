<?php namespace Application\Block\TitleContent;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{

    protected $btTable = 'btTitleContent';
    protected $btExportTables = ['btTitleContent'];
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
        return t('Title Content');
    }

    public function getBlockTypeDescription() {
        return t('');
    }

    public function getSearchableContent() {

        $content = [];
        $content[] = $this->title;
        $content[] = $this->content;

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

    }

    public function addEdit() {

        $this->set('title', $this->title);
        $this->set('content', $this->content);

        // Load form.css
        $al = AssetList::getInstance();
        $al->register('css', 'title-content/form', 'blocks/title_content/css_files/form.css', [], false);
        $this->requireAsset('css', 'title-content/form');

        // Make $app available in view
        $this->set('app', $this->app);

    }

    public function view() {

        // Make $app available in view
        $this->set('app', $this->app);

        // Wysiwyg editors
        $this->set('content', LinkAbstractor::translateFrom($this->content));

    }

    public function save($args) {

        // Basic fields
        $args['title']   = !empty($args['title']) ? trim($args['title']) : '';
        $args['content'] = !empty($args['content']) ? LinkAbstractor::translateTo($args['content']) : '';

        parent::save($args);

    }

    public function duplicate($newBlockID) {

        parent::duplicate($newBlockID);

    }

    public function delete() {

    }

    public function validate($args) {

        $error = $this->app->make('helper/validation/error');

        return $error;

    }

    public function composer() {

        $this->edit();

    }

    public function scrapbook() {

        $this->edit();

    }

}
