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

$config = JFactory::getConfig();

if ($config->getValue('config.debug')) {
	error_reporting(E_ALL);
	@ini_set('display_errors','1');
}

jimport('joomla.application.component.helper');
ximport('Hubzero_View_Helper_Html');

require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'tags.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'comment.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'article.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'category.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'vote.php' );
require_once( JPATH_COMPONENT.DS.'controller.php' );

// Instantiate controller
$controller = new KbController();
$controller->execute();
$controller->redirect();

