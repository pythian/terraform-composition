<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\attribute\value\value\selectvalue$selectedoptions][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T23:52:12-05:00 */



$loaded = true;
$expiration = 1744347132;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToMany::__set_state(array(
     'targetEntity' => 'SelectValueOption',
     'mappedBy' => NULL,
     'inversedBy' => 'values',
     'cascade' =>
    array (
      0 => 'persist',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinTable::__set_state(array(
     'name' => 'atSelectOptionsSelected',
     'schema' => NULL,
     'joinColumns' =>
    array (
      0 =>
      \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
         'name' => 'avID',
         'referencedColumnName' => 'avID',
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
         'name' => 'avSelectOptionID',
         'referencedColumnName' => 'avSelectOptionID',
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
$data['createdOn'] = 1743932359;
