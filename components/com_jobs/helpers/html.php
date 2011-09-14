<?php
/**
 * @package     hubzero-cms
 * @author      Alissa Nedossekina <alisa@purdue.edu>
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

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined('n')) {
	define('t',"\t");
	define('n',"\n");
	define('r',"\r");
	define('br','<br />');
	define('sp','&#160;');
	define('a','&amp;');
}

class JobsHtml
{
	public function txt_unpee($pee)
	{
		$pee = str_replace("\t", '', $pee);
		$pee = str_replace('</p><p>', '', $pee);
		$pee = str_replace('<p>', '', $pee);
		$pee = str_replace('</p>', "\n", $pee);
		$pee = str_replace('<br />', '', $pee);
		$pee = trim($pee);
		return $pee;
	}

	public function confirmscreen($returnurl, $actionurl, $action='cancelsubscription')
	{
		$html  = '<div class="confirmwrap">'.n;
		$html .= t.'<div class="confirmscreen">'.n;
		$html .= t.'<p class="warning">'.JText::_('CONFIRM_ARE_YOU_SURE').' ';
		if ($action=='cancelsubscription') {
			$html .= strtolower(JText::_('SUBSCRIPTION_CANCEL_THIS'));
		} else if ($action=='withdrawapp') {
			$html .=  JText::_('APPLICATION_WITHDRAW');
		} else {
			$html .= JText::_('ACTION_PERFORM_THIS');
		}
		$yes  = strtoupper(JText::_('YES'));
		$yes .= $action=='cancelsubscription' ? ', '.JText::_('ACTION_CANCEL_IT') : '';
		$yes .= $action=='withdrawapp' ? ', '.JText::_('ACTION_WITHDRAW') : '';

		$no  = strtoupper(JText::_('NO'));
		$no .= $action=='cancelsubscription' ? ', '.JText::_('ACTION_DO_NOT_CANCEL') : '';
		$no .= $action=='withdrawapp' ? ', '.JText::_('ACTION_DO_NOT_WITHDRAW') : '';

		$html .= '?</p>'.n;
		$html .= t.'<p><span class="yes"><a href="'.$actionurl.'">'.$yes.'</a></span> <span class="no"><a href="'.$returnurl.'">'.$no.'</a></span></p>';
		$html .= t.'</div>'.n;
		$html .= '</div>'.n;

		return $html;
	}

	public function formSelect($name, $array, $value, $class='')
	{
		$out  = '<select name="'.$name.'" id="'.$name.'"';
		$out .= ($class) ? ' class="'.$class.'">'.n : '>'.n;
		foreach ($array as $avalue => $alabel)
		{
			$selected = ($avalue == $value || $alabel == $value)
					  ? ' selected="selected"'
					  : '';
			$out .= ' <option value="'.$avalue.'"'.$selected.'>'.$alabel.'</option>'.n;
		}
		$out .= '</select>'.n;
		return $out;
	}

	public function wikiHelp()
	{
		$out  = '<table class="wiki-reference" summary="Wiki Syntax Reference">'.n;
		$out .= '<caption>Wiki Syntax Reference</caption>'.n;
		$out .= '	<tbody>'.n;
		$out .= '		<tr>'.n;
		$out .= '			<td>\'\'\'bold\'\'\'</td>'.n;
		$out .= '			<td><b>bold</b></td>'.n;
		$out .= '		</tr>'.n;
		$out .= '		<tr>'.n;
		$out .= '			<td>\'\'italic\'\'</td>'.n;
		$out .= '			<td><i>italic</i></td>'.n;
		$out .= '		</tr>'.n;
		$out .= '		<tr>'.n;
		$out .= '			<td>__underline__</td>'.n;
		$out .= '			<td><span style="text-decoration:underline;">underline</span></td>'.n;
		$out .= '		</tr>'.n;
		$out .= '		<tr>'.n;
		$out .= '			<td>{{{monospace}}}</td>'.n;
		$out .= '			<td><code>monospace</code></td>'.n;
		$out .= '		</tr>'.n;
		$out .= '		<tr>'.n;
		$out .= '			<td>~~strike-through~~</td>'.n;
		$out .= '		<td><del>strike-through</del></td>'.n;
		$out .= '		</tr>'.n;
		$out .= '		<tr>'.n;
		$out .= '			<td>^superscript^</td>'.n;
		$out .= '			<td><sup>superscript</sup></td>'.n;
		$out .= '		</tr>'.n;
		$out .= '		<tr>'.n;
		$out .= '			<td>,,subscript,,</td>'.n;
		$out .= '			<td><sub>subscript</sub></td>'.n;
		$out .= '		</tr>'.n;
		$out .= '	</tbody>'.n;
		$out .= '</table>'.n;

		return $out;
	}
}

