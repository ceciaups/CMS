<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: career.php' );
  die();
  
}

if( isset( $_POST['career'] ) )
{
  if( $_POST['career'] AND $_POST['location'] AND $_POST['start_date'] AND $_POST['career_type_id'] )
  {
    
    $query = 'UPDATE career SET
      career = "'.mysqli_real_escape_string( $connect, $_POST['career'] ).'",
      location = "'.mysqli_real_escape_string( $connect, $_POST['location'] ).'",
      start_date = "'.mysqli_real_escape_string( $connect, $_POST['start_date'] ).'",
      end_date = "'.mysqli_real_escape_string( $connect, $_POST['end_date'] ).'",
      career_type_id = "'.mysqli_real_escape_string( $connect, $_POST['career_type_id'] ).'",
      user_id = "'.( ( $_SESSION['id'] != 1 ) ? mysqli_real_escape_string( $connect, $_SESSION['id']) : mysqli_real_escape_string( $connect, $_POST['user']) ).'"
      WHERE career_id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query( $connect, $query );
    
    if ( $_SESSION['id'] != 1 ) {
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
    }

    set_message( 'Career has been updated' );
    
  }

  header( 'Location: career.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT c.career_id, c.career, c.location, c.start_date, c.end_date, GROUP_CONCAT(s.name SEPARATOR ", ") AS skills
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

<h2>Edit Career</h2>

<form method="post">
  
  <?php

  if ( $_SESSION['id'] == 1 ) {
    echo '<label for="user">User:</label>';
    echo '<select name="user" id="user" select="">';
    while( $record_user = mysqli_fetch_assoc( $result_user ) ):
      if ($record_user['id'] != 1) {
        echo '<option value="'.$record_user['id'].'"';
        if ($record['user_id'] == $record_user['id']) {
          echo 'selected = "selected"';
        }
        echo '>'.$record_user['first'].' '.$record_user['last'].'</option>';
      }
    endwhile;
    echo '</select>';
    echo '<br>';
  }

  ?>

  <label for="career">Career:</label>
  <input type="text" name="career" id="career" value="<?php echo htmlentities( $record['career'] ); ?>">
  
  <br>

  <label for="location">Location:</label>
  <input type="text" name="location" id="location" value="<?php echo htmlentities( $record['location'] ); ?>">
    
  <br>
  
  <label for="start_date">Start Date:</label>
  <input type="date" name="start_date" id="start_date" value="<?php echo htmlentities( $record['start_date'] ); ?>">
  
  <br>

  <label for="end_date">End Date:</label>
  <input type="date" name="end_date" id="end_date" value="<?php echo htmlentities( $record['end_date'] ); ?>">
  
  <br>

  <label for="career_type_id">Career Type:</label>
  <select name="career_type_id" id="career_type_id" value="<?php echo htmlentities( $record['career_type_id'] ); ?>">

    <?php
    
    while( $record_career_type = mysqli_fetch_assoc( $result_career_type ) ):
      echo '<option value="'.$record_career_type['career_type_id'].'"';
      if ($record['career_type_id'] == $record_career_type['career_type_id']) {
        echo 'selected = "selected"';
      }
      echo '>'.$record_career_type['career_type'].'</option>';
    endwhile;
    
    ?>

  </select>
    
  <br>
  
  <?php

  if ( $_SESSION['id'] != 1 ) {
    echo '<label>Skills:</label><div class="skills-container">';
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
    echo '</div><br>';
  }

  ?>

  <input type="submit" value="Edit Career">
  
</form>

<p><a href="career.php"><i class="fas fa-arrow-circle-left"></i> Return to Career List</a></p>


<?php

include( 'includes/footer.php' );

?>