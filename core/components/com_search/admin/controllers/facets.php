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

namespace Components\Search\Admin\Controllers;

use Hubzero\Component\AdminController;
use Components\Search\Models\Facet;
use stdClass;

/**
 * Search AdminController Class
 */
class Facets extends AdminController
{
	/**
	 * Display the overview
	 */
	public function displayTask()
	{
		$filter = Request::getWord('type', null);

		/** 
			state: 0 - unpublished
			       1 - published
						 2 - deleted
		**/
		$state  = Request::getInt('state', 1);

		$rows = Facet::all()
						->rows()
						->toObject();

		$enabledTypes = Event::trigger('search.onGetTypes');
		$this->view->facets = $rows;
		$this->view->plugins = $enabledTypes;
		$this->view->display();
	}

	public function editTask()
	{
		$id = Request::getInt('id',0);

		$row = Facet::oneOrFail($id)
						->row()
						->toObject();

		$enabledTypes = Event::trigger('search.onGetTypes');

		$this->view->parentTypes = $enabledTypes;
		$this->view->row = $row;
		$this->view->display();
	}
		
}
