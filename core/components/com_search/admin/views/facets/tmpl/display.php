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
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

// No direct access.
defined('_HZEXEC_') or die();

Toolbar::title(Lang::txt('COM_SEARCH_FACETS'));
Toolbar::spacer();
Toolbar::preferences($this->option, '550');
?>

<form action="/administrator/index.php?option=com_plugins" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-select fltrt">
			<select name="filter_folder" class="inputbox" onchange="this.form.submit()">
				<option value="">- Master Type -</option>
				<?php
					foreach ($this->plugins as $plugin)
					{
					?>
						<option	value="<?php echo $plugin; ?>"><?php echo $plugin; ?></option>
				<?php
					}
				?>
			</select>
		</div>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th>
					<input type="checkbox" name="checkall-toggle" value="" title="Check All"/>
				</th>
				<th scope="col" class="title">
					<?php echo Lang::txt('COM_SEARCH_TITLE'); ?>
				</th>
				<th scope="col">
					<?php echo Lang::txt('COM_SEARCH_DESCRIPTION'); ?>
				</th>
				<th scope="col" class="priority-2">
					<?php echo Lang::txt('COM_SEARCH_ENABLED'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="12">
					<nav class="pagination">
						<ul class="list-footer">
							<li class="counter">Results 1 - 10 of 10</li>
							<li class="limit"><label for="limit">Display #</label> <select id="limit" name="limit" class="inputbox" size="1" onchange="Joomla.submitform();">
								<option value="5">5</option>
								<option value="10">10</option>
								<option value="15">15</option>
								<option value="20" selected="selected">20</option>
								<option value="25">25</option>
								<option value="30">30</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="1000">1000</option>
							</select>
							</li>
							<li class="pagination-start start"><span class="pagenav">Start</span></li>
							<li class="pagination-prev prev"><span class="pagenav">Prev</span></li>
							<li class="page"><strong>1</strong></li>
							<li class="pagination-next next"><span class="pagenav">Next</span></li>
							<li class="pagination-end end"><span class="pagenav">End</span></li>
						</ul>
						<input type="hidden" name="limitstart" value="0" />
					</nav>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<!-- Facets -->
			<?php foreach ($this->facets as $facet)
			{
			?>
				<tr>
					<td><input type="checkbox" name="ids[]" value="<?php echo $facet->id; ?>" /></td>
					<td><?php echo $facet->title; ?></td>
					<td><?php echo $facet->description; ?></td>
					<td><?php echo $facet->state; ?></td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="" autocomplete="off" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="folder" />
	<input type="hidden" name="filter_order_Dir" value="asc" />
	<input type="hidden" name="58e4bcd7eead15547ab90fda271c6640" value="1" />
</form>
