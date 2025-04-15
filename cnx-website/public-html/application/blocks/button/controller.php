<?php namespace Application\Block\Button;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\File\File;
use Concrete\Core\Page\Page;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{

    protected $btTable = 'btButton';
    protected $btExportTables = ['btButton'];
    protected $btInterfaceWidth = '1000';
    protected $btInterfaceHeight = '650';
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = 0;

    protected $btDefaultSet = ''; // basic, navigation, form, express, social, multimedia

    protected $link;

    private $uniqueID;

    public function getBlockTypeName() {
        return t('Button');
    }

    public function getBlockTypeDescription() {
        return t('');
    }

    public function on_start() {

        // Unique identifier
        $this->uniqueID = $this->app->make('helper/validation/identifier')->getString(18);
        $this->set('uniqueID', $this->uniqueID);

        // Link (link) - Link
        $this->link = is_array($this->link) ? $this->link : json_decode($this->link, true);
        $this->set('link', $this->link);

    }

    public function add() {

        $this->addEdit();
        $this->set('entries', []);

    }

    public function edit() {

        $this->addEdit();

    }

    public function addEdit() {

        $this->set('link', $this->link);

        // Load form.css
        $al = AssetList::getInstance();
        $al->register('css', 'button/form', 'blocks/button/css_files/form.css', [], false);
        $this->requireAsset('css', 'button/form');

        // External link protocols
        $externalLinkProtocols = [
            'http://'  => 'http://',
            'https://' => 'https://',
            'BASE_URL' => 'BASE_URL',
            'CURRENT_PAGE' => 'CURRENT_PAGE',
            'other'    => '----'
        ];
        $this->set('externalLinkProtocols', $externalLinkProtocols);

        // Link types
        $linkTypes = [
            ''                       => '----',
            'link_from_sitemap'      => t('Link from Sitemap'),
            'link_from_file_manager' => t('Link from File Manager'),
            'external_link'          => t('External Link')
        ];
        $this->set('linkTypes', $linkTypes);

        // Make $app available in view
        $this->set('app', $this->app);

    }

    public function view() {

        // Make $app available in view
        $this->set('app', $this->app);

        // Prepare fields for view
        // Link (link) - Link
        if (!empty($this->link) and $this->link['link_type'] == 'link_from_sitemap') {
            $this->prepareForViewLinkFromSitemap('view', [
                'link'            => $this->link['link_from_sitemap'],
                'link_ending'     => $this->link['ending'],
                'link_text'       => $this->link['text'],
                'link_title'      => $this->link['title'],
                'link_new_window' => $this->link['new_window']
            ]);
        } elseif (!empty($this->link) and $this->link['link_type'] == 'link_from_file_manager') {
            $this->prepareForViewLinkFromFileManager('view', [
                'link'            => $this->link['link_from_file_manager'],
                'link_ending'     => $this->link['ending'],
                'link_text'       => $this->link['text'],
                'link_title'      => $this->link['title'],
                'link_new_window' => $this->link['new_window']
            ]);
        } elseif (!empty($this->link) and $this->link['link_type'] == 'external_link') {
            $this->prepareForViewExternalLink('view', [
                'link'            => $this->link['external_link'],
                'link_protocol'   => $this->link['protocol'],
                'link_ending'     => $this->link['ending'],
                'link_text'       => $this->link['text'],
                'link_title'      => $this->link['title'],
                'link_new_window' => $this->link['new_window']
            ]);
        }

    }

    public function save($args) {

        // Basic fields
        $args['link'] = !empty($args['link']) ? trim($args['link']) : '';

        // Link (link) - Link
        $args['link'] = json_encode([
            'link_type'              => !empty($args['link_link_type']) ? trim($args['link_link_type']) : null,
            'show_additional_fields' => !empty($args['link_show_additional_fields']) ? intval($args['link_show_additional_fields']) : 0,
            'link_from_sitemap'      => !empty($args['link_link_from_sitemap']) ? intval($args['link_link_from_sitemap']) : 0,
            'link_from_file_manager' => !empty($args['link_link_from_file_manager']) ? intval($args['link_link_from_file_manager']) : 0,
            'protocol'               => !empty($args['link_protocol']) ? trim($args['link_protocol']) : null,
            'external_link'          => !empty($args['link_external_link']) ? trim($args['link_external_link']) : null,
            'ending'                 => !empty($args['link_ending']) ? trim($args['link_ending']) : null,
            'text'                   => !empty($args['link_text']) ? trim($args['link_text']) : null,
            'title'                  => !empty($args['link_title']) ? trim($args['link_title']) : null,
            'new_window'             => !empty($args['link_new_window']) ? intval($args['link_new_window']) : 0
        ]);

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

    private function prepareForViewLinkFromFileManager($type, $fields) {

        $keys = array_keys($fields);
        $fileIDFieldName    = $keys[0];
        $endingFieldName    = $keys[1];
        $textFieldName      = $keys[2];
        $titleFieldName     = $keys[3];
        $newWindowFieldName = $keys[4];

        $fileID    = $fields[$fileIDFieldName];
        $ending    = $fields[$endingFieldName];
        $text      = $fields[$textFieldName];
        $title     = $fields[$titleFieldName];
        $newWindow = !empty($fields[$newWindowFieldName]) ? 'target="_blank" rel="noopener"' : '';

        $fileObject = false;
        $filename   = '';
        $link       = '';


        if (!empty($fileID)) {

            $fileObject = File::getByID($fileID);

            if (is_object($fileObject)) {

                $link     = $fileObject->getURL();
                $filename = $fileObject->getFileName();

            }

        }

        if ($type == 'view') {

            // Fields from database
            $this->set($fileIDFieldName, $fileID);
            $this->set($endingFieldName, $ending);
            $this->set($textFieldName, $text);
            $this->set($titleFieldName, $title);
            $this->set($newWindowFieldName, $newWindow);

            // Additional data
            $this->set($fileIDFieldName.'_object', $fileObject);
            $this->set($fileIDFieldName.'_filename', $filename);
            $this->set($fileIDFieldName.'_link', $link);
            $this->set($fileIDFieldName.'_link_type', 'link_from_file_manager');

        } elseif ($type == 'entry') {

            $entry = [];

            // Fields from database
            $entry[$fileIDFieldName]    = $fileID;
            $entry[$endingFieldName]    = $ending;
            $entry[$textFieldName]      = $text;
            $entry[$titleFieldName]     = $title;
            $entry[$newWindowFieldName] = $newWindow;

            // Additional data
            // $entry[$fileIDFieldName.'_object']    = $fileObject;
            $entry[$fileIDFieldName.'_filename']  = $filename;
            $entry[$fileIDFieldName.'_link']      = $link;
            $entry[$fileIDFieldName.'_link_type'] = 'link_from_file_manager';

            return $entry;

        }

    }

    private function prepareForViewExternalLink($type, $fields) {

        $keys = array_keys($fields);
        $linkFieldName      = $keys[0];
        $protocolFieldName  = $keys[1];
        $endingFieldName    = $keys[2];
        $textFieldName      = $keys[3];
        $titleFieldName     = $keys[4];
        $newWindowFieldName = $keys[5];

        $link      = $fields[$linkFieldName];
        $protocol  = $fields[$protocolFieldName];
        $ending    = $fields[$endingFieldName];
        $text      = $fields[$textFieldName];
        $title     = $fields[$titleFieldName];
        $newWindow = !empty($fields[$newWindowFieldName]) ? 'target="_blank" rel="noopener"' : '';

        if ($type == 'view') {

            // Fields from database
            $this->set($linkFieldName, $link);
            $this->set($protocolFieldName, $protocol);
            $this->set($endingFieldName, $ending);
            $this->set($textFieldName, $text);
            $this->set($titleFieldName, $title);
            $this->set($newWindowFieldName, $newWindow);

            // Additional data
            if (!empty($link) AND in_array($protocol, ['http://', 'https://'])) {
                $link = $protocol.$link;
            }
            if (!empty($link) AND $protocol=='BASE_URL') {
                $separator = '';
                if (substr($link, 0, 1)!='/') {
                    $separator = '/';
                }
                $link = BASE_URL.$separator.$link;
            }
            if (!empty($link) AND $protocol=='CURRENT_PAGE') {
                $separator = '';
                if (substr($link, 0, 1)!='/') {
                    $separator = '/';
                }
                $link = Page::getCurrentPage()->getCollectionLink().$separator.$link;
            }
            $this->set($linkFieldName.'_link', $link);
            $this->set($linkFieldName.'_link_type', 'external_link');

        } elseif ($type == 'entry') {

            $entry = [];

            // Fields from database
            $entry[$linkFieldName]      = $link;
            $entry[$protocolFieldName]  = $protocol;
            $entry[$endingFieldName]    = $ending;
            $entry[$textFieldName]      = $text;
            $entry[$titleFieldName]     = $title;
            $entry[$newWindowFieldName] = $newWindow;

            // Additional data
            if (!empty($link) AND in_array($protocol, ['http://', 'https://'])) {
                $link = $protocol.$link;
            }
            if (!empty($link) AND $protocol=='BASE_URL') {
                $separator = '';
                if (substr($link, 0, 1)!='/') {
                    $separator = '/';
                }
                $link = BASE_URL.$separator.$link;
            }
            if (!empty($link) AND $protocol=='CURRENT_PAGE') {
                $separator = '';
                if (substr($link, 0, 1)!='/') {
                    $separator = '/';
                }
                $link = Page::getCurrentPage()->getCollectionLink().$separator.$link;
            }
            $entry[$linkFieldName.'_link']      = $link;
            $entry[$linkFieldName.'_link_type'] = 'external_link';

            return $entry;

        }

    }

}
