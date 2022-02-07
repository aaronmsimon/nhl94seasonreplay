<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		// Globals
		$this->data['base_url'] = $this->config->item('base_url');
		$this->data['site_name'] = $this->config->item('site_name');
		$this->data['jquery_vers'] = $this->config->item('jquery_vers');
		$this->data['jqueryui_theme'] = $this->config->item('jqueryui_theme');
	}
}
