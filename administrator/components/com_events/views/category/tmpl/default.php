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
$text = ($this->task == 'editcat') ? JText::_( 'EDIT' ) : JText::_( 'NEW' );
JToolBarHelper::title( JText::_( 'EVENT' ).': <small><small>[ '. $text.' '.JText::_('EVENTS_CAL_LANG_EVENT_CATEGORY').' ]</small></small>', 'addedit.png' );
JToolBarHelper::spacer();
JToolBarHelper::save('savecat');
JToolBarHelper::spacer();
JToolBarHelper::media_manager();
JToolBarHelper::cancel('cancelcat');

if ($this->row->image == '') {
	$this->row->image = 'blank.png';
}

$editor =& JFactory::getEditor();
?>

<script language="javascript" type="text/javascript">
function submitbutton(pressbutton, section) 
{
	if (pressbutton == 'cancelcat') {
		submitform( pressbutton );
		return;
	}
	
	if (document.adminForm.name.value == ''){
		alert("Category must have a name");
	} else {
		submitform(pressbutton);
	}
}
</script>

<form action="index.php" method="post" name="adminForm">
	<fieldset class="adminform">
	<legend><?php echo $this->row->name; ?></legend>

	<table class="admintable">
	 <tr>
	  <td class="key"><?php echo JText::_('EVENTS_CAL_LANG_CATEGORY_TITLE'); ?>:</td>
	  <td colspan="2"><input type="text" name="title" value="<?php echo $this->row->title; ?>" size="50" maxlength="50" title="A short name to appear in menus" /></td>
	 </tr>
	 <tr>
	  <td class="key"><?php echo JText::_('EVENTS_CAL_LANG_CATEGORY_NAME'); ?>:</td>
	  <td colspan="2"><input type="text" name="name" value="<?php echo $this->row->name; ?>" size="50" maxlength="255" title="A long name to be displayed in headings" /></td>
	 </tr>
	 <tr>
	  <td class="key"><?php echo JText::_('EVENTS_CAL_LANG_CATEGORY_IMAGE'); ?>:</td>
	  <td><?php echo $this->imagelist; ?></td>
	  <td rowspan="4">
		<script type="text/javascript">
			if (document.forms[0].image.options.value!=''){
			  jsimg='../images/stories/' + getSelectedValue( 'adminForm', 'image' );
			} else {
			  jsimg='../images/M_images/blank.png';
			}
			document.write('<img src=' + jsimg + ' name="imagelib" width="80" height="80" border="2" alt="Preview" />');
		</script>
	  </td>
	 </tr>
	 <tr>
	  <td class="key"><?php echo JText::_('EVENTS_CAL_LANG_CATEGORY_IMAGE_POSITION'); ?>:</td>
	  <td><?php echo $this->iposlist; ?></td>
	 </tr>
	 <tr>
	  <td class="key"><?php echo JText::_('EVENTS_CAL_LANG_CATEGORY_ORDERING'); ?>:</td>
	  <td><?php echo $this->orderlist; ?></td>
	 </tr>
	 <tr>
	  <td class="key"><?php echo JText::_('EVENTS_CAL_LANG_EVENT_ACCESSLEVEL'); ?>:</td>
	  <td><?php echo $this->glist; ?></td>
	 </tr>
	 <tr>
	  <td class="key" style="vertical-align: top;"><?php echo JText::_('EVENTS_CAL_LANG_EVENT_DESCRIPTION'); ?>:</td>
	  <td colspan="2"><?php echo $editor->display('description', $this->row->description, 'auto', 'auto', '45', '10', false); ?></td>
	 </tr>
	</table>

	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="section" value="<?php echo $this->row->section; ?>" />
	<input type="hidden" name="oldtitle" value="<?php echo $this->row->title ; ?>" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="task" value="savecat" />
	</fieldset>

	<?php echo JHTML::_( 'form.token' ); ?>
</form>
