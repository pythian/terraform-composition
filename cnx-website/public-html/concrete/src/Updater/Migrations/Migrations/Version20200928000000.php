<?php

namespace Concrete\Core\Updater\Migrations\Migrations;

use Concrete\Core\Application\UserInterface\Dashboard\Navigation\NavigationCache;
use Concrete\Core\Page\Page;
use Concrete\Core\Updater\Migrations\AbstractMigration;
use Concrete\Core\Updater\Migrations\RepeatableMigrationInterface;

final class Version20200928000000 extends AbstractMigration implements RepeatableMigrationInterface
{

    public function upgradeDatabase()
    {

        $c = Page::getByPath('/dashboard/system/calendar/permissions');

        if (is_object($c) && !$c->isError()) {
            $c->update(['cName' => 'Calendar Permissions']);
        }

        /** @var NavigationCache $navigationCache */
        $navigationCache = $this->app->make(NavigationCache::class);
        $navigationCache->clear();

    }
}
