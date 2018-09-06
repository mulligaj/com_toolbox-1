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
$forwardUrl = Route::url(
	"index.php?option=$option&controller=$controller&task=context"
);
$originUrl = Route::url(
	"index.php?option=$option&controller=$controller&task=frameworks"
);
$query = $this->query;
?>

<form id="hubForm" class="full" method="post" action="<?php echo $action; ?>">

		<div class="fieldset-wrapper">
			<?php
				$this->view('_aacu_rubric_fields')
				->set('query', $query)
				->display();
			?>
		</div>

		<div class="fieldset-wrapper">
			<?php
				$this->view('_idc_fields')
				->set('query', $query)
				->display();
			?>
		</div>

		<div class="fieldset-wrapper">
			<?php
				$this->view('_bergs_fields')
				->set('query', $query)
				->display();
			?>
		</div>

		<div class="fieldset-wrapper">
			<?php
				$this->view('_other_fields')
				->set('query', $query)
				->display();
			?>
		</div>

		<?php echo Html::input('token'); ?>
		<input type="hidden" name="origin" value="<?php echo $originUrl; ?>">
		<input type="hidden" name="forward" value="<?php echo $forwardUrl; ?>">

		<input class="btn btn-success" type="submit"
			value="<?php echo Lang::txt('COM_TOOLBOX_COMMON_NEXT'); ?>">

</form>
