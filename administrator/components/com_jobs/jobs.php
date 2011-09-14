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

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

error_reporting(E_ALL);
@ini_set('display_errors','1');

jimport('joomla.application.component.helper');

// Include scripts
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'tables'.DS.'admin.php' );
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'tables'.DS.'application.php' );
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'tables'.DS.'category.php' );
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'tables'.DS.'employer.php' );
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'tables'.DS.'job.php' );
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'tables'.DS.'prefs.php' );
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'tables'.DS.'resume.php' );
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'tables'.DS.'seeker.php' );
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'tables'.DS.'shortlist.php' );
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'tables'.DS.'stats.php' );
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'tables'.DS.'type.php' );

include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'helpers'.DS.'html.php' );
include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$option.DS.'controller.php' );

ximport('Hubzero_User');
ximport('Hubzero_Bank');

// Initiate controller
$controller = new JobsController();
$controller->execute();
$controller->redirect();

