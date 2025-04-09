<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\express\entity$attributes][1]/ */
/* Type: array */
/* Expiration: 2025-04-12T13:26:41-05:00 */



$loaded = true;
$expiration = 1744482401;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'entity',
     'targetEntity' => '\\Concrete\\Core\\Entity\\Attribute\\Key\\ExpressKey',
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
