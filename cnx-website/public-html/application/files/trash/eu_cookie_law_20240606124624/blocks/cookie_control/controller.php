<?php

/**
 * @project:   Urheberrechts Zentrale
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2016 Fabian Bitter (www.bitter.de)
 * @version    1.1.2
 */

namespace Concrete\Package\EuCookieLaw\Block\CookieControl;

use \Concrete\Core\Block\BlockController;

class Controller extends BlockController
{
    public $helpers = array(
        'form',
    );

    protected $btTable = 'btCookieControl';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputLifetime = 300;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;

    public function getBlockTypeName()
    {
        return t("Cookie Control");
    }

    public function getBlockTypeDescription()
    {
        return t("Add Opt-Out link to your website.");
    }

    public function registerViewAssets($outputContent = '')
    {
        parent::registerViewAssets($outputContent);

        $this->requireAsset("cookie-disclosure");
    }
}
