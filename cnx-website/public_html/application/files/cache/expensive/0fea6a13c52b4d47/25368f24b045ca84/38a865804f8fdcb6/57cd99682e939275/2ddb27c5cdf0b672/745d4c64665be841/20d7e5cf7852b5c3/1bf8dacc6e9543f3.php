<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\user\usersignup$createdby][1]/ */
/* Type: array */
/* Expiration: 2025-04-12T11:06:22-05:00 */



$loaded = true;
$expiration = 1744473982;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => '\\Concrete\\Core\\Entity\\User\\User',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => NULL,
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'createdBy',
     'referencedColumnName' => 'uID',
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
