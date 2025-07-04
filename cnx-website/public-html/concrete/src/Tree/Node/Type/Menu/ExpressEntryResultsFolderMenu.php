<?php
namespace Concrete\Core\Tree\Node\Type\Menu;

use Concrete\Core\Application\UserInterface\ContextMenu\Item\DividerItem;
use Concrete\Core\Tree\Menu\Item\Category\EditExpressEntryResultsFolderItem;
use Concrete\Core\Tree\Menu\Item\CloneItem;
use Concrete\Core\Tree\Menu\Item\Category\DeleteExpressEntryCategoryItem;
use Concrete\Core\Tree\Menu\Item\EditPermissionsItem;
use Concrete\Core\Tree\Node\Type\Category;
use Concrete\Core\Tree\Menu\Menu;

class ExpressEntryResultsFolderMenu extends Menu
{

    public function __construct(Category $category)
    {
        parent::__construct($category);
        $p = new \Permissions($category);
        if ($p->canEditTreeNode()) {
            $this->addItem(new EditExpressEntryResultsFolderItem($category));
        }
        if ($p->canDuplicateTreeNode()) {
            $this->addItem(new CloneItem($category));
        }
        if ($p->canEditTreeNodePermissions() || $p->canDeleteTreeNode()) {
            $this->addItem(new DividerItem());
        }
        if ($p->canEditTreeNodePermissions()) {
            $this->addItem(new EditPermissionsItem($category));
        }
        if ($p->canDeleteTreeNode()) {
            $this->addItem(new DeleteExpressEntryCategoryItem($category));
        }
    }

}
