<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\summary\calendareventtemplate$event][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T12:19:04-05:00 */



$loaded = true;
$expiration = 1744305544;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'Concrete\\Core\\Entity\\Calendar\\CalendarEvent',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'summary_templates',
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
$data['createdOn'] = 1743906409;
