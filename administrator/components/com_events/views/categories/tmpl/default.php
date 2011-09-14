<?php
/**
 * @package     hubzero-cms
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
JToolBarHelper::title( JText::_( 'EVENTS_MANAGER').': <small><small>[ '.JText::_('EVENTS_CAL_LANG_EVENT_CATEGORIES').' ]</small></small>', 'addedit.png' );
JToolBarHelper::publishList('publishcat');
JToolBarHelper::unpublishList('unpublishcat');
JToolBarHelper::spacer();
JToolBarHelper::addNew('newcat');
JToolBarHelper::editList('editcat');
JToolBarHelper::deleteList('','removecat',JText::_('DELETE_CATEGORY'));

?>

<form action="index.php" method="post" name="adminForm">
	<table class="adminlist">
	 <thead>
	  <tr>
	   <th>#</th>
	   <th><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->rows );?>);" /></th>
	   <th><?php echo JText::_('EVENTS_CAL_LANG_CATEGORY_NAME'); ?></th>
	   <th><?php echo JText::_('EVENTS_CAL_LANG_CATEGORY_NUM_RECORDS'); ?></th>
	   <th><?php echo JText::_('EVENTS_CAL_LANG_CATEGORY_NUM_CHECKEDOUT'); ?></th>
	   <th><?php echo JText::_('EVENTS_E_PUBLISHING'); ?></th>
	   <th><?php echo JText::_('EVENTS_CAL_LANG_EVENT_CHECKEDOUT'); ?></th>
	   <th><?php echo JText::_('EVENTS_CAL_LANG_EVENT_ACCESS'); ?></th>
	   <th colspan="2"><?php echo JText::_('EVENTS_CAL_LANG_CATEGORY_REORDER'); ?></th>
	  </tr>
	 </thead>
	 <tfoot>
	 	<tr>
	 		<td colspan="10"><?php echo $this->pageNav->getListFooter(); ?></td>
	 	</tr>
	 </tfoot>
	 <tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->rows ); $i < $n; $i++)
	{
		$row = &$this->rows[$i];
		$class = $row->published ? 'published' : 'unpublished';
		$alt = $row->published ? 'Published' : 'Unpublished';
		$task = $row->published ? 'unpublishcat' : 'publishcat';

		if ( $row->groupname == 'Public' ) {
			$color_access = 'style="color: green;"';
		} else if ( $row->groupname == 'Special' ) {
			$color_access = 'style="color: red;"';
		} else {
			$color_access = 'style="color: black;"';
		}
	?>
	  <tr class="<?php echo "row$k"; ?>">
	   <td><?php echo $i+$this->pageNav->limitstart+1;?></td>
	   <td><?php 
	   	if ($row->checked_out && $row->checked_out != $myid) {
			?>&nbsp;<?php	
		} else {
			?><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /><?php 
		} ?></td>
	   <td width="35%"><a href="index.php?option=<?php echo $this->option; ?>&amp;task=editcat&amp;id=<?php echo $row->id;?>"><?php echo "$row->name ($row->title)"; ?></a></td>
 	   <td><?php echo $row->num; ?></td>
	   <td><?php echo $row->checked_out; ?></td>
	   <td><a class="<?php echo $class;?>" href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><span><?php echo $alt; ?></span></a></td>
	   <td><?php echo $row->editor; ?></td>
	   <td><span <?php echo $color_access;?>><?php echo $row->groupname;?></span></td>
	   <td><?php	
		if ($i > 0 || ($i+$this->pageNav->limitstart > 0)) {
			?><a href="#reorder" class="order up" onclick="return listItemTask('cb<?php echo $i;?>','orderup')" title="Move Up"><img src="images/uparrow.png" alt="Move up" /></a><?php
		} else {
			?>&nbsp;<?php 
		}
		?></td>
	   <td><?php	
		if ($i < $n-1 || $i+$this->pageNav->limitstart < $this->pageNav->total-1) {
			?><a href="#reorder" class="order down" onclick="return listItemTask('cb<?php echo $i;?>','orderdown')" title="Move Down"><img src="images/downarrow.png" alt="Move down" /></a><?php 
		} else {
			?>&nbsp;<?php
		}
		?></td>
	  </tr>
	<?php
		$k = 1 - $k;
	} // for loop 
	?>
	 </tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="section" value="<?php echo $this->section; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="chosen" value="" />
	<input type="hidden" name="boxchecked" value="0" />

	<?php echo JHTML::_( 'form.token' ); ?>
</form>
