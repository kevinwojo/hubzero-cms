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

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

jimport('joomla.event.plugin');

/**
 * Short description for 'plgSystemXFeed'
 * 
 * Long description (if any) ...
 */
class plgSystemXFeed extends JPlugin
{

	/**
	 * Short description for 'plgSystemXFeed'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown &$subject Parameter description (if any) ...
	 * @return     void
	 */
	function plgSystemXFeed(& $subject)
	{
		parent::__construct($subject, NULL);
	}

	/**
	 * Short description for 'onAfterInitialise'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	function onAfterInitialise()
	{
		$uri = $_SERVER['REQUEST_URI'];
		$bits = explode('?', $uri);
		$bit = $bits[0];
		$bi = explode('.',$bit);
		$b = end($bi);
		if ($b == strtolower('rss') || $b == strtolower('atom')) {
			$_GET['no_html'] = 1;
			$_REQUEST['no_html'] = 1;
		}
	}
}

