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

jimport( 'joomla.plugin.plugin' );
JPlugin::loadLanguage( 'plg_usage_partners' );

/**
 * Short description for 'plgUsagePartners'
 * 
 * Long description (if any) ...
 */
class plgUsagePartners extends JPlugin
{

	/**
	 * Short description for 'plgUsagePartners'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown &$subject Parameter description (if any) ...
	 * @param      unknown $config Parameter description (if any) ...
	 * @return     void
	 */
	public function plgUsagePartners(&$subject, $config)
	{
		parent::__construct($subject, $config);

		// load plugin parameters
		$this->_plugin = JPluginHelper::getPlugin( 'usage', 'partners' );
		$this->_params = new JParameter( $this->_plugin->params );
	}

	/**
	 * Short description for 'onUsageAreas'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     array Return description (if any) ...
	 */
	public function onUsageAreas()
	{
		$areas = array(
			'partners' => JText::_('PLG_USAGE_PARTNERS')
		);
		return $areas;
	}

	/**
	 * Short description for 'onUsageDisplay'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      string $option Parameter description (if any) ...
	 * @param      string $task Parameter description (if any) ...
	 * @param      unknown $db Parameter description (if any) ...
	 * @param      unknown $months Parameter description (if any) ...
	 * @param      unknown $monthsReverse Parameter description (if any) ...
	 * @param      unknown $enddate Parameter description (if any) ...
	 * @return     string Return description (if any) ...
	 */
	public function onUsageDisplay( $option, $task, $db, $months, $monthsReverse, $enddate )
	{
		// Check if our task is the area we want to return results for
		if ($task) {
			if (!in_array( $task, $this->onUsageAreas() )
			 && !in_array( $task, array_keys( $this->onUsageAreas() ) )) {
				return '';
			}
		}

		// Set some vars
		$thisyear = date("Y");

		$o = UsageHelper::options( $db, $enddate, $thisyear, $monthsReverse, 'check_for_regiondata' );

		// Build HTML
		$html  = '<form method="post" action="'. JRoute::_('index.php?option='.$option.'&task='.$task) .'">'."\n";
		$html .= "\t".'<fieldset class="filters">'."\n";
		$html .= "\t\t".'<label>'."\n";
		$html .= "\t\t\t".JText::_('PLG_USAGE_SHOW_DATA_FOR').': '."\n";
		$html .= "\t\t\t".'<select name="selectedPeriod" id="selectedPeriod">'."\n";
		$html .= $o;
		$html .= "\t\t\t".'</select>'."\n";
		$html .= "\t\t".'</label> <input type="submit" value="'.JText::_('PLG_USAGE_VIEW').'" />'."\n";
		$html .= "\t".'</fieldset>'."\n";
		$html .= '</form>'."\n";
		$html .= UsageHelper::toplist($db, 24, 1, $enddate);
		$html .= UsageHelper::toplist($db, 22, 2, $enddate);
		$html .= UsageHelper::toplist($db, 26, 3, $enddate);
		$html .= UsageHelper::toplist($db, 25, 4, $enddate);
		$html .= UsageHelper::toplist($db, 27, 5, $enddate);
		$html .= UsageHelper::toplist($db, 23, 6, $enddate);
		$html .= UsageHelper::toplist($db, 21, 7, $enddate);
		$html .= UsageHelper::toplist($db, 20, 8, $enddate);

		// Return HTML
		return $html;
	}
}
?>