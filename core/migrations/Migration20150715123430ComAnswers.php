<?php

use Hubzero\Content\Migration\Base;

// No direct access
defined('_HZEXEC_') or die();

/**
 * Migration script for allowing registered users to create questions by default.
 **/
class Migration20150715123430ComAnswers extends Base
{
	/**
	 * Up
	 **/
	public function up()
	{
		if ($this->db->tableExists('#__assets'))
		{
			$rules = '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"2":1},"core.delete":[],"core.edit":{"2":1},"core.edit.state":[],"core.edit.own":[]}';

			$query = "SELECT id FROM `#__assets` WHERE `name` = 'com_answers' LIMIT 1";
			$this->db->setQuery($query);
			$id = $this->db->loadResult();

			if (!$id)
			{
				include_once(PATH_CORE . DS . 'libraries' . DS . 'joomla' . DS . 'database' . DS . 'table' . DS . 'asset.php');

				$tbl = new \JTableAsset($this->db);
				$tbl->level  = 1;
				$tbl->parent = 1;
				$tbl->name   = 'com_answers';
				$tbl->title  = 'com_answers';
				$tbl->rules  = $rules;
				$tbl->check();
				$tbl->store();
			}
			else
			{
				// Set the first zone as default
				$query = "UPDATE `#__assets` SET `rules` = " . $this->db->quote($rules) . " WHERE `id` = " . $this->db->quote($id);
				$this->db->setQuery($query);
				$this->db->query();
			}
		}
	}

	/**
	 * Down
	 **/
	public function down()
	{
		if ($this->db->tableExists('#__assets'))
		{
			$rules = '{"core.admin":{"7":1},"core.manage":{"6":1},"core.view":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":[]}';

			$query = "SELECT id FROM `#__assets` WHERE `name` = 'com_answers' LIMIT 1";
			$this->db->setQuery($query);
			$id = $this->db->loadResult();

			if (!$id)
			{
				include_once(PATH_CORE . DS . 'libraries' . DS . 'joomla' . DS . 'database' . DS . 'table' . DS . 'asset.php');

				$tbl = new \JTableAsset($this->db);
				$tbl->level  = 1;
				$tbl->parent = 1;
				$tbl->name   = 'com_answers';
				$tbl->title  = 'com_answers';
				$tbl->rules  = $rules;
				$tbl->check();
				$tbl->store();
			}
			else
			{
				// Set the first zone as default
				$query = "UPDATE `#__assets` SET `rules` = " . $this->db->quote($rules) . " WHERE `id` = " . $this->db->quote($id);
				$this->db->setQuery($query);
				$this->db->query();
			}
		}
	}
}