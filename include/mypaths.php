<?php
//Define directory separator
define('DS',DIRECTORY_SEPARATOR);

//Define the root directory
define('ROOT',$_SERVER['DOCUMENT_ROOT'].DS.'synergy');
//Define php folder
define('PHP',ROOT.DS.'php');

//Define classes directory
define('MYCLASSES',PHP.DS.'classes');

//Define models path
define('MODELS',PHP.DS.'models');

//Define include dir
define('INCLUDES',ROOT.DS.'include');

//Define monthly reports path
define('MONTHLY',ROOT.DS.'monthlyreports');
//echo MONTHLY;

include_once INCLUDES.DS.'sesscheck.php';
include_once INCLUDES.DS.'connector.php';

include_once MYCLASSES.DS.'datatables.php';
include_once MYCLASSES.DS.'DataObject.php';