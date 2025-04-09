<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\permission\ipaccesscontrolrange$expiration][1]/ */
/* Type: array */
/* Expiration: 2025-04-11T22:52:13-05:00 */



$loaded = true;
$expiration = 1744429933;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\Column::__set_state(array(
     'name' => 'iacrExpiration',
     'type' => 'datetime',
     'length' => NULL,
     'precision' => NULL,
     'scale' => NULL,
     'unique' => false,
     'nullable' => true,
     'insertable' => true,
     'updatable' => true,
     'enumType' => NULL,
     'options' =>
    array (
      'comment' => 'The date/time when this range expires (NULL means no expiration)',
    ),
     'columnDefinition' => NULL,
     'generated' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1744053795;
