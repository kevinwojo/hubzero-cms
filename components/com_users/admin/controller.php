<?php
/**
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Users master display controller.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @since		1.6
 */
class UsersController extends JControllerLegacy
{
	/**
	 * Checks whether a user can see this view.
	 *
	 * @param	string	$view	The view name.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function canView($view)
	{
		$canDo = UsersHelper::getActions();

		switch ($view)
		{
			// Special permissions.
			case 'groups':
			case 'group':
			case 'levels':
			case 'level':
				return $canDo->get('core.admin');
				break;

			// Default permissions.
			default:
				return true;
		}
	}

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// Load the submenu.
		UsersHelper::addSubmenu(Request::getCmd('view', 'users'));

		$view   = Request::getCmd('view', 'users');
		$layout = Request::getCmd('layout', 'default');
		$id     = Request::getInt('id');

		if (!$this->canView($view))
		{
			throw new Exception(Lang::txt('JERROR_ALERTNOAUTHOR'), 404);
			return;
		}

		// Check for edit form.
		if ($view == 'user' && $layout == 'edit' && !$this->checkEditId('com_users.edit.user', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(Lang::txt('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(Route::url('index.php?option=com_users&view=users', false));

			return false;
		}
		elseif ($view == 'group' && $layout == 'edit' && !$this->checkEditId('com_users.edit.group', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(Lang::txt('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(Route::url('index.php?option=com_users&view=groups', false));

			return false;
		}
		elseif ($view == 'level' && $layout == 'edit' && !$this->checkEditId('com_users.edit.level', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(Lang::txt('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(Route::url('index.php?option=com_users&view=levels', false));

			return false;
		}
		elseif ($view == 'note' && $layout == 'edit' && !$this->checkEditId('com_users.edit.note', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(Lang::txt('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(Route::url('index.php?option=com_users&view=notes', false));

			return false;
		}

		return parent::display();
	}
}
