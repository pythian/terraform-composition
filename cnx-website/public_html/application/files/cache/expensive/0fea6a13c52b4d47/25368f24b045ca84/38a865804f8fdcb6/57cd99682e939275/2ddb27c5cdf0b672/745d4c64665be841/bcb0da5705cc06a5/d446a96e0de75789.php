<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\attribute\category][1]/ */
/* Type: array */
/* Expiration: 2025-04-12T02:29:23-05:00 */



$loaded = true;
$expiration = 1744442963;

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
     'name' => 'AttributeKeyCategories',
     'schema' => NULL,
     'indexes' =>
    array (
      0 =>
      \Doctrine\ORM\Mapping\Index::__set_state(array(
         'name' => 'pkgID',
         'columns' =>
        array (
          0 => 'pkgID',
          1 => 'akCategoryID',
        ),
         'fields' => NULL,
         'flags' => NULL,
         'options' => NULL,
      )),
      1 =>
      \Doctrine\ORM\Mapping\Index::__set_state(array(
         'name' => 'akCategoryHandle',
         'columns' =>
        array (
          0 => 'akCategoryHandle',
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
