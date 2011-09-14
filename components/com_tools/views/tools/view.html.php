<?php
/**
 * @package     hubzero-cms
 * @author      Nicholas J. Kisseberth <nkissebe@purdue.edu>
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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Tools Component
 */

class ToolsViewTools extends JView
{
	function display($tpl = null)
	{
		ximport('Hubzero_Document');

		$xhub  = & Hubzero_Factory::getHub();
		$model = & $this->getModel();

		// Get some vars to fill in text
		$forgeName = $xhub->getCfg('forgeName');
		$forgeURL = $xhub->getCfg('forgeURL');
		$hubShortName = $xhub->getCfg('hubShortName');
		$hubShortURL = $xhub->getCfg('hubShortURL');
		$hubLongURL = $xhub->getCfg('hubLongURL');

		// Get the tool list
		$appTools = $model->getApplicationTools();

		// Get the forge image
		$image = Hubzero_Document::getComponentImage('com_projects', 'forge.png', 1);

		$this->assignRef( 'forgeName', $forgeName );
		$this->assignRef( 'forgeURL', $forgeURL);
		$this->assignRef( 'hubShortURL', $hubShortURL );
		$this->assignRef( 'hubLongURL', $hubLongURL );
		$this->assignRef( 'hubShortName', $hubShortName );
		$this->assignRef( 'appTools', $appTools);
		$this->assignRef( 'image', $image);

        parent::display($tpl);
    }
}

