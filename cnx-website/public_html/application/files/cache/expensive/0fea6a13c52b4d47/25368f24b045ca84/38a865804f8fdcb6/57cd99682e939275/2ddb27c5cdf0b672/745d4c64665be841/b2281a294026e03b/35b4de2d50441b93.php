<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\attribute\value\value\addressvalue][1]/ */
/* Type: array */
/* Expiration: 2025-04-11T21:00:09-05:00 */



$loaded = true;
$expiration = 1744423209;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\Entity::__set_state(array(
     'repositoryClass' => NULL,
     'readOnly' => false,
  )),
  1 =>
  \Doctrine\ORM\Mapping\Table::__set_state(array(
     'name' => 'atAddress',
     'schema' => NULL,
     'indexes' =>
    array (
      0 =>
      \Doctrine\ORM\Mapping\Index::__set_state(array(
         'name' => 'postal_code',
         'columns' =>
        array (
          0 => 'postal_code',
        ),
         'fields' => NULL,
         'flags' => NULL,
         'options' => NULL,
      )),
    ),
     'uniqueConstraints' => NULL,
     'options' =>
    array (
    ),
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1744052195;
