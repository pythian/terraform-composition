<?php
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\attribute\value\value\selectvalueoption$list][1]/ */
/* Type: array */
/* Expiration: 2025-04-09T21:10:04-05:00 */



$loaded = true;
$expiration = 1744251004;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 =>
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'SelectValueOptionList',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'options',
  )),
  1 =>
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'avSelectOptionListID',
     'referencedColumnName' => 'avSelectOptionListID',
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
$data['createdOn'] = 1743840015;
