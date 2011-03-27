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

if ($this->review->id) {
	$title = JText::_('PLG_RESOURCES_REVIEWS_EDIT_YOUR_REVIEW');
} else {
	$title = JText::_('PLG_RESOURCES_REVIEWS_WRITE_A_REVIEW');
}
?>
	</div>
</div>
<div class="clear"></div>

<div class="below section">
	<h3 id="reviewform-title">
		<?php echo $title; ?>
	</h3>
	<form action="<?php echo JRoute::_('index.php?option='.$this->option.'&id='.$this->review->resource_id.'&active=reviews'); ?>" method="post" id="commentform">
		<div class="aside">
			<table class="wiki-reference" summary="Wiki Syntax Reference">
				<caption>Wiki Syntax Reference</caption>
				<tbody>
					<tr>
						<td>'''bold'''</td>
						<td><b>bold</b></td>
					</tr>
					<tr>
						<td>''italic''</td>
						<td><i>italic</i></td>
					</tr>
					<tr>
						<td>__underline__</td>
						<td><span style="text-decoration:underline;">underline</span></td>
					</tr>
					<tr>
						<td>{{{monospace}}}</td>
						<td><code>monospace</code></td>
					</tr>
					<tr>
						<td>~~strike-through~~</td>
						<td><del>strike-through</del></td>
					</tr>
					<tr>
						<td>^superscript^</td>
						<td><sup>superscript</sup></td>
					</tr>
					<tr>
						<td>,,subscript,,</td>
						<td><sub>subscript</sub></td>
					</tr>
				</tbody>
			</table>
<?php if ($this->banking) {	?>
			<p class="help"><?php echo JText::_('PLG_RESOURCES_REVIEWS_DID_YOU_KNOW_YOU_CAN'); ?> <a href="<?php echo $this->infolink; ?>"><?php echo JText::_('PLG_RESOURCES_REVIEWS_EARN_POINTS'); ?></a> <?php echo JText::_('PLG_RESOURCES_REVIEWS_FOR_REVIEWS'); ?>? <?php echo JText::_('PLG_RESOURCES_REVIEWS_EARN_POINTS_EXP'); ?></p>
<?php } ?>
		</div><!-- / .aside -->
		<div class="subject">
			<p class="comment-member-photo">
				<span class="comment-anchor"><a name="reviewform"></a></span>
<?php
			if (!$this->juser->get('guest')) {
				$jxuser = new Hubzero_User_Profile();
				$jxuser->load( $this->juser->get('id') );
				$thumb = plgResourcesReviews::getMemberPhoto($jxuser, 0);
			} else {
				$config =& JComponentHelper::getParams( 'com_members' );
				$thumb = $config->get('defaultpic');
				if (substr($thumb, 0, 1) != DS) {
					$thumb = DS.$dfthumb;
				}
				$thumb = plgResourcesReviews::thumbit($thumb);
			}
?>
				<img src="<?php echo $thumb; ?>" alt="" />
			</p>
			<fieldset>
				<input type="hidden" name="created" value="<?php echo $this->review->created; ?>" />
				<input type="hidden" name="reviewid" value="<?php echo $this->review->id; ?>" />
				<input type="hidden" name="user_id" value="<?php echo $this->review->user_id; ?>" />
				<input type="hidden" name="resource_id" value="<?php echo $this->review->resource_id; ?>" />
				<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
				<input type="hidden" name="task" value="view" />
				<input type="hidden" name="id" value="<?php echo $this->review->resource_id; ?>" />
				<input type="hidden" name="action" value="savereview" />
				<input type="hidden" name="active" value="reviews" />
				
				<fieldset>
					<legend><?php echo JText::_('PLG_RESOURCES_REVIEWS_FORM_RATING'); ?>:</legend>
					<label>
						<input class="option" id="review_rating_1" name="rating" type="radio" value="1"<?php if ($this->review->rating == 1) { echo ' checked="checked"'; } ?> /> 
						<img src="/components/<?php echo $this->option; ?>/images/stars/1.gif" alt="<?php echo JText::_('PLG_RESOURCES_REVIEWS_RATING_1_STAR'); ?>" /> 
						<?php echo JText::_('PLG_RESOURCES_REVIEWS_RATING_POOR'); ?>
					</label>
					<label>
						<input class="option" id="review_rating_2" name="rating" type="radio" value="2"<?php if ($this->review->rating == 2) { echo ' checked="checked"'; } ?> /> 
						<img src="/components/<?php echo $this->option; ?>/images/stars/2.gif" alt="<?php echo JText::_('PLG_RESOURCES_REVIEWS_RATING_2_STARS'); ?>" /> 
						<?php echo JText::_('PLG_RESOURCES_REVIEWS_RATING_FAIR'); ?>
					</label>
					<label>
						<input class="option" id="review_rating_3" name="rating" type="radio" value="3"<?php if ($this->review->rating == 3) { echo ' checked="checked"'; } ?> /> 
						<img src="/components/<?php echo $this->option; ?>/images/stars/3.gif" alt="<?php echo JText::_('PLG_RESOURCES_REVIEWS_RATING_3_STARS'); ?>" /> 
						<?php echo JText::_('PLG_RESOURCES_REVIEWS_RATING_GOOD'); ?>
					</label>
					<label>
						<input class="option" id="review_rating_4" name="rating" type="radio" value="4"<?php if ($this->review->rating == 4) { echo ' checked="checked"'; } ?> /> 
						<img src="/components/<?php echo $this->option; ?>/images/stars/4.gif" alt="<?php echo JText::_('PLG_RESOURCES_REVIEWS_RATING_4_STARS'); ?>" /> 
						<?php echo JText::_('PLG_RESOURCES_REVIEWS_RATING_VERY_GOOD'); ?>
					</label>
					<label>
						<input class="option" id="review_rating_5" name="rating" type="radio" value="5"<?php if ($this->review->rating == 5) { echo ' checked="checked"'; } ?> /> 
						<img src="/components/<?php echo $this->option; ?>/images/stars/5.gif" alt="<?php echo JText::_('PLG_RESOURCES_REVIEWS_RATING_5_STARS'); ?>" /> 
						<?php echo JText::_('PLG_RESOURCES_REVIEWS_RATING_EXCELLENT'); ?>
					</label>
				</fieldset>

				<label for="review_comments">
					<?php echo JText::_('PLG_RESOURCES_REVIEWS_FORM_COMMENTS');
		if ($this->banking) {
			echo ' ( <span class="required">'.JText::_('PLG_RESOURCES_REVIEWS_REQUIRED').'</span> '.JText::_('PLG_RESOURCES_REVIEWS_FOR_ELIGIBILITY').' <a href="'.$this->infolink.'">'.JText::_('PLG_RESOURCES_REVIEWS_EARN_POINTS').'</a> )';
		}
		?>
					<?php
					ximport('Hubzero_Wiki_Editor');
					$editor =& Hubzero_Wiki_Editor::getInstance();
					echo $editor->display('comment', 'review_comments', $this->review->comment, '', '35', '10');
					?>
				</label>

				<label id="review-anonymous-label">
					<input class="option" type="checkbox" name="anonymous" id="review-anonymous" value="1"<?php if ($this->review->anonymous != 0) { echo ' checked="checked"'; } ?> />
					<?php echo JText::_('PLG_RESOURCES_REVIEWS_FORM_ANONYMOUS'); ?>
				</label>

				<p class="submit">
					<input type="submit" value="<?php echo JText::_('PLG_RESOURCES_REVIEWS_SUBMIT'); ?>" />
				</p>
				
				<div class="sidenote">
					<p>
						<strong>Please keep comments relevant to this entry. Comments deemed inappropriate may be removed.</strong>
					</p>
					<p>
						Line breaks and paragraphs are automatically converted. URLs (starting with http://) or email addresses will automatically be linked. <a href="/topics/Help:WikiFormatting" class="popup 400x500">Wiki syntax</a> is supported.
					</p>
				</div>
			</fieldset>
		</div><!-- / .subject -->
		<div class="clear"></div>
	</form>
</div><!-- / .below section -->
