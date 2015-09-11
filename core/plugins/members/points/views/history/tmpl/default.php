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

$this->css();
?>

<h3 class="section-header">
	<?php echo Lang::txt('PLG_MEMBERS_POINTS'); ?>
</h3>

<div class="grid">
	<div class="col span-half">
		<div class="point-balance-container">
			<h4><?php echo Lang::txt('PLG_MEMBERS_POINTS_BALANCE'); ?></h4>
			<div class="point-balance">
				<strong><?php echo number_format($this->sum); ?> <span><?php echo strtolower(Lang::txt('PLG_MEMBERS_POINTS')); ?></span></strong>
				<span class="spend">( <?php echo number_format($this->funds) . ' ' . strtolower(Lang::txt('PLG_MEMBERS_POINTS_AVAILABLE')); ?> )</span>
			</div>
		</div>
	</div>
	<div class="col span-half omega">
		<p class="help">
			<strong><?php echo Lang::txt('PLG_MEMBERS_POINTS_HOW_ARE_POINTS_AWARDED'); ?></strong><br />
			<?php echo Lang::txt('PLG_MEMBERS_POINTS_AWARDED_EXPLANATION'); ?>
		</p>
	</div>
</div>

<div class="container">
	<table class="entries transactions">
		<caption><?php echo Lang::txt('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_CAPTION'); ?></caption>
		<thead>
			<tr>
				<th scope="col" class="textual-data"><?php echo Lang::txt('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_TH_DATE'); ?></th>
				<th scope="col" class="textual-data"><?php echo Lang::txt('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_TH_DESCRIPTION'); ?></th>
				<th scope="col" class="textual-data"><?php echo Lang::txt('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_TH_TYPE'); ?></th>
				<th scope="col" class="numerical-data"><?php echo Lang::txt('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_TH_AMOUNT'); ?></th>
				<th scope="col" class="numerical-data"><?php echo Lang::txt('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_TH_BALANCE'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ($this->hist) : ?>
				<?php foreach ($this->hist as $item) : ?>
					<tr>
						<td><?php echo Date::of($item->created)->toLocal(Lang::txt('DATE_FORMAT_HZ1')); ?></td>
						<td><?php echo $this->escape($item->description); ?></td>
						<td><?php echo $this->escape($item->type); ?></td>
					<?php if ($item->type == 'withdraw') : ?>
						<td class="numerical-data"><span class="withdraw">-<?php echo $this->escape($item->amount); ?></span></td>
					<?php elseif ($item->type == 'hold') : ?>
						<td class="numerical-data"><span class="hold">(<?php echo $this->escape($item->amount); ?>)</span></td>
					<?php else : ?>
						<td class="numerical-data"><span class="deposit">+<?php echo $this->escape($item->amount); ?></span></td>
					<?php endif; ?>
						<td class="numerical-data"><?php echo $this->escape($item->balance); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr><td colspan="5"><?php echo Lang::txt('PLG_MEMBERS_POINTS_NO_TRANSACTIONS'); ?></td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
