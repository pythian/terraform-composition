<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\express\form$field_sets][1]/ */
/* Type: array */
/* Expiration: 2025-04-12T21:04:02-05:00 */



$loaded = true;
$expiration = 1744509842;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'form',
     'targetEntity' => 'FieldSet',
     'cascade' =>
    array (
      0 => 'persist',
      1 => 'remove',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 =>
  \Doctrine\ORM\Mapping\OrderBy::__set_state(array(
     'value' =>
    array (
      'position' => 'ASC',
    ),
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1744125100;
