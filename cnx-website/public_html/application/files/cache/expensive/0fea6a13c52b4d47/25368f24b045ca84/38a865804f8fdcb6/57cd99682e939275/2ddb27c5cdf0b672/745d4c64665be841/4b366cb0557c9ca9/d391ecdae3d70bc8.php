<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\site\group\relation$group][1]/ */
/* Type: array */
/* Expiration: 2025-04-11T23:15:50-05:00 */



$loaded = true;
$expiration = 1744431350;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'Group',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'group_relations',
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'siteGID',
     'referencedColumnName' => 'siteGID',
     'unique' => false,
     'nullable' => true,
     'onDelete' => NULL,
     'columnDefinition' => NULL,
     'fieldName' => NULL,
     'options' =>
    array (
    ),
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1744052193;
