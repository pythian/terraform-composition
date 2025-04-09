<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendarevent$workflow_progress_objects][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T17:02:34-05:00 */



$loaded = true;
$expiration = 1744322554;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'event',
     'targetEntity' => 'CalendarEventWorkflowProgress',
     'cascade' =>
    array (
      0 => 'remove',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'eventID',
     'referencedColumnName' => 'eventID',
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
$data['createdOn'] = 1743891187;
