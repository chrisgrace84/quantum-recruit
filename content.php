<?php ob_start(); ?>
<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>
<?php include("includes/header.php"); ?>
  
    <div id="side-bar-left">
        <div class="element">
			<?php echo navigation($selected_page, $public = false); ?>
			
			<p><a href="new_page.php">+ Add a new page</a></p>
			<p><a href="new_job.php">+ Add a new job</a></p>
			<p><a href="job_list.php">Job list</a></p>
		</div>
	</div><!--#side-bar-left-->
    
    <div id="content">
		<?php 
			if (!is_null($selected_page)) { 
				echo "<h1>{$selected_page["page_title"]}</h1>";
				echo "<p>" . $selected_page["content"] . "</p>";
				echo "<p><a href=\"edit_page.php?page=" . $selected_page["id"] . "\">Edit Page</a></p>";
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
           	<?php get_recent_jobs($public = false); ?>
		</div>
    </div><!--#side-bar-right-->

<?php require("includes/footer.php"); ?>
<?php ob_flush(); ?>