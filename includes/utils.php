<?php

	/*
	 * utils.php
	 * 
	 * Various functions and wrappers
	 * 
	 */
	

	// lbt stands for "Little Bobby Tables".
	// Google it.
	function lbt($inStr)
	{
//		$inStr = stripslashes($inStr);
		$inStr = str_replace("\\", "\\\\", $inStr);
		$inStr = str_replace("'", "\'", $inStr);
		$inStr = str_replace("`", "\`", $inStr);
		$inStr = str_replace(";", "\;", $inStr);
		return $inStr;
	}
	
	// Action is a varible often passed in as a GET variable.  This 
	// function will make sure that the user hasn't changed it to 
	// something else.  Only "edit", "add" and "delete" are allowed.
	function validateAction($action) 
	{
		if ($action != "edit" and $action !="add" and $action !="delete" and $action != "open" and $action != "close") return "";
		else return $action;
	}
	
	// retPage is a variable often passed in as a GET variable.
	// Validates the return page to make sure that the user hasnt' inserted something 
	// else into this value
	// TODO Currently a blank function
	function validateRetpage($retPage)
	{
		return $retPage;
	}
	

	// function to close out result after a storedproc call to avoid the dreaded "out of sync" error.
	function freeResult($mysqli) {
		while(mysqli_next_result($mysqli)) {
			if($result = mysqli_store_result($mysqli)){
				mysqli_free_result($result);
			}
			if (!$mysqli->more_results()) {
				break;
			}
		}
	}
		
	// Checks that the user is logged in, if they are then
	// reset the cookie for another 20 minutes (1200 seconds)
	function getLoggedinUser()
	{
		$cookie_name = "pixie";
		if(!isset($_COOKIE[$cookie_name])) {
			return "";
		}
		
		list($userName,$isAdmin) = explode(",", $_COOKIE[$cookie_name]);
		setcookie($cookie_name, $userName.",".$isAdmin, time() + 1200, "/");
		return [$userName,$isAdmin];
	}

	// Connect to the localhost database
	function DBConnect()
	{
		$username = 'pixie';
		$password = 'd13go';
		$hostspec = 'localhost';
		$database = 'pixie';
		$phptype  = 'mysql';
		$port = 3306;
		
		// connect to the database, get information on the current animal
		$mysqli = new mysqli($hostspec, $username, $password, $database, $port);
		if ($mysqli->connect_errno) errorPage($mysqli->connect_errno, $mysqli->error);
		return $mysqli;
	}
	
	// Format an address to an address block 
	function prettyAddress($address1, $address2, $city, $state, $zip) {
		$addressStr = "";
		$addressStr .= ($address1?$address1."<br>":"");
		$addressStr .= ($address2?$address2."<br>":"");
		$addressStr .= ($city?$city . ", ":"");
		$addressStr .= ($state?$state . ", ":"");
		$addressStr .= ($zip?$zip . ", ":"");
		return substr($addressStr, 0, -2);
	}
		
	// Returns the difference between two dates in days.
	function diffDays($date1, $date2, $abs=true)
	{
//		if (strtotime($date1) > strtotime($date2)) {
//			$temp=$date2;
//			$date2=$date1;
//			$date1=$temp;
//		}

		if ($abs) $diff = abs(strtotime($date2) - strtotime($date1));
		else $diff = (strtotime($date2) - strtotime($date1));

		return floor($diff / (60*60*24));
	}


	// Returns a "pretty" age.  Check the animal ages in the DB to see how this works.
	// Basically it will show the number of years, months, days that a animal is,
	// dropping off less specific strings as the age goes up. 
	function  prettyAge($date1, $date2, $showAll = false)
	{
		$retStr = "";
		if ($date1 and $date2 and ($date1 != '0000-00-00')) {

			$diff = abs(strtotime($date2) - strtotime($date1));

			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

			
			if ($showAll) 
				$retStr = "$years year".($years==1?"":"s").", $months month".($months==1?"":"s").", $days day".($days==1?"":"s");
			elseif ($years > 2) 
				$retStr = "$years year".($years==1?"":"s");
			elseif ($years > 0) 
				$retStr = "$years year".($years==1?"":"s"). ($months>0?", $months month".($months==1?"":"s"):"");
			elseif ($months > 3) 
				$retStr = "$months month".($months==1?"":"s");
			elseif ($months > 0) 
				$retStr = "$months month".($months==1?"":"s") . ($days>0?", $days day".($days==1?"":"s"):"");
			else 
				$retStr = "$days day".($days==1?"":"s");
		}

		return $retStr;
	}
	
	// Returns a string in the MySQL format (Y-m-d)
	function DateTime2MySQL($inDate) {
		if ($inDate != "") return date("Y-m-d H-i-s", strtotime($inDate));
		else return "";
	}

	// Returns a string in the MySQL format (Y-m-d)
	function Date2MySQL($inDate) {
		if ($inDate != "") return date("Y-m-d", strtotime($inDate));
		else return "";
	}
	
	// Returns a string in the standard format (d/m/y)
	function MySQL2DateTime($inDate) {
		$preferredDateStr = 'm/d/y h:i a';
		if ($inDate != "")
			return date($preferredDateStr, strtotime($inDate));	
		else return "";
	}


	// Returns a string in the standard format (d/m/y)
	function MySQL2Date($inDate) {
		$preferredDateStr = 'm/d/y';
		if ($inDate != "")
			return date($preferredDateStr, strtotime($inDate));	
		else return "";
	}

	// Returns a string in the standard time
	function MySQL2Time($inDate) {
		$preferredDateStr = 'h:i:sa';
		if ($inDate != "")
			return date($preferredDateStr, strtotime($inDate));	
		else return "";
	}

    function AddDays($inDate, $days) {
        $date=date_create(Date2MySQL($inDate));
		date_add($date,new DateInterval('P'.$days.'D'));
		$nextDose = date_format($date,"Y-m-d");
        return $nextDose;
    }
