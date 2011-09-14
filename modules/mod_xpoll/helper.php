<?php
/**
 * @package     hubzero-cms
 * @author      Shawn Rice <zooley@purdue.edu>
 * @copyright   Copyright 2005-2011 Purdue University. All rights reserved.
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
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
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class modXPoll
{
	private $attributes = array();

	//-----------
	public function __construct( $params )
	{
		$this->params = $params;
	}

	//-----------
	public function __set($property, $value)
	{
		$this->attributes[$property] = $value;
	}

	//-----------
	public function __get($property)
	{
		if (isset($this->attributes[$property])) {
			return $this->attributes[$property];
		}
	}

	//-----------
	public function display()
	{
		require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_xpoll'.DS.'tables'.DS.'poll.php' );
		require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_xpoll'.DS.'tables'.DS.'data.php' );
		require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_xpoll'.DS.'tables'.DS.'date.php' );
		require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_xpoll'.DS.'tables'.DS.'menu.php' );

		$database =& JFactory::getDBO();

		$params =& $this->params;
		$this->formid = $params->get( 'formid' );

		// Load the latest poll
		$poll = new XPollPoll( $database );
		$poll->getLatestPoll();

		// Did we get a result from the database?
		if ($poll->id && $poll->title) {
			$this->poll = $poll;

			$xpdata = new XPollData( $database );
			$this->options = $xpdata->getPollOptions( $poll->id, false );

			// Push the module CSS to the template
			ximport('Hubzero_Document');
			Hubzero_Document::addModuleStyleSheet('mod_xpoll');
		}
	}
}
