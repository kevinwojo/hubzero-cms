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

ximport('Hubzero_Controller');

/**
 * Short description for 'ContributeController'
 * 
 * Long description (if any) ...
 */
class ContributeController extends Hubzero_Controller
{

	/**
	 * Short description for 'execute'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	public function execute()
	{
		$this->steps = array('Type','Compose','Attach','Authors','Tags','Review');

		// Load the com_resources component config
		$config =& JComponentHelper::getParams( 'com_resources' );
		$this->config = $config;

		// Get the task at hand
		$this->_task = JRequest::getVar( 'task', '' );
		$this->step = JRequest::getInt( 'step', 0 );
		if ($this->step && !$this->_task) {
			$this->_task = 'start';
		}

		if ($this->juser->get('guest')) {
			$this->_task = ($this->_task) ? 'login' : '';
		}

		// Push some styles to the template
		$this->_getStyles();

		// Push some scripts to the template
		$this->_getScripts();

		// Build the title
		$this->_buildTitle();

		// Build the pathway
		$this->_buildPathway();

		// Execute the task
		switch ($this->_task)
		{
			case 'rename':       $this->attach_rename();  break;
			case 'saveattach':   $this->attach_save();    break;
			case 'deleteattach': $this->attach_delete();  break;
			case 'attach':       $this->attachments();    break;
			case 'orderupa':     $this->reorder_attach(); break;
			case 'orderdowna':   $this->reorder_attach(); break;

			case 'saveauthor':   $this->author_save();    break;
			case 'removeauthor': $this->author_remove();  break;
			case 'updateauthor': $this->author_update();  break;
			case 'authors':      $this->authors();        break;
			case 'orderupc':     $this->reorder_author(); break;
			case 'orderdownc':   $this->reorder_author(); break;

			/*case 'new':     $this->edit();   break;
			case 'edit':    $this->edit();   break;*/
			case 'save':    $this->save();   break;
			case 'submit':  $this->submit(); break;
			case 'delete':  $this->delete(); break;
			case 'cancel':  $this->delete(); break;
			case 'discard': $this->delete(); break;
			case 'retract': $this->retract(); break;

			case 'start':   $this->steps();  break;
			case 'login':   $this->login();  break;

			default: $this->intro(); break;
		}
	}

	/**
	 * Short description for '_buildPathway'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	protected function _buildPathway()
	{
		$app =& JFactory::getApplication();
		$pathway =& $app->getPathway();

		if (count($pathway->getPathWay()) <= 0) {
			$pathway->addItem(
				JText::_(strtoupper($this->_option)),
				'index.php?option='.$this->_option
			);
		}
		if ($this->_task) {
			$pathway->addItem(
				JText::_(strtoupper($this->_option).'_'.strtoupper($this->_task)),
				'index.php?option='.$this->_option.'&task='.$this->_task
			);
		}
		if ($this->step) {
			$pathway->addItem(
				JText::sprintf('COM_CONTRIBUTE_STEP_NUMBER', $this->step).': '.JText::_('COM_CONTRIBUTE_STEP_'.strtoupper($this->steps[$this->step])),
				'index.php?option='.$this->_option.'&task='.$this->_task.'&step='.$this->step
			);
		}
	}

	/**
	 * Short description for '_buildTitle'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	protected function _buildTitle()
	{
		$this->_title = JText::_(strtoupper($this->_option));
		if ($this->_task) {
			$this->_title .= ': '.JText::_(strtoupper($this->_option).'_'.strtoupper($this->_task));
		}
		if ($this->step) {
			$this->_title .= ': '.JText::sprintf('COM_CONTRIBUTE_STEP_NUMBER', $this->step).': '.JText::_('COM_CONTRIBUTE_STEP_'.strtoupper($this->steps[$this->step]));
		}

		$document =& JFactory::getDocument();
		$document->setTitle( $this->_title );
	}

	//----------------------------------------------------------
	// Views
	//----------------------------------------------------------

	/**
	 * Short description for 'login'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	protected function login()
	{
		// Instantiate a view
		$view = new JView( array('name'=>'login') );
		$view->option = $this->_option;
		$view->title = $this->_title;
		if ($this->getError()) {
			$view->setError( $this->getError() );
		}
		$view->display();
	}

	/**
	 * Short description for 'intro'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	protected function intro()
	{
		// Output HTML
		$view = new JView( array('name'=>'summary') );
		$view->option = $this->_option;
		$view->title = $this->_title;
		if ($this->getError()) {
			$view->setError( $this->getError() );
		}
		$view->display();
	}

	/**
	 * Short description for 'check_progress'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     void
	 */
	protected function check_progress($id)
	{
		$steps = $this->steps;
		$laststep = (count($steps) - 1);
		$stepchecks = array();

		$progress['submitted'] = 0;
		for ($i=1, $n=count( $steps ); $i < $n; $i++)
		{
			$check = 'step_'.$steps[$i].'_check';
			$stepchecks[$steps[$i]] = $this->$check( $id );

			if ($stepchecks[$steps[$i]]) {
				$progress[$steps[$i]] = 1;
				if ($i == $laststep) {
					$progress['submitted'] = 1;
				}
			} else {
				$progress[$steps[$i]] = 0;
			}
		}
		$this->progress = $progress;
	}

	/**
	 * Short description for 'steps'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	protected function steps()
	{
		$steps = $this->steps;
		$step  = $this->step;
		if ($step > count($steps)) {
			$step = count($steps);
		}

		$pre = ($step > 0) ? $step - 1 : 0;
		$preprocess = 'step_'.strtolower($steps[$pre]).'_process';
		$activestep = 'step_'.strtolower($steps[$step]);

		if (isset($_POST['step'])) {
			$this->$preprocess();
		}

		if (!$this->getError()) {
			$id = JRequest::getInt( 'id', 0 );

			$this->check_progress($id);

			$this->$activestep();
		}
	}

	//----------------------------------------------------------
	// Steps
	//----------------------------------------------------------

	/**
	 * Short description for 'step_type'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	protected function step_type()
	{
		$step = $this->step;
		$step++;

		// Get available resource types
		$rt = new ResourcesType( $this->database );
		$types = $rt->getMajorTypes();

		// Output HTML
		$view = new JView( array('name'=>'steps','layout'=>'type') );
		$view->option = $this->_option;
		$view->title = $this->_title;
		$view->step = $step;
		$view->steps = $this->steps;
		$view->types = $types;
		if ($this->getError()) {
			$view->setError( $this->getError() );
		}
		$view->display();
	}

	/**
	 * Short description for 'step_compose'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      object $row Parameter description (if any) ...
	 * @return     void
	 */
	protected function step_compose($row=null)
	{
		$xhub = Hubzero_Factory::getHub();

		$type = JRequest::getVar( 'type', '' );

		if ($type == '7') {
			$xhub->redirect(JRoute::_('index.php?option=com_contribtool&task=create'));
		}

		$step = $this->step;
		$next_step = $step+1;

		// Incoming
		$id = JRequest::getInt( 'id', 0 );

		if (!is_object($row))
		{
			// Instantiate a new resource object
			$row = new ResourcesResource( $this->database );
			if ($id) {
				// Load the resource
				$row->load( $id );
			} else {
				// Load the type and set the state
				$row->type = JRequest::getVar( 'type', '' );
				$row->published = 2;
			}
		}

		// Output HTML
		$view = new JView( array('name'=>'steps','layout'=>'compose') );
		$view->option = $this->_option;
		$view->title = $this->_title;
		$view->step = $step;
		$view->steps = $this->steps;
		$view->row = $row;
		$view->config = $this->config;
		$view->next_step = $next_step;
		$view->database = $this->database;
		$view->id = $id;
		$view->progress = $this->progress;
		$view->task = 'start';
		if ($this->getError()) {
			foreach ($this->getErrors() as $error)
			{
				$view->setError($error);
			}
		}
		$view->display();
	}

	/**
	 * Short description for 'step_attach'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function step_attach()
	{
		$step = $this->step;
		$next_step = $step+1;

		// Incoming
		$id = JRequest::getInt( 'id', 0 );

		// Ensure we have an ID to work with
		if (!$id) {
			JError::raiseError( 500, JText::_('COM_CONTRIBUTE_NO_ID') );
			return;
		}

		// Load the resource
		$row = new ResourcesResource( $this->database );
		$row->load( $id );

		// Output HTML
		$view = new JView( array('name'=>'steps','layout'=>'attach') );
		$view->option = $this->_option;
		$view->title = $this->_title;
		$view->step = $step;
		$view->steps = $this->steps;
		$view->row = $row;
		$view->config = $this->config;
		$view->next_step = $next_step;
		$view->database = $this->database;
		$view->id = $id;
		$view->progress = $this->progress;
		$view->task = 'start';
		if ($this->getError()) {
			foreach ($this->getErrors() as $error)
			{
				$view->setError($error);
			}
		}
		$view->display();
	}

	/**
	 * Short description for 'step_authors'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function step_authors()
	{
		$step = $this->_data['step'];
		$next_step = $step+1;

		// Incoming
		$id = JRequest::getInt( 'id', 0 );

		// Ensure we have an ID to work with
		if (!$id) {
			JError::raiseError( 500, JText::_('COM_CONTRIBUTE_NO_ID') );
			return;
		}

		// Load the resource
		$row = new ResourcesResource( $this->database );
		$row->load( $id );

		// Get groups
		ximport('Hubzero_User_Profile');
		$profile = Hubzero_User_Profile::getInstance($this->juser->get('id'));
		$groups = $profile->getGroups('members');

		// Output HTML
		$view = new JView( array('name'=>'steps','layout'=>'authors') );
		$view->option = $this->_option;
		$view->title = $this->_title;
		$view->step = $step;
		$view->steps = $this->steps;
		$view->row = $row;
		$view->groups = $groups;
		$view->next_step = $next_step;
		$view->database = $this->database;
		$view->id = $id;
		$view->progress = $this->progress;
		$view->task = 'start';
		if ($this->getError()) {
			foreach ($this->getErrors() as $error)
			{
				$view->setError($error);
			}
		}
		$view->display();
	}

	/**
	 * Short description for 'step_tags'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function step_tags()
	{
		$step = $this->step;
		$next_step = $step+1;

		// Incoming
		$id = JRequest::getInt( 'id', 0 );

		// Ensure we have an ID to work with
		if (!$id) {
			JError::raiseError( 500, JText::_('COM_CONTRIBUTE_NO_ID') );
			return;
		}

		// Get any HUB focus areas
		// These are used where any resource is required to have one of these tags
		$tconfig =& JComponentHelper::getParams( 'com_tags' );
		$fa1 = $tconfig->get('focus_area_01');
		$fa2 = $tconfig->get('focus_area_02');
		$fa3 = $tconfig->get('focus_area_03');
		$fa4 = $tconfig->get('focus_area_04');
		$fa5 = $tconfig->get('focus_area_05');
		$fa6 = $tconfig->get('focus_area_06');
		$fa7 = $tconfig->get('focus_area_07');
		$fa8 = $tconfig->get('focus_area_08');
		$fa9 = $tconfig->get('focus_area_09');
		$fa10 = $tconfig->get('focus_area_10');

		// Instantiate our tag object
		$tagcloud = new ResourcesTags($this->database);

		// Normalize the focus areas
		$tagfa1 = $tagcloud->normalize_tag($fa1);
		$tagfa2 = $tagcloud->normalize_tag($fa2);
		$tagfa3 = $tagcloud->normalize_tag($fa3);
		$tagfa4 = $tagcloud->normalize_tag($fa4);
		$tagfa5 = $tagcloud->normalize_tag($fa5);
		$tagfa6 = $tagcloud->normalize_tag($fa6);
		$tagfa7 = $tagcloud->normalize_tag($fa7);
		$tagfa8 = $tagcloud->normalize_tag($fa8);
		$tagfa9 = $tagcloud->normalize_tag($fa9);
		$tagfa10 = $tagcloud->normalize_tag($fa10);

		// Get all the tags on this resource
		$tags_men = $tagcloud->get_tags_on_object($id, 0, 0, 0, 0);
		$mytagarray = array();
		$tagfa = '';

		$fas = array($tagfa1,$tagfa2,$tagfa3,$tagfa4,$tagfa5,$tagfa6,$tagfa7,$tagfa8,$tagfa9,$tagfa10);
		$fats = array();
		if ($fa1) {
			$fats[$fa1] = $tagfa1;
		}
		if ($fa2) {
			$fats[$fa2] = $tagfa2;
		}
		if ($fa3) {
			$fats[$fa3] = $tagfa3;
		}
		if ($fa4) {
			$fats[$fa4] = $tagfa4;
		}
		if ($fa5) {
			$fats[$fa5] = $tagfa5;
		}
		if ($fa6) {
			$fats[$fa6] = $tagfa6;
		}
		if ($fa7) {
			$fats[$fa7] = $tagfa7;
		}
		if ($fa8) {
			$fats[$fa8] = $tagfa8;
		}
		if ($fa9) {
			$fats[$fa9] = $tagfa9;
		}
		if ($fa10) {
			$fats[$fa10] = $tagfa10;
		}

		// Loop through all the tags and pull out the focus areas - those will be displayed differently
		foreach ($tags_men as $tag_men)
		{
			if (in_array($tag_men['tag'],$fas)) {
				$tagfa = $tag_men['tag'];
			} else {
				$mytagarray[] = $tag_men['raw_tag'];
			}
		}
		$tags = implode( ', ', $mytagarray );

		$etags = JRequest::getVar( 'tags', '' );
		if (!$tags) {
			$tags = $etags;
		}
		$err = JRequest::getInt( 'err', 0 );
		if ($err) {
			$this->setError( JText::_('Please select one of the focus areas.') );
		}

		// Output HTML
		$view = new JView( array('name'=>'steps','layout'=>'tags') );
		$view->option = $this->_option;
		$view->title = $this->_title;
		$view->step = $step;
		$view->steps = $this->steps;
		$view->tags = $tags;
		$view->tagfa = $tagfa;
		$view->fats = $fats;
		$view->next_step = $next_step;
		$view->database = $this->database;
		$view->id = $id;
		$view->progress = $this->progress;
		$view->task = 'start';
		if ($this->getError()) {
			foreach ($this->getErrors() as $error)
			{
				$view->setError($error);
			}
		}
		$view->display();
	}

	/**
	 * Short description for 'step_review'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function step_review()
	{
		$step = $this->step;
		$next_step = $step+1;

		// Incoming
		$id = JRequest::getInt( 'id', 0 );

		// Ensure we have an ID to work with
		if (!$id) {
			JError::raiseError( 500, JText::_('COM_CONTRIBUTE_NO_ID') );
			return;
		}

		// Push some needed styles to the tmeplate
		$this->_getStyles('com_resources');

		// Get some needed libraries
		include_once( JPATH_ROOT.DS.'components'.DS.'com_resources'.DS.'helpers'.DS.'html.php' );
		include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_resources'.DS.'tables'.DS.'license.php' );

		// Load resource info
		$resource = new ResourcesResource( $this->database );
		$resource->load( $id );

		if (!$this->juser->get('guest')) {
			ximport('Hubzero_User_Helper');
			$xgroups = Hubzero_User_Helper::getGroups($this->juser->get('id'), 'all');
			// Get the groups the user has access to
			$usersgroups = $this->_getUsersGroups($xgroups);
		} else {
			$usersgroups = array();
		}

		// Output HTML
		$view = new JView( array('name'=>'steps','layout'=>'review') );
		$view->option = $this->_option;
		$view->title = $this->_title;
		$view->step = $step;
		$view->steps = $this->steps;
		$view->usersgroups = $usersgroups;
		$view->config = $this->config;
		$view->resource = $resource;
		$view->next_step = $next_step;
		$view->database = $this->database;
		$view->id = $id;
		$view->progress = $this->progress;
		$view->task = 'submit';
		
		$rl = new ResourcesLicense($this->database);
		$view->licenses = $rl->getRecords();
		
		if ($this->getError()) {
			foreach ($this->getErrors() as $error)
			{
				$view->setError($error);
			}
		}
		$view->display();
	}

	/**
	 * Short description for '_getUsersGroups'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      array $groups Parameter description (if any) ...
	 * @return     array Return description (if any) ...
	 */
	private function _getUsersGroups($groups)
	{
		$arr = array();
		if (!empty($groups)) {
			foreach ($groups as $group)
			{
				if ($group->regconfirmed) {
					$arr[] = $group->cn;
				}
			}
		}
		return $arr;
	}

	//----------------------------------------------------------
	//  Pre Processing
	//----------------------------------------------------------

	/**
	 * Short description for 'step_type_process'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	protected function step_type_process()
	{
		// do nothing
	}

	/**
	 * Short description for 'step_compose_process'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function step_compose_process()
	{
		// Initiate extended database class
		$row = new ResourcesResource( $this->database );
		if (!$row->bind( $_POST )) {
			JError::raiseError( 500, $row->getError() );
			return;
		}
		$isNew = $row->id < 1;

		$row->created = ($row->created) ? $row->created : date( 'Y-m-d H:i:s' );
		$row->created_by = ($row->created_by) ? $row->created_by : $this->juser->get('id');

		// Set status to "composing"
		if ($isNew) {
			$row->published = 2;
		} else {
			$row->published = ($row->published) ? $row->published : 2;
		}
		$row->publish_up = ($row->publish_up) ? $row->publish_up : date( 'Y-m-d H:i:s' );
		$row->publish_down = '0000-00-00 00:00:00';
		$row->modified = date( 'Y-m-d H:i:s' );
		$row->modified_by = $this->juser->get('id');

		// Get custom areas, add wrapper tags, and compile into fulltext
		$type = new ResourcesType( $this->database );
		$type->load($row->type);

		include_once(JPATH_ROOT . DS . 'components' . DS . 'com_resources' . DS . 'models' . DS . 'elements.php');
		$elements = new ResourcesElements(array(), $type->customFields);
		$schema = $elements->getSchema();

		$fields = array();
		foreach ($schema->fields as $field)
		{
			$fields[$field->name] = $field;
		}

		$nbtag = $_POST['nbtag'];
		$found = array();
		foreach ($nbtag as $tagname => $tagcontent)
		{
			$row->fulltext .= "\n".'<nb:'.$tagname.'>';
			if (is_array($tagcontent))
			{
				foreach ($tagcontent as $key => $val)
				{
					$row->fulltext .= '<'.$key.'>' . trim($val) . '</'.$key.'>';
				}
			}
			else 
			{
				$row->fulltext .= (isset($fields[$tagname]) && $fields[$tagname]->type == 'textarea') ? $this->_txtAutoP(trim($tagcontent), 1) : trim($tagcontent);
			}
			$row->fulltext .= '</nb:'.$tagname.'>'."\n";
			
			if (!$tagcontent && isset($fields[$tagname]) && $fields[$tagname]->required) 
			{
				$this->setError(JText::sprintf('COM_CONTRIBUTE_REQUIRED_FIELD_CHECK', $fields[$tagname]->label));
			}
			
			$found[] = $tagname;
		}

		foreach ($fields as $field)
		{
			if (!in_array($field->name, $found) && $field->required)
			{
				$found[] = $field->name;
				$this->setError(JText::sprintf('COM_CONTRIBUTE_REQUIRED_FIELD_CHECK', $field->label));
			}
		}

		$row->title = preg_replace('/\s+/', ' ',$row->title);
		$row->title = $this->_txtClean($row->title);

		// Strip any scripting there may be
		if (trim($row->fulltext)) {
			$row->fulltext   = $this->_txtClean($row->fulltext);
			//$row->fulltext   = $this->_txtAutoP($row->fulltext,1);
			$row->footertext = $this->_txtClean($row->footertext);
			$row->introtext  = Hubzero_View_Helper_Html::shortenText($row->fulltext, 500, 0);
		}

		// Check content
		if (!$row->check()) {
			$this->setError($row->getError());
		}
		
		if ($this->getError())
		{
			$this->step--;
			$this->step_compose($row);
			return;
		}

		// Store new content
		if (!$row->store()) {
			$this->setError($row->getError());
			$this->step--;
			$this->step_compose($row);
			return;
		}

		// Checkin the resource
		$row->checkin();

		// Is it a new resource?
		if ($isNew) {
			// Get the resource ID
			if (!$row->id) {
				$row->id = $row->insertid();
			}

			// Automatically attach this user as the first author
			$_REQUEST['pid'] = $row->id;
			$_POST['authid'] = $this->juser->get('id');
			$_REQUEST['id'] = $row->id;

			$this->author_save(0);
		}
	}

	/**
	 * Short description for 'step_attach_process'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     void
	 */
	protected function step_attach_process()
	{
		// do nothing
	}

	/**
	 * Short description for 'step_authors_process'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function step_authors_process()
	{
		// Incoming
		$id = JRequest::getInt( 'id', 0 );

		// Ensure we have an ID to work with
		if (!$id) {
			return;
		}

		// Load the resource
		$row = new ResourcesResource( $this->database );
		$row->load( $id );

		// Set the group and access level
		$row->group_owner = JRequest::getVar( 'group_owner', '' );
		$row->access = JRequest::getInt( 'access', 0 );

		if ($row->access > 0 && !$row->group_owner) {
			$this->setError( JText::_('Please select a group to restrict access to.') );
			$this->step--;
			$this->step_authors();
			return;
		}

		// Check content
		if (!$row->check()) {
			JError::raiseError( 500, $row->getError() );
			return;
		}

		// Store new content
		if (!$row->store()) {
			JError::raiseError( 500, $row->getError() );
			return;
		}
	}

	/**
	 * Short description for 'step_tags_process'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function step_tags_process()
	{
		// Incoming
		$id    = JRequest::getInt( 'id', 0, 'post' );
		$tags  = JRequest::getVar( 'tags', '', 'post' );
		$tagfa = JRequest::getVar( 'tagfa', '', 'post' );

		$tagcloud = new ResourcesTags($this->database);

		$tconfig =& JComponentHelper::getParams( 'com_tags' );
		$fa = array();
		$fa[] = $tagcloud->normalize_tag($tconfig->get('focus_area_01'));
		$fa[] = $tagcloud->normalize_tag($tconfig->get('focus_area_02'));
		$fa[] = $tagcloud->normalize_tag($tconfig->get('focus_area_03'));
		$fa[] = $tagcloud->normalize_tag($tconfig->get('focus_area_04'));
		$fa[] = $tagcloud->normalize_tag($tconfig->get('focus_area_05'));
		$fa[] = $tagcloud->normalize_tag($tconfig->get('focus_area_06'));
		$fa[] = $tagcloud->normalize_tag($tconfig->get('focus_area_07'));
		$fa[] = $tagcloud->normalize_tag($tconfig->get('focus_area_08'));
		$fa[] = $tagcloud->normalize_tag($tconfig->get('focus_area_09'));
		$fa[] = $tagcloud->normalize_tag($tconfig->get('focus_area_10'));
		$required = false;
		foreach ($fa as $r)
		{
			if ($r != '') {
				$required = true;
			}
		}
		//print_r($required);
		if ($required) {
			//echo $tagfa; print_r($fa); die;
			if (!$tagfa || ($tagfa && !in_array($tagfa, $fa))) {
				$this->_redirect = 'index.php?option='.$this->_option.'&step=4&id='.$id.'&err=1&tags='.$tags;
				$this->_message = JText::_('Please select one of the focus areas.');
				$this->_messageType = 'error';
				return;
			}
		}

		if ($tags) {
			$tags = $tagfa.', '.$tags;
		} else {
			$tags = $tagfa;
		}

		// Tag the resource
		$rt = new ResourcesTags($this->database);
		$rt->tag_object($this->juser->get('id'), $id, $tags, 1, 1);
	}

	//----------------------------------------------------------
	// Final submission
	//----------------------------------------------------------

	/**
	 * Short description for 'submit'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function submit()
	{
		// Incoming
		$id = JRequest::getInt( 'id', 0 );

		// Ensure we have an ID to work with
		if (!$id) {
			JError::raiseError( 500, JText::_('COM_CONTRIBUTE_NO_ID') );
			return;
		}

		// Load resource info
		$resource = new ResourcesResource( $this->database );
		$resource->load( $id );

		// Set a flag for if the resource was already published or not
		$published = 0;
		if ($resource->published != 2) {
			$published = 1;
		}

		// Check if a newly submitted resource was authorized to be published
		$authorized = JRequest::getInt( 'authorization', 0 );
		if (!$authorized && !$published) {
			$this->setError( JText::_('COM_CONTRIBUTE_CONTRIBUTION_NOT_AUTHORIZED') );
			$this->check_progress($id);
			$this->step_review();
			return;
		}

		// Is this a newly submitted resource?
		if (!$published) {
			// 0 = unpublished, 1 = published, 2 = composing, 3 = pending (submitted), 4 = deleted
			// Are submissions auto-approved?
			if ($this->config->get('autoapprove') == 1) {
				// Set status to published
				$resource->published = 1;
			} else {
				$apu = $this->config->get('autoapproved_users');
				$apu = explode(',', $apu);
				$apu = array_map('trim',$apu);

				if (in_array($this->juser->get('username'),$apu)) {
					// Set status to published
					$resource->published = 1;
				} else {
					// Set status to pending review (submitted)
					$resource->published = 3;
				}
			}

			// Get the resource's contributors
			$helper = new ResourcesHelper( $id, $this->database );
			$helper->getCons();

			$contributors = $helper->_contributors;

			if (!$contributors || count($contributors) <= 0) {
				$this->setError( JText::_('COM_CONTRIBUTE_CONTRIBUTION_HAS_NO_AUTHORS') );
				$this->check_progress($id);
				$this->step_review();
				return;
			}
		}

		// Is this resource licensed under Creative Commons?
		if ($this->config->get('cc_license')) {
			$license = JRequest::getVar('license', '');
			if ($license) {
				$params = explode("\n",$resource->params);
				$newparams = array();
				$flag = 0;

				// Loop through the params and check if a license param exist
				foreach ($params as $param)
				{
					$p = explode('=',$param);
					if ($p[0] == 'license') {
						$flag = 1;
						$p[1] = $license;
					}
					$param = implode('=',$p);
					$newparams[] = $param;
				}

				// No license param so add it
				if ($flag == 0) {
					$newparams[] = 'license=' . $license;
				}

				// Overwrite the resource's params with the new params
				$resource->params = implode("\n",$newparams);
				
				if (($licenseText = JRequest::getVar('license-text', ''))) 
				{
					if ($licenseText == '[ENTER LICENSE HERE]') 
					{
						$this->setError( JText::_('Please enter a license.') );
						$this->check_progress($id);
						$this->step_review();
						return;
					}
					
					include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_resources'.DS.'tables'.DS.'license.php' );
					
					$rl = new ResourcesLicense($this->database);
					$rl->name = 'custom' . $resouce->id;
					$rl->text = $licenseText;
					$rl->check();
					$rl->store();
				}
			}
		}

		// Save and checkin the resource
		$resource->store();
		$resource->checkin();

		// If a previously published resource, redirect to the resource page
		if ($published == 1) {
			if ($resource->alias) {
				$url = JRoute::_('index.php?option=com_resources&alias='.$resource->alias);
			} else {
				$url = JRoute::_('index.php?option=com_resources&id='.$resource->id);
			}
			$this->_redirect = $url;
			return;
		}

		/*$jconfig =& JFactory::getConfig();
		
		// E-mail "from" info
		$from = array();
		$from['email'] = $jconfig->getValue('config.mailfrom');
		$from['name']  = $jconfig->getValue('config.sitename').' '.JText::_('COM_CONTRIBUTE_SUBMISSIONS');
		
		// E-mail subject
		$subject = $jconfig->getValue('config.sitename').' '.JText::_('COM_CONTRIBUTE_EMAIL_SUBJECT');
		
		// E-mail message
		$message  = JText::sprintf('COM_CONTRIBUTE_EMAIL_MESSAGE', $jconfig->getValue('config.live_site'))."\r\n";
		$message .= JRoute::_('index.php?option=com_resources&id='.$id);

		// Send e-mail
		ximport('Hubzero_Toolbox');
		foreach ($contributors as $contributor)
		{
			$juser = JUser::getInstance( $contributor->id );
			if (is_object($juser)) {
				if ($juser->get('email')) {
					Hubzero_Toolbox::send_email($from, $email, $subject, $message);
				}
			}
		}*/

		// Output HTML
		$view = new JView( array('name'=>'thanks') );
		$view->option = $this->_option;
		$view->title = $this->_title;
		$view->config = $this->config;
		$view->resource = $resource;
		if ($this->getError()) {
			$view->setError( $this->getError() );
		}
		$view->display();
	}

	/**
	 * Short description for 'delete'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function delete()
	{
		// Incoming
		$id = JRequest::getInt( 'id', 0 );

		// Ensure we have an ID to work with
		if (!$id) {
			$this->_redirect = JRoute::_('index.php?option='.$this->_option);
			return;
		}

		// Incoming step
		$step = JRequest::getVar( 'step', 1 );

		// Perform step
		switch ($step)
		{
			case 1:
				$steps = $this->steps;

				$progress = array();
				$progress['submitted'] = 0;
				for ($i = 1, $n = count( $steps ); $i < $n; $i++)
				{
					$progress[$steps[$i]] = 0;
				}

				// Load the resource
				$row = new ResourcesResource( $this->database );
				$row->load( $id );
				$row->typetitle = $row->getTypeTitle(0);

				// Output HTML
				$view = new JView( array('name'=>'delete') );
				$view->option = $this->_option;
				$view->title = $this->_title;
				$view->step = 'discard';
				$view->row = $row;
				$view->steps = $steps;
				$view->id = $id;
				$view->progress = $progress;
				if ($this->getError()) {
					$view->setError( $this->getError() );
				}
				$view->display();
			break;

			case 2:
				// Incoming confirmation flag
				$confirm = JRequest::getVar( 'confirm', '', 'post' );

				// Did they confirm the deletion?
				if ($confirm != 'confirmed') {
					$this->redirect = JRoute::_('index.php?option='.$this->_option);
					return;
				}

				// Load the resource
				$resource = new ResourcesResource( $this->database );
				$resource->load( $id );

				// Check if the resource was "published"
				if ($resource->published == 1) {
					// It was, so we can only mark it as "deleted"
					if (!$this->markRemovedContribution( $id )) {
						JError::raiseError( 500, $this->getError() );
						return;
					}
				} else {
					// It wasn't. Attempt to delete the resource
					if (!$this->deleteContribution( $id )) {
						JError::raiseError( 500, $this->getError() );
						return;
					}
				}

				// Redirect to the start page
				$this->_redirect = JRoute::_('index.php?option='.$this->_option);
			break;
		}
	}

	/**
	 * Short description for 'retract'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function retract()
	{
		// Incoming
		$id = JRequest::getInt( 'id', 0 );

		// Ensure we have an ID to work with
		if (!$id) {
			$this->_redirect = JRoute::_('index.php?option='.$this->_option);
			return;
		}

		// Load the resource
		$resource = new ResourcesResource( $this->database );
		$resource->load( $id );

		// Check if it's in pending status
		if ($resource->published == 3) {
			// Set it back to "draft" status
			$resource->published = 2;
			// Save changes
			$resource->store();
		}

		// Redirect
		$this->_redirect = JRoute::_('index.php?option='.$this->_option);
	}

	/**
	 * Short description for 'markRemovedContribution'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     boolean Return description (if any) ...
	 */
	protected function markRemovedContribution( $id )
	{
		// Make sure we have a record to pull
		if (!$id) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_ID') );
			return false;
		}

		// Load resource info
		$row = new ResourcesResource( $this->database );
		$row->load( $id );

		// Mark resource as deleted
		$row->published = 4;
		if (!$row->store()) {
			$this->setError( $row->getError() );
			return false;
		}

		// Return success
		return true;
	}

	/**
	 * Short description for 'deleteContribution'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     boolean Return description (if any) ...
	 */
	protected function deleteContribution( $id )
	{
		// Make sure we have a record to pull
		if (!$id) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_ID') );
			return false;
		}

		jimport('joomla.filesystem.folder');

		// Load resource info
		$row = new ResourcesResource( $this->database );
		$row->load( $id );

		// Get the resource's children
		$helper = new ResourcesHelper( $id, $this->database );
		$helper->getChildren();
		$children = $helper->children;

		// Were there any children?
		if ($children) {
			// Loop through each child and delete its files and associations
			foreach ($children as $child)
			{
				// Skip standalone children
				if ($child->standalone == 1) {
					continue;
				}

				// Get path and delete directories
				if ($child->path != '') {
					$listdir = $child->path;
				} else {
					// No stored path, derive from created date		
					$listdir = $this->_buildPathFromDate( $child->created, $child->id, '' );
				}

				// Build the path
				$path = $this->_buildUploadPath( $listdir, '' );

				// Check if the folder even exists
				if (!is_dir($path) or !$path) {
					$this->setError( JText::_('COM_CONTRIBUTE_DIRECTORY_NOT_FOUND') );
				} else {
					// Attempt to delete the folder
					if (!JFolder::delete($path)) {
						$this->setError( JText::_('COM_CONTRIBUTE_UNABLE_TO_DELETE_DIRECTORY') );
					}
				}

				// Delete associations to the resource
				$row->deleteExistence( $child->id );

				// Delete the resource
				$row->delete( $child->id );
			}
		}

		// Get path and delete directories
		if ($row->path != '') {
			$listdir = $row->path;
		} else {
			// No stored path, derive from created date		
			$listdir = $this->_buildPathFromDate( $row->created, $id, '' );
		}

		// Build the path
		$path = $this->_buildUploadPath( $listdir, '' );

		// Check if the folder even exists
		if (!is_dir($path) or !$path) {
			$this->setError( JText::_('COM_CONTRIBUTE_DIRECTORY_NOT_FOUND') );
		} else {
			// Attempt to delete the folder
			if (!JFolder::delete($path)) {
				$this->setError( JText::_('COM_CONTRIBUTE_UNABLE_TO_DELETE_DIRECTORY') );
			}
		}

		// Delete associations to the resource
		$row->deleteExistence();

		// Delete the resource
		$row->delete();

		// Return success (null)
		return true;
	}

	//----------------------------------------------------------
	// Attachments
	//----------------------------------------------------------

	/**
	 * Short description for 'attach_rename'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     boolean Return description (if any) ...
	 */
	protected function attach_rename()
	{
		// Check if they are logged in
		if ($this->juser->get('guest')) {
			return false;
		}

		// Incoming
		$id = JRequest::getInt( 'id', 0 );
		$name = trim(JRequest::getVar( 'name', '' ));

		// Ensure we have everything we need
		if ($id && $name != '') {
			$r = new ResourcesResource( $this->database );
			$r->load( $id );
			$r->title = $name;
			$r->store();
		}

		// Echo the name
		echo $name;
	}

	/**
	 * Short description for 'attach_save'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     boolean Return description (if any) ...
	 */
	protected function attach_save()
	{
		// Check if they are logged in
		if ($this->juser->get('guest')) {
			return false;
		}

		// Incoming
		$pid = JRequest::getInt( 'pid', 0 );
		if (!$pid) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_ID') );
			$this->attachments( $pid );
		}

		// Incoming file
		$file = JRequest::getVar( 'upload', '', 'files', 'array' );
		if (!$file['name']) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_FILE') );
			$this->attachments( $pid );
			return;
		}

		// Make the filename safe
		jimport('joomla.filesystem.file');
		$file['name'] = JFile::makeSafe($file['name']);
		// Ensure file names fit.
		$ext = JFile::getExt($file['name']);
		$file['name'] = str_replace(' ','_',$file['name']);
		if (strlen($file['name']) > 230)
		{
			$file['name'] = substr($file['name'], 0, 230);
			$file['name'] .= '.' . $ext;
		}

		// Instantiate a new resource object
		$row = new ResourcesResource( $this->database );
		if (!$row->bind( $_POST )) {
			$this->setError( $row->getError() );
			$this->attachments( $pid );
			return;
		}
		$row->title = ($row->title) ? $row->title : $file['name'];
		$row->introtext = $row->title;
		$row->created = date( 'Y-m-d H:i:s' );
		$row->created_by = $this->juser->get('id');
		$row->published = 1;
		$row->publish_up = date( 'Y-m-d H:i:s' );
		$row->publish_down = '0000-00-00 00:00:00';
		$row->standalone = 0;

		// Check content
		if (!$row->check()) {
			$this->setError( $row->getError() );
			$this->attachments( $pid );
			return;
		}
		// Store new content
		if (!$row->store()) {
			$this->setError( $row->getError() );
			$this->attachments( $pid );
			return;
		}

		if (!$row->id) {
			$row->id = $row->insertid();
		}

		// Build the path
		$listdir = $this->_buildPathFromDate( $row->created, $row->id, '' );
		$path = $this->_buildUploadPath( $listdir, '' );

		// Make sure the upload path exist
		if (!is_dir( $path )) {
			jimport('joomla.filesystem.folder');
			if (!JFolder::create( $path, 0777 )) {
				$this->setError( JText::_('COM_CONTRIBUTE_UNABLE_TO_CREATE_UPLOAD_PATH') );
				$this->attachments( $pid );
				return;
			}
		}

		// Perform the upload
		if (!JFile::upload($file['tmp_name'], $path.DS.$file['name'])) {
			$this->setError( JText::_('COM_CONTRIBUTE_ERROR_UPLOADING') );
			return;
		} else {
			// File was uploaded

			// Check the file type
			$row->type = $this->_getChildType($file['name']);

			// If it's a package (ZIP, etc) ...
			if ($row->type == 38) {
				/*jimport('joomla.filesystem.archive');
				
				// Extract the files
				if (!JArchive::extract( $file_to_unzip, $path )) {
					$this->setError( JText::_('Could not extract package.') );
				}*/
				require_once( JPATH_ROOT.DS.'administrator'.DS.'includes'.DS.'pcl'.DS.'pclzip.lib.php' );

				if (!extension_loaded('zlib')) {
					$this->setError( JText::_('COM_CONTRIBUTE_ZLIB_PACKAGE_REQUIRED') );
				} else {
					// Check the table of contents and look for a Breeze viewer.swf file
					$isbreeze = 0;

					$zip = new PclZip( $path.DS.$file['name'] );

					$file_to_unzip = preg_replace('/(.+)\..*$/', '$1', $path.DS.$file['name']);

					if (($list = $zip->listContent()) == 0) {
						die('Error: '.$zip->errorInfo(true));
					}

					for ($i=0; $i<sizeof($list); $i++)
					{
						if (substr($list[$i]['filename'], strlen($list[$i]['filename']) - 10, strlen($list[$i]['filename'])) == 'viewer.swf') {
							$isbreeze = $list[$i]['filename'];
							break;
						}
						//$this->setError( substr($list[$i]['filename'], strlen($list[$i]['filename']), -4).' '.substr($file['name'], strlen($file['name']), -4) );
					}
					if (!$isbreeze) {
						for ($i=0; $i<sizeof($list); $i++)
						{
							if (strtolower(substr($list[$i]['filename'], -3)) == 'swf'
							 && substr($list[$i]['filename'], strlen($list[$i]['filename']), -4) == substr($file['name'], strlen($file['name']), -4)) {
								$isbreeze = $list[$i]['filename'];
								break;
							}
							//$this->setError( substr($list[$i]['filename'], strlen($list[$i]['filename']), -4).' '.substr($file['name'], strlen($file['name']), -4) );
						}
					}

					// It IS a breeze presentation
					if ($isbreeze) {
						// unzip the file
						$do = $zip->extract($path);
						if (!$do) {
							$this->setError( JText::_( 'COM_CONTRIBUTE_UNABLE_TO_EXTRACT_PACKAGE' ) );
						} else {
							$row->path = $listdir.DS.$isbreeze;

							@unlink( $path.DS.$file['name'] );
						}
						$row->type = $this->_getChildType($row->path);
						$row->title = $isbreeze;
					}
				}
			}
		}
		
		// Scan for viruses
		$path = $path . DS . $file['name']; //JPATH_ROOT.DS.'virustest';
		exec("clamscan -i --no-summary --block-encrypted $path", $output, $status);
		if ($status == 1)
		{
			if (JFile::delete($path)) 
			{
				// Delete associations to the resource
				$row->deleteExistence();

				// Delete resource
				$row->delete();
			}
			
			$this->setError( JText::_('File rejected due to possible security risk.') );
			$this->attachments( $pid );
			return;
		}

		if (!$row->path) {
			$row->path = $listdir.DS.$file['name'];
		}
		if (substr($row->path, 0, 1) == DS) {
			$row->path = substr($row->path, 1, strlen($row->path));
		}

		// Store new content
		if (!$row->store()) {
			$this->setError( $row->getError() );
			$this->attachments( $pid );
			return;
		}

		// Instantiate a ResourcesAssoc object
		$assoc = new ResourcesAssoc( $this->database );

		// Get the last child in the ordering
		$order = $assoc->getLastOrder( $pid );
		$order = ($order) ? $order : 0;

		// Increase the ordering - new items are always last
		$order = $order + 1;

		// Create new parent/child association
		$assoc->parent_id = $pid;
		$assoc->child_id = $row->id;
		$assoc->ordering = $order;
		$assoc->grouping = 0;
		if (!$assoc->check()) {
			$this->setError( $assoc->getError() );
		}
		if (!$assoc->store(true)) {
			$this->setError( $assoc->getError() );
		}

		// Push through to the attachments view
		$this->attachments( $pid );
	}

	/**
	 * Short description for 'attach_delete'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     boolean Return description (if any) ...
	 */
	protected function attach_delete()
	{
		// Check if they are logged in
		if ($this->juser->get('guest')) {
			return false;
		}

		// Incoming parent ID
		$pid = JRequest::getInt( 'pid', 0 );
		if (!$pid) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_ID') );
			$this->attachments( $pid );
		}

		// Incoming child ID
		$id = JRequest::getInt( 'id', 0 );
		if (!$id) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_CHILD_ID') );
			$this->attachments( $pid );
		}

		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		// Load resource info
		$row = new ResourcesResource( $this->database );
		$row->load( $id );

		// Get path and delete directories
		if ($row->path != '') {
			$listdir = $row->path;
		} else {
			// No stored path, derive from created date		
			$listdir = $this->_buildPathFromDate( $row->created, $id, '' );
		}

		// Build the path
		$path = $this->_buildUploadPath( $listdir, '' );

		// Check if the file even exists
		if (!is_file($path) or !$path) {
			$this->setError( JText::_('COM_CONTRIBUTE_FILE_NOT_FOUND') );
		} else {
			// Attempt to delete the file
			if (!JFile::delete($path)) {
				$this->setError( JText::_('COM_CONTRIBUTE_UNABLE_TO_DELETE_FILE') );
			}
		}

		if (!$this->getError()) {
			$file = basename($path);
			$path = substr($path, 0, (strlen($path) - strlen($file)));
			$year = substr(trim($row->created), 0, 4);
			$month = substr(trim($row->created), 5, 2);
			$path = str_replace(JPATH_ROOT,'',$path);
			$path = str_replace($this->config->get('uploadpath'),'',$path);
			$bits = explode('/', $path);
			$p = array();
			$b = '';
			$g = array_pop($bits);
			foreach ($bits as $bit)
			{
				if ($bit == '/' || $bit == $year || $bit == $month || $bit == Hubzero_View_Helper_Html::niceidformat($id)) {
					$b .= ($bit != '/') ? DS.$bit : '';
				} else if ($bit != '/') {
					$p[] = $bit;
				}
			}
			if (count($p) > 1) {
				$p = array_reverse($p);
				foreach ($p as $v)
				{
					$npath = JPATH_ROOT.$this->config->get('uploadpath').$b.DS.$v;

					// Check if the folder even exists
					if (!is_dir($npath) or !$npath) {
						$this->setError( JText::_('COM_CONTRIBUTE_DIRECTORY_NOT_FOUND') );
					} else {
						// Attempt to delete the folder
						if (!JFolder::delete($npath)) {
							$this->setError( JText::_('COM_CONTRIBUTE_UNABLE_TO_DELETE_DIRECTORY') );
						}
					}
				}
			}
		}

		if (!$this->getError()) {
			// Delete associations to the resource
			$row->deleteExistence();

			// Delete resource
			$row->delete();
		}

		// Push through to the attachments view
		$this->attachments( $pid );
	}

	/**
	 * Short description for 'attachments'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     boolean Return description (if any) ...
	 */
	protected function attachments( $id=null )
	{
		// Check if they are logged in
		if ($this->juser->get('guest')) {
			return false;
		}

		// Incoming
		if (!$id) {
			$id = JRequest::getInt( 'id', 0 );
		}

		// Ensure we have an ID to work with
		if (!$id) {
			JError::raiseError( 500, JText::_('COM_CONTRIBUTE_NO_ID') );
			return;
		}

		// Initiate a resource helper class
		$helper = new ResourcesHelper( $id, $this->database );
		$helper->getChildren();

		// Output HTML
		$view = new JView( array('name'=>'steps','layout'=>'attachments') );
		$view->option = $this->_option;
		$view->config = $this->config;
		$view->children = $helper->children;
		$view->path = '';
		$view->id = $id;
		if ($this->getError()) {
			$view->setError( $this->getError() );
		}
		$view->display();
	}

	/**
	 * Short description for '_buildUploadPath'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      string $listdir Parameter description (if any) ...
	 * @param      string $subdir Parameter description (if any) ...
	 * @return     string Return description (if any) ...
	 */
	private function _buildUploadPath( $listdir, $subdir='' )
	{
		if ($subdir) {
			// Make sure the path doesn't end with a slash
			if (substr($subdir, -1) == DS) {
				$subdir = substr($subdir, 0, strlen($subdir) - 1);
			}
			// Ensure the path starts with a slash
			if (substr($subdir, 0, 1) != DS) {
				$subdir = DS.$subdir;
			}
		}

		// Get the configured upload path
		$base_path = $this->config->get('uploadpath');
		if ($base_path) {
			// Make sure the path doesn't end with a slash
			if (substr($base_path, -1) == DS) {
				$base_path = substr($base_path, 0, strlen($base_path) - 1);
			}
			// Ensure the path starts with a slash
			if (substr($base_path, 0, 1) != DS) {
				$base_path = DS.$base_path;
			}
		}

		// Make sure the path doesn't end with a slash
		if (substr($listdir, -1) == DS) {
			$listdir = substr($listdir, 0, strlen($listdir) - 1);
		}
		// Ensure the path starts with a slash
		if (substr($listdir, 0, 1) != DS) {
			$listdir = DS.$listdir;
		}
		// Does the beginning of the $listdir match the config path?
		if (substr($listdir, 0, strlen($base_path)) == $base_path) {
			// Yes - ... this really shouldn't happen
		} else {
			// No - append it
			$listdir = $base_path.$listdir;
		}

		// Build the path
		return JPATH_ROOT.$listdir.$subdir;
	}

	/**
	 * Short description for '_getChildType'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $filename Parameter description (if any) ...
	 * @return     integer Return description (if any) ...
	 */
	private function _getChildType($filename)
	{
		$filename_arr = explode('.',$filename);
		$ftype = end($filename_arr);
		$ftype = (strlen($ftype) > 3) ? substr($ftype, 0, 3) : $ftype;
		$ftype = strtolower($ftype);

		switch ($ftype)
		{
			case 'mov': $type = 15; break;
			case 'swf': $type = 32; break;
			case 'ppt': $type = 35; break;
			case 'asf': $type = 37; break;
			case 'asx': $type = 37; break;
			case 'wmv': $type = 37; break;
			case 'zip': $type = 38; break;
			case 'tar': $type = 38; break;
			case 'pdf': $type = 33; break;
			default:    $type = 13; break;
		}

		return $type;
	}

	/**
	 * Short description for 'reorder_attach'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function reorder_attach()
	{
		// Incoming
		$id = JRequest::getInt( 'id', 0 );
		$pid = JRequest::getInt( 'pid', 0 );

		// Ensure we have an ID to work with
		if (!$id) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_CHILD_ID') );
			$this->attachments( $pid );
			return;
		}

		// Ensure we have a parent ID to work with
		if (!$pid) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_ID') );
			$this->attachments( $pid );
			return;
		}

		$move = substr($this->_task, 0, (strlen($this->_task) - 1));

		// Get the element moving down - item 1
		$resource1 = new ResourcesAssoc( $this->database );
		$resource1->loadAssoc( $pid, $id );

		// Get the element directly after it in ordering - item 2
		$resource2 = clone( $resource1 );
		$resource2->getNeighbor( $move );

		switch ($move)
		{
			case 'orderup':
				// Switch places: give item 1 the position of item 2, vice versa
				$orderup = $resource2->ordering;
				$orderdn = $resource1->ordering;

				$resource1->ordering = $orderup;
				$resource2->ordering = $orderdn;
				break;

			case 'orderdown':
				// Switch places: give item 1 the position of item 2, vice versa
				$orderup = $resource1->ordering;
				$orderdn = $resource2->ordering;

				$resource1->ordering = $orderdn;
				$resource2->ordering = $orderup;
				break;
		}

		// Save changes
		$resource1->store();
		$resource2->store();

		// Push through to the attachments view
		$this->attachments( $pid );
	}

	//----------------------------------------------------------
	// contributors manager
	//----------------------------------------------------------

	/**
	 * Short description for 'author_save'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      integer $show Parameter description (if any) ...
	 * @return     unknown Return description (if any) ...
	 */
	protected function author_save($show=1)
	{
		// Incoming resource ID
		$id = JRequest::getInt( 'pid', 0 );
		if (!$id) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_ID') );
			$this->authors( $id );
			return;
		}

		ximport('Hubzero_User_Profile');

		// Incoming authors
		$authid = JRequest::getInt( 'authid', 0, 'post' );
		$authorsNewstr = JRequest::getVar( 'new_authors', '', 'post' );
		$role = JRequest::getVar( 'role', '', 'post' );

		// Instantiate a resource/contributor association object
		$rc = new ResourcesContributor( $this->database );
		$rc->subtable = 'resources';
		$rc->subid = $id;

		// Get the last child in the ordering
		$order = $rc->getLastOrder( $id, 'resources' );
		$order = $order + 1; // new items are always last

		// Was there an ID? (this will come from the author <select>)
		if ($authid) {
			// Check if they're already linked to this resource
			$rc->loadAssociation( $authid, $id, 'resources' );
			if ($rc->authorid) {
				$this->setError( JText::sprintf('COM_CONTRIBUTE_USER_IS_ALREADY_AUTHOR', $authid) );
			} else {
				// Perform a check to see if they have a contributors page. If not, we'll need to make one
				//$juser =& JUser::getInstance( $authid );
				$xprofile = new Hubzero_User_Profile();
				$xprofile->load( $authid );
				if ($xprofile) {
					$this->_author_check($authid);

					// New record
					$rc->authorid = $authid;
					$rc->ordering = $order;
					$rc->name = $xprofile->get('name');
					$rc->role = $role;
					$rc->organization = $xprofile->get('organization');
					$rc->createAssociation();

					$order++;
				}
			}
		}
		$xprofile = null;
		// Do we have new authors?
		if ($authorsNewstr) {
			// Turn the string into an array of usernames
			$authorsNew = preg_split('#,#',$authorsNewstr);

			jimport('joomla.user.helper');

			// loop through each one
			for ($i=0, $n=count( $authorsNew ); $i < $n; $i++)
			{
				$cid = strtolower(trim($authorsNew[$i]));

				// Find the user's account info
				$uid = JUserHelper::getUserId($cid);
				if (!$uid) {
					$this->setError( JText::sprintf('COM_CONTRIBUTE_UNABLE_TO_FIND_USER_ACCOUNT', $cid) );
					continue;
				}

				$juser =& JUser::getInstance( $uid );
				if (!is_object($juser)) {
					$this->setError( JText::sprintf('COM_CONTRIBUTE_UNABLE_TO_FIND_USER_ACCOUNT', $cid) );
					continue;
				}

				$uid = $juser->get('id');

				if (!$uid) {
					$this->setError( JText::sprintf('COM_CONTRIBUTE_UNABLE_TO_FIND_USER_ACCOUNT', $cid) );
					continue;
				}

				// Check if they're already linked to this resource
				$rcc = new ResourcesContributor( $this->database );
				$rcc->loadAssociation( $uid, $id, 'resources' );
				if ($rcc->authorid) {
					$this->setError( JText::sprintf('COM_CONTRIBUTE_USER_IS_ALREADY_AUTHOR', $cid) );
					continue;
				}

				$this->_author_check($juser->get('id'));

				// New record
				$xprofile = Hubzero_User_Profile::getInstance($juser->get('id'));
				$rcc->subtable = 'resources';
				$rcc->subid = $id;
				$rcc->authorid = $uid;
				$rcc->ordering = $order;
				$rcc->name = $xprofile->get('name');
				$rcc->role = $role;
				$rcc->organization = $xprofile->get('organization');
				$rcc->createAssociation();

				$order++;
			}
		}

		if ($show) {
			// Push through to the authors view
			$this->authors( $id );
		}
	}

	/**
	 * Short description for '_author_check'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     void
	 */
	private function _author_check($id)
	{
		$xprofile = Hubzero_User_Profile::getInstance($id);
		if ($xprofile->get('givenName') == '' && $xprofile->get('middleName') == '' && $xprofile->get('surname') == '') {
			$bits = explode(' ', $xprofile->get('name'));
			$xprofile->set('surname', array_pop($bits));
			if (count($bits) >= 1) {
				$xprofile->set('givenName', array_shift($bits));
			}
			if (count($bits) >= 1) {
				$xprofile->set('middleName', implode(' ',$bits));
			}
		}
	}

	/**
	 * Short description for 'author_remove'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function author_remove()
	{
		// Incoming
		$id  = JRequest::getInt( 'id', 0 );
		$pid = JRequest::getInt( 'pid', 0 );

		// Ensure we have a resource ID ($pid) to work with
		if (!$pid) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_ID') );
			$this->authors();
			return;
		}

		// Ensure we have the contributor's ID ($id)
		if ($id) {
			$rc = new ResourcesContributor( $this->database );
			if (!$rc->deleteAssociation( $id, $pid, 'resources' )) {
				$this->setError( $rc->getError() );
			}
		}

		// Push through to the authors view
		$this->authors( $pid );
	}
	
	/**
	 * Update information for a resource author
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function author_update()
	{
		// Incoming
		$ids = JRequest::getVar( 'authors', array(), 'post');
		$pid = JRequest::getInt( 'pid', 0 );

		// Ensure we have a resource ID ($pid) to work with
		if (!$pid) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_ID') );
			$this->authors();
			return;
		}

		// Ensure we have the contributor's ID ($id)
		if ($ids) 
		{
			foreach ($ids as $id => $role)
			{
				$rc = new ResourcesContributor($this->database);
				$rc->loadAssociation($id, $pid, 'resources');
				$rc->role = $role;
				$rc->updateAssociation();
			}
		}

		// Push through to the authors view
		$this->authors($pid);
	}

	/**
	 * Short description for 'reorder_author'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	protected function reorder_author()
	{
		// Incoming
		$id = JRequest::getInt( 'id', 0 );
		$pid = JRequest::getInt( 'pid', 0 );

		// Ensure we have an ID to work with
		if (!$id) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_CHILD_ID') );
			$this->authors( $pid );
			return;
		}

		// Ensure we have a parent ID to work with
		if (!$pid) {
			$this->setError( JText::_('COM_CONTRIBUTE_NO_ID') );
			$this->authors( $pid );
			return;
		}

		$move = substr($this->_task, 0, (strlen($this->_task) - 1));

		// Get the element moving down - item 1
		$author1 = new ResourcesContributor( $this->database );
		$author1->loadAssociation( $id, $pid, 'resources' );

		// Get the element directly after it in ordering - item 2
		$author2 = clone( $author1 );
		$author2->getNeighbor( $move );

		switch ($move)
		{
			case 'orderup':
				// Switch places: give item 1 the position of item 2, vice versa
				$orderup = $author2->ordering;
				$orderdn = $author1->ordering;

				$author1->ordering = $orderup;
				$author2->ordering = $orderdn;
				break;

			case 'orderdown':
				// Switch places: give item 1 the position of item 2, vice versa
				$orderup = $author1->ordering;
				$orderdn = $author2->ordering;

				$author1->ordering = $orderdn;
				$author2->ordering = $orderup;
				break;
		}

		// Save changes
		$author1->updateAssociation();
		$author2->updateAssociation();

		// Push through to the attachments view
		$this->authors( $pid );
	}

	/**
	 * Short description for 'authors'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     unknown Return description (if any) ...
	 */
	protected function authors( $id=null )
	{
		// Incoming
		if (!$id) {
			$id = JRequest::getInt( 'id', 0 );
		}

		// Ensure we have an ID to work with
		if (!$id) {
			JError::raiseError( 500, JText::_('COM_CONTRIBUTE_NO_ID') );
			return;
		}

		// Get all contributors of this resource
		$helper = new ResourcesHelper( $id, $this->database );
		$helper->getCons();

		// Get a list of all existing contributors
		include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_members'.DS.'tables'.DS.'profile.php' );
		include_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_members'.DS.'tables'.DS.'association.php' );
		
		include_once(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_resources' . DS . 'tables' . DS . 'role.type.php');
		
		$resource = new ResourcesResource($this->database);
		$resource->load($id);
		
		$rt = new ResourcesContributorRoleType($this->database);

		// Initiate a members object
		$mp = new MembersProfile( $this->database );

		$filters = array();
		$filters['search'] = '';
		$filters['show']   = '';
		$filters['index']  = '';
		$filters['limit']  = 'all';
		$filters['sortby'] = 'name';
		$filters['authorized'] = false;

		// Get all members
		$rows = $mp->getRecords( $filters, false );

		// Output HTML
		$view = new JView( array('name'=>'steps','layout'=>'authorslist') );
		$view->option = $this->_option;
		$view->config = $this->config;
		$view->contributors = $helper->_contributors;
		$view->rows = $rows;
		$view->id = $id;
		
		$view->roles = $rt->getRolesForType($resource->type);
		
		if ($this->getError()) {
			$view->setError( $this->getError() );
		}
		$view->display();
	}

	//----------------------------------------------------------
	// Checks
	//----------------------------------------------------------

	/**
	 * Short description for 'step_type_check'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     void
	 */
	protected function step_type_check( $id )
	{
		// do nothing
	}

	/**
	 * Short description for 'step_compose_check'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     unknown Return description (if any) ...
	 */
	protected function step_compose_check( $id )
	{
		return $id;
	}

	/**
	 * Short description for 'step_attach_check'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     integer Return description (if any) ...
	 */
	protected function step_attach_check( $id )
	{
		if ($id) {
			$ra = new ResourcesAssoc( $this->database );
			$total = $ra->getCount( $id );
		} else {
			$total = 0;
		}
		return $total;
	}

	/**
	 * Short description for 'step_authors_check'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     integer Return description (if any) ...
	 */
	protected function step_authors_check( $id )
	{
		if ($id) {
			$rc = new ResourcesContributor( $this->database );
			$contributors = $rc->getCount( $id, 'resources' );
		} else {
			$contributors = 0;
		}

		return $contributors;
	}

	/**
	 * Short description for 'step_tags_check'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     integer Return description (if any) ...
	 */
	protected function step_tags_check( $id )
	{
		$rt = new ResourcesTags( $this->database );
		$tags = $rt->getTags( $id );

		if (count($tags) > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * Short description for 'step_review_check'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $id Parameter description (if any) ...
	 * @return     integer Return description (if any) ...
	 */
	protected function step_review_check( $id )
	{
		$row = new ResourcesResource( $this->database );
		$row->load( $id );

		if ($row->published == 1) {
			return 1;
		} else {
			return 0;
		}
	}

	//----------------------------------------------------------
	// Misc
	//----------------------------------------------------------

	/**
	 * Short description for '_txtClean'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown &$text Parameter description (if any) ...
	 * @return     integer Return description (if any) ...
	 */
	private function _txtClean( &$text )
	{
		// Handle special characters copied from MS Word
		$text = str_replace('“','"', $text);
		$text = str_replace('”','"', $text);
		$text = str_replace("’","'", $text);
		$text = str_replace("‘","'", $text);
		
		$text = preg_replace( '/{kl_php}(.*?){\/kl_php}/s', '', $text );
		$text = preg_replace( '/{.+?}/', '', $text );
		$text = preg_replace( "'<style[^>]*>.*?</style>'si", '', $text );
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
		$text = preg_replace( '/<!--.+?-->/', '', $text );
		return $text;
	}

	/**
	 * Short description for '_txtAutoP'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      string $pee Parameter description (if any) ...
	 * @param      integer $br Parameter description (if any) ...
	 * @return     string Return description (if any) ...
	 */
	private function _txtAutoP($pee, $br = 1)
	{
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		foreach ($trans_tbl as $k => $v)
		{
			if ($k != '<' && $k != '>' && $k != '"' && $k != "'") {
				$ttr[utf8_encode($k)] = $v;
			}
		}
		$pee = strtr($pee, $ttr);

		$ent = array(
		    'Ć'=>'&#262;',
		    'ć'=>'&#263;',
		    'Č'=>'&#268;',
		    'č'=>'&#269;',
		    'Đ'=>'&#272;',
		    'đ'=>'&#273;',
		    'Š'=>'&#352;',
		    'š'=>'&#353;',
		    'Ž'=>'&#381;',
		    'ž'=>'&#382;'
		);

		$pee = strtr($pee, $ent);

		// converts paragraphs of text into xhtml
		$pee = $pee . "\n"; // just to make things a little easier, pad the end
		$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
		$pee = preg_replace('!(<(?:table|ul|ol|li|pre|form|blockquote|h[1-6])[^>]*>)!', "\n$1", $pee); // Space things out a little
		$pee = preg_replace('!(</(?:table|ul|ol|li|pre|form|blockquote|h[1-6])>)!', "$1\n", $pee); // Space things out a little
		$pee = preg_replace("/(\r\n|\r)/", "\n", $pee); // cross-platform newlines 
		$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
		$pee = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "<p>$1</p>\n", $pee); // make paragraphs, including one at the end 
		$pee = preg_replace('|<p>\s*?</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace 
		$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
		$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
		$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
		$pee = preg_replace('!<p>\s*(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)!', "$1", $pee);
		$pee = preg_replace('!(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*</p>!', "$1", $pee);
		if ($br) $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
		$pee = preg_replace('!(</?(?:table|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*<br />!', "$1", $pee);
		$pee = preg_replace('!<br />(\s*</?(?:p|li|div|th|pre|td|ul|ol)>)!', '$1', $pee);
		//$pee = preg_replace('/&([^#])(?![a-z]{1,8};)/', '&#038;$1', $pee);

		return $pee;
	}

	/**
	 * Short description for '_txtUnpee'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $pee Parameter description (if any) ...
	 * @return     unknown Return description (if any) ...
	 */
	public function _txtUnpee($pee)
	{
		$pee = str_replace("\t", '', $pee);
		$pee = str_replace('</p><p>', '', $pee);
		$pee = str_replace('<p>', '', $pee);
		$pee = str_replace('</p>', "\n", $pee);
		$pee = str_replace('<br />', '', $pee);
		$pee = trim($pee);
		return $pee;
	}

	/**
	 * Short description for '_buildPathFromDate'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $date Parameter description (if any) ...
	 * @param      unknown $id Parameter description (if any) ...
	 * @param      string $base Parameter description (if any) ...
	 * @return     unknown Return description (if any) ...
	 */
	private function _buildPathFromDate( $date, $id, $base='' )
	{
		if ($date && preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})/", $date, $regs )) {
			$date = mktime( $regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1] );
		}
		if ($date) {
			$dir_year  = date('Y', $date);
			$dir_month = date('m', $date);
		} else {
			$dir_year  = date('Y');
			$dir_month = date('m');
		}
		$dir_id = Hubzero_View_Helper_Html::niceidformat( $id );

		$path = $base.DS.$dir_year.DS.$dir_month.DS.$dir_id;

		return $path;
	}
}

