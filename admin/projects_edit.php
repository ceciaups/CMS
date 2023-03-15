<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: projects.php' );
  die();
  
}

if( isset( $_POST['title'] ) )
{
  
  if( $_POST['title'] and $_POST['content'] )
  {
    
    $query = 'UPDATE projects SET
      user_id = "'.( ( $_SESSION['id'] != 1 ) ? mysqli_real_escape_string( $connect, $_SESSION['id']) : mysqli_real_escape_string( $connect, $_POST['user']) ).'",
      title = "'.mysqli_real_escape_string( $connect, $_POST['title'] ).'",
      content = "'.mysqli_real_escape_string( $connect, $_POST['content'] ).'",
      url = "'.mysqli_real_escape_string( $connect, $_POST['url'] ).'",
      github = "'.mysqli_real_escape_string( $connect, $_POST['github'] ).'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query( $connect, $query );

    if ( $_SESSION['id'] != 1 ) {
      $new_skills = $_POST['skills'];

      // Get existing projects-skills
      $query_skills = 'SELECT * FROM projects_skills WHERE project_id = '.$_GET['id'];
      $result_skills = mysqli_query( $connect, $query_skills );
      while( $record = mysqli_fetch_assoc( $result_skills ) ):
        if (in_array($record['skill_id'], $new_skills)) {
        // Remains unchanged for the same projects-skills
          $index = array_search($record['skill_id'], $new_skills);
          array_splice($new_skills, $index, 1);
        }
        else {
          // Delete old projects-skills
          $query_del_skills = 'DELETE FROM projects_skills WHERE id = '.$record['id'];
          mysqli_query( $connect, $query_del_skills );
        }
      endwhile;

      // Add new projects-skills
      $query_add_skills = 'INSERT INTO projects_skills (
        project_id,
        skill_id
      ) VALUES ';
      $skills_length = count($new_skills);
      for ($i = 0; $i < $skills_length; $i++) {
        if ($i != 0) {
          $query_add_skills .= ',';
        }
        $query_add_skills .= '('.$_GET['id'].', '.$new_skills[$i].')';
      }

      if ($skills_length) {
        mysqli_query( $connect, $query_add_skills );
      }
    }
    
    set_message( 'Project has been updated' );
    
  }

  header( 'Location: projects.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT p.id, p.user_id, p.title, p.content, p.url, p.github, p.photo, GROUP_CONCAT(s.name SEPARATOR ", ") AS skills
    FROM projects p
    LEFT JOIN projects_skills ps ON p.id = ps.project_id
    LEFT JOIN skills s ON ps.skill_id = s.id
    WHERE p.id = '.$_GET['id'].'
    GROUP BY p.id
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {
    
    header( 'Location: projects.php' );
    die();
    
  }
  
  $record = mysqli_fetch_assoc( $result );
  
}

include( 'includes/header.php' );

if ( $_SESSION['id'] == 1 ) {
  $query_user = 'SELECT *
    FROM users';
  $result_user = mysqli_query( $connect, $query_user );
}
else {
  $query_skills = 'SELECT *
  FROM skills 
  WHERE user_id = '.$_SESSION['id'];
  $result_skills = mysqli_query( $connect, $query_skills );
}

?>

<h2>Edit Project</h2>

<form method="post">

  <?php

  if ( $_SESSION['id'] == 1 ) {
    echo '<label for="user">User:</label>';
    echo '<select name="user" id="user">';
    while( $record_user = mysqli_fetch_assoc( $result_user ) ):
      if ($record_user['id'] != 1) {
        echo '<option value="'.$record_user['id'].'"';
        if ( $record['user_id'] == $record_user['id'] ) {
          echo ' selected';
        }
        echo '>'.$record_user['first'].' '.$record_user['last'].'</option>';
      }
    endwhile;
    echo '</select>';
    echo '<br>';
  }

  ?>
  
  <label for="title">Title:</label>
  <input type="text" name="title" id="title" value="<?php echo htmlentities( $record['title'] ); ?>">
    
  <br>
  
  <label for="content">Content:</label>
  <textarea type="text" name="content" id="content" rows="5"><?php echo htmlentities( $record['content'] ); ?></textarea>
  
  <script>

  ClassicEditor
    .create( document.querySelector( '#content' ) )
    .then( editor => {
        console.log( editor );
    } )
    .catch( error => {
        console.error( error );
    } );
    
  </script>
  
  <br>
  
  <label for="url">URL:</label>
  <input type="text" name="url" id="url" value="<?php echo htmlentities( $record['url'] ); ?>">
    
  <br>

  <label for="github">GitHub:</label>
  <input type="text" name="github" id="github" value="<?php echo htmlentities( $record['github'] ); ?>">
    
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
  
  <input type="submit" value="Edit Project">
  
</form>

<p><a href="projects.php"><i class="fas fa-arrow-circle-left"></i> Return to Project List</a></p>


<?php

include( 'includes/footer.php' );

?>