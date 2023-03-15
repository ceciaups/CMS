<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( isset( $_POST['title'] ) )
{

  if( $_POST['title'] and $_POST['content'] )
  {
    
    $query = 'INSERT INTO projects (
        user_id,
        title,
        content,
        url,
        github
      ) VALUES (
         "'.( ( $_SESSION['id'] != 1 ) ? mysqli_real_escape_string( $connect, $_SESSION['id']) : mysqli_real_escape_string( $connect, $_POST['user']) ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['title'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['content'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['url'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['github'] ).'"
      )';
    mysqli_query( $connect, $query );

    if ( $_SESSION['id'] != 1 ) {
      $query_skills = 'INSERT INTO projects_skills (
        project_id,
        skill_id
      ) VALUES ';
      $skills_length = count($_POST['skills']);
      for ($i = 0; $i < $skills_length; $i++) {
        if ($i != 0) {
          $query_skills .= ',';
        }
        $query_skills .= '('.$connect->insert_id.', '.$_POST['skills'][$i].')';
      }
      mysqli_query( $connect, $query_skills );
    }
    
    set_message( 'Project has been added' );
    
  }
  
  header( 'Location: projects.php' );
  die();
  
}

include( 'includes/header.php' );

if ( $_SESSION['id'] == 1 ) {
  $query = 'SELECT *
    FROM users';
  $result = mysqli_query( $connect, $query );
}
else {
  $query = 'SELECT *
  FROM skills 
  WHERE user_id = '.$_SESSION['id'];
  $result = mysqli_query( $connect, $query );
}

?>

<h2>Add Project</h2>

<form method="post">
  
  <?php

  if ( $_SESSION['id'] == 1 ) {
    echo '<label for="type">User:</label>';
    echo '<select name="user" id="user">';
    while( $record = mysqli_fetch_assoc( $result ) ):
      if ($record['id'] != 1) {
        echo '<option value="'.$record['id'].'"';
        echo '>'.$record['first'].' '.$record['last'].'</option>';
      }
    endwhile;
    echo '</select>';
    echo '<br>';
  }
  
  ?>

  <label for="title">Title:</label>
  <input type="text" name="title" id="title">
    
  <br>
  
  <label for="content">Content:</label>
  <textarea type="text" name="content" id="content" rows="10"></textarea>
      
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
  <input type="text" name="url" id="url">
  
  <br>
  
  <label for="github">GitHub:</label>
  <input type="text" name="github" id="github">
  
  <br>

  <?php

  if ( $_SESSION['id'] != 1 ) {
    echo '<label>Skills:</label><div class="skills-container">';
    while( $record = mysqli_fetch_assoc( $result ) ):
      echo '<div class="skills">';
      echo '<input type="checkbox" name="skills[]" value="'.$record['id'].'" />';
      echo '<label>'.$record['name'].'</label>';
      echo '</div>';
    endwhile;
    echo '</div><br>';
  }

  ?>
  
  <input type="submit" value="Add Project">
  
</form>

<p><a href="projects.php"><i class="fas fa-arrow-circle-left"></i> Return to Project List</a></p>


<?php

include( 'includes/footer.php' );

?>