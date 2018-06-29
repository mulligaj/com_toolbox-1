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

$action = $this->action;
$controller = $this->controller;
$option = $this->option;
$step = $this->step;
$tags = $this->tags;
$tool = $this->tool;
$toolId = $tool->get('id');
$selectedTagsIds = $this->selectedTagsIds;
$forwardUrl = Route::url(
	"index.php?option=$option&controller=$controller&task=review&id=$toolId"
);
$originUrl = Route::url(
	"index.php?option=$option&controller=$controller&task=edittags&id=$toolId"
);
?>

<form id="hubForm" class="full" method="post" action="<?php echo $action; ?>">

	<fieldset>
		<legend><?php echo Lang::txt('COM_TOOLBOX_TAGS_TOOLS_LEGEND'); ?></legend>
		<div class="grid">
			<div id="related-field-wrapper" class="col span12">

				<select name="tagsIds[]" size="20" multiple="multiple">
					<?php
					foreach ($tags as $tag):
					$tagId = $tag->get('id')
					?>
					<option value="<?php echo $tagId; ?>"
						<?php if (in_array($tagId, $selectedTagsIds)) echo 'selected'; ?>>
						<?php echo $tag->get('tag'); ?>
					</option>
					<?php endforeach; ?>
				</select>

			</div>
		</div>
	</fieldset>


	<input type="hidden" name="step" value="<?php echo $step; ?>" />

	<?php echo Html::input('token'); ?>
	<input type="hidden" name="origin" value="<?php echo $originUrl; ?>">
	<input type="hidden" name="forward" value="<?php echo $forwardUrl; ?>">

	<input class="btn btn-success" type="submit"
		value="<?php echo Lang::txt('COM_TOOLBOX_COMMON_SAVE_CONTINUE'); ?>">

</form>

<style>
#related-field-wrapper {
	padding: 1.5em 0 0 0;
}
.btn-success {
	max-width: 15%;
	float: right;
}
</style>
