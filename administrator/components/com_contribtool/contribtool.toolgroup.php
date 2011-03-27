<?php
/**
 * @package     hubzero-cms
 * @author      Alissa Nedossekina <alisa@purdue.edu>
 * @copyright   Copyright 2005-2011 Purdue University. All rights reserved.
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 *
 * Copyright 2005-2011 Purdue University. All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

//----------------------------------------------------------
// Extended database class
//----------------------------------------------------------

class ToolGroup extends  JTable
{
	var $cn      	   = NULL;  // @var varchar (255)
	var $toolid        = NULL;  // @var int (11)
	var $role      	   = NULL;  // @var tinyint(2)
	
	
	//-----------

	public function __construct( &$db ) 
	{
		parent::__construct( '#__tool_groups', 'cn', $db );
	}
	
	//-----------
	
	public function check() 
	{
		
		if (!$this->cn) {
			$this->setError( JText::_('CONTRIBTOOL_ERROR_GROUP_NO_CN') );
			return false;
		}

		if (!$this->toolid) {
			$this->setError( JText::_('CONTRIBTOOL_ERROR_GROUP_NO_ID') );
			return false;
		}
		
		return true;
	}
	//-----------

	public function save($cn, $toolid, $role) 
	{
		$query = "INSERT INTO $this->_tbl (cn, toolid, role) VALUES ('".$cn."','".$toolid."','".$role."')";
		$this->_db->setQuery( $query );
		$this->_db->query();
	}
	
	//-----------
	
	public function saveGroup($toolid=NULL, $devgroup, $members, $exist)
	{
		ximport('Hubzero_Group');
		
		if (!$toolid or !$devgroup) {
			return false;
		}
		
		$members = ContribtoolHelper::transform($members, 'uidNumber');
		$group = new Hubzero_Group();

		if(Hubzero_Group::exists($devgroup)) {
			$group->select($devgroup);		
			$existing_members = ContribtoolHelper::transform(Tool::getToolDevelopers($toolid), 'uidNumber');
			$group->set('members',$existing_members);
			$group->set('managers',$existing_managers);
		}
		else {
			$group->create();
			$group->set('type', 2 );
			$group->set('published', 1 );
			$group->set('access', 4 );
			$group->set('description', 'Dev group for tool '.$toolid );
			$group->set('cn', $devgroup );
			$group->set('members',$existing_members);
			$group->set('managers',$existing_managers);
		}		

		$group->update();

		if(!$exist) { $this->save($devgroup, $toolid, '1'); }
		
		return true;
	
	}
	//-----------
	
	public function saveMemberGroups($toolid=NULL, $newgroups, $editversion='dev', $membergroups=array())
	{
		ximport('Hubzero_Tool');
		ximport('Hubzero_Group');

		if (!$toolid) {
			return false;
		}

		$membergroups = Hubzero_Tool::getToolGroups($toolid);
		$membergroups = ContribtoolHelper::transform($membergroups, 'cn');
		$newgroups = ContribtoolHelper::transform($newgroups, 'cn');
		$to_delete = array_diff($membergroups, $newgroups);
		
		if(count($to_delete) > 0 && $editversion!='current' ) {		
			foreach($to_delete as $del) {
				$query = "DELETE FROM $this->_tbl WHERE cn='". $del."' AND toolid='".$toolid."' AND role=0";
				$this->_db->setQuery( $query );
				$this->_db->query();
			}
		}
		
		if(count($newgroups) > 0) {
			foreach($newgroups as $newgroup) {
				if(Hubzero_Group::exists($newgroup) && !in_array($newgroup, $membergroups)) {
					// create an entry in tool_groups table
					$this->save($newgroup, $toolid, '0');
				
				}
			}
		}
		
		return true;
	
	}
	
	
	//-----------
	
	public function writeMemberGroups($new, $id, $database, &$err='') 
	{
		ximport('Hubzero_Group');
		
		$toolhelper = new ContribtoolHelper();
		
		$groups 	= is_array($new) ? $new : $toolhelper->makeArray($new);
		$grouplist 	= array();
		$invalid	= '';
		$i = 0;
		
		if(count($groups) > 0) {
			 foreach($groups as $group) {
			 	if(Hubzero_Group::exists($group)) {
					if($id) { $grouplist[$i]->cn = $group; }
					else { $grouplist[$i] = $group; }
					$i++;
				}
				else {
				 	$err = 	JText::_('CONTRIBTOOL_ERROR_GROUP_DOES_NOT_EXIST');
					$invalid .= ' '.$group.';';
				}
			 }
		}
		if($err) { $err.= $invalid; }
				
		return $grouplist;
	
	}
	
	//-----------
	
	public function writeTeam($new, $id, $database, &$err='') {
	
		
		$toolhelper = new ContribtoolHelper();
		
		$members 	= is_array($new) ? $new : $toolhelper->makeArray($new);
		$teamlist	= array();
		$invalid	= '';
		$i = 0;
		
		if(count($members) > 0) {
			 foreach($members as $member) {
			 	$juser =& JUser::getInstance ($member);
			 	if(is_object($juser)) {
					if($id) { $teamlist[$i]->uidNumber = $juser->get('id'); }
					else { $teamlist[$i] = $juser->get('id'); }
					$i++;
				}
				else {
				 	$err = JText::_('CONTRIBTOOL_ERROR_LOGIN_DOES_NOT_EXIST');
					$invalid .= ' '.$member.';';
				}
			 }
		}
		if($err) { $err.= $invalid; }
					
		return $teamlist;
	
	}
	
	//-----------

}


?>

