<?php ob_start(); ?> // start output buffering as headers are being used for redirects 
<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?> // function confirms if the user is logged in. Otherwise they are redirected to the login page 
<?php find_selected_page(); ?> // function grabs values from the url to decide what page or job is selected 
<?php include("includes/header.php"); ?>
  
    <div id="side-bar-left">
        <div class="element">
			<?php 
				// call navigation function to display the staff area nav
				// staff area nav displays pages that have been set invisible to the public area
				// staff can then select this page to edit or set visible to the public area
				echo navigation($selected_page, $public = false); 
			?>
			
			<p><a href="new_page.php">+ Add a new page</a></p>
			<p><a href="new_job.php">+ Add a new job</a></p>
			<p><a href="job_list.php">Job list</a></p>
		</div>
	</div><!--#side-bar-left-->
    
    <div id="content">
		<?php 
			// display the selected page if pages are selected
			if (!is_null($selected_page)) { 
				echo "<h1>{$selected_page["page_title"]}</h1>";
				echo "<p>" . $selected_page["content"] . "</p>";
				echo "<p><a href=\"edit_page.php?page=" . $selected_page["id"] . "\">Edit Page</a></p>";
			// otherwise, display the selected job if a job is selected
			} elseif (!is_null($selected_job)) { 
				$output = "<h1>{$selected_job["job_title"]}</h1>";
				$output .= "<p><strong>Location: </strong>" . $selected_job["location"] . "</p>";
				$output .= "<p><strong>Salary: </strong>&pound;" .  $selected_job["salary"] . "</p>";
				$output .= "<p><strong>Job type: </strong>" . $selected_job["job_type"] . "</p>";
				$output .= "<p><strong>Job reference: </strong>" . $selected_job["job_ref"] . "</p>";
				$output .= "<p><strong>Posted on: </strong>" . date("jS F Y", strtotime($selected_job["date_posted"])) . "</p>";
				$output .= "<p><strong>Job description:</strong><br />" . $selected_job["content"] . "</p>";
				$output .= "<p><a href=\"edit_job.php?job=" . $selected_job["id"] . "\">Edit Job Post</a></p>";
				echo $output;
			// if neither a page or a job is selected, redirect to a default page (page 1)
			} else {
				redirect_to("content.php?page=1");
			} 
		?>
		
  	</div><!--#content-->
    
    <div id="side-bar-right">
        <div class="element">
			<div class="element-header">Subscribe</div>
			<p>Subscribe to our mailing list and receive jobs updates on a weekily basis.</p>
			<form action="" method="get">
				<p>Name:</p>
				<input name="" type="text" />
				<p>Email:</p>
				<input name="" type="text" />
				<input type="submit" value="Submit" name='submit' />
			</form>
		</div>
		<div class="element">
			<div class="element-header">Latest Jobs</div>
	           	<?php 
	           		// display all recently added jobs
	           		get_recent_jobs($public = false); 
	           	?>
		</div>
    </div><!--#side-bar-right-->

<?php require("includes/footer.php"); ?>
<?php ob_flush(); ?> // destroy the output buffering 
