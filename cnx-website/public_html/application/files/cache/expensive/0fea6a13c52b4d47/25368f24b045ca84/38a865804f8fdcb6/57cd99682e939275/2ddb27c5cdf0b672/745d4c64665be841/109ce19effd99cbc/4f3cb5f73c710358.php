<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendarevent$custom_summary_templates][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T07:44:13-05:00 */



$loaded = true;
$expiration = 1744289053;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToMany::__set_state(array(
     'targetEntity' => 'Concrete\\Core\\Entity\\Summary\\Template',
     'mappedBy' => NULL,
     'inversedBy' => NULL,
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinTable::__set_state(array(
     'name' => 'CalendarEventCustomSummaryTemplates',
     'schema' => NULL,
     'joinColumns' =>
    array (
      0 =>
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
    ),
     'inverseJoinColumns' =>
    array (
      0 =>
      \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
         'name' => 'template_id',
         'referencedColumnName' => 'id',
         'unique' => false,
         'nullable' => true,
         'onDelete' => NULL,
         'columnDefinition' => NULL,
         'fieldName' => NULL,
         'options' =>
        array (
        ),
      )),
    ),
     'options' =>
    array (
    ),
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1743891187;
