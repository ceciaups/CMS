<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

// If the form has been submitted
if( isset( $_POST['career'] ) )
{
  
  // Check for minimum required content
  if( $_POST['career'] AND $_POST['location'] AND $_POST['start_date'] AND $_POST['career_type_id'] )
  {
    
    $query = 'INSERT INTO career (
        career,
        location,
        start_date,
        end_date,
        career_type_id,
        user_id
      ) VALUES (
         "'.mysqli_real_escape_string( $connect, $_POST['career'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['location'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['start_date'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['end_date'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['career_type_id'] ).'",
         "'.( ( $_SESSION['id'] != 1 ) ? mysqli_real_escape_string( $connect, $_SESSION['id']) : mysqli_real_escape_string( $connect, $_POST['user']) ).'"
      )';
    mysqli_query( $connect, $query );
    
    $skills_length = count($_POST['skills']);

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
        $query_add_career_skills .= '('.$_GET['id'].', '.$$_POST['skills'][$i].')';
      }

      mysqli_query( $connect, $query_add_career_skills );

    }

    set_message( 'Career has been added' );
    
  }
  
  header( 'Location: career.php' );
  die();
  
}

include( 'includes/header.php' );

if ( $_SESSION['id'] == 1 ) {
  $query_user = 'SELECT *
    FROM users';
  $result_user = mysqli_query( $connect, $query_user );
} else {
  $query_skills = 'SELECT *
  FROM skills 
  WHERE user_id = '.$_SESSION['id'];
  $result_skills = mysqli_query( $connect, $query_skills );
}


$query_career_type = 'SELECT *
  FROM career_type';
$result_career_type = mysqli_query( $connect, $query_career_type );

?>

<h2>Add Career</h2>

<form method="post">
  
  <?php

  if ( $_SESSION['id'] == 1 ) {
    echo '<label for="user">User:</label>';
    echo '<select name="user" id="user">';
    while( $record_user = mysqli_fetch_assoc( $result_user ) ):
      if ($record_user['id'] != 1) {
        echo '<option value="'.$record_user['id'].'"';
        echo '>'.$record_user['first'].' '.$record_user['last'].'</option>';
      }
    endwhile;
    echo '</select>';
    echo '<br>';
  }

  ?>

  <label for="career">Career:</label>
  <input type="text" name="career" id="career">
  
  <br>

  <label for="location">Location:</label>
  <input type="text" name="location" id="location">
    
  <br>
  
  <label for="start_date">Start Date:</label>
  <input type="date" name="start_date" id="start_date">
  
  <br>

  <label for="end_date">End Date:</label>
  <input type="date" name="end_date" id="end_date">
  
  <br>

  <label for="career_type_id">Career Type:</label>
  <select name="career_type_id" id="career_type_id">

    <?php
    
    while( $record_career_type = mysqli_fetch_assoc( $result_career_type ) ):
      echo '<option value="'.$record_career_type['career_type_id'].'"';
      echo '>'.$record_career_type['career_type'].'</option>';
    endwhile;
    
    ?>

  </select>
    
  <br>
  
  <?php

  if ( $_SESSION['id'] != 1 ) {
    echo '<label>Skills:</label><div class="skills-container">';
    while( $record_skills = mysqli_fetch_assoc( $result_skills ) ):
      echo '<div class="skills">';
      echo '<input type="checkbox" name="skills[]" value="'.$record_skills['id'].'" />';
      echo '<label>'.$record_skills['name'].'</label>';
      echo '</div>';
    endwhile;
    echo '</div><br>';
  }

  ?>

  <input type="submit" value="Add Career">
  
</form>

<p><a href="career.php"><i class="fas fa-arrow-circle-left"></i> Return to Career List</a></p>


<?php

include( 'includes/footer.php' );

?>