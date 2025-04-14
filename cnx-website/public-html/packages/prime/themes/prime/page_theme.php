<?php namespace Concrete\Package\Prime\Theme\prime;
use Concrete\Core\Area\Layout\Preset\Provider\ThemeProviderInterface;
use Concrete\Core\Page\Theme\Theme;

class PageTheme extends Theme implements ThemeProviderInterface
{
      protected $pThemeGridFrameworkHandle = 'bootstrap5';
    public function registerAssets()
    {
      $this->requireAsset('css', 'font-awesome');
      $this->requireAsset('javascript', 'jquery');
      $this->requireAsset('javascript', 'picturefill');
      $this->requireAsset('javascript-conditional', 'respond');
    }
    public function getThemeName()
    {
        return t('Prime');
    }
    public function getThemeDescription()
    {
        return t('Prime');
    }
    /**
     * @return array
     */
    public function getThemeBlockClasses()
    {
    }
    /**
     * @return array
     */
    public function getThemeAreaClasses()
    {

    }
    /**
     * @return array
     */
    public function getThemeDefaultBlockTemplates()
    {

    }
    /**
     * @return array
     */
    public function getThemeEditorClasses()
    {
        return [
            ['title' => t('Title Thin'), 'menuClass' => 'title-thin', 'spanClass' => 'title-thin', 'forceBlock' => 1],
            ['title' => t('Title Caps Bold'), 'menuClass' => 'title-caps-bold', 'spanClass' => 'title-caps-bold', 'forceBlock' => 1],
            ['title' => t('Title Caps'), 'menuClass' => 'title-caps', 'spanClass' => 'title-caps', 'forceBlock' => 1],
            ['title' => t('Center Align Image'), 'menuClass' => 'image-center', 'spanClass' => 'image-center', 'forceBlock' => -1],
            ['title' => t('Image Caption'), 'menuClass' => 'image-caption', 'spanClass' => 'image-caption', 'forceBlock' => '-1'],
            ['title' => t('Standard Button'), 'menuClass' => '', 'spanClass' => 'btn btn-default', 'forceBlock' => '-1'],
            ['title' => t('Success Button'), 'menuClass' => '', 'spanClass' => 'btn btn-success', 'forceBlock' => '-1'],
            ['title' => t('Yellow Button'), 'menuClass' => '', 'spanClass' => 'btn btn-primary', 'forceBlock' => '-1'],
            ['title' => t('Arrow Button'),'menuClass' => '', 'spanClass' => 'btn btn-arrow', 'forceBlock' => '-1']
        ];
    }
    /**
     * @return array
     */
    public function getThemeAreaLayoutPresets()
    {
        $presets = [
            [
                'handle' => 'left_sidebar',
                'name' => 'Left Sidebar',
                'container' => '<div class="row"></div>',
                'columns' => [
                    '<div class="col-sm-4"></div>',
                    '<div class="col-sm-8"></div>',
                ],
            ],
            [
                'handle' => 'right_sidebar',
                'name' => 'Right Sidebar',
                'container' => '<div class="row"></div>',
                'columns' => [
                    '<div class="col-sm-8"></div>',
                    '<div class="col-sm-4"></div>',
                ],
            ],
        ];
        return $presets;
    }
}
