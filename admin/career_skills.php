<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( !isset( $_GET['id'] ) )
{
  
  set_message( 'ERROR: 1' );
  header( 'Location: career.php' );
  die();
  
}

if( isset( $_POST['skills'] ) )
{

  $new_skills = $_POST['skills'];

  // Get existing career-skills
  $query_career_skills = 'SELECT * FROM career_skills WHERE career_id = '.$_GET['id'];
  $result_career_skills = mysqli_query( $connect, $query_career_skills );
  while( $record_career_skills = mysqli_fetch_assoc( $result_career_skills ) ):
    if (in_array($record_career_skills['skills_id'], $new_skills)) {
    // Remains unchanged for the same career-skills
      $index = array_search($record_career_skills['skills_id'], $new_skills);
      array_splice($new_skills, $index, 1);
    }
    else {
      // Delete old career-skills
      $query_del_career_skills = 'DELETE FROM career_skills WHERE career_skills_id = '.$record_career_skills['career_skills_id'];
      mysqli_query( $connect, $query_del_career_skills );
    }
  endwhile;

  $skills_length = count($new_skills);
  
  // Add new career-skills only if there is a new skill
  if ($skills_length) {
    $query_add_career_skills = 'INSERT INTO career_skills (
      career_id,
      skills_id
    ) VALUES ';
    for ($i = 0; $i < $skills_length; $i++) {
      if ($i != 0) {
        $query_add_career_skills .= ',';
      }
      $query_add_career_skills .= '('.$_GET['id'].', '.$new_skills[$i].')';
    }

      mysqli_query( $connect, $query_add_career_skills );

  }
  
  set_message( 'Career has been updated' );
    
  header( 'Location: career.php' );
  die();
  
}

if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT c.career_id, c.career, c.location, c.start_date, c.end_date, c.user_id, GROUP_CONCAT(s.name SEPARATOR ", ") AS skills
    FROM career c
    JOIN career_skills cs ON c.career_id = cs.career_id
    JOIN skills s ON cs.skills_id = s.id
    WHERE c.career_id = '.$_GET['id'].'
    GROUP BY c.career_id 
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {

    header( 'Location: career.php' );
    die();
    
  }
  
  $record = mysqli_fetch_assoc( $result );
  
}

include( 'includes/header.php' );

$query_skills = 'SELECT *
FROM skills 
WHERE user_id = '.$record['user_id'];
$result_skills = mysqli_query( $connect, $query_skills );

?>

<h2>Edit Career</h2>

<form method="post" enctype="multipart/form-data">
  
  <label>Skills:</label>

  <div class="skills-container">

    <?php 

    $skills_checked = explode(", ", $record['skills']);
    while( $record_skills = mysqli_fetch_assoc( $result_skills ) ):
      echo '<div class="skills">';
      echo '<input type="checkbox" name="skills[]" value="'.$record_skills['id'].'" ';
      foreach ($skills_checked as $skill) {
        if ($record_skills['name'] == $skill) {
          echo 'checked ';
        }
      }
      echo '/>';
      echo '<label>'.$record_skills['name'].'</label>';
      echo '</div>';
    endwhile;
    
    ?>

  </div>
  
  <br>

  <input type="submit" value="Save Skills">
  
</form>

<p><a href="career.php"><i class="fas fa-arrow-circle-left"></i> Return to Career List</a></p>


<?php

include( 'includes/footer.php' );

?>