<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendareventversion$repetitions][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T15:25:40-05:00 */



$loaded = true;
$expiration = 1744316740;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'version',
     'targetEntity' => 'CalendarEventVersionRepetition',
     'cascade' =>
    array (
      0 => 'all',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'versionRepetitionID',
     'referencedColumnName' => 'versionRepetitionID',
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
$data['createdOn'] = 1743899283;
