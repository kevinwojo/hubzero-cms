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

/**
 * Wiki macro class for listing children of a page
 */
class ChildrenMacro extends WikiMacro
{
	/**
	 * Returns description of macro, use, and accepted arguments
	 *
	 * @return     array
	 */
	public function description()
	{
		$txt = array();
		$txt['wiki'] = 'Inserts an alphabetic list of all sub-pages (children) of the current page into the output. Accepts two parameters:
 * \'\'\'depth: how deep to mine for pages. Default is one level.
 * \'\'\'description: show/hide the first line of text from the page.';
		$txt['html'] = '<p>Inserts an alphabetic list of all sub-pages (children) of the current page into the output. Accepts one parameter:</p>
		<ul>
			<li><strong>depth</strong>: how deep to mine for pages. Default is one level.</li>
			<!-- <li><strong>description</strong>: show/hide the first line of text from the page</li> -->
		</ul>
		<p>Example usage: <code>[[Children(depth=3)]]</code></p>';
		return $txt['html'];
	}

	/**
	 * Generate macro output
	 *
	 * @return     string
	 */
	public function render()
	{
		$depth = 1;
		$description = 0;

		if ($this->args)
		{
			$args = explode(',', $this->args);
			if (is_array($args))
			{
				foreach ($args as $arg)
				{
					$arg = trim($arg);
					if (substr($arg, 0, 6) == 'depth=')
					{
						$bits = preg_split('#=#', $arg);
						$depth = intval(trim(end($bits)));
						continue;
					}
					if (substr($arg, 0, 12) == 'description=')
					{
						$bits = preg_split('#=#', $arg);
						$description = intval(trim(end($bits)));
						continue;
					}
				}
			}
			else
			{
				$arg = trim($args);
				if (substr($arg, 0, 6) == 'depth=')
				{
					$bits = preg_split('#=#', $arg);
					$depth = intval(trim(end($bits)));
				}
				if (substr($arg, 0, 12) == 'description=')
				{
					$bits = preg_split('#=#', $arg);
					$description = intval(trim(end($bits)));
				}
			}
		}

		$scope = ($this->scope) ? $this->scope . DS . $this->pagename : $this->pagename;

		return $this->listChildren(1, $depth, $scope);
	}

	/**
	 * List children of a page
	 *
	 * @param      integer $currentDepth How far down the tree we are
	 * @param      integer $targetDepth  How far down the tree to go
	 * @param      string  $scope        Page scope
	 * @return     string HMTL
	 */
	private function listChildren($currentDepth, $targetDepth, $scope='')
	{
		$html = '';

		if ($currentDepth > $targetDepth)
		{
			return $html;
		}

		$rows = $this->getchildren($scope);

		if ($rows)
		{
			$html = '<ul>';
			foreach ($rows as $row)
			{
				$title = ($row->title) ? $row->title : $row->pagename;

				$url  = 'index.php?option=' . $this->option;
				$url .= '&scope=' . $row->scope;
				$url .= '&pagename=' . $row->pagename;

				/*$html .= ' * ['.$url;
				$html .= ($row->title) ? ' '.stripslashes($row->title) : ' '.$row->pagename;
				$html .= ']'."\n";*/
				$html .= '<li><a href="' . $url . '">';
				$html .= ($row->title) ? stripslashes($row->title) : $row->pagename;
				$html .= '</a>';
				$html .= $this->listChildren($currentDepth + 1, $targetDepth, $row->scope . DS . $row->pagename);
				$html .= '</li>'."\n";
			}
			$html .= '</ul>';
		}
		elseif ($currentDepth == 1)
		{
			// Return error message
			//return '(TitleIndex('.$et.') failed)';
			return '<p>(No sub-pages to display)</p>';
		}

		return $html;
	}

	/**
	 * Get the children of a page
	 *
	 * @param      string $scope Page scope
	 * @return     array
	 */
	private function getChildren($scope)
	{
		// Get all pages
		$sql = "SELECT * FROM `#__wiki_page` WHERE `scope`=" . $this->_db->quote($scope) . " AND `group_cn`=" . $this->_db->quote($this->domain) . " ORDER BY pagename ASC";

		// Perform query
		$this->_db->setQuery($sql);
		return $this->_db->loadObjectList();
	}
}

