<?php namespace Application\Block\InsideBanner;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\File\File;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{

    protected $btTable = 'btInsideBanner';
    protected $btExportTables = ['btInsideBanner'];
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

    protected $title;
    protected $image;
    protected $image_alt;
    protected $image_data;

    private $uniqueID;

    public function getBlockTypeName() {
        return t('Inside Banner');
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

        // Image (image_data) - Additional fields for Image
        $this->image_data = is_array($this->image_data) ? $this->image_data : json_decode($this->image_data, true);
        $this->set('image_data', $this->image_data);

    }

    public function add() {

        $this->addEdit();
        $this->set('entries', []);

    }

    public function edit() {

        $this->addEdit();

    }

    public function addEdit() {

        $this->set('title', $this->title);
        $this->set('image', $this->image);
        $this->set('image_alt', $this->image_alt);
        $this->set('image_data', $this->image_data);

        // Load form.css
        $al = AssetList::getInstance();
        $al->register('css', 'inside-banner/form', 'blocks/inside_banner/css_files/form.css', [], false);
        $this->requireAsset('css', 'inside-banner/form');

        // Make $app available in view
        $this->set('app', $this->app);

    }

    public function view() {

        // Make $app available in view
        $this->set('app', $this->app);

        // Prepare fields for view
        $this->prepareForViewImage('view', [
            'image'     => $this->image,
            'image_alt' => $this->image_alt
        ], [
            'thumbnail'        => false,
            'thumbnailWidth'   => false,
            'thumbnailHeight'  => false,
            'thumbnailCrop'    => false,

            'fullscreen'       => false,
            'fullscreenWidth'  => false,
            'fullscreenHeight' => false,
            'fullscreenCrop'   => false
        ]);

    }

    public function save($args) {

        // Basic fields
        $args['title']     = !empty($args['title']) ? trim($args['title']) : '';
        $args['image']     = !empty($args['image']) ? intval($args['image']) : 0;
        $args['image_alt'] = !empty($args['image_alt']) ? trim($args['image_alt']) : '';

        // Image (image) - Additional fields for Image
        $args['image_data'] = json_encode([
            'show_additional_fields'         => !empty($args['image_show_additional_fields']) ? intval($args['image_show_additional_fields']) : 0,
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

                            $thumbnail       = $this->app->make('helper/image')->getThumbnail($fileObject, $thumbnailWidth, $thumbnailHeight, $thumbnailCrop);
                            $thumbnailLink   = $thumbnail->src;
                            $thumbnailWidth  = $thumbnail->width;
                            $thumbnailHeight = $thumbnail->height;

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

}
