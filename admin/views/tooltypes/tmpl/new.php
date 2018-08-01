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

Html::behavior('framework', true);

$this->css('typeNew');
$this->js('typeNew');

$component = $this->option;
$controller = $this->controller;
$createUrl = Route::url(
	"/administrator/index.php?option=$component&controller=$controller&task=create"
);
$tmpl = Request::getString('tmpl', '');
$type = $this->type;

?>

<form action="<?php echo $createUrl; ?>" method="POST" id="new-form">

	<?php if ($tmpl === 'component'): ?>
		<fieldset>
			<div class="configuration">
			</div>
		</fieldset>
	<?php endif; ?>

	<fieldset class="grid">
		<div class="col span10 offset1">
			<label>
				<?php echo Lang::txt('COM_TOOLBOX_TYPES_DESCRIPTION_LABEL'); ?>

				<span class="required">
					<?php echo Lang::txt('COM_TOOLBOX_COMMON_REQUIRED'); ?>
				</span>

				<input type="text" name="type[description]"
					value="<?php echo $type->get('description'); ?>">
			</label>
		</div>
	</fieldset>

	<?php echo Html::input('token'); ?>

	<input type="submit" id="new-form-submit"
		value="<?php echo Lang::txt('COM_TOOLBOX_TYPES_CREATE_BUTTON'); ?>">

</form>
