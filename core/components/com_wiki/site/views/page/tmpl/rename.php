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

// No direct access.
defined('_HZEXEC_') or die();

if (!$this->sub)
{
	$this->css();
}
?>
	<header id="<?php echo ($this->sub) ? 'sub-content-header' : 'content-header'; ?>">
		<h2><?php echo $this->escape($this->title); ?></h2>
		<?php
		if (!$this->page->isStatic())
		{
			$this->view('authors', 'page')
			     ->setBasePath($this->base_path)
			     ->set('page', $this->page)
			     ->display();
		}
		?>
	</header><!-- /#content-header -->

<?php if ($this->getError()) { ?>
	<p class="error"><?php echo $this->getError(); ?></p>
<?php } ?>
<?php if ($this->message) { ?>
	<p class="passed"><?php echo $this->message; ?></p>
<?php } ?>

<?php
if ($this->page->exists())
{
	$this->view('submenu', 'page')
	     ->setBasePath($this->base_path)
	     ->set('option', $this->option)
	     ->set('controller', $this->controller)
	     ->set('page', $this->page)
	     ->set('task', $this->task)
	     ->set('sub', $this->sub)
	     ->display();
}
?>

<section class="main section">
<?php if ($this->page->isLocked() && !$this->page->access('manage')) { ?>

	<p class="warning"><?php echo Lang::txt('COM_WIKI_WARNING_NOT_AUTH_EDITOR'); ?></p>

<?php } else { ?>

	<form action="<?php echo Route::url($this->page->link('base')); ?>" method="post" id="hubForm">
		<div class="explaination">
			<p><?php echo Lang::txt('COM_WIKI_PAGENAME_EXPLANATION'); ?></p>
		</div>
		<fieldset>
			<legend><?php echo Lang::txt('COM_WIKI_CHANGE_PAGENAME'); ?></legend>

			<label for="newpagename">
				<?php echo Lang::txt('COM_WIKI_FIELD_PAGENAME'); ?>:
				<input type="text" name="newpagename" id="newpagename" value="<?php echo $this->escape($this->page->get('pagename')); ?>" size="38" />
				<span><?php echo Lang::txt('COM_WIKI_FIELD_PAGENAME_HINT'); ?></span>
			</label>

			<input type="hidden" name="oldpagename" value="<?php echo $this->escape($this->page->get('pagename')); ?>" />
			<input type="hidden" name="scope" value="<?php echo $this->escape($this->page->get('scope')); ?>" />
			<input type="hidden" name="pageid" value="<?php echo $this->escape($this->page->get('id')); ?>" />
			<input type="hidden" name="option" value="<?php echo $this->escape($this->option); ?>" />
		<?php if ($this->sub) { ?>
			<input type="hidden" name="active" value="<?php echo $this->escape($this->sub); ?>" />
			<input type="hidden" name="action" value="saverename" />
		<?php } else { ?>
			<input type="hidden" name="task" value="saverename" />
		<?php } ?>

			<?php echo Html::input('token'); ?>
		</fieldset><div class="clear"></div>

		<p class="submit">
			<input type="submit" class="btn btn-success" value="<?php echo Lang::txt('COM_WIKI_SUBMIT'); ?>" />
		</p>
	</form>

<?php } ?>
</section><!-- / .main section -->
