<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\site\skeletonlocale$tree][1]/ */
/* Type: array */
/* Expiration: 2025-04-13T04:47:57-05:00 */



$loaded = true;
$expiration = 1744537677;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\OneToOne::__set_state(array(
     'targetEntity' => 'SkeletonTree',
     'mappedBy' => 'locale',
     'inversedBy' => NULL,
     'cascade' =>
    array (
      0 => 'all',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'siteTreeID',
     'referencedColumnName' => 'siteTreeID',
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
$data['createdOn'] = 1744125076;
