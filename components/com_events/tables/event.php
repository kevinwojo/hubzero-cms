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

/**
 * Short description for 'EventsEvent'
 * 
 * Long description (if any) ...
 */
class EventsEvent extends JTable
{

	/**
	 * Description for 'id'
	 * 
	 * @var unknown
	 */
	var $id               = NULL;

	/**
	 * Description for 'sid'
	 * 
	 * @var unknown
	 */
	var $sid              = NULL;

	/**
	 * Description for 'catid'
	 * 
	 * @var unknown
	 */
	var $catid            = NULL;

	/**
	 * Description for 'title'
	 * 
	 * @var unknown
	 */
	var $title            = NULL;

	/**
	 * Description for 'content'
	 * 
	 * @var unknown
	 */
	var $content          = NULL;

	/**
	 * Description for 'contact_info'
	 * 
	 * @var unknown
	 */
	var $contact_info     = NULL;

	/**
	 * Description for 'adresse_info'
	 * 
	 * @var unknown
	 */
	var $adresse_info     = NULL;

	/**
	 * Description for 'extra_info'
	 * 
	 * @var unknown
	 */
	var $extra_info       = NULL;

	/**
	 * Description for 'color_bar'
	 * 
	 * @var unknown
	 */
	var $color_bar        = NULL;

	/**
	 * Description for 'useCatColor'
	 * 
	 * @var unknown
	 */
	var $useCatColor      = NULL;

	/**
	 * Description for 'state'
	 * 
	 * @var unknown
	 */
	var $state            = NULL;

	/**
	 * Description for 'mask'
	 * 
	 * @var unknown
	 */
	var $mask             = NULL;

	/**
	 * Description for 'created'
	 * 
	 * @var unknown
	 */
	var $created          = NULL;

	/**
	 * Description for 'created_by'
	 * 
	 * @var unknown
	 */
	var $created_by       = NULL;

	/**
	 * Description for 'created_by_alias'
	 * 
	 * @var unknown
	 */
	var $created_by_alias = NULL;

	/**
	 * Description for 'modified'
	 * 
	 * @var unknown
	 */
	var $modified         = NULL;

	/**
	 * Description for 'modified_by'
	 * 
	 * @var unknown
	 */
	var $modified_by      = NULL;

	/**
	 * Description for 'checked_out'
	 * 
	 * @var unknown
	 */
	var $checked_out      = NULL;

	/**
	 * Description for 'checked_out_time'
	 * 
	 * @var unknown
	 */
	var $checked_out_time = NULL;

	/**
	 * Description for 'publish_up'
	 * 
	 * @var unknown
	 */
	var $publish_up       = NULL;

	/**
	 * Description for 'publish_down'
	 * 
	 * @var unknown
	 */
	var $publish_down     = NULL;

	/**
	 * Description for 'images'
	 * 
	 * @var unknown
	 */
	var $images           = NULL;

	/**
	 * Description for 'reccurtype'
	 * 
	 * @var unknown
	 */
	var $reccurtype       = NULL;

	/**
	 * Description for 'reccurday'
	 * 
	 * @var unknown
	 */
	var $reccurday        = NULL;

	/**
	 * Description for 'reccurweekdays'
	 * 
	 * @var unknown
	 */
	var $reccurweekdays   = NULL;

	/**
	 * Description for 'reccurweeks'
	 * 
	 * @var unknown
	 */
	var $reccurweeks      = NULL;

	/**
	 * Description for 'approved'
	 * 
	 * @var unknown
	 */
	var $approved         = NULL;

	/**
	 * Description for 'announcement'
	 * 
	 * @var unknown
	 */
	var $announcement     = NULL;

	/**
	 * Description for 'ordering'
	 * 
	 * @var unknown
	 */
	var $ordering         = NULL;

	/**
	 * Description for 'archived'
	 * 
	 * @var unknown
	 */
	var $archived         = NULL;

	/**
	 * Description for 'access'
	 * 
	 * @var unknown
	 */
	var $access           = NULL;

	/**
	 * Description for 'hits'
	 * 
	 * @var unknown
	 */
	var $hits             = NULL;

	/**
	 * Description for 'registerby'
	 * 
	 * @var unknown
	 */
	var $registerby       = NULL;

	/**
	 * Description for 'params'
	 * 
	 * @var unknown
	 */
	var $params           = NULL;

	/**
	 * Description for 'restricted'
	 * 
	 * @var unknown
	 */
	var $restricted       = NULL;

	/**
	 * Description for 'email'
	 * 
	 * @var unknown
	 */
	var $email            = NULL;

	/**
	 * Short description for '__construct'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown &$db Parameter description (if any) ...
	 * @return     void
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__events', 'id', $db );
	}

	/**
	 * Short description for 'check'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     boolean Return description (if any) ...
	 */
	public function check()
	{
		if (trim( $this->title ) == '') {
			$this->setError( JText::_('EVENTS_MUST_HAVE_TITLE') );
			return false;
		}
		if (trim( $this->catid ) == '' || trim( $this->catid ) == 0) {
			$this->setError( JText::_('EVENTS_MUST_HAVE_CATEGORY') );
			return false;
		}
		return true;
	}

	/**
	 * Short description for 'hit'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $oid Parameter description (if any) ...
	 * @return     void
	 */
	public function hit( $oid=NULL )
	{
		$k = $this->_tbl_key;
		if ($oid !== NULL) {
			$this->$k = intval( $oid );
		}
		$this->_db->setQuery( "UPDATE $this->_tbl SET hits=(hits+1) WHERE id=$this->id" );
		$this->_db->query();
	}

	/**
	 * Short description for 'publish'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $oid Parameter description (if any) ...
	 * @return     void
	 */
	public function publish( $oid=NULL )
	{
		if (!$oid) {
			$oid = $this->id;
		}
		$this->_db->setQuery( "UPDATE $this->_tbl SET state=1 WHERE id=$oid" );
		$this->_db->query();
	}

	/**
	 * Short description for 'unpublish'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $oid Parameter description (if any) ...
	 * @return     void
	 */
	public function unpublish( $oid=NULL )
	{
		if (!$oid) {
			$oid = $this->id;
		}
		$this->_db->setQuery( "UPDATE $this->_tbl SET state=0 WHERE id=$oid" );
		$this->_db->query();
	}

	/**
	 * Short description for 'getFirst'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     object Return description (if any) ...
	 */
	public function getFirst()
	{
		$this->_db->setQuery( "SELECT publish_up FROM $this->_tbl ORDER BY publish_up ASC LIMIT 1" );
		return $this->_db->loadResult();
	}

	/**
	 * Short description for 'getLast'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     object Return description (if any) ...
	 */
	public function getLast()
	{
		$this->_db->setQuery( "SELECT publish_down FROM $this->_tbl ORDER BY publish_down DESC LIMIT 1" );
		return $this->_db->loadResult();
	}

	/**
	 * Short description for 'getEvents'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      string $period Parameter description (if any) ...
	 * @param      array $filters Parameter description (if any) ...
	 * @return     object Return description (if any) ...
	 */
	public function getEvents( $period='month', $filters=array() )
	{
		$gid = (isset($filters['gid'])) ? $filters['gid'] : 0;

		// Build the query
		switch ($period)
		{
			case 'month':
				$select_date = $filters['select_date'];
				$select_date_fin = $filters['select_date_fin'];

				$sql = "SELECT $this->_tbl.* 
						FROM #__categories AS b, $this->_tbl
						WHERE $this->_tbl.catid = b.id 
						AND b.access <= $gid 
						AND $this->_tbl.access <= $gid 
						AND ( ((publish_up >= '$select_date%' AND publish_up <= '$select_date_fin%') 
							OR (publish_down >= '$select_date%' AND publish_down <= '$select_date_fin%') 
							OR (publish_up >= '$select_date%' AND publish_down <= '$select_date_fin%') 
							OR (publish_up <= '$select_date%' AND publish_down >= '$select_date_fin%')) 
							AND $this->_tbl.state = '1'";
				$sql .= ($filters['category'] != 0) ? " AND b.id=".$filters['category'] : "";
				$sql .= ") ORDER BY publish_up ASC";
			break;

			case 'year':
				$year = $filters['year'];

				$sql = "SELECT $this->_tbl.* FROM #__categories AS b, $this->_tbl
						WHERE $this->_tbl.catid = b.id AND b.access <= $gid AND $this->_tbl.access <= $gid
						AND publish_up LIKE '$year%' AND (publish_down >= '$year%' OR publish_down = '0000-00-00 00:00:00')
						AND $this->_tbl.state = '1'";
				$sql .= ($filters['category'] != 0) ? " AND b.id=".$filters['category'] : "";
				$sql .= " ORDER BY publish_up ASC";
				//$sql .= " LIMIT ".$filters['start'].", ".$filters['limit'];
			break;

			case 'week':
				$startdate = $filters['startdate'];
				$enddate = $filters['enddate'];

				$sql = "SELECT * FROM $this->_tbl 
					WHERE ((publish_up >= '$startdate%' AND publish_up <= '$enddate%') 
					OR (publish_down >= '$startdate%' AND publish_down <= '$enddate%') 
					OR (publish_up >= '$startdate%' AND publish_down <= '$enddate%') 
					OR (publish_down >= '$enddate%' AND publish_up <= '$startdate%')) 
					AND state = '1' ORDER BY publish_up ASC";
			break;

			case 'day':
				$select_date = $filters['select_date'];

				$sql = "SELECT $this->_tbl.* FROM #__categories AS b, $this->_tbl 
						WHERE $this->_tbl.catid = b.id AND b.access <= $gid AND $this->_tbl.access <= $gid AND 
							((publish_up >= '$select_date 00:00:00' AND publish_up <= '$select_date 23:59:59') 
							OR (publish_down >= '$select_date 00:00:00' AND publish_down <= '$select_date 23:59:59') 
							OR (publish_up <= '$select_date 00:00:00' AND publish_down >= '$select_date 23:59:59') 
							OR (publish_up >= '$select_date 00:00:00' AND publish_down <= '$select_date 23:59:59')";
				$sql .= ($filters['category'] != 0) ? " AND b.id=".$filters['category'] : "";
				$sql .= ") AND $this->_tbl.state = '1' ORDER BY publish_up ASC";
			break;
		}

		$this->_db->setQuery( $sql );
		return $this->_db->loadObjectList();
	}

	/**
	 * Short description for 'getCount'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      array $filters Parameter description (if any) ...
	 * @return     object Return description (if any) ...
	 */
	public function getCount( $filters=array() )
	{
		$query = "SELECT count(*) FROM $this->_tbl AS a";
		$where = array();
		if ($filters['catid'] > 0) {
			$where[] = "a.catid='".$filters['catid']."'";
		}
		if ($filters['search']) {
			$where[] = "LOWER(a.title) LIKE '%".$filters['search']."%'";
		}
		$query .= (count( $where )) ? " WHERE ".implode( ' AND ', $where ) : "";

		$this->_db->setQuery( $query );
		return $this->_db->loadResult();
	}

	/**
	 * Short description for 'getRecords'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      array $filters Parameter description (if any) ...
	 * @return     object Return description (if any) ...
	 */
	public function getRecords( $filters=array() )
	{
		$query = "SELECT a.*, cc.name AS category, u.name AS editor, g.name AS groupname 
				FROM $this->_tbl AS a 
				LEFT JOIN #__users AS u ON u.id = a.checked_out 
				LEFT JOIN #__groups AS g ON g.id = a.access, 
				#__categories AS cc";

		$where = array();
		if ($filters['catid'] > 0) {
			$where[] = "a.catid='".$filters['catid']."'";
		}
		if ($filters['search']) {
			$where[] = "LOWER(a.title) LIKE '%".$filters['search']."%'";
		}
		$where[] = "a.catid=cc.id";

		$query .= (count( $where )) ? " WHERE ".implode( ' AND ', $where ) : "";
		$query .= " ORDER BY a.publish_up DESC LIMIT ".$filters['start'].",".$filters['limit'];

		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
}

