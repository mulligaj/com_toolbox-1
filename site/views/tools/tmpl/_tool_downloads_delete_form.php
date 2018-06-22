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

$controller = $this->controller;
$downloads = $this->downloads;
$formAction = Route::url(
	"index.php?option=$this->option&controller=downloads&task=destroy"
);
$option = $this->option;
$toolId = $this->toolId;
$editDownloadsUrl = Route::url(
	"index.php?option=$option&controller=$controller&task=editdownloads&id=$toolId"
);
?>

<div class="grid">
	<h3>Current Downloads:</h3>
	<form action="<?php echo $formAction; ?>" method="POST">
		<ul id="downloads">
			<?php foreach($downloads as $download): ?>
				<?php if ($download->get('desynchronized')) continue; ?>
				<li>
					<input type="checkbox" name="downloads[]" value="<?php echo $download->get('id'); ?>">
					<a href="<?php echo $download->url(); ?>" download>
						<?php echo $download->get('name'); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php echo Html::input('token'); ?>
		<input type="hidden" name="origin" value="<?php echo $editDownloadsUrl; ?>">
		<input type="hidden" name="forward" value="<?php echo $editDownloadsUrl; ?>">

		<input type="submit" value="Delete" class="btn btn-danger">
	</form>
</div>

<style>
#downloads {
	list-style: none;
	margin: 0 0 0 .5em;
}
</style>
