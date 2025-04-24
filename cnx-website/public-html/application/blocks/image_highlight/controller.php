<?php namespace Application\Block\ImageHighlight;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\File\File;
use Concrete\Core\Page\Page;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{

    protected $btTable = 'btImageHighlight';
    protected $btExportTables = ['btImageHighlight'];
    protected $btExportFileColumns = ['image_left', 'image_right'];
    protected $btInterfaceWidth = '1000';
    protected $btInterfaceHeight = '650';
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = 0;

    protected $btDefaultSet = ''; // basic, navigation, form, express, social, multimedia

    protected $image_left;
    protected $image_left_alt;
    protected $image_left_data;
    protected $image_right;
    protected $image_right_alt;
    protected $image_right_data;
    protected $title;
    protected $content;
    protected $link;

    private $uniqueID;

    public function getBlockTypeName() {
        return t('Image Highlight');
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

        // Image Left (image_left_data) - Additional fields for Image
        $this->image_left_data = is_array($this->image_left_data) ? $this->image_left_data : json_decode($this->image_left_data, true);
        $this->set('image_left_data', $this->image_left_data);

        // Image Right (image_right_data) - Additional fields for Image
        $this->image_right_data = is_array($this->image_right_data) ? $this->image_right_data : json_decode($this->image_right_data, true);
        $this->set('image_right_data', $this->image_right_data);

        // Link Address (link) - Link
        $this->link = is_array($this->link) ? $this->link : json_decode($this->link, true);
        $this->set('link', $this->link);

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

        $this->set('image_left', $this->image_left);
        $this->set('image_left_alt', $this->image_left_alt);
        $this->set('image_left_data', $this->image_left_data);
        $this->set('image_right', $this->image_right);
        $this->set('image_right_alt', $this->image_right_alt);
        $this->set('image_right_data', $this->image_right_data);
        $this->set('title', $this->title);
        $this->set('content', $this->content);
        $this->set('link', $this->link);

        // Load form.css
        $al = AssetList::getInstance();
        $al->register('css', 'image-highlight/form', 'blocks/image_highlight/css_files/form.css', [], false);
        $this->requireAsset('css', 'image-highlight/form');

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

        // Prepare fields for view
        $this->prepareForViewImage('view', [
            'image_left'     => $this->image_left,
            'image_left_alt' => $this->image_left_alt
        ], [
            'thumbnail'        => true,
            'thumbnailWidth'   => !empty($this->image_left_data['override_dimensions']) ? (!empty($this->image_left_data['custom_width']) ? $this->image_left_data['custom_width'] : false) : 480,
            'thumbnailHeight'  => !empty($this->image_left_data['override_dimensions']) ? (!empty($this->image_left_data['custom_height']) ? $this->image_left_data['custom_height'] : false) : 270,
            'thumbnailCrop'    => !empty($this->image_left_data['override_dimensions']) ? (!empty($this->image_left_data['custom_crop']) ? true : false) : 1,

            'fullscreen'       => true,
            'fullscreenWidth'  => !empty($this->image_left_data['override_fullscreen_dimensions']) ? (!empty($this->image_left_data['custom_fullscreen_width']) ? $this->image_left_data['custom_fullscreen_width'] : false) : 1920,
            'fullscreenHeight' => !empty($this->image_left_data['override_fullscreen_dimensions']) ? (!empty($this->image_left_data['custom_fullscreen_height']) ? $this->image_left_data['custom_fullscreen_height'] : false) : 1080,
            'fullscreenCrop'   => !empty($this->image_left_data['override_fullscreen_dimensions']) ? (!empty($this->image_left_data['custom_fullscreen_crop']) ? true : false) : false
        ]);

        $this->prepareForViewImage('view', [
            'image_right'     => $this->image_right,
            'image_right_alt' => $this->image_right_alt
        ], [
            'thumbnail'        => true,
            'thumbnailWidth'   => !empty($this->image_right_data['override_dimensions']) ? (!empty($this->image_right_data['custom_width']) ? $this->image_right_data['custom_width'] : false) : 480,
            'thumbnailHeight'  => !empty($this->image_right_data['override_dimensions']) ? (!empty($this->image_right_data['custom_height']) ? $this->image_right_data['custom_height'] : false) : 270,
            'thumbnailCrop'    => !empty($this->image_right_data['override_dimensions']) ? (!empty($this->image_right_data['custom_crop']) ? true : false) : 1,

            'fullscreen'       => true,
            'fullscreenWidth'  => !empty($this->image_right_data['override_fullscreen_dimensions']) ? (!empty($this->image_right_data['custom_fullscreen_width']) ? $this->image_right_data['custom_fullscreen_width'] : false) : 1920,
            'fullscreenHeight' => !empty($this->image_right_data['override_fullscreen_dimensions']) ? (!empty($this->image_right_data['custom_fullscreen_height']) ? $this->image_right_data['custom_fullscreen_height'] : false) : 1080,
            'fullscreenCrop'   => !empty($this->image_right_data['override_fullscreen_dimensions']) ? (!empty($this->image_right_data['custom_fullscreen_crop']) ? true : false) : false
        ]);

        // Link Address (link) - Link
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
        $args['image_left']      = !empty($args['image_left']) ? intval($args['image_left']) : 0;
        $args['image_left_alt']  = !empty($args['image_left_alt']) ? trim($args['image_left_alt']) : '';
        $args['image_right']     = !empty($args['image_right']) ? intval($args['image_right']) : 0;
        $args['image_right_alt'] = !empty($args['image_right_alt']) ? trim($args['image_right_alt']) : '';
        $args['title']           = !empty($args['title']) ? trim($args['title']) : '';
        $args['content']         = !empty($args['content']) ? LinkAbstractor::translateTo($args['content']) : '';
        $args['link']            = !empty($args['link']) ? trim($args['link']) : '';

        // Image Left (image_left) - Additional fields for Image
        $args['image_left_data'] = json_encode([
            'show_additional_fields'         => !empty($args['image_left_show_additional_fields']) ? intval($args['image_left_show_additional_fields']) : 0,
            'override_dimensions'            => !empty($args['image_left_override_dimensions']) ? intval($args['image_left_override_dimensions']) : 0,
            'custom_width'                   => !empty($args['image_left_custom_width']) ? intval($args['image_left_custom_width']) : 0,
            'custom_height'                  => !empty($args['image_left_custom_height']) ? intval($args['image_left_custom_height']) : 0,
            'custom_crop'                    => (!empty($args['image_left_custom_crop']) and $args['image_left_custom_crop']==='1' and (!(bool)$args['image_left_custom_width'] or !(bool)$args['image_left_custom_height'])) ? false : (isset($args['image_left_custom_crop']) ? intval($args['image_left_custom_crop']) : 0), // do not crop without width and height filled
            'override_fullscreen_dimensions' => !empty($args['image_left_override_fullscreen_dimensions']) ? intval($args['image_left_override_fullscreen_dimensions']) : 0,
            'custom_fullscreen_width'        => !empty($args['image_left_custom_fullscreen_width']) ? intval($args['image_left_custom_fullscreen_width']) : 0,
            'custom_fullscreen_height'       => !empty($args['image_left_custom_fullscreen_height']) ? intval($args['image_left_custom_fullscreen_height']) : 0,
            'custom_fullscreen_crop'         => (!empty($args['image_left_custom_fullscreen_crop']) and $args['image_left_custom_fullscreen_crop']==='1' and (!(bool)$args['image_left_custom_fullscreen_width'] or !(bool)$args['image_left_custom_fullscreen_height'])) ? false : (isset($args['image_left_custom_fullscreen_crop']) ? intval($args['image_left_custom_fullscreen_crop']) : 0), // do not crop without width and height filled
        ]);

        // Image Right (image_right) - Additional fields for Image
        $args['image_right_data'] = json_encode([
            'show_additional_fields'         => !empty($args['image_right_show_additional_fields']) ? intval($args['image_right_show_additional_fields']) : 0,
            'override_dimensions'            => !empty($args['image_right_override_dimensions']) ? intval($args['image_right_override_dimensions']) : 0,
            'custom_width'                   => !empty($args['image_right_custom_width']) ? intval($args['image_right_custom_width']) : 0,
            'custom_height'                  => !empty($args['image_right_custom_height']) ? intval($args['image_right_custom_height']) : 0,
            'custom_crop'                    => (!empty($args['image_right_custom_crop']) and $args['image_right_custom_crop']==='1' and (!(bool)$args['image_right_custom_width'] or !(bool)$args['image_right_custom_height'])) ? false : (isset($args['image_right_custom_crop']) ? intval($args['image_right_custom_crop']) : 0), // do not crop without width and height filled
            'override_fullscreen_dimensions' => !empty($args['image_right_override_fullscreen_dimensions']) ? intval($args['image_right_override_fullscreen_dimensions']) : 0,
            'custom_fullscreen_width'        => !empty($args['image_right_custom_fullscreen_width']) ? intval($args['image_right_custom_fullscreen_width']) : 0,
            'custom_fullscreen_height'       => !empty($args['image_right_custom_fullscreen_height']) ? intval($args['image_right_custom_fullscreen_height']) : 0,
            'custom_fullscreen_crop'         => (!empty($args['image_right_custom_fullscreen_crop']) and $args['image_right_custom_fullscreen_crop']==='1' and (!(bool)$args['image_right_custom_fullscreen_width'] or !(bool)$args['image_right_custom_fullscreen_height'])) ? false : (isset($args['image_right_custom_fullscreen_crop']) ? intval($args['image_right_custom_fullscreen_crop']) : 0), // do not crop without width and height filled
        ]);

        // Link Address (link) - Link
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

}
