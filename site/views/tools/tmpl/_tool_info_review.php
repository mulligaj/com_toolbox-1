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

// No direct access
defined('_HZEXEC_') or die();

$this->css('toolInfoReview');

use Hubzero\Date;

$review = $this->review;
$user = $this->user;
$userId = $user->get('id');
$username = $user->get('username');
$userPicture = $user->picture();
$userUrl = Route::url("index.php?option=com_members&id=$userId");
?>

<li class="review">

	<span>
		<img src="<?php echo $userPicture; ?>"
			alt="User <?php echo $username; ?>'s profile picture" />
	</span>

	<span class="review-content">
		<span class="review-header">
			<strong>
				<a href="<?php echo $userUrl; ?>">
					<?php echo $username; ?>
				</a>
			</strong>
			<span><?php echo date("g:i a d F Y ", strtotime($review->get('created'))); ?></span>
		</span>

		<span class="comment-body">
			<?php echo $review->get('content'); ?>
		</span>
	</span>

</li>
