<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

// No direct access
defined('_HZEXEC_') or die();

$text = ($this->task == 'edit' ? Lang::txt('JACTION_EDIT') : Lang::txt('JACTION_CREATE'));

Toolbar::title(Lang::txt('COM_BLOG_TITLE') . ': ' . $text, 'blog');
Toolbar::apply();
Toolbar::save();
Toolbar::spacer();

Toolbar::cancel();
Toolbar::spacer();
Toolbar::help('entry');
?>
<script type="text/javascript">
Joomla.submitbutton = function(pressbutton) {
	var form = document.adminForm;

	if (pressbutton == 'cancel') {
		Joomla.submitform(pressbutton, document.getElementById('item-form'));
		return;
	}

	<?php echo $this->editor()->save('text'); ?>

	// do field validation
	if ($('#field-title').val() == ''){
		alert("<?php echo Lang::txt('COM_BLOG_ERROR_MISSING_TITLE'); ?>");
	} else if ($('#field-content').val() == ''){
		alert("<?php echo Lang::txt('COM_BLOG_ERROR_MISSING_CONTENT'); ?>");
	} else {
		Joomla.submitform(pressbutton, document.getElementById('item-form'));
	}
}
</script>

<form action="<?php echo Route::url('index.php?option=' . $this->option . '&controller=' . $this->controller); ?>" method="post" name="adminForm" class="editform" id="item-form">
	<div class="grid">
		<div class="col span12">
			<fieldset class="adminform">
				<legend><span><?php echo Lang::txt('JDETAILS'); ?></span></legend>

				<div class="input-wrap" data-hint="<?php echo Lang::txt('COM_SEARCH_FACET_TITLE_HINT'); ?>">
					<label for="field-title"><?php echo Lang::txt('COM_SEARCH_FACET_TITLE'); ?>:</label><br />
						<input type="text" name="title" id="field-title" value="<?php echo $this->row->title; ?>" />
				</div><!-- /.input-wrap -->

				<div class="input-wrap" data-hint="<?php echo Lang::txt('COM_SEARCH_FACET_DESCRIPTION_HINT'); ?>">
					<label for="field-description"><?php echo Lang::txt('COM_SEARCH_FACET_DESCRIPTION'); ?>:</label><br />
						<input type="text" name="description" id="field-description" value="<?php echo $this->row->description; ?>" />
				</div><!-- /.input-wrap -->

				<div class="input-wrap" data-hint="<?php echo Lang::txt('COM_SEARCH_FACET_GROUP_HINT'); ?>">
					<label for="field-description"><?php echo Lang::txt('COM_SEARCH_FACET_GROUP'); ?>:</label><br />
						<select name="group" id="field-group">
						<?php foreach ($this->parentTypes as $type)
						{
						?>
							<option value="<?php echo $type; ?>"><?php echo $type; ?></option>
						<?php
						}
						?>
						</select>
				</div><!-- /.input-wrap -->

				<div classs="grid">
					<div class="col span2">
						<div class="input-wrap" data-hint="<?php echo Lang::txt('COM_SEARCH_FACET_DESCRIPTION_HINT'); ?>">
							<label for="field-description"><?php echo Lang::txt('COM_SEARCH_FACET_DESCRIPTION'); ?>:</label><br />
								<input type="text" name="description" id="field-description" value="<?php echo $this->row->description; ?>" />
						</div><!-- /.input-wrap -->
					</div><!-- /.col .span6 -->
				</div><!-- /.grid -->

			</fieldset>
		</div>
	</div>

	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
	<input type="hidden" name="task" value="save" />
	<?php echo Html::input('token'); ?>
</form>

