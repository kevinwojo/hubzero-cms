<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2011 Purdue University. All rights reserved.
 * All rights reserved.
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

/**
 * Short description for 'modLatestusage'
 * 
 * Long description (if any) ...
 */
class modLatestusage
{

	/**
	 * Description for 'attributes'
	 * 
	 * @var array
	 */
	private $attributes = array();

	//-----------


	/**
	 * Short description for '__construct'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $params Parameter description (if any) ...
	 * @return     void
	 */
	public function __construct( $params )
	{
		$this->params = $params;
	}

	//-----------


	/**
	 * Short description for '__set'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $property Parameter description (if any) ...
	 * @param      unknown $value Parameter description (if any) ...
	 * @return     void
	 */
	public function __set($property, $value)
	{
		$this->attributes[$property] = $value;
	}

	//-----------


	/**
	 * Short description for '__get'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $property Parameter description (if any) ...
	 * @return     array Return description (if any) ...
	 */
	public function __get($property)
	{
		if (isset($this->attributes[$property])) {
			return $this->attributes[$property];
		}
	}

	//-----------


	/**
	 * Short description for '_getOnlineCount'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     array Return description (if any) ...
	 */
	private function _getOnlineCount()
	{
	    $db =& JFactory::getDBO();
		$sessions = null;

		// calculate number of guests and members
		$result = array();
		$user_array = 0;
		$guest_array = 0;

		$query = "SELECT guest, usertype, client_id FROM #__session WHERE client_id = 0";
		$db->setQuery($query);
		$sessions = $db->loadObjectList();

		if ($db->getErrorNum()) {
			JError::raiseWarning( 500, $db->stderr() );
		}

		if (count($sessions)) {
			foreach ($sessions as $session)
			{
				// If guest increase guest count by 1
				if ($session->guest == 1 && !$session->usertype) {
					$guest_array++;
				}
				// If member increase member count by 1
				if ($session->guest == 0) {
					$user_array++;
				}
			}
		}

		$result['user']  = $user_array;
		$result['guest'] = $guest_array;

		return $result;
	}

	//-----------


	/**
	 * Short description for 'display'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	public function display()
	{
		$database =& JFactory::getDBO();

		$params =& $this->params;

		//$count = $this->_getOnlineCount();
		include_once( JPATH_ROOT.DS.'components'.DS.'com_usage'.DS.'usage.helper.php' );
		$udb =& UsageHelper::getUDBO();

		$this->cls = trim($params->get( 'moduleclass_sfx' ));

		if ($udb) {
			$udb->setQuery( 'SELECT value FROM summary_user_vals WHERE datetime = (SELECT MAX(datetime) FROM summary_user_vals) AND period = "12" AND colid = "1" AND rowid = "1"' );
			$this->users = $udb->loadResult();

			$udb->setQuery( 'SELECT value FROM summary_simusage_vals WHERE datetime  = (SELECT MAX(datetime) FROM summary_simusage_vals) AND period = "12" AND colid = "1" AND rowid = "2"' );
			$this->sims = $udb->loadResult();
		} else {
			$database->setQuery( "SELECT COUNT(*) FROM #__users" );
			$this->users = $database->loadResult();

			$this->sims = 0;
		}

		$database->setQuery( "SELECT COUNT(*) FROM #__resources WHERE standalone=1 AND published=1 AND access!=1 AND access!=4" );
		$this->resources = $database->loadResult();

		$database->setQuery( "SELECT COUNT(*) FROM #__resources WHERE standalone=1 AND published=1 AND access!=1 AND access!=4 AND type=7" );
		$this->tools = $database->loadResult();
	}
}

