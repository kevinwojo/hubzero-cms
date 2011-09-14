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

/**
 * Short description for 'XSearchPhrase'
 * 
 * Long description (if any) ...
 */
class XSearchPhrase
{

	/**
	 * Description for '_text'
	 * 
	 * @var unknown
	 */
	private $_text  = NULL;     // The original search text - should NEVER BE CHANGED


	/**
	 * Description for '_stem'
	 * 
	 * @var unknown
	 */
	private $_stem  = NULL;     // A flag for if we should stem words or not


	/**
	 * Description for '_data'
	 * 
	 * @var array
	 */
	private $_data  = array();  // Processed text


	/**
	 * Description for '_error'
	 * 
	 * @var unknown
	 */
	private $_error = NULL;     // Error holder

	//-----------


	/**
	 * Short description for '__construct'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $text Parameter description (if any) ...
	 * @param      boolean $stem Parameter description (if any) ...
	 * @return     void
	 */
	public function __construct( $text=NULL, $stem=false )
	{
		$this->_text = $text;
		$this->_stem = $stem;
		$this->searchTokens = array();
	}

	/**
	 * Short description for '__set'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $property Parameter description (if any) ...
	 * @param      unknown $value Parameter description (if any) ...
	 * @return     void
	 */
	public function __set($property, $value)
	{
		$this->_data[$property] = $value;
	}

	/**
	 * Short description for '__get'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $property Parameter description (if any) ...
	 * @return     array Return description (if any) ...
	 */
	public function __get($property)
	{
		if (isset($this->_data[$property])) {
			return $this->_data[$property];
		}
	}

	/**
	 * Short description for 'process'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     unknown Return description (if any) ...
	 */
	public function process()
	{
		if (trim($this->_text) == '') {
			return;
		}

		// An array for all the keywords
		$words = array();
		$phrases = array();

		$keyword = stripslashes($this->_text);
		// Look for anything in quotes, indicating an exact phrase search
		if (preg_match_all('/"([^"]*)"|\'([^\']*)\'/', $keyword, $matches)) {
			// Find all the matches and store them in the phrases array
			// then remove them from the keyword string
			foreach ($matches[0] as $match)
			{
				$keyword = str_replace($match, '', $keyword);

				$phrases[] = trim(substr($match, 1, -1));
			}

			$keyword = trim($keyword);
		}

		// Explode the remaining keyword string into individual words
		$bits = explode(' ', $keyword);

		// Loop through each word
		foreach ($bits as $bit)
		{
			// Trim it and make sure it's actually a word
			// Prevents cases with double spaces between words. example: Joe  Smith
			$bit = trim($bit);
			if ($bit != '') {
				$words[] = $bit;
				// Are we stemming?
				if ($this->_stem) {
					// Get the stem
					$stem = PorterStemmer::Stem($bit);
					// Make sure it's different than the original word
					if ($stem != $bit) {
						$words[] = $stem;
					}
				}
			}
		}

		$this->searchPhrases = $phrases;
		$this->searchWords   = $words;
		$this->searchTokens  = array_merge($phrases, $words);
		$this->searchText    = $this->_text;
	}
}

