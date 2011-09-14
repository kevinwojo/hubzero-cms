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
?>
<h3 class="section-header"><a name="points"></a><?php echo JText::_('PLG_MEMBERS_POINTS'); ?></h3>
<div class="aside">
	<p id="point-balance">
		<span><?php echo JText::_('PLG_MEMBERS_POINTS_YOU_HAVE'); ?> </span> <?php echo $this->sum; ?><small> <?php echo strtolower(JText::_('PLG_MEMBERS_POINTS')); ?></small><br />
		<small style="font-size:70%; font-weight:normal">( <?php echo $this->funds; ?> <?php echo strtolower(JText::_('PLG_MEMBERS_POINTS_AVAILABLE')); ?> )</small>
	</p>
	
	<p class="help">
		<strong><?php echo JText::_('PLG_MEMBERS_POINTS_HOW_ARE_POINTS_AWARDED'); ?></strong><br />
		<?php echo JText::_('PLG_MEMBERS_POINTS_AWARDED_EXPLANATION'); ?>
	</p>
</div><!-- / .aside -->
<div class="subject">
	<table class="transactions" summary="<?php echo JText::_('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_SUMMARY'); ?>">
		<caption><?php echo JText::_('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_CAPTION'); ?></caption>
		<thead>
			<tr>
				<th scope="col"><?php echo JText::_('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_TH_DATE'); ?></th>
				<th scope="col"><?php echo JText::_('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_TH_DESCRIPTION'); ?></th>
				<th scope="col"><?php echo JText::_('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_TH_TYPE'); ?></th>
				<th scope="col" class="numerical-data"><?php echo JText::_('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_TH_AMOUNT'); ?></th>
				<th scope="col" class="numerical-data"><?php echo JText::_('PLG_MEMBERS_POINTS_TRANSACTIONS_TBL_TH_BALANCE'); ?></th>
			</tr>
		</thead>
		<tbody>
<?php 
	if ($this->hist) {
		$cls = 'even';
		foreach ($this->hist as $item)
		{
			$cls = (($cls == 'even') ? 'odd' : 'even');
?>
			<tr class="<?php echo $cls; ?>">
				<td><?php echo JHTML::_('date',$item->created, '%d %b, %Y'); ?></td>
				<td><?php echo $item->description; ?></td>
				<td><?php echo $item->type; ?></td>
<?php if ($item->type == 'withdraw') { ?>
				<td class="numerical-data"><span class="withdraw">-<?php echo $item->amount; ?></span></td>
<?php } elseif ($item->type == 'hold') { ?>
				<td class="numerical-data"><span class="hold">(<?php echo $item->amount; ?>)</span></td>
<?php } else { ?>
				<td class="numerical-data"><span class="deposit">+<?php echo $item->amount; ?></span></td>
<?php } ?>
				<td class="numerical-data"><?php echo $item->balance; ?></td>
			</tr>
<?php
		}
	} else {
?>
			<tr class="odd">
				<td colspan="5"><?php echo JText::_('PLG_MEMBERS_POINTS_NO_TRANSACTIONS'); ?></td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>
</div><!-- / .subject -->
