<?php

	function redirect_to($location = NULL){
		if ($location != NULL){
			header("Location: {$location}");
			exit;
		}
	}

	function mysql_escape_prep( $value ) {
		$magic_quotes_active = get_magic_quotes_gpc();
		$new_enough_php = function_exists( "mysql_real_escape_string" ); // i.e. PHP >= v4.3.0
		if( $new_enough_php ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if( $magic_quotes_active ) { $value = stripslashes( $value ); }
			$value = mysql_real_escape_string( $value );
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if( !$magic_quotes_active ) { $value = addslashes( $value ); }
			// if magic quotes are active, then the slashes already exist
		}
		return $value;
	}
	
	// if query fails, then kill the page load, and display error message
	function confirm_query($result_set){
		if (!$result_set) {
			die("Database query failed: " . mysql_error());
		}
	}
	
	function get_all_pages($public = true){
		global $connection;
		$query = "SELECT *
				FROM pages";
		// only show visible pages in the public website
		if ($public){ 
			$query .= " WHERE visible = 1";
		}
		$query .= " ORDER BY position ASC";
		$page_set = mysql_query($query, $connection);
		confirm_query($page_set);
		return $page_set;
	}
	
	function get_all_jobs($public = true){
		global $connection;
		$query = "SELECT *
				FROM jobs";
		if ($public){ 
			$query .= " WHERE visible = 1";
		}
		$query .= " ORDER BY position ASC";
		$job_set = mysql_query($query, $connection);
		confirm_query($job_set);
		return $job_set;
	}
	
	// Function can be used for getting both pages and jobs tables
	/*function get_all_rows($table){
		global $connection;
		$query = "SELECT * 
				FROM {$table}
				ORDER BY position ASC";
		$result_set = mysql_query($query, $connection);
		confirm_query($result_set);
		return $result_set;
	}*/
	
	function get_page_by_id($page_id){
		global $connection;
		$query = "SELECT * "; 
		$query .= "FROM pages ";
		$query .= "WHERE id=" . $page_id; 
		$query .= "	LIMIT 1";
		$page_row = mysql_query($query, $connection);
		confirm_query($page_row);
		if ($page = mysql_fetch_array($page_row)){
			return $page;
		} else {
			return NULL;
		}
	}
	
	function get_job_by_id($job_id){
		global $connection;
		$query = "SELECT * "; 
		$query .= "FROM jobs ";
		$query .= "WHERE id=" . $job_id; 
		$query .= "	LIMIT 1";
		$job_row = mysql_query($query, $connection);
		confirm_query($job_row);
		if ($job = mysql_fetch_array($job_row)){
			return $job;
		} else {
			return NULL;
		}
	}
	
	function find_selected_page() {
		global $selected_page;
		global $selected_job;
		if(isset($_GET["page"])){
			$selected_page = get_page_by_id($_GET["page"]);
			$selected_job = NULL;
		} elseif(isset($_GET["job"])) {
			$selected_page = NULL;
			$selected_job = get_job_by_id($_GET["job"]);
		} else {
			$selected_job = NULL;
			$selected_page = NULL;
		}
	}
	
	function navigation($selected_page, $public = true) {
		$output = "<ul id=\"category-menu\">";
		// catch the database query to get all pages
		$page_set = get_all_pages($public);
		// loop all returned pages 
		while ($page = mysql_fetch_array($page_set)) {
			$output .= "<li";
			if ($selected_page["id"] == $page["id"]){
				$output .= " class=\"selected\"";
			}
			// output page rows by their names in the associate array/database
			$output .= "><a href=\"";
			// show the public website
			if ($public) {
				$output .= "recruitment.php";
			// show the staff area
			} else {
				$output .= "content.php";
			}
			$output .= "?page=" . urlencode($page["id"]) . "\">" . $page["page_title"] . "</a></li>";
		}
		$output .= "</ul>";
		return $output;
	}
	
	function get_recent_jobs($public = true) {
		$job_set = get_all_jobs($public);
		while ($job = mysql_fetch_array($job_set)) {
			$query = "<strong><a href=\"";
			if ($public) {
				$query .= "recruitment.php";
			} else {
				$query .= "content.php";
			}
			$query .= "?job=" . urlencode($job["id"]) . "\">" . $job["job_title"] . "</a></strong>";
			$query .= "<ul>";
			$query .= "<li>{$job["location"]}</li>";
			$query .= "<li>{$job["job_type"]}</li>";
			$query .= "<li>&pound;{$job["salary"]}</li>";
			$query .= "</ul>";
			echo $query;
		}
	}
		
?>