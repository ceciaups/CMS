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
    
    set_message( 'Project has been updated' );
    
  }

  header( 'Location: projects.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT *
    FROM projects
    WHERE id = '.$_GET['id'].'
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
  
  <input type="submit" value="Edit Project">
  
</form>

<p><a href="projects.php"><i class="fas fa-arrow-circle-left"></i> Return to Project List</a></p>


<?php

include( 'includes/footer.php' );

?>