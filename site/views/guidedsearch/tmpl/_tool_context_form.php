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

$this->css('guidedContext');

$action = $this->action;
$controller = $this->controller;
$option = $this->option;
$forwardUrl = Route::url(
	"index.php?option=$option&controller=tools&task=list"
);
$originUrl = Route::url(
	"index.php?option=$option&controller=$controller&task=context"
);
$query = $this->query;
?>

<form id="hubForm" class="full" method="post" action="<?php echo $action; ?>">

	<div class="fieldset-wrapper">
		<h4>
			<?php echo Lang::txt('COM_TOOLBOX_GUIDED_CONTEXT_SUBGROUP_SIZE'); ?>
			<span class="required">
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_REQUIRED'); ?>
			</span>
		</h4>

		<div class="grid">
			<div class="col span3">
				<?php
					$this->view('_subgroup_select')
						->set('query', $query)
						->display();
				?>
			</div>
		</div>
	</div>

	<div class="fieldset-wrapper">
		<h4>
			<?php echo Lang::txt('COM_TOOLBOX_GUIDED_CONTEXT_EXTERNAL_COST'); ?>
			<span class="required">
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_REQUIRED'); ?>
			</span>
		</h4>

		<span class="inline-radio">
			<input type="radio" name="query[external_cost]" value="1"
				<?php if (!!$query->get('external_cost')) echo 'checked'; ?>>
			<?php echo Lang::txt('COM_TOOLBOX_COMMON_YES'); ?>
		</span>
		<span class="inline-radio">
			<input type="radio" name="query[external_cost]" value="0"
				<?php if (!$query->get('external_cost')) echo 'checked'; ?>>
			<?php echo Lang::txt('COM_TOOLBOX_COMMON_NO'); ?>
		</span>
	</div>

	<div class="fieldset-wrapper">
		<h4>
			<?php echo Lang::txt('COM_TOOLBOX_GUIDED_CONTEXT_DURATION'); ?>
			<span class="required">
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_REQUIRED'); ?>
			</span>
		</h4>

		<div id="duration-fields" class="grid">
			<div class="col span1">
				<input type="number" name="query[duration_min]" min="0"
					value="<?php echo $query->get('duration_min'); ?>"
					placeholder="min">
			</div>

			<div class="col span1 text">
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_TO'); ?>
			</div>

			<div class="col span1">
				<input type="number" name="query[duration_max]" min="0"
					value="<?php echo $query->get('duration_max'); ?>"
					placeholder="max">
			</div>

			<div class="col span1 text">
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_MINUTES'); ?>
			</div>
		</div>
	</div>

	<?php echo Html::input('token'); ?>
	<input type="hidden" name="origin" value="<?php echo $originUrl; ?>">
	<input type="hidden" name="forward" value="<?php echo $forwardUrl; ?>">

	<input class="btn btn-success" type="submit"
		value="<?php echo Lang::txt('COM_TOOLBOX_COMMON_SEARCH'); ?>">

</form>
