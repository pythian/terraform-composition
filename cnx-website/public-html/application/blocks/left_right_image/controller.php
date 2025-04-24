<?php namespace Application\Block\LeftRightImage;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\File\File;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{

    protected $btTable = 'btLeftRightImage';
    protected $btExportTables = ['btLeftRightImage', 'btLeftRightImageEntries'];
    protected $btExportFileColumns = ['image'];
    protected $btInterfaceWidth = '1000';
    protected $btInterfaceHeight = '650';
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = 0;

    protected $btDefaultSet = ''; // basic, navigation, form, express, social, multimedia

    private $uniqueID;

    public function getBlockTypeName() {
        return t('Left Right Image');
    }

    public function getBlockTypeDescription() {
        return t('');
    }

    public function getSearchableContent() {

        $content = [];

        $entries = $this->getEntries('edit');
        foreach ($entries as $entry) {
            $content[] = $entry['title'];
            $content[] = $entry['content'];
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

        // Get entries
        $entries = $this->getEntries('edit');
        $this->set('entries', $entries);

    }

    public function addEdit() {

        // Load assets for repeatable entries
        $this->requireAsset('core/file-manager');

        // Get entry column names
        $entryColumnNames = $this->getEntryColumnNames();

        // Image (image) - Fields that don't exist in database, but are required in repeatable entry (image)
        $entryColumnNames[] = 'image_show_additional_fields';

        $this->set('entryColumnNames', $entryColumnNames);

        // Load form.css
        $al = AssetList::getInstance();
        $al->register('css', 'left-right-image/form', 'blocks/left_right_image/css_files/form.css', [], false);
        $this->requireAsset('css', 'left-right-image/form');

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

        parent::save($args);

        $db = $this->app->make('database')->connection();

        // Delete existing entries of current block's version
        $db->delete('btLeftRightImageEntries', ['bID' => $this->bID]);

        if (isset($args['entry']) AND is_array($args['entry']) AND count($args['entry'])) {

            $i = 1;

            foreach ($args['entry'] as $entry) {

                // Prepare data for insert
                $data = [];
                $data['position']  = $i;
                $data['bID']       = $this->bID;
                $data['title']     = trim($entry['title']);
                $data['content']   = LinkAbstractor::translateTo($entry['content']);
                $data['image']     = intval($entry['image']);
                $data['image_alt'] = trim($entry['image_alt']);

                // Image (image) - Image
                $data['image_data'] = json_encode([
                    'show_additional_fields'         => intval($entry['image_show_additional_fields']),
                ]);

                $db->insert('btLeftRightImageEntries', $data);

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
                btLeftRightImageEntries.*
            FROM
                btLeftRightImageEntries
            WHERE
                btLeftRightImageEntries.bID = :bID
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
                $db->insert('btLeftRightImageEntries', $data);
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
        $al->register('javascript', 'left-right-image/auto-js', 'blocks/left_right_image/auto.js', [], false);
        $this->requireAsset('javascript', 'left-right-image/auto-js');

        $this->edit();

    }

    public function scrapbook() {

        $this->edit();

    }

    private function getEntries($outputMethod = 'view') {

        $db = $this->app->make('database')->connection();

        $sql = '
            SELECT
                btLeftRightImageEntries.*
            FROM
                btLeftRightImageEntries
            WHERE
                btLeftRightImageEntries.bID = :bID
            ORDER BY
                btLeftRightImageEntries.position ASC
        ';
        $parameters = [];
        $parameters['bID'] = $this->bID;

        $entries = $db->fetchAll($sql, $parameters);

        $modifiedEntries = [];

        foreach ($entries as $entry) {

            $entry['content'] = ($outputMethod=='edit') ? LinkAbstractor::translateFromEditMode($entry['content']) : LinkAbstractor::translateFrom($entry['content']);
            $entry['image'] = (is_object(File::getByID($entry['image']))) ? $entry['image'] : 0;
            // Image (image) - Image
            $imageArray = json_decode($entry['image_data'], true);
            $entry['image_show_additional_fields']         = $imageArray['show_additional_fields'] ?? '';

            $modifiedEntries[] = $entry;

        }

        return $modifiedEntries;

    }

     private function getEntryColumnNames() {

        $db = $this->app->make('database')->connection();

        $columns = $db->getSchemaManager()->listTableColumns('btLeftRightImageEntries');

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

                $entriesForView[] = $entry;

            }

        }

        return $entriesForView;

    }

}
