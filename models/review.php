<?php
/*
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
 * @author    Anthony Fuentes <fuentesa@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Components\Toolbox\Models;

$toolboxPath = Component::path('com_toolbox');

require_once "$toolboxPath/models/tool.php";

use Hubzero\Database\Relational;

class Review extends Relational
{

	/*
	 * Scope to class mapping
	 *
	 * @var   array
	 */
	protected $_scopeClassMap = [
		'tool' => 'Tool'
	];

	/*
	 * Records table
	 *
	 * @var string
	 */
	protected $table = '#__toolbox_reviews';

	/*
	 * Attributes to be populated on record creation
	 *
	 * @var array
	 */
	public $initiate = ['created'];

	/*
	 * Attribute validation
	 *
	 * @var  array
	 */
	protected $rules = [
		'user_id' => 'positive',
		'scope' => 'notempty',
		'scope_id' => 'positive',
		'content' => 'notempty'
	];

	/*
	 * Returns created date formatted
	 *
	 * @return   object
	 */
	public function formattedCreated()
	{
		$created = $this->get('created');

		$formattedCreated = date("g:i a d F Y ", strtotime($created));

		return $formattedCreated;
	}

	/*
	 * Returns the user that created the review
	 *
	 * @return   object
	 */
	public function user()
	{
		$userModelName = 'Hubzero\User\User';
		$foreignKey = 'user_id';

		$user = $this->belongsToOne($userModelName, $foreignKey)->row();

		return $user;
	}

	/*
	 * Returns the user's ID
	 *
	 * @return   object
	 */
	public function userId()
	{
		$user = $this->user();

		$userId = $user->get('id');

		return $userId;
	}

	/*
	 * Returns the user's name
	 *
	 * @return   object
	 */
	public function userName()
	{
		$user = $this->user();

		$userName = $user->get('username');

		return $userName;
	}

	/*
	 * Returns the subject of the review
	 *
	 * @return   object
	 */
	public function subject()
	{
		$subjectClass = $this->_getSubjectModel();
		$foreignKey = 'scope_id';

		$subject = $this->belongsToOne($subjectClass, $foreignKey)->row();

		return $subject;
	}

	/*
	 * Returns the subject's ID
	 *
	 * @return   object
	 */
	public function subjectId()
	{
		$subject = $this->subject();

		$subjectId = $subject->get('id');

		return $subjectId;
	}

	/*
	 * Returns the class of the associated subject
	 *
	 * @return   string
	 */
	public function _getSubjectModel()
	{
		$scope = $this->get('scope');

		$subjectClass = $this->_scopeClassMap[$scope];

		return $subjectClass;
	}

}
