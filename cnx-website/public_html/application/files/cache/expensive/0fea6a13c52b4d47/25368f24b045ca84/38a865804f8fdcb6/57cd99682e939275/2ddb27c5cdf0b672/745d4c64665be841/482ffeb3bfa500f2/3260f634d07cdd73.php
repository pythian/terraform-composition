<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendareventversionoccurrence$occurrence][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T03:57:44-05:00 */



$loaded = true;
$expiration = 1744275464;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'CalendarEventOccurrence',
     'cascade' =>
    array (
      0 => 'persist',
    ),
     'fetch' => 'LAZY',
     'inversedBy' => NULL,
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'occurrenceID',
     'referencedColumnName' => 'occurrenceID',
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
$data['createdOn'] = 1743877382;
