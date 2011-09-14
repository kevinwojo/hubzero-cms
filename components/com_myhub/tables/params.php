<?php
/**
 * @package     hubzero-cms
 * @author      Shawn Rice <zooley@purdue.edu>
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

class MyhubParams extends JTable
{
	var $uid    = NULL;  // int(11)
	var $mid    = NULL;  // int(11)
	var $params = NULL;  // text

	//-----------

	public function __construct( &$db )
	{
		parent::__construct( '#__myhub_params', 'uid', $db );
	}

	public function check()
	{
		if (!$this->uid) {
			$this->setError( JText::_('ERROR_NO_USER_ID') );
			return false;
		}

		if (!$this->mid) {
			$this->setError( JText::_('ERROR_NO_MOD_ID') );
			return false;
		}

		return true;
	}

	public function loadParams( $uid=NULL, $mid=NULL )
	{
		if ($uid === NULL) {
			return false;
		}
		if ($mid === NULL) {
			return false;
		}

		$this->_db->setQuery( "SELECT * FROM $this->_tbl WHERE uid='$uid' AND mid='$mid' LIMIT 1" );
		if ($result = $this->_db->loadAssoc()) {
			return $this->bind( $result );
		} else {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
	}

	public function storeParams( $new=false )
	{
		if (!$new) {
			$this->_db->setQuery( "UPDATE $this->_tbl SET params='$this->params' WHERE uid=".$this->uid." AND mid=".$this->mid);
			if ($this->_db->query()) {
				$ret = true;
			} else {
				$ret = false;
			}
		} else {
			$this->_db->setQuery( "INSERT INTO $this->_tbl (uid,mid,params) VALUES ($this->uid,$this->mid,'$this->params')" );
			if ($this->_db->query()) {
				$ret = true;
			} else {
				$ret = false;
			}
		}
		if (!$ret) {
			$this->setError( strtolower(get_class( $this )).'::store failed <br />' . $this->_db->getErrorMsg() );
			return false;
		} else {
			return true;
		}
	}

	public function loadModule( $uid=NULL, $mid=NULL )
	{
		if ($uid === NULL) {
			return false;
		}
		if ($mid === NULL) {
			return false;
		}

		include_once( JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table'.DS.'module.php' );
		$jmodule = new JTableModule( $this->_db );

		$query = "SELECT m.*, p.params AS myparams"
				. " FROM ".$jmodule->getTableName()." AS m"
				. " LEFT JOIN $this->_tbl AS p ON m.id=p.mid AND p.uid=".$uid.""
				. " WHERE m.id='".$mid."' LIMIT 1";
		$this->_db->setQuery( $query );
		$modules = $this->_db->loadObjectList();
		if (!empty($modules)) {
			return $modules[0];
		} else {
			return false;
		}
	}
}

