<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\site\skeletontree$locale][1]/ */
/* Type: array */
/* Expiration: 2025-04-12T16:48:22-05:00 */



$loaded = true;
$expiration = 1744494502;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\OneToOne::__set_state(array(
     'targetEntity' => 'SkeletonLocale',
     'mappedBy' => NULL,
     'inversedBy' => 'tree',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'skeletonLocaleID',
     'referencedColumnName' => 'skeletonLocaleID',
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
