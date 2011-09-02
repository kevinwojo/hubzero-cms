<?php
/**
 * @package		HUBzero CMS
 * @author		Shawn Rice <zooley@purdue.edu>
 * @copyright	Copyright 2005-2009 by Purdue Research Foundation, West Lafayette, IN 47906
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 *
 * Copyright 2005-2009 by Purdue Research Foundation, West Lafayette, IN 47906.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License,
 * version 2 as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

function supportBuildRoute(&$query)
{
	$segments = array();

	if (!empty($query['view']) && strncmp($query['view'],'article',7) == 0 ) {
		unset( $query['view'] );
		unset( $query['id'] );
	}

	if (!empty($query['task'])) {
		switch ($query['task']) 
		{
			case 'delete':
			case 'download':
			case 'stats':
			case 'ticket':
			case 'tickets':
			case 'reportabuse':
				$segments[] = $query['task'];
				unset($query['task']);
				
				if (!empty($query['id'])) {
					$segments[] = $query['id'];
					unset($query['id']);
				}
				if (!empty($query['file'])) {
					$segments[] = $query['file'];
					unset($query['file']);
				}
			/*break;
			
			case 'ticket':
				if (!empty($query['id'])) {
					$segments[] = 'ticket';
					$segments[] = $query['id'];
					unset($query['task']);
					unset($query['id']);
				}
			break;
			
			case 'delete':
				if (!empty($query['id'])) {
					$segments[] = 'delete';
					$segments[] = $query['id'];
					unset($query['task']);
					unset($query['id']);
				}*/
			break;
			
			case 'feed':
				$segments[] = 'tickets';
				$segments[] = 'feed';
				unset($query['task']);
			break;
			
			default:
				$segments[] = $query['task'];
				unset($query['task']);
			break;
		}
	}

	return $segments;
}

function supportParseRoute($segments)
{
	$vars = array();

	$count = count($segments);

	if ($count == 0) {
		$vars['option'] = 'com_support';
		$vars['view'] = '';
		$vars['task'] = '';
		return $vars;
	}
	
	switch ($segments[0])
	{
		case 'report_problems':
	    	$vars['option'] = 'com_feedback';
	    	$vars['task'] = 'report';
		break;
		
		case 'tickets':
			if (isset($segments[1])) {
				$vars['task'] = 'feed';
				$vars['no_html'] = 1;
				$_GET['no_html'] = 1;
			} else {
				$vars['task'] = 'tickets';
			}
		break;
		
		case 'ticket':
		case 'delete':
		case 'reportabuse':
		default:
			$vars['task'] = (isset($segments[0])) ? $segments[0] : '';

			if (!empty($segments[1])) {
				$vars['id'] = $segments[1];
			}
			if (!empty($segments[2])) {
				$vars['file'] = $segments[2];
			}
		break;
	}

	return $vars;
}
