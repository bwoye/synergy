<?php
//C:\wamp\www\synergy\php\makefillefunctions.php

/**
 * function for making standing file names fro uploaded
 * monthly periods
 */
function mkfilename($period, $fileno, $xyear)
{
    $clfile = mynewfile($fileno);
    return $period . $xyear . $clfile;
}

/**
 * function for sorting multi-dimensional arrays
 */
function sortArray($myr, $mkey)
{
    $b = array();

    foreach ($myr as $k => $v) {
        $b[] = $v[$mkey];
    }
    asort($b);
    
    $mylist2 = array();
    foreach ($b as $v => $k) {
        $mylist2[] = $myr[$v];
    } 
    return $mylist2;
}

/**
 * Function for removing file extensions
 */
function removeExt($pt)
{
    $fm = array();
    foreach ($pt as $k) {
        $dd = explode('.', $k);
        $fm[] = $dd[0];
    }
    return $fm;
}

function getfilenoFromreturn($filename)
{
    //Explode the filename
    $myreturn = array();

    $months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'november', 'december');
    $gotfile = explode('.', $filename);

    //Get the file without extension from the array above
    $usefile = $gotfile[0];

    //Get the year from the array
    preg_match('/\d\d\d\d/', $usefile, $matches);
    $myreturn['filename'] = $filename;
    $myreturn['Year'] = $matches[0];

    //Get the name of the month
    $dmon = explode($matches[0], $usefile);
    $myreturn['month'] = $dmon[0];
    $tt = 1;
    foreach ($months as $fk) {
        if ($fk == $dmon[0]) {
            $myreturn['monthnum'] = $tt;
            $myreturn['forsort'] = $myreturn['Year'] . $tt;
            break;
        }
        $tt += 1;
    }
    return $myreturn;
}

/**
 * function for returning file number used on returns
 */
function mynewfile($fileno)
{
    $clfile = str_replace('/', '', $fileno);
    $clfile = str_replace('-', '', $clfile);

    return $clfile;
}

// http://localhost/synergy/php/makefilename.php
