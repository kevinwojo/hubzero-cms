<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_newsfeeds
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_HZEXEC_') or die();

// Require the com_content helper library
require_once JPATH_COMPONENT.'/helpers/route.php';
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

$controller	= JControllerLegacy::getInstance('Newsfeeds');
$controller->execute(Request::getCmd('task'));
$controller->redirect();
