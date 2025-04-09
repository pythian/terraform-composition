<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendareventversionrepetition$repetition][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T19:38:48-05:00 */



$loaded = true;
$expiration = 1744331928;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'CalendarEventRepetition',
     'cascade' =>
    array (
      0 => 'refresh',
      1 => 'persist',
    ),
     'fetch' => 'LAZY',
     'inversedBy' => NULL,
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'repetitionID',
     'referencedColumnName' => 'repetitionID',
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
$data['createdOn'] = 1743912220;
