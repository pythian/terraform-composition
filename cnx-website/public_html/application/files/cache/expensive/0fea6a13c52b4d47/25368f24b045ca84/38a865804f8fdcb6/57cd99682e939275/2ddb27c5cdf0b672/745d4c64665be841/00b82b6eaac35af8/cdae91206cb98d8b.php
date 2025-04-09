<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\file\file$versions][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T16:08:48-05:00 */



$loaded = true;
$expiration = 1744319328;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'file',
     'targetEntity' => 'Version',
     'cascade' =>
    array (
      0 => 'persist',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'fID',
     'referencedColumnName' => 'id',
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
