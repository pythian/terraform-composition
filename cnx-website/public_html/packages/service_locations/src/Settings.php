<?php

namespace Concrete\Package\ServiceLocations\Src;
use Loader;
defined('C5_EXECUTE') or die(_("Access Denied."));
class MSettings  {
	public function saveVariable($data){
		$db=Loader::db();
		$varible = $db->GetAll('SELECT * FROM  btServiceLocationVariable ');
		if($varible !=''){
		$db->query('DELETE FROM btServiceLocationVariable;');
		}
		foreach($data as $varible){
			if($varible!=''){
			$db->query('INSERT INTO btServiceLocationVariable(vID,variable)values("","'.strtolower($varible).'")');
			}
		}
		return true;
	}
	public function getVariable(){
		$db=Loader::db();
		$varible = $db->GetAll('SELECT * FROM  `btServiceLocationVariable` ');
		return $varible;
	}
	public function getVariableName(){
		$db=Loader::db();
		$varible = $db->GetAll('SELECT `variable` FROM `btServiceLocationVariable`');
		return $varible;
	}
}?>
