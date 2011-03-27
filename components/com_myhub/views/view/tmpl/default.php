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

?>
<div id="content-header">
	<h2><?php echo $this->title; ?></h2>
</div>
<?php if (!$this->config->get('allow_customization')) { ?>
<div id="content-header-extra">
	<ul id="useroptions">
<?php if ($this->act == 'customize') { ?>
		<li class="last"><a id="personalize" href="<?php echo JRoute::_('index.php?option='.$this->option); ?>" title="<?php echo JText::_('COM_MYHUB_FINISH_PERSONALIZE_TITLE'); ?>"><?php echo JText::_('COM_MYHUB_FINISH_PERSONALIZE'); ?></a></li>
<?php } else { ?>
		<li class="last"><a id="personalize" href="<?php echo JRoute::_('index.php?option='.$this->option.'&act=customize'); ?>" title="<?php echo JText::_('COM_MYHUB_PERSONALIZE_TITLE'); ?>"><?php echo JText::_('COM_MYHUB_PERSONALIZE'); ?></a></li>
<?php } ?>
	</ul>
</div><!-- / #content-header-extra -->
<?php } ?>
<div class="main section">
	<table id="droppables" summary="<?php echo JText::_('COM_MYHUB_MY_MODULES'); ?>">
		<tbody>
			<tr>
<?php
// Initialize customization abilities
if ($this->act == 'customize' && $this->config->get('allow_customization') != 1) {
?>
				<td id="modules-dock">
					<form action="<?php echo JRoute::_('index.php?option='.$this->option); ?>" method="post" name="mysettings" id="cpnlc">
						<input type="hidden" name="uid" id="uid" value="<?php echo $this->juser->get('id'); ?>" />
						<input type="hidden" name="serials" id="serials" value="<?php echo $this->usermods[0].';'.$this->usermods[1].';'.$this->usermods[2]; ?>" />
						<h3><?php echo JText::_('COM_MYHUB_MODULES'); ?></h3>
						<p><?php echo JText::_('COM_MYHUB_MODULE_INSTRUCTIONS'); ?></p>
						<div id="available">
							<?php
							// Instantiate a view
							$view = new JView( array('name'=>'view','layout'=>'modulelist') );
							$view->modules = $this->availmods;
							$view->display();
							?>
						</div>
						<div class="clear"></div>
					</form>
					<p class="undo"><a href="<?php echo JRoute::_('index.php?option='.$this->option.'&act=customize&task=restore'); ?>"><?php echo JText::_('COM_MYHUB_RESTORE_SETTINGS'); ?></a></p>
				</td>
<?php } else { ?>
				<input type="hidden" name="uid" id="uid" value="<?php echo $this->juser->get('id'); ?>" />
<?php } ?>
<?php
// Loop through each column and output modules assigned to each one
for ($c = 0; $c < count($this->columns); $c++)
{
?>
				<td class="sortable" id="sortcol_<?php echo $c; ?>">
					<?php echo $this->columns[$c]; ?>
				</td>
<?php
}
?>
			</tr>
		</tbody>
	</table>
</div><!-- / .main section -->
