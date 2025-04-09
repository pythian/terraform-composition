<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\summary\template$categories][1]/ */
/* Type: array */
/* Expiration: 2025-04-10T17:43:12-05:00 */



$loaded = true;
$expiration = 1744324992;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToMany::__set_state(array(
     'targetEntity' => 'Category',
     'mappedBy' => NULL,
     'inversedBy' => 'templates',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinTable::__set_state(array(
     'name' => 'SummaryTemplateCategories',
     'schema' => NULL,
     'joinColumns' =>
    array (
    ),
     'inverseJoinColumns' =>
    array (
    ),
     'options' =>
    array (
    ),
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1743926008;
