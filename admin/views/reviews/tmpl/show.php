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

$this->css('reviewShow');

$component = $this->option;
$controller = $this->controller;
$permissions = $this->permissions;
$review = $this->review;
$approved = $review->get('approved');
$content = $this->escape($review->get('content'));
$created = $review->formattedCreated();
$id = $review->get('id');
$toolbarTitle = $this->title;
$username = $review->userName();

Toolbar::title($toolbarTitle);

if ($permissions->get('core.edit'))
{
	Toolbar::apply();
	Toolbar::save();
	Toolbar::spacer();
}
Toolbar::cancel();
?>

<form action="" method="post" name="adminForm" class="editform" id="item-form">
	<div class="grid">

		<div class="col span7">
			<fieldset class="adminform">
				<legend>
					<span>
						<?php echo Lang::txt('COM_TOOLBOX_REVIEW_FIELD_CONTENT'); ?>
					</span>
				</legend>
				<div class="content">
					<?php echo $content; ?>
				</div>
			</fieldset>
		</div>

		<div class="col span5">
			<table class="meta">
				<tbody>
					<tr>
						<th><?php echo Lang::txt('COM_TOOLBOX_REVIEW_FIELD_ID'); ?>:</th>
						<td>
							<?php echo $id; ?>
							<input type="hidden" name="id" value="<?php echo $id; ?>">
						</td>
					</tr>
					<tr>
						<th><?php echo Lang::txt('COM_TOOLBOX_REVIEW_FIELD_AUTHOR'); ?>:</th>
						<td>
							<?php echo $username; ?>
						</td>
					</tr>
					<tr>
						<th><?php echo Lang::txt('COM_TOOLBOX_REVIEW_FIELD_CREATED'); ?>:</th>
						<td>
							<?php echo $created; ?>
						</td>
					</tr>
				</tbody>
			</table>

			<fieldset class="adminform">
				<legend>
					<span>
						<?php echo Lang::txt('COM_TOOLBOX_REVIEW_FIELD_APPROVAL'); ?>
					</span>
				</legend>
				<div class="content">
					<select name="review[approved]">
						<option value="1" <?php if($approved) echo 'selected'; ?>>
							Approved
						</option>
						<option value="0" <?php if(!$approved) echo 'selected'; ?>>
							Unapproved
						</option>
					</select>
				</div>
			</fieldset>
		</div>

		<input type="hidden" name="option" value="<?php echo $component; ?>" />
		<input type="hidden" name="controller" value="<?php echo $controller; ?>" />
		<input type="hidden" name="task" value="save" />

	</div>
</form>
