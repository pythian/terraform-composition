<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendarevent$summary_templates][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T04:37:16-05:00 */



$loaded = true;
$expiration = 1744277836;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'event',
     'targetEntity' => 'Concrete\\Core\\Entity\\Calendar\\Summary\\CalendarEventTemplate',
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
$data['createdOn'] = 1743891187;
