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

//----------------------------------------------------------

error_reporting(E_ALL);
@ini_set('display_errors','1');

$jacl =& JFactory::getACL();
$jacl->addACL( $option, 'manage', 'users', 'super administrator' );
$jacl->addACL( $option, 'manage', 'users', 'administrator' );

// Ensure user has access to this function
$juser = & JFactory::getUser();
if (!$juser->authorize($option, 'manage')) {
	$app =& JFactory::getApplication();
	$app->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

require_once(JPATH_ROOT.DS.'components'.DS.$option.DS.'helpers'.DS.'tags.php');
require_once(JPATH_ROOT.DS.'components'.DS.$option.DS.'helpers'.DS.'date.php');
require_once(JPATH_ROOT.DS.'components'.DS.$option.DS.'helpers'.DS.'repeat.php');
require_once(JPATH_ROOT.DS.'components'.DS.$option.DS.'tables'.DS.'category.php');
require_once(JPATH_ROOT.DS.'components'.DS.$option.DS.'tables'.DS.'event.php');
require_once(JPATH_ROOT.DS.'components'.DS.$option.DS.'tables'.DS.'config.php');
require_once(JPATH_ROOT.DS.'components'.DS.$option.DS.'tables'.DS.'page.php');
require_once(JPATH_ROOT.DS.'components'.DS.$option.DS.'tables'.DS.'respondent.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'html.php');
require_once(JPATH_COMPONENT.DS.'controller.php');

// Instantiate controller
$controller = new EventsController();
$controller->execute();
$controller->redirect();
