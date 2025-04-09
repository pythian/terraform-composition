<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\user\user$signup][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T18:45:15-05:00 */



$loaded = true;
$expiration = 1744328715;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\OneToOne::__set_state(array(
     'targetEntity' => '\\Concrete\\Core\\Entity\\User\\UserSignup',
     'mappedBy' => 'user',
     'inversedBy' => NULL,
     'cascade' =>
    array (
      0 => 'remove',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1743897906;
