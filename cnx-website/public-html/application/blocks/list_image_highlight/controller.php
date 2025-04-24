<?php namespace Application\Block\ListImageHighlight;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\File\File;
use Concrete\Core\Page\Page;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{

    protected $btTable = 'btListImageHighlight';
    protected $btExportTables = ['btListImageHighlight', 'btListImageHighlightEntries'];
    protected $btExportFileColumns = ['image', 'hover_image'];
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
        return t('List Image Highlight');
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

        // Load assets for repeatable entries
        $this->requireAsset('core/sitemap');
        $this->requireAsset('core/file-manager');

        // Get entry column names
        $entryColumnNames = $this->getEntryColumnNames();

        // Link (link) - Fields that don't exist in database, but are required in repeatable entry (link)
        $entryColumnNames[] = 'link_link_type';
        $entryColumnNames[] = 'link_show_additional_fields';
        $entryColumnNames[] = 'link_link_from_sitemap';
        $entryColumnNames[] = 'link_link_from_file_manager';
        $entryColumnNames[] = 'link_protocol';
        $entryColumnNames[] = 'link_external_link';
        $entryColumnNames[] = 'link_ending';
        $entryColumnNames[] = 'link_text';
        $entryColumnNames[] = 'link_title';
        $entryColumnNames[] = 'link_new_window';

        // Image (image) - Fields that don't exist in database, but are required in repeatable entry (image)
        $entryColumnNames[] = 'image_show_additional_fields';

        // Hover Image (hover_image) - Fields that don't exist in database, but are required in repeatable entry (image)
        $entryColumnNames[] = 'hover_image_show_additional_fields';

        $this->set('entryColumnNames', $entryColumnNames);

        // Load form.css
        $al = AssetList::getInstance();
        $al->register('css', 'list-image-highlight/form', 'blocks/list_image_highlight/css_files/form.css', [], false);
        $this->requireAsset('css', 'list-image-highlight/form');

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

        // Wysiwyg editors
        $this->set('content', LinkAbstractor::translateFrom($this->content));

        // Get entries
        $entries = $this->getEntries();
        $entries = $this->prepareEntriesForView($entries);
        $this->set('entries', $entries);

    }

    public function save($args) {

        // Basic fields
        $args['title']   = !empty($args['title']) ? trim($args['title']) : '';
        $args['content'] = !empty($args['content']) ? LinkAbstractor::translateTo($args['content']) : '';

        parent::save($args);

        $db = $this->app->make('database')->connection();

        // Delete existing entries of current block's version
        $db->delete('btListImageHighlightEntries', ['bID' => $this->bID]);

        if (isset($args['entry']) AND is_array($args['entry']) AND count($args['entry'])) {

            $i = 1;

            foreach ($args['entry'] as $entry) {

                // Prepare data for insert
                $data = [];
                $data['position']        = $i;
                $data['bID']             = $this->bID;
                $data['image']           = intval($entry['image']);
                $data['image_alt']       = trim($entry['image_alt']);
                $data['hover_image']     = intval($entry['hover_image']);
                $data['hover_image_alt'] = trim($entry['hover_image_alt']);
                $data['title']           = trim($entry['title']);
                $data['link']            = !empty($entry['link']) ? trim($entry['link']) : null;

                // Link (link) - Link
                $data['link'] = json_encode([
                    'link_type'              => trim($entry['link_link_type']),
                    'show_additional_fields' => intval($entry['link_show_additional_fields']),
                    'link_from_sitemap'      => intval($entry['link_link_from_sitemap']),
                    'link_from_file_manager' => !empty($entry['link_link_from_file_manager']) ? intval($entry['link_link_from_file_manager']) : 0,
                    'protocol'               => trim($entry['link_protocol']),
                    'external_link'          => trim($entry['link_external_link']),
                    'ending'                 => trim($entry['link_ending']),
                    'text'                   => trim($entry['link_text']),
                    'title'                  => trim($entry['link_title']),
                    'new_window'             => intval($entry['link_new_window'])
                ]);

                // Image (image) - Image
                $data['image_data'] = json_encode([
                    'show_additional_fields'         => intval($entry['image_show_additional_fields']),
                ]);

                // Hover Image (hover_image) - Image
                $data['hover_image_data'] = json_encode([
                    'show_additional_fields'         => intval($entry['hover_image_show_additional_fields']),
                ]);

                $db->insert('btListImageHighlightEntries', $data);

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
                btListImageHighlightEntries.*
            FROM
                btListImageHighlightEntries
            WHERE
                btListImageHighlightEntries.bID = :bID
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
                $db->insert('btListImageHighlightEntries', $data);
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
        $al->register('javascript', 'list-image-highlight/auto-js', 'blocks/list_image_highlight/auto.js', [], false);
        $this->requireAsset('javascript', 'list-image-highlight/auto-js');

        $this->edit();

    }

    public function scrapbook() {

        $this->edit();

    }

    private function getEntries($outputMethod = 'view') {

        $db = $this->app->make('database')->connection();

        $sql = '
            SELECT
                btListImageHighlightEntries.*
            FROM
                btListImageHighlightEntries
            WHERE
                btListImageHighlightEntries.bID = :bID
            ORDER BY
                btListImageHighlightEntries.position ASC
        ';
        $parameters = [];
        $parameters['bID'] = $this->bID;

        $entries = $db->fetchAll($sql, $parameters);

        $modifiedEntries = [];

        foreach ($entries as $entry) {

            $entry['image'] = (is_object(File::getByID($entry['image']))) ? $entry['image'] : 0;
            // Image (image) - Image
            $imageArray = json_decode($entry['image_data'], true);
            $entry['image_show_additional_fields']         = $imageArray['show_additional_fields'] ?? '';
            $entry['hover_image'] = (is_object(File::getByID($entry['hover_image']))) ? $entry['hover_image'] : 0;
            // Hover Image (hover_image) - Image
            $hover_imageArray = json_decode($entry['hover_image_data'], true);
            $entry['hover_image_show_additional_fields']         = $hover_imageArray['show_additional_fields'] ?? '';
            // Link (link) - Link
            $linkArray = json_decode($entry['link'], true);
            $entry['link_link_type']              = $linkArray['link_type'] ?? '';
            $entry['link_show_additional_fields'] = $linkArray['show_additional_fields'] ?? '';
            $entry['link_link_from_sitemap']      = $linkArray['link_from_sitemap'] ?? 0;
            $entry['link_link_from_file_manager'] = (!empty($linkArray['link_from_file_manager']) and is_object(File::getByID($linkArray['link_from_file_manager']))) ? $linkArray['link_from_file_manager'] : 0;
            $entry['link_protocol']               = $linkArray['protocol'] ?? '';
            $entry['link_external_link']          = $linkArray['external_link'] ?? '';
            $entry['link_ending']                 = $linkArray['ending'] ?? '';
            $entry['link_text']                   = $linkArray['text'] ?? '';
            $entry['link_title']                  = $linkArray['title'] ?? '';
            $entry['link_new_window']             = $linkArray['new_window'] ?? 0;

            $modifiedEntries[] = $entry;

        }

        return $modifiedEntries;

    }

     private function getEntryColumnNames() {

        $db = $this->app->make('database')->connection();

        $columns = $db->getSchemaManager()->listTableColumns('btListImageHighlightEntries');

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

    private function prepareForViewImage($type, $fields, $options = []) {

        // Options
        if (!is_array($options)) {
            $options = [];
        }

        $defaultOptions = [];
        $defaultOptions['fullscreen']       = false;
        $defaultOptions['fullscreenWidth']  = 1920;
        $defaultOptions['fullscreenHeight'] = 1080;
        $defaultOptions['fullscreenCrop']   = false;

        $defaultOptions['thumbnail']        = false;
        $defaultOptions['thumbnailWidth']   = 480;
        $defaultOptions['thumbnailHeight']  = 270;
        $defaultOptions['thumbnailCrop']    = true;

        $options = array_merge($defaultOptions, $options);

        // Prepare links/images
        $keys = array_keys($fields);
        $fileIDFieldName = $keys[0];
        $altFieldName    = $keys[1];

        $fileID = $fields[$fileIDFieldName];
        $alt    = $fields[$altFieldName];

        $fileObject   = false;
        $filename     = '';
        $relativePath = '';
        $fileType     = '';

        $link   = '';
        $width  = '';
        $height = '';

        $fullscreenLink   = '';
        $fullscreenWidth  = '';
        $fullscreenHeight = '';

        $thumbnailLink   = '';
        $thumbnailWidth  = '';
        $thumbnailHeight = '';

        if (!empty($fileID)) {

            $fileObject = File::getByID($fileID);

            if (is_object($fileObject)) {

                $filename     = $fileObject->getFileName();
                $fileType     = $fileObject->getType();
                $relativePath = $fileObject->getRelativePath();

                if (empty($alt)) {
                    $alt = $fileObject->getTitle();
                    $removableExtensions = ['jpg', 'jpeg', 'png', 'tiff', 'svg', 'webp'];
                    $extension = strtolower(pathinfo($alt, PATHINFO_EXTENSION));
                    if (!empty($extension) and in_array($extension, $removableExtensions)) {
                        $alt = pathinfo($alt, PATHINFO_FILENAME); // Remove extension
                        $alt = preg_replace('/ - [0-9]*$/', '', $alt); // Remove counter at the end of file name, " - 001", " - 002" and so on.
                    }
                }

                // Original image
                $link   = $fileObject->getURL();
                $width  = $fileObject->canEdit() ? $fileObject->getAttribute('width') : $options['thumbnailWidth'];
                $height = $fileObject->canEdit() ? $fileObject->getAttribute('height') : $options['thumbnailHeight'];

                if ($fileObject->canEdit()) {

                    // Fullscreen image
                    if (!empty($options['fullscreen'])) {

                        $fullscreenWidth  = $options['fullscreenWidth'];
                        $fullscreenHeight = $options['fullscreenHeight'];
                        $fullscreenCrop   = $options['fullscreenCrop'];

                        if ($fileObject->canEdit() AND (($width > $fullscreenWidth AND $fullscreenWidth!=false) OR ($height > $fullscreenHeight AND $fullscreenHeight!=false))) {

                            $fullscreen       = $this->app->make('helper/image')->getThumbnail($fileObject, $fullscreenWidth, $fullscreenHeight, $fullscreenCrop);
                            $fullscreenLink   = $fullscreen->src;
                            $fullscreenWidth  = $fullscreen->width;
                            $fullscreenHeight = $fullscreen->height;

                        } else {

                            $fullscreenLink   = $link;
                            $fullscreenWidth  = $width;
                            $fullscreenHeight = $height;

                        }

                    }

                    // Thumbnail image
                    if (!empty($options['thumbnail'])) {

                        $thumbnailWidth  = $options['thumbnailWidth'];
                        $thumbnailHeight = $options['thumbnailHeight'];
                        $thumbnailCrop   = $options['thumbnailCrop'];

                        if ($fileObject->canEdit() AND (($width > $thumbnailWidth AND $thumbnailWidth!=false) OR ($height > $thumbnailHeight AND $thumbnailHeight!=false))) {
                            try {
                                $thumbnail       = $this->app->make('helper/image')->getThumbnail($fileObject, $thumbnailWidth, $thumbnailHeight, $thumbnailCrop);
                                if ($thumbnail && isset($thumbnail->src)) {
                                    $thumbnailLink   = $thumbnail->src;
                                    $thumbnailWidth  = $thumbnail->width;
                                    $thumbnailHeight = $thumbnail->height;
                                } else {
                                    throw new \Exception('Thumbnail generation failed');
                                }
                            } catch (\Exception $e) {
                                $thumbnailLink   = $link;
                                $thumbnailWidth  = $width;
                                $thumbnailHeight = $height;
                            }
                        } else {
                            $thumbnailLink   = $link;
                            $thumbnailWidth  = $width;
                            $thumbnailHeight = $height;
                        }

                    }

                }

            }

        }

        if ($type == 'view') {

            // Fields from database
            $this->set($fileIDFieldName, $fileID);
            $this->set($altFieldName, $alt);

            // Additional data
            $this->set($fileIDFieldName.'_object', $fileObject);
            $this->set($fileIDFieldName.'_filename', $filename);
            $this->set($fileIDFieldName.'_type', $fileType);
            $this->set($fileIDFieldName.'_relativePath', $relativePath);

            $this->set($fileIDFieldName.'_link', $link);
            $this->set($fileIDFieldName.'_width', $width);
            $this->set($fileIDFieldName.'_height', $height);

            $this->set($fileIDFieldName.'_fullscreenLink', $fullscreenLink);
            $this->set($fileIDFieldName.'_fullscreenWidth', $fullscreenWidth);
            $this->set($fileIDFieldName.'_fullscreenHeight', $fullscreenHeight);

            $this->set($fileIDFieldName.'_thumbnailLink', $thumbnailLink);
            $this->set($fileIDFieldName.'_thumbnailWidth', $thumbnailWidth);
            $this->set($fileIDFieldName.'_thumbnailHeight', $thumbnailHeight);

        } elseif ($type == 'entry') {

            $entry = [];

            // Fields from database
            $entry[$fileIDFieldName] = $fileID;
            $entry[$altFieldName]    = $alt;

            // Additional data
            // $entry[$fileIDFieldName.'_object']    = $fileObject;
            $entry[$fileIDFieldName.'_filename']     = $filename;
            $entry[$fileIDFieldName.'_type']         = $fileType;
            $entry[$fileIDFieldName.'_relativePath'] = $relativePath;

            $entry[$fileIDFieldName.'_link']   = $link;
            $entry[$fileIDFieldName.'_width']  = $width;
            $entry[$fileIDFieldName.'_height'] = $height;

            $entry[$fileIDFieldName.'_fullscreenLink']   = $fullscreenLink;
            $entry[$fileIDFieldName.'_fullscreenWidth']  = $fullscreenWidth;
            $entry[$fileIDFieldName.'_fullscreenHeight'] = $fullscreenHeight;

            $entry[$fileIDFieldName.'_thumbnailLink']   = $thumbnailLink;
            $entry[$fileIDFieldName.'_thumbnailWidth']  = $thumbnailWidth;
            $entry[$fileIDFieldName.'_thumbnailHeight'] = $thumbnailHeight;

            return $entry;

        }

    }

    private function prepareEntriesForView($entries) {

        $entriesForView = [];

        if (is_array($entries) AND count($entries)) {

            foreach ($entries as $key => $entry) {

                // Image (image) - Image
                $modifiedEntry = $this->prepareForViewImage('entry', [
                    'image'     => $entry['image'],
                    'image_alt' => $entry['image_alt']
                ], [
                    'thumbnail'       => false,
                    'thumbnailWidth'  => false,
                    'thumbnailHeight' => false,
                    'thumbnailCrop'   => false,

                    'fullscreen'        => false,
                    'fullscreenWidth'   => false,
                    'fullscreenHeight'  => false,
                    'fullscreenCrop'    => false
                ]);
                $entry = array_merge($entry, $modifiedEntry);

                // Hover Image (hover_image) - Image
                $modifiedEntry = $this->prepareForViewImage('entry', [
                    'hover_image'     => $entry['hover_image'],
                    'hover_image_alt' => $entry['hover_image_alt']
                ], [
                    'thumbnail'       => false,
                    'thumbnailWidth'  => false,
                    'thumbnailHeight' => false,
                    'thumbnailCrop'   => false,

                    'fullscreen'        => false,
                    'fullscreenWidth'   => false,
                    'fullscreenHeight'  => false,
                    'fullscreenCrop'    => false
                ]);
                $entry = array_merge($entry, $modifiedEntry);

                // Link (link) - Link
                $modifiedEntry = [];
                if ($entry['link_link_type'] == 'link_from_sitemap') {
                    $modifiedEntry = $this->prepareForViewLinkFromSitemap('entry', [
                        'link'            => $entry['link_link_from_sitemap'],
                        'link_ending'     => $entry['link_ending'],
                        'link_text'       => $entry['link_text'],
                        'link_title'      => $entry['link_title'],
                        'link_new_window' => $entry['link_new_window']
                    ]);
                } elseif ($entry['link_link_type'] == 'link_from_file_manager') {
                    $modifiedEntry = $this->prepareForViewLinkFromFileManager('entry', [
                        'link'            => $entry['link_link_from_file_manager'],
                        'link_ending'     => $entry['link_ending'],
                        'link_text'       => $entry['link_text'],
                        'link_title'      => $entry['link_title'],
                        'link_new_window' => $entry['link_new_window']
                    ]);
                } elseif ($entry['link_link_type'] == 'external_link') {
                    $modifiedEntry = $this->prepareForViewExternalLink('entry', [
                        'link'            => $entry['link_external_link'],
                        'link_protocol'   => $entry['link_protocol'],
                        'link_ending'     => $entry['link_ending'],
                        'link_text'       => $entry['link_text'],
                        'link_title'      => $entry['link_title'],
                        'link_new_window' => $entry['link_new_window']
                    ]);
                }
                $entry = array_merge($entry, $modifiedEntry);

                $entriesForView[] = $entry;

            }

        }

        return $entriesForView;

    }

}
