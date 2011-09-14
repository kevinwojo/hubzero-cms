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
 * @author    Alissa Nedossekina <alisa@purdue.edu>
 * @copyright Copyright 2005-2011 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

JToolBarHelper::title( '<a href="index.php?option=com_jobs">'.JText::_( 'Jobs Manager' ).'</a>: <small><small>[ Categories ]</small></small>', 'addedit.png' );
JToolBarHelper::addNew( 'newcat', 'New Category' );
JToolBarHelper::editList( 'editcat' );
JToolBarHelper::save( 'saveorder', 'Save Order' );
JToolBarHelper::deleteList( '', 'deletecat', 'Delete' );

?>
<h3><?php echo JText::_('Job Categories'); ?></h3>
<form action="index.php" method="post" name="adminForm">
	<table class="adminlist" summary="<?php echo JText::_('A list of job categories'); ?>">
		<thead>
			<tr>
				<th width="2%" nowrap="nowrap"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->rows );?>);" /></th>
				<th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('ID'), 'id', @$this->filters['sort_Dir'], @$this->filters['sort'] ); ?></th>
                <th width="8%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('Order'), 'ordernum', @$this->filters['sort_Dir'], @$this->filters['sort'] ); ?>
                </th>
				<th><?php echo JHTML::_('grid.sort', JText::_('Title'), 'category', @$this->filters['sort_Dir'], @$this->filters['sort'] ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="4"><?php echo $this->pageNav->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
<?php
$k = 0;
for ($i=0, $n=count( $this->rows ); $i < $n; $i++)
{
	$row =& $this->rows[$i];

?>
			<tr class="<?php echo "row$k"; ?>">
				<td><input type="checkbox" name="id[]" id="cb<?php echo $i;?>" value="<?php echo $row->id ?>" onclick="isChecked(this.checked);" /></td>
				<td class="order"><?php echo $row->id; ?></td>
                <td class="order" nowrap="nowrap">
					<input type="text" name="order[<?php echo $row->id; ?>]" size="5" value="<?php echo $row->ordernum; ?>"  class="text_area" style="text-align: center" />
                </td>
				<td><a href="index.php?option=<?php echo $this->option ?>&amp;task=editcat&amp;id[]=<?php echo $row->id; ?>"><?php echo $row->category; ?></a></td>
			</tr>
<?php
	$k = 1 - $k;
}
?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="task" value="categories" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->filters['sort']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filters['sort_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

