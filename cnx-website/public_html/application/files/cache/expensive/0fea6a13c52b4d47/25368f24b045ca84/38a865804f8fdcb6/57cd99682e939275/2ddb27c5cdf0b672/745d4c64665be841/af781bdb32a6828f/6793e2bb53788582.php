<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\site\skeletonlocale$skeleton][1]/ */
/* Type: array */
/* Expiration: 2025-04-12T20:28:08-05:00 */



$loaded = true;
$expiration = 1744507688;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'Skeleton',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'locales',
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'siteSkeletonID',
     'referencedColumnName' => 'siteSkeletonID',
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
