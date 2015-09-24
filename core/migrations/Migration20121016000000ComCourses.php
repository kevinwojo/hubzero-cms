<?php

use Hubzero\Content\Migration\Base;

// No direct access
defined('_HZEXEC_') or die();

/**
 * Migration script for adding courses component entry
 **/
class Migration20121016000000ComCourses extends Base
{
	public function up()
	{
		$this->addComponentEntry('courses', 'com_courses', 0);
	}
}