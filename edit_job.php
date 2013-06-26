<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>
<?php include_once("includes/form_functions.php"); ?>
<?php
	// check that url value for page is an integer, otherwise redirect to index.php
	if (intval($_GET['job']) == 0) {
		redirect_to("index.php");
	}
	
	if (isset($_POST['submit'])) {
		
		$errors = array();
		
		// function disallows page title to be submitted if its over 30 characters
		$fields_with_lengths = array('job_title' => 'Job name');
		foreach($fields_with_lengths as $fieldname => $formalname ) {
			if (strlen(trim(mysql_escape_prep($_POST[$fieldname]))) > 50) { 
				$errors[] = $formalname; 
			}
		}
		
		// function disallows fields to be submitted if its empty
		$required_fields = array('job_title' => 'Job name',
								'location' => 'Location',
								'salary' => 'Salary',
								'job_type' => 'Job type',
								'job_ref' => 'Job reference',
								'position' => 'Position', 
								'visible' => 'Visible', 
								'content' => 'Content');
		foreach($required_fields as $fieldname => $formalname) {
			if (!isset($_POST[$fieldname]) || $_POST[$fieldname] == NULL) {
				$errors[] = $formalname;
			}
		}
		
		if (empty($errors)) {
			// perform page update
			$id = mysql_escape_prep($_GET['job']);
			$job_title = mysql_escape_prep($_POST['job_title']);
			$location = mysql_escape_prep($_POST['location']);
			$salary = mysql_escape_prep($_POST['salary']);
			$job_type = mysql_escape_prep($_POST['job_type']);
			$job_ref = mysql_escape_prep($_POST['job_ref']);
			$position = mysql_escape_prep($_POST['position']);
			$visible = mysql_escape_prep($_POST['visible']);
			$content = mysql_escape_prep($_POST['content']);
			
			$date = date_create();
			
			$query = "UPDATE jobs SET
						job_title = '{$job_title}',
						location = '{$location}',
						salary = {$salary},
						job_type = '{$job_type}',
						job_ref = '{$job_ref}',
						position = {$position},
						visible = {$visible},
						content = '{$content}'
					WHERE id = {$id}";
			$result = mysql_query($query, $connection);
			// confirm_query($result); // alternative error message
			if (mysql_affected_rows() == 1) {
				// Message was submitted
				$message = "The job was successfully updated.";
			} else {
			// Message failed to submit
				$message = "The job failed to update.";
				$message .= "<br />" . mysql_error();
			}
		} else {
			// $errors occurred
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		} 
		
	} // end: if (isset($_POST['submit']))

?>
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
			<h1>Edit Job Post: <?php echo $selected_job['job_title']; ?></h1>
			<?php if (!empty($message)) { echo "<p>" . $message . "</p>"; } ?>
			<?php if (isset($errors)) { display_errors($errors); } ?>
						
			<form action="edit_job.php?job=<?php echo urlencode($selected_job['id']); ?>" method="post">
				<p>Job name: 
					<input type="text" name="job_title" value="<?php echo $selected_job['job_title']; ?>" id="page_title" style="width:200px" />
				</p>
				<p>Location: 
					<input type="text" name="location" value="<?php echo $selected_job['location']; ?>" id="page_title" />
				</p>
				<p>Salary: 
					<input type="text" name="salary" value="<?php echo $selected_job['salary']; ?>" id="page_title" />
				</p>
				<p>Job type: 
					<input type="text" name="job_type" value="<?php echo $selected_job['job_type']; ?>" id="page_title" />
				</p>
				<p>Job reference: 
					<input type="text" name="job_ref" value="<?php echo $selected_job['job_ref']; ?>" id="page_title" />
				</p>
				<p>Position: 
					<select name="position">
						<?php
							$job_set = get_all_jobs($public);
							$job_count = mysql_num_rows($job_set);
							// $subject_count + 1 cos we are adding a subject
							for($count=1; $count <= $job_count; $count++) {
								echo "<option value=\"{$count}\"";
								if ($selected_job['position'] == $count){
									echo " selected";
								}
								echo ">{$count}</option>";
							}
						?>
					</select>
				</p>
				<p>Visible: 
					<input type="radio" name="visible" value="0"<?php if ($selected_job['visible'] == 0) { echo " checked"; } ?> /> No
					&nbsp;
					<input type="radio" name="visible" value="1"<?php if ($selected_job['visible'] == 1) { echo " checked"; } ?> /> Yes
				</p>
				<p>Content: <br />
					<textarea class="ckeditor" name="content"><?php echo $selected_job['content']; ?></textarea>
				</p>
				<input type="submit" name="submit" value="Edit Job Post" />
				&nbsp;&nbsp; 
				<a href="delete_job.php?job=<?php echo urlencode($selected_job['id']); ?>" onclick="return confirm('Are you sure you want to delete this job?')">Delete Job Post</a>
			</form>
			
			<p><a href="job_list.php">Cancel</a></p>
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
           	<?php echo get_recent_jobs($public = false); ?>
		</div>
    </div><!--#side-bar-right-->

<?php require("includes/footer.php"); ?>