<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\file\file$storagelocation][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T20:26:13-05:00 */



$loaded = true;
$expiration = 1744334773;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => '\\Concrete\\Core\\Entity\\File\\StorageLocation\\StorageLocation',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'files',
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'fslID',
     'referencedColumnName' => 'fslID',
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
$data['createdOn'] = 1743916763;
