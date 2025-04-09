<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\express\entity$forms][1]/ */
/* Type: array */
/* Expiration: 2025-04-11T20:21:06-05:00 */



$loaded = true;
$expiration = 1744420866;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'entity',
     'targetEntity' => 'Form',
     'cascade' =>
    array (
      0 => 'persist',
      1 => 'remove',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1744052201;
