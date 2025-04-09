<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendareventversionoccurrence$version][1]/ */
/* Type: array */
/* Expiration: 2025-04-09T23:58:05-05:00 */



$loaded = true;
$expiration = 1744261085;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'CalendarEventVersion',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'occurrences',
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'eventVersionID',
     'referencedColumnName' => 'eventVersionID',
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
