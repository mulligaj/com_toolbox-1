<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

class Migration20180727092931ComToolboxAddComponent extends Base
{

	public function up()
	{
		$this->addComponentEntry('toolbox');
	}

	public function down()
	{
		$this->deleteComponentEntry('toolbox');
	}

}
