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

namespace Hubzero\Html\Parameter\Element;

use Hubzero\Html\Parameter\Element;
use Hubzero\Html\Builder;

/**
 * Renders a list element
 */
class Select extends Element
{
	/**
	 * Element type
	 *
	 * @var  string
	 */
	protected $_name = 'Select';

	/**
	 * Get the options for the element
	 *
	 * @param   object  &$node  XMLElement node object containing the settings for the element
	 * @return  array
	 */
	protected function _getOptions(&$node)
	{
		$options = array();
		foreach ($node->children() as $option)
		{
			$val  = $option['value'];
			$text = (string) $option;
			$options[] = Builder\Select::option($val, \App::get('language')->txt($text));
		}
		return $options;
	}

	/**
	 * Fetch a calendar element
	 *
	 * @param   string  $name          Element name
	 * @param   string  $value         Element value
	 * @param   object  &$node         XMLElement node object containing the settings for the element
	 * @param   string  $control_name  Control name
	 * @return  string
	 */
	public function fetchElement($name, $value, &$node, $control_name)
	{
		$ctrl = $control_name . '[' . $name . ']';
		$attribs = ' ';

		if ($v = $node['size'])
		{
			$attribs .= 'size="' . (string) $v . '"';
		}
		if ($v = $node['class'])
		{
			$attribs .= 'class="' . (string) $v . '"';
		}
		else
		{
			$attribs .= 'class="inputbox"';
		}
		if ($m = $node['multiple'])
		{
			$attribs .= 'multiple="multiple"';
			$ctrl .= '[]';
		}

		return Builder\Select::genericlist(
			$this->_getOptions($node),
			$ctrl,
			array(
				'id' => $control_name . $name,
				'list.attr' => $attribs,
				'list.select' => $value
			)
		);
	}
}
