<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Christopher Smoak <csmoak@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Components\Developer\Tables\Api\Application\Team;

/**
 * Developer application table class
 */
class Member extends \JTable
{
	/**
	 * Constructor
	 * 
	 * @param   object  $db  Database object
	 * @return  void
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__developer_application_team_members', 'id', $db);
	}

	/**
	 * Get collection of application records
	 * 
	 * @param  array  $filters Filters for querying
	 * @return array           Array of applications
	 */
	public function find($filters = array())
	{
		$sql  = "SELECT * FROM {$this->_tbl}";
		$sql .= $this->_buildQuery($filters);

		$this->_db->setQuery($sql);
		return $this->_db->loadObjectList();
	}

	/**
	 * Get count of records based on filters
	 * 
	 * @param  array  $filters  Filters for querying
	 * @return int              Record count
	 */
	public function count($filters = array())
	{
		$sql  = "SELECT COUNT(*) FROM {$this->_tbl}";
		$sql .= $this->_buildQuery( $filters );

		$this->_db->setQuery($sql);
		return $this->_db->loadResult();
	}

	/**
	 * Generic method to take an array of filters & gnerate sql
	 * 
	 * @param  array  $filters  Filters for querying
	 * @return string           SQL query
	 */
	public function _buildQuery($filters = array())
	{
		// var to hold conditions
		$where = array();
		$sql   = '';

		// created by
		if (isset($filters['application_id']))
		{
			$where[] = "application_id=" . $this->_db->quote($filters['application_id']);
		}

		// client id
		if (isset($filters['uidNumber']))
		{
			$where[] = "uidNumber=" . $this->_db->quote($filters['uidNumber']);
		}

		// if we have and conditions
		if (count($where) > 0)
		{
			$sql = " WHERE " . implode(" AND ", $where);
		}

		return $sql;
	}
}