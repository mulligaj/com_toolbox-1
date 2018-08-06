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

use Hubzero\Utility\Str;

$i = $this->i;
$k = $this->k;

$component = $this->option;
$controller = $this->controller;
$review = $this->review;
$approved = $review->get('approved');
$content = Str::truncate(
	$this->escape($review->get('content')),
	125,
	['exact' => true]
);
$created = $review->formattedCreated();
$id = $review->get('id');
$approveUrl = Route::url(
	"index.php?option=$component&controller=$controller&task=approve&id=$id"
);
$unapproveUrl = Route::url(
	"index.php?option=$component&controller=$controller&task=unapprove&id=$id"
);
$url = Route::url(
	"/administrator/index.php?option=$component&controller=$controller&task=show&id=$id"
);
$userId = $review->userId();
$userName = $review->userName();
?>

<tr class="<?php echo "row$k"; ?>">

	<td>
		<input class="record-checkbox" type="checkbox" name="reviewIds[]" id="cb<?php echo $i; ?>"
			value="<?php echo $id; ?>" />
	</td>

	<td class="priority-5">
		<a href="<?php echo $url; ?>">
			<?php echo $id; ?>
		</a>
	</td>

	<td>
		<?php echo $content; ?>
	</td>

	<td>
		<?php echo $userName; ?>
	</td>

	<td>
		<?php echo $created; ?>
	</td>

	<td>
		<?php if ($approved): ?>
			<a href="<?php echo $unapproveUrl; ?>">
				<span class="state publish">
					<span><?php echo Lang::txt('UNPUBLISH'); ?></span>
				</span>
			</a>
		<?php else: ?>
			<a href="<?php echo $approveUrl; ?>">
				<span class="state unpublish">
					<span><?php echo Lang::txt('PUBLISH'); ?></span>
				</span>
			</a>
		<?php endif; ?>
	</td>

</tr>
