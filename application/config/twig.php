<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Twig Configuration
|--------------------------------------------------------------------------
|
| help from here:  http://acmeextension.com/integrate-twig-with-codeigniter/
|
*/
$config['twig']['template_dir'] = VIEWPATH;
$config['twig']['template_ext'] = 'php';
$config['twig']['environment']['autoescape'] = TRUE;
$config['twig']['environment']['cache'] = FALSE;
$config['twig']['environment']['debug'] = FALSE;
