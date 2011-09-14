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

ximport('Hubzero_Module_Helper');
$config =& JFactory::getConfig();
$juser =& JFactory::getUser();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
 <head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	
	<link rel="stylesheet" href="/templates/hubbasic/css/main.css" type="text/css" />
	<link rel="stylesheet" href="/templates/hubbasic/css/error.css" type="text/css" />
	<link rel="stylesheet" href="/templates/hubbasic/html/mod_reportproblems/mod_reportproblems.css" type="text/css" />
	<script type="text/javascript" src="/media/system/js/mootools.js"></script>
	<script type="text/javascript" src="/templates/hubbasic/js/hub.js"></script>
	<script type="text/javascript" src="/modules/mod_reportproblems/mod_reportproblems.js"></script>

	<link rel="stylesheet" type="text/css" media="print" href="<?php echo $this->baseurl ?>/templates/hubbasic/css/print.css" />
	<!--[if IE 8]>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->baseurl ?>/templates/hubbasic/css/ie8.css" />
	<![endif]-->
	<!--[if lte IE 7]>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->baseurl ?>/templates/hubbasic/css/ie7.css" />
	<![endif]-->
 </head>
 <body>
<?php Hubzero_Module_Helper::displayModules('notices'); ?>
	<div id="top">
		<a name="top"></a>
		<p class="skip" id="to-content"><a href="#content">Skip to content</a></p>
		<p id="tab">
			<a href="/support/" title="Need help? Send a trouble report to our support team.">
				<span>Need Help?</span>
			</a>
		</p>
		<div class="clear"></div>
	</div><!-- / #top -->
	
	<?php Hubzero_Module_Helper::displayModules('helppane'); ?>
	
	<div id="header">
		<div id="header-wrap">
			<a name="header"></a>
			<h1>
				<a href="." title="<?php echo $config->getValue('config.sitename'); ?>">
					<?php echo $config->getValue('config.sitename'); ?> 
					<span id="tagline">A HUBzero site</span>
				</a>
			</h1>
		
			<ul id="toolbar" class="<?php if (!$juser->get('guest')) { echo 'loggedin'; } else { echo 'loggedout'; } ?>">
<?php
if (!$juser->get('guest')) {
	// Find the user's most recent support tickets
	ximport('Hubzero_Message');
	$database =& JFactory::getDBO();
	$recipient = new Hubzero_Message_Recipient( $database );
	$rows = $recipient->getUnreadMessages( $juser->get('id'), 0 );

	echo "\t\t\t\t".'<li id="logout"><a href="/logout"><span>Logout</span></a></li>'."\n";
	echo "\t\t\t\t".'<li id="myaccount"><a href="'.JRoute::_('index.php?option=com_members&id='.$juser->get('id')).'"><span>My Account</span></a></li>'."\n";
	echo "\t\t\t\t".'<li id="username"><a href="'.JRoute::_('index.php?option=com_members&id='.$juser->get('id')).'"><span>'.$juser->get('name').' ('.$juser->get('username').')</span></a></li>'."\n";
	echo "\t\t\t\t".'<li id="usermessages"><a href="'.JRoute::_('index.php?option=com_members&id='.$juser->get('id').'&active=messages&task=inbox').'">'.count($rows).' New Message(s)</a></li>'."\n";
} else {
	echo "\t\t\t\t".'<li id="login"><a href="/login" title="Login">Login</a></li>'."\n";
	echo "\t\t\t\t".'<li id="register"><a href="/register" title="Sign up for a free account">Register</a></li>'."\n";
}
?>
			</ul>
		
			<?php Hubzero_Module_Helper::displayModules('search'); ?>
		</div><!-- / #header-wrap -->
	</div><!-- / #header -->
	
	<div id="nav">
		<a name="nav"></a>
		<h2>Navigation</h2>
		<?php Hubzero_Module_Helper::displayModules('user3'); ?>
		<div class="clear"></div>
	</div><!-- / #nav -->

	<div id="trail">
		You are here: <?php
	$app =& JFactory::getApplication();
	$pathway =& $app->getPathway();

	$items = $pathway->getPathWay();
	$l = array();
	foreach ($items as $item)
	{
		$text = trim(stripslashes($item->name));
		if (strlen($text) > 50) {
			$text = $text.' ';
			$text = substr($text,0,50);
			$text = substr($text,0,strrpos($text,' '));
			$text = $text.' &#8230;';
		}
		$url = JRoute::_($item->link);
		$url = str_replace('%20','+',$url);
		$l[] = '<a href="'.$url.'">'.$text.'</a>';
	}
	echo implode(' &rsaquo; ',$l);
	?>
	</div><!-- / #trail -->

	<div id="wrap">
		<div id="content" class="<?php echo $option; ?>">
			<div id="content-wrap">
			<a name="content"></a>

			<div id="outline">
				<div id="errorbox" class="code-<?php echo $this->error->code ?>">
					<h2><?php echo $this->error->message ?></h2>

					<p><?php echo JText::_('You may not be able to visit this page because of:'); ?></p>

					<ol>
<?php if ($this->error->code != 403) { ?>
						<li><?php echo JText::_('An out-of-date bookmark/favourite.'); ?></li>
						<li><?php echo JText::_('A search engine that has an out-of-date listing for this site.'); ?></li>
						<li><?php echo JText::_('A mis-typed address.'); ?></li>
						<li><?php echo JText::_('The requested resource was not found.'); ?></li>
<?php } ?>
						<li><?php echo JText::_('This page may belong to a group with restricted access.  Only members of the group can view the contents.'); ?></li>
						<li><?php echo JText::_('An error has occurred while processing your request.'); ?></li>
					</ol>
<?php if ($this->error->code != 403) { ?>
					<p><?php echo JText::_('If difficulties persist, please contact the system administrator of this site.'); ?></p>
<?php } else { ?>
					<p><?php echo JText::_('If difficulties persist and you feel that you should have access to the page, please file a trouble report by clicking on the Help! option on the menu above.'); ?></p>
<?php } ?>
				</div>

				<form method="get" action="/search">
					<fieldset>
						<?php echo JText::_('Please try the'); ?> <a href="/index.php" title="<?php echo JText::_('Go to the home page'); ?>"><?php echo JText::_('Home Page'); ?></a> <span><?php echo JText::_('or'); ?></span> 
						<label>
							<?php echo JText::_('Search:'); ?> 
							<input type="text" name="searchword" value="" />
						</label>
						<input type="submit" value="<?php echo JText::_('Go'); ?>" />
					</fieldset>
				</form>
			</div>
<?php 
			if ($this->debug || $juser->get('username') == 'nkissebe' || $juser->get('username') == 'zooley') :
				echo "\t\t".'<div id="techinfo">'."\n";
				echo $this->renderBacktrace()."\n";
				echo "\t\t".'</div>'."\n";
			endif;
?>

			</div><!-- / #content-wrap -->
		</div><!-- / #content -->
	</div><!-- / #wrap -->
	
	<div id="footer">
		<a name="footer"></a>
		<!-- Start footer modules output -->
		<?php Hubzero_Module_Helper::displayModules('footer'); ?>
		<!-- End footer modules output -->
	</div><!-- / #footer -->
 </body>
</html>
<?php
$title = $this->getTitle();
$this->setTitle( $config->getValue('config.sitename').' - '.$title );
?>
