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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

//----------------------------------------------------------
// Extended database class
//----------------------------------------------------------

class ToolVersion extends  JTable
{
	var $id      	   = NULL;  // @var int (primary key)
	var $toolid        = NULL;  // @var int (11)
	var $toolname      = NULL;  // @var string (15)
	var $instance      = NULL; // @var string (30)
	var $title         = NULL;  // @var string (127)
	var $description   = NULL;  // @var text
	var $fulltext      = NULL;  // @var text
	var $toolaccess    = NULL;  // @var string (15)
	var $codeaccess	   = NULL;  // @var string (15)
	var $wikiaccess	   = NULL;  // @var string (15)
	var $version       = NULL;  // @var string (15)
	var $revision 	   = NULL;  // @var int
	var $state         = NULL;  // @var int (11)
	var $vnc_geometry  = NULL;  // @var string (15)
	var $vnc_command   = NULL;  // @var string (100)
	var $mw   		   = NULL;  // @var string (15)
	var $released      = NULL;  // @var dateandtime
	var $released_by   = NULL;  // @var string
	var $unpublished   = NULL;  // @var dateandtime
	var $license	   = NULL;  // @var text
	var $params        = NULL;  // @var text
	var $exportControl = NULL;  // @var string (15)

	//-----------

	public function __construct( &$db )
	{
		parent::__construct( '#__tool_version', 'id', $db );
	}

	public function check()
	{
		if (!$this->id && trim( $this->toolname ) == '') {
			$this->setError( JText::_('CONTRIBTOOL_ERROR_VERSION_NO_TOOLNAME') );
			return false;
		}

		if (!$this->id && trim( $this->title ) == '') {
			$this->setError( JText::_('CONTRIBTOOL_ERROR_VERSION_NO_TITLE') );
			return false;
		}

		if (!$this->id && trim( $this->revision) == '') {
			$this->setError( JText::_('CONTRIBTOOL_ERROR_VERSION_NO_REVISION') );
			return false;
		}

		if (!$this->id && trim( $this->version ) == '') {
			$this->setError( JText::_('CONTRIBTOOL_ERROR_VERSION_NO_VERSION') );
			return false;
		}

		return true;
	}
	//-----------

	public function loadFromInstance( $tool=NULL )
	{
		if ($tool === NULL) {
			return false;
		}

		$query  = "SELECT * FROM $this->_tbl AS v WHERE v.instance='".$tool."' LIMIT 1";

		$this->_db->setQuery( $query );
		if ($result = $this->_db->loadAssoc()) {
			return $this->bind( $result );
		} else {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
	}

	public function getAll($includedev = 1)
	{

		$sql = "SELECT * FROM #__tool_version";
		if(!$includedev) {
		$sql.= " WHERE state!='3'";
		}

		$this->_db->setQuery( $sql );
		return $this->_db->loadObjectList();
	}
	//-----------

	public function getVersions( $alias )
	{
		// will load versions excluding dev
		if ($alias === NULL) {
			$alias = $this->toolname;
		}
		if (!$alias) {
			return false;
		}

		$rd = new ResourcesDoi( $this->_db );

		$query  = "SELECT v.*, d.doi_label as doi ";
		$query .= "FROM $this->_tbl as v ";
		$query .= "LEFT JOIN $rd->_tbl as d ON d.alias=v.toolname  AND d.local_revision=v.revision ";
		$query .= "WHERE v.toolname = '".$alias."' AND v.state!=3 ORDER BY v.revision DESC";

		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}

	public function getVersionIdFromResource($rid=NULL, $version ='dev')
	{
		if ($rid=== NULL) {
			return false;
		}

		$query = "SELECT v.id FROM #__tool_version as v JOIN #__resources as r ON r.alias = v.toolname WHERE r.id='".$rid."'";
		if($version=='dev') {
		$query.= " AND v.state=3 LIMIT 1";
		}
		else if($version=='current') {
		$query.= " AND v.state=1 ORDER BY revision DESC LIMIT 1";
		}
		else {
		$query.= " AND v.version='".$version."' LIMIT 1";
		}

		$this->_db->setQuery( $query );
		return $this->_db->loadResult();
	}

	public function loadFromName( $alias )
	{
		if ($alias === NULL) {
			return false;
		}

		$query  = "SELECT * FROM $this->_tbl as v WHERE v.toolname='".$alias."' AND state='1' ORDER BY v.revision DESC LIMIT 1";

		$this->_db->setQuery( $query );
		if ($result = $this->_db->loadAssoc()) {
			return $this->bind( $result );
		} else {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
	}

	public function load_version ( $toolid=NULL, $version='dev' )
	{
		if ($toolid === NULL) {
			return false;
		}
		$query = "SELECT * FROM $this->_tbl WHERE toolid='".$toolid."' AND ";
		if(!$version or $version=='dev') {
		$query.= "state='3'";
		}
		else if ($version=='current') {
		$query.= "state='1'";
		}
		else {
		$query.= "version='".$version."'";
		}
		$query.=" ORDER BY revision DESC LIMIT 1";
		$this->_db->setQuery($query );
		return $this->_db->loadObject( $this );
	}
	//-----------

	public function setUnpublishDate($toolid=NULL, $toolname='', $vid=0)
	{
		if (!$toolid) {
			return false;
		}
		if($toolname or $vid) {
			$query = "UPDATE #__tool_version SET unpublished='".date( 'Y-m-d H:i:s' )."' WHERE ";
			if($toolname)  {
			$query.= "toolname='".$toolname."' ";
			}
			else if($vid) {
			$query.= "id='".$vid."' ";
			}
			$query.= "AND state='1'";
			$this->_db->setQuery( $query);
			if($this->_db->query()) { return true; }
		}
		else {
			return false;
		}

	}
	//-----------

	public function unpublish($toolid=NULL, $vid=0)
	{
	    	$xlog = &Hubzero_Factory::getLogger();

		if (!$toolid) {
			return false;
		}

		$query = "UPDATE #__tool_version SET state='0', unpublished='".date( 'Y-m-d H:i:s' )."' WHERE ";
		if(intval($vid)) {
		$query.= "id='".$vid."' AND ";
		}
		$query.= "toolid='".$toolid."' AND state='1'";

		$this->_db->setQuery( $query);

		$xlog->logDebug(__FUNCTION__ . "()  $query");

		if($this->_db->query()) { return true; }
		else {
			return false;
		}
	}
	//-----------

	public function save($toolid=NULL, $version='dev', $create_new = 0)
	{
		die('1');
	    	$xlog = &Hubzero_Factory::getLogger();

		if(!$this->toolid) {
			$this->toolid= $toolid;
		}
		if (!$this->toolid) {
			return false;
		}

		$query = "SELECT id FROM #__tool_version WHERE toolid='".$this->toolid."'";
		if(!$version or $version=='dev') {
		$query.= " AND state='3'";
		}
		else if ($version=='current') {
		$query.= " AND state='1'";
		}
		else {
		$query.= " AND version='".$version."'";
		}
		$query.=" ORDER BY revision DESC LIMIT 1";

		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();

		$this->id = $result ? $result : 0;

		$xlog->logDebug(__FUNCTION__ . " $toolid $version $create_new ");

		if ((!$result && $create_new) or $this->id)
		{
			if (!$this->store()) {
			$this->setError( JText::_('CONTRIBTOOL_ERROR_VERSION_UPDATE_FAILED') );
			return false;
			}
		}

		return true;

	}

	public function store()
	{
	    	if (empty($this->id))
		{
	    		$xlog = &Hubzero_Factory::getLogger();
	    		$query = "SELECT id FROM #__tool_version WHERE toolname=" . $this->_db->Quote($this->toolname) .
		    		" AND instance=" . $this->_db->Quote($this->instance) . ";";
        		$this->_db->setQuery($query);
	     	$result = $this->_db->loadResult();

			if ($result)
			{
				$xlog->logDebug(__FUNCTION__ . " someone created this before me. Fixed");
			}

          	$this->id = $result ? $result : 0;
		}

	    	return parent::store();
	}
	//-----------

	public function getToolVersions( $toolid, &$versions, $toolname='', $ldap=false, $exclude_dev = 0)
	{
		ximport('Hubzero_Tool');
	    	$xlog =& Hubzero_Factory::getLogger();

		$objA = new ToolAuthor( $this->_db);

		if($ldap) {
		    	$xlog->logDebug("getToolVersions(): ldap read DEPRECATED.");
			$ldap = false;
		}

		$query  = "SELECT v.*, d.doi_label as doi ";
		$query .= "FROM #__tool_version as v LEFT JOIN #__doi_mapping as d ON d.alias = v.toolname AND d.local_revision=v.revision ";
		if($toolid) {
			$query .= "WHERE v.toolid = '".$toolid."' ";
		} else if($toolname) {
			$query .= "WHERE v.toolname = '".$toolname."' ";
		}
		if(($toolname or $toolid) && $exclude_dev) {
			$query .= "AND v.state != '3'";
		}
		$query .= " ORDER BY v.state DESC, v.revision DESC";

		$this->_db->setQuery( $query );
		$versions = $this->_db->loadObjectList();

		if($versions) {
			foreach($versions as $version) {
				// get list of authors
				if($version->state!=3) {
					$version->authors = $objA->getToolAuthors($version->id);
				}
				else {
					$rid = Hubzero_Tool::getResourceId($version->toolid);
					$version->authors = $objA->getToolAuthors('dev', $rid);
				}
			}
		}

		return $versions;
	}
	//-----------

	public function getVersionInfo($id, $version='', $toolname='', $instance='', $ldap=false)
	{
	    	$xlog =& Hubzero_Factory::getLogger();

	    	if ($ldap) {
		 	$xlog->logDebug("getVersionInfo() ldap read DEPRECATED");
			$ldap = false;
		}

		// data comes from mysql
		$juser  =& JFactory::getUser();
		$query  = "SELECT v.*, d.doi_label as doi ";
		$query .= "FROM #__tool_version as v LEFT JOIN #__doi_mapping as d ON d.alias = v.toolname AND d.local_revision=v.revision ";
		if($id) {
			$query .= "WHERE v.id = '".$id."' ";
		}
		else if($version && $toolname) {
			$query.= "WHERE v.toolname='".$toolname."' ";
			if($version=='current') {
				$query .= "AND v.state=1 ORDER BY v.revision DESC LIMIT 1 ";
			}
			else if($version=='dev') {
				$query .= "AND v.state=3 LIMIT 1";
			}
			else {
				$query .= "AND v.version = '".$version."' ";
			}
		}
		else if($instance) {
			$query.= "WHERE v.instance='".$instance."' ";
		}
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}

	public function compileResource ($thistool, $curtool='', $resource, $revision, $config)
	{
		if($curtool) {
		//print_r($thistool);
			$resource->curversion    = $curtool->version;
			$resource->currevision   = $curtool->revision;
			$resource->cursource   	 = ($curtool->codeaccess=='@OPEN') ? 1: 0;
			if(!$thistool) {
				$resource->revision      = $curtool->revision;
				$revision 			 	 = $resource->revision;
				$resource->version       = $curtool->version;
				$resource->versionid     = $curtool->id;
				$resource->tool      	 = $curtool->instance;
				$resource->toolpublished = 1;
				$resource->license 		 = $curtool->license;
				$resource->title         = stripslashes($curtool->title);
				$resource->introtext     = stripslashes($curtool->description);
				$resource->fulltext      = $curtool->fulltext;
				$resource->toolsource    = ($curtool->codeaccess=='@OPEN') ? 1: 0;
				$resource->doi 			 = $curtool->doi;
			}

		}

		if($thistool) {
			$resource->revision      = ($thistool) ? $thistool->revision : 1;
			$resource->revision      = ($revision !='dev') ? $resource->revision : 'dev';
			$revision 			 	 = $resource->revision;
			$resource->versionid     = ($revision && $thistool) ? $thistool->id  : 0;
			$resource->version       = ($revision && $thistool) ? $thistool->version  : 1;
			$resource->tool      	 = ($revision && $thistool) ? $thistool->instance : $resource->alias.'_r'.$revision;
			$resource->toolpublished = ($revision && $thistool) ? $thistool->state    : 1;
			$resource->license 		 = ($revision && $thistool) ? $thistool->license  : '';
			$resource->title         = ($revision && $thistool) ? stripslashes($thistool->title) : $resource->title;
			$resource->introtext     = ($revision && $thistool && isset($thistool->description)) ? stripslashes($thistool->description) : $resource->introtext;
			$resource->fulltext      = ($revision && $thistool && isset($thistool->fulltext)) ? $thistool->fulltext : $resource->fulltext;
			$resource->toolsource    = ($thistool && isset($thistool->codeaccess) && $thistool->codeaccess=='@OPEN') ? 1: 0;
			$resource->doi 			 = ($thistool && isset($thistool->doi)) ? $thistool->doi: 0;
		}
		else if(!$curtool) {
			$resource->revision      = 1;
			$revision 			 	 = $resource->revision;
			$resource->version       = 1;
			$resource->versionid     = 0;
			$resource->tool      	 = $resource->alias.'_r'.$revision;
			$resource->toolpublished = 1;
			$resource->license 		 = '';
			$resource->title         = $resource->title;
			$resource->introtext     = $resource->introtext;
			$resource->fulltext      = $resource->fulltext;
			$resource->toolsource    = 0;
			$resource->doi 			 = 0;
		}
			$resource->revision      = ($revision !='dev') ? $resource->revision : 'dev';

		// Get some needed libraries
		//include_once( JPATH_ROOT.DS.'components'.DS.'com_resources'.DS.'helpers'.DS.'html.php' );
		$resource->tarname = $resource->alias.'-r'.$resource->revision.'.tar.gz';
		$tarball_path = $config->get('sourcecodePath');
		$resource->tarpath = $tarball_path.DS.$resource->alias.DS;
		// Is tarball available?
		$resource->taravailable = (file_exists( $resource->tarpath . $resource->tarname )) ? 1 : 0;

		return true;

	}

	//----------------------------------------------------------
	// Validate input
	//----------------------------------------------------------

	public function validLicense($toolname, $license, $code, &$error, $result=0)
	{

		preg_replace( '/\[([^]]+)\]/', ' ', $license['text'], -1, $bingo );

		if(!$license['text']) {
			$error = JText::_('ERR_LICENSE_EMPTY') ;
		}
		else if ($bingo) {
			$error = JText::_('ERR_LICENSE_DEFAULTS') ;

		}
		else if(!$license['authorize'] && $code=='@OPEN') {
			$error = JText::_('ERR_LICENSE_AUTH_MISSING') ;
		}
		else {
			$result = 1;
		}

		//--------
		return $result;

	}
	//-----------

	public function validToolReg(&$tool, &$err, $id, $ldap, $config, $checker=0, $result=1)
	{
	    	$xlog =& Hubzero_Factory::getLogger();

		if ($ldap) {
		    	$xlog->logDebug("validToolReg() ldap read DEPRECATED");
			$ldap = false;
		}

		$tgObj = new ToolGroup($this->_db);

		//  check if toolname exists in tool table
		$query  = "SELECT t.id ";
		$query .= "FROM #__tool as t ";
		$query .= "WHERE t.toolname LIKE '". $tool['toolname']."' ";
		if($id) {
		$query .= "AND t.id!='". $id."' ";
		}

		$this->_db->setQuery( $query );
		$checker =$this->_db->loadResult();

		if ($checker or (in_array($tool['toolname'], array('test','shortname','hub','tool')) && !$id)) {
			$err['toolname'] = JText::_('ERR_TOOLNAME_EXISTS');
		}
		else if (ereg('^[a-zA-Z0-9]{3,15}$',$tool['toolname']) == '' && !$id ) {
			$err['toolname'] = JText::_('ERR_TOOLNAME');
		}

		// check if title can be used - tool table	
		$query  = "SELECT title, toolname ";
		$query .= "FROM #__tool ";
		if($id) {
		$query .= "WHERE id!='". $id."' ";
		}

		$this->_db->setQuery( $query );
		$rows = $this->_db->loadObjectList();
		if($rows) {
			for($i=0, $n=count( $rows ); $i < $n; $i++) {
				if(strtolower($rows[$i]->title) == strtolower($tool['title']) && $rows[$i]->toolname != $tool['toolname'] ) {
				$checker = 1;
				}
			}

		}

		$tool['toolname'] = strtolower($tool['toolname']);	// make toolname lower case by default	

		if ($checker) {  // check if title exists for other tools
			$err['title'] = JText::_('ERR_TITLE_EXISTS');
		}

		else if ($tool['title']=='') {
			$err['title'] = JText::_('ERR_TITLE');
		}

		if ($tool['description']=='') {
			$err['description'] = JText::_('ERR_DESC');
		}

		if ($tool['version']) {
			$this->validVersion($tool['toolname'], $tool['version'], $error_v, $ldap, 0);
			if($error_v) { $err['version'] = $error_v; }
		}

		if ($tool['exec']=='') {
			$err['exec'] = JText::_('ERR_EXEC');
		}

		if($tool['exec']=='@GROUP' && $tool['membergroups']=='') {
			$err['membergroups'] = JText::_('ERR_GROUPS_EMPTY');
			$tool['membergroups'] = array();
		}
		else if($tool['membergroups']=='' or $tool['exec']!='@GROUP') {
			$tool['membergroups'] = array();
		}
		else if($tool['exec']=='@GROUP') {
			$tool['membergroups'] = $tgObj->writeMemberGroups($tool['membergroups'], $id, $this->_db, $error_g);
			if($error_g) { $err['membergroups'] = $error_g; }
		}

		if ($tool['code']=='') {
			$err['code'] = JText::_('ERR_CODE');
		}

		if ($tool['wiki']=='') {
			$err['wiki'] = JText::_('ERR_WIKI');
		}

		if($tool['developers']=='') {
			$tool['developers']=array();
			$err['developers'] =  JText::_('ERR_TEAM_EMPTY');
		}
		else {
			$tool['developers'] = $tgObj->writeTeam($tool['developers'], $id, $this->_db, $error_t);
			if($error_t) { $err['developers'] = $error_t; }
		}

		// format some data
		$vnc     = isset($config->parameters['default_vnc']) ? $config->parameters['default_vnc'] : '780x600';
		if($tool['vncGeometryX'] && $tool['vncGeometryY']  && !ereg('[^0-9]' , $tool['vncGeometryX']) && !ereg('[^0-9]' , $tool['vncGeometryY']) ) {
					$tool['vncGeometry'] =$tool['vncGeometryX'].'x'.$tool['vncGeometryY'] ;
		}
		else { $tool['vncGeometry']= $vnc; }

		// return result and errors
		if(count($err) > 0) { $result = 0; }

		return $result;

	}

	public function validVersion($toolname, $newversion, &$error, $ldap=0, $required=1, $result=1)
	{
		$toolhelper = new ContribtoolHelper();

		if($required && !$newversion) { // was left blank
			$result = 0;
			$error = JText::_('ERR_VERSION_BLANK');
		}

		else if ($toolhelper->check_validInput($newversion)) { // illegal characters
			$result = 0;
			$error = JText::_('ERR_VERSION_ILLEGAL');
		}

		else if($required) {

			$this->getToolVersions( '', $versions, $toolname, $ldap, 1);

			if($versions) {
				foreach ($versions as $t) {
					if(strtolower($t->version) == strtolower($newversion)) {
					$result = 0;
					$error = JText::_('ERR_VERSION_EXISTS');
					}
				}
			}
		}

		return $result;

	}
	//-----------

	public function getToolname ($instance) {

		$database =& JFactory::getDBO();
		$query  = "SELECT toolname FROM #__tool_version WHERE instance='".$instance."' LIMIT 1";
		$this->_db->setQuery( $query );
		$toolname = $this->_db->loadResult();
		if(!$toolname) { $toolname = $instance; }
		return $toolname;
	}

	public function getCurrentVersionProperty ($toolname, $property) {

		$database =& JFactory::getDBO();
		$query  = "SELECT ".$property." FROM #__tool_version  WHERE toolname='".$toolname."' AND state=1 ORDER BY revision DESC LIMIT 1";
		$this->_db->setQuery( $query );
		return $this->_db->loadResult();
	}

	public function getDevVersionProperty ($toolname, $property) {

		$database =& JFactory::getDBO();
		$query  = "SELECT ".$property." FROM #__tool_version WHERE toolname='".$toolname."' AND state=3 ORDER BY revision DESC LIMIT 1";
		$this->_db->setQuery( $query );
		return $this->_db->loadResult();
	}

}

?>