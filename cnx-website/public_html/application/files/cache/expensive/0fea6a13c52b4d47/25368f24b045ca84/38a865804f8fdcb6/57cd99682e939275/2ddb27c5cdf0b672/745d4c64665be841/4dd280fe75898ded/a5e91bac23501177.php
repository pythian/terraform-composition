<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\permission\ipaccesscontrolcategory$banduration][1]/ */
/* Type: array */
/* Expiration: 2025-04-12T09:32:32-05:00 */



$loaded = true;
$expiration = 1744468352;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\Column::__set_state(array(
     'name' => 'iaccBanDuration',
     'type' => 'integer',
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
      'unsigned' => true,
      'comment' => 'The duration (in seconds) of the ban when the maximum number of events occur in the time window (NULL means forever)',
    ),
     'columnDefinition' => NULL,
     'generated' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1744053795;
