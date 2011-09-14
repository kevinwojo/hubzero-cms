<?php
/**
 * HUBzero CMS
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
 *
 * @package   hubzero-cms
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2011 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_support'.DS.'tables'.DS.'acos.php' );
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_support'.DS.'tables'.DS.'aros_acos.php' );
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_support'.DS.'tables'.DS.'aros.php' );

/**
 * Short description for 'SupportACL'
 * 
 * Long description (if any) ...
 */
class SupportACL extends JObject
{

	/**
	 * Description for '_juser'
	 * 
	 * @var object
	 */
	private $_juser;

	/**
	 * Description for '_db'
	 * 
	 * @var object
	 */
	private $_db;

	/**
	 * Description for '_raw_data'
	 * 
	 * @var array
	 */
	private $_raw_data;

	/**
	 * Description for '_user_groups'
	 * 
	 * @var array
	 */
	private $_user_groups;

	/**
	 * Short description for '__construct'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	public function __construct()
	{
		$this->_juser = JFactory::getUser();
		$this->_db = JFactory::getDBO();

		$sql = "SELECT m.*, r.model AS aro_model, r.foreign_key AS aro_foreign_key, r.alias AS aro_alias, c.model AS aco_model, c.foreign_key AS aco_foreign_key
				FROM #__support_acl_aros_acos AS m 
				LEFT JOIN #__support_acl_aros AS r ON m.aro_id=r.id 
				LEFT JOIN #__support_acl_acos AS c ON m.aco_id=c.id";

		$this->_db->setQuery( $sql );
		$this->_raw_data = $this->_db->loadAssocList();

		if (!$this->_juser->get('guest')) {
			ximport('Hubzero_User_Helper');
			$this->_user_groups = Hubzero_User_Helper::getGroups( $this->_juser->get('id') );
		}
	}

	/**
	 * Short description for 'getACL'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     object Return description (if any) ...
	 */
	public function &getACL()
	{
		static $instance;

		if (!is_object($instance)) {
			$instance = new SupportACL();
		}

		return $instance;
	}

	/**
	 * Short description for 'check'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      string $action Parameter description (if any) ...
	 * @param      unknown $aco Parameter description (if any) ...
	 * @param      unknown $aco_foreign_key Parameter description (if any) ...
	 * @param      unknown $aro_foreign_key Parameter description (if any) ...
	 * @return     integer Return description (if any) ...
	 */
	public function check( $action=null, $aco=null, $aco_foreign_key=null, $aro_foreign_key=null )
	{
		$permission = 0;

		// Check if they are logged in
		if (!$aro_foreign_key && $this->_juser->get('guest')) {
			return $permission;
		}

		if ($aro_foreign_key) {
			$this->setUser($aro_foreign_key);
		}

		// Check user's groups
		if ($this->_user_groups && count($this->_user_groups) > 0) {
			foreach ($this->_user_groups as $ug)
			{
				foreach ($this->_raw_data as $line)
				{
					// Get the aco permission
					if ($line['aro_model'] == 'group'
					 && $line['aro_foreign_key'] == $ug->gidNumber
					 && $line['aco_model'] == $aco) {
						$permission = ($line['action_'.$action] > $permission || ($line['action_'.$action] < 0 && $permission == 0)) ? $line['action_'.$action] : $permission;
					}
					// Get the specific aco model permission if specified (overrides aco permission)
					if ($aco_foreign_key) {
						if ($line['aro_model'] == 'group'
						 && $line['aro_foreign_key'] == $ug->gidNumber
						 && $line['aco_model'] == $aco
						 && $line['aco_foreign_key'] == $aco_foreign_key) {
							$permission = ($line['action_'.$action] > $permission || ($line['action_'.$action] < 0 && $permission == 0)) ? $line['action_'.$action] : $permission;
						}
					}
				}
			}
		}
		$grouppermission = $permission;
		$userspecific = false;
		// Check individual
		$permission = 0;
		foreach ($this->_raw_data as $line)
		{
			// Get the aco permission
			if ($line['aro_model'] == 'user'
			 && $line['aro_foreign_key'] == $this->_juser->get('id')
			 && $line['aco_model'] == $aco) {
				if (isset($line['action_'.$action])) {
					$permission = ($line['action_'.$action] > $permission || ($line['action_'.$action] < 0 && $permission == 0)) ? $line['action_'.$action] : $permission;
					$userspecific = true;
				}
			}
			// Get the specific aco model permission if specified (overrides aco permission)
			if ($aco_foreign_key) {
				if ($line['aro_model'] == 'user'
				 && $line['aro_foreign_key'] == $this->_juser->get('id')
				 && $line['aco_model'] == $aco
				 && $line['aco_foreign_key'] == $aco_foreign_key) {
					if (isset($line['action_'.$action])) {
						$permission = ($line['action_'.$action] > $permission || ($line['action_'.$action] < 0 && $permission == 0)) ? $line['action_'.$action] : $permission;
						$userspecific = true;
					}
				}
			}
		}

		if ($userspecific) {
			return $permission;
		}

		return $grouppermission;
	}

	/**
	 * Short description for 'setUser'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $aro_foreign_key Parameter description (if any) ...
	 * @return     void
	 */
	public function setUser($aro_foreign_key=null)
	{
		if ($aro_foreign_key) {
			if ($this->_juser->get('id') != $aro_foreign_key) {
				ximport('Hubzero_User_Helper');
				$this->_juser = JUser::getInstance($aro_foreign_key);
				$this->_user_groups = Hubzero_User_Helper::getGroups( $this->_juser->get('id') );
			}
		}
	}

	/**
	 * Short description for 'setAccess'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      string $action Parameter description (if any) ...
	 * @param      unknown $aco Parameter description (if any) ...
	 * @param      unknown $permission Parameter description (if any) ...
	 * @param      unknown $aco_foreign_key Parameter description (if any) ...
	 * @param      unknown $aro_foreign_key Parameter description (if any) ...
	 * @return     void
	 */
	public function setAccess($action=null, $aco=null, $permission=null, $aco_foreign_key=null, $aro_foreign_key=null)
	{
		if ($aro_foreign_key) {
			$this->setUser($aro_foreign_key);
		}
		$set = false;
		for ($i=0, $n=count( $this->_raw_data ); $i < $n; $i++)
		{
			$line =& $this->_raw_data[$i];

			// Get the aco permission
			if ($line['aro_model'] == 'user'
			 && $line['aro_foreign_key'] == $this->_juser->get('id')
			 && $line['aco_model'] == $aco) {
				$line['action_'.$action] = $permission;
				$set = true;
			}
			// Get the specific aco model permission if specified (overrides aco permission)
			if ($aco_foreign_key) {
				if ($line['aro_model'] == 'user'
				 && $line['aro_foreign_key'] == $this->_juser->get('id')
				 && $line['aco_model'] == $aco
				 && $line['aco_foreign_key'] == $aco_foreign_key) {
					$line['action_'.$action] = $permission;
					$set = true;
				}
			}
		}
		if (!$set) {
			$l = array(
				'aro_model' => 'user',
				'aro_foreign_key' => $this->_juser->get('id'),
				'aco_model' => $aco,
				'aco_foreign_key' => $aco_foreign_key,
				'action_'.$action => $permission
			);
			array_push($this->_raw_data, $l);
		}
	}

	/**
	 * Short description for 'authorize'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $group Parameter description (if any) ...
	 * @return     boolean Return description (if any) ...
	 */
	public function authorize($group=null)
	{
		if ($group && $this->_user_groups && count($this->_user_groups) > 0) {
			foreach ($this->_user_groups as $ug)
			{
				if ($ug->cn == $group) {
					return true;
				}
			}
		}
		return false;
	}
}

