<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: skills.php' );
  die();
  
}

if( isset( $_POST['name'] ) )
{
  
  if( $_POST['name'] and $_POST['percent'] )
  {
    
    $query = 'UPDATE skills SET
      user_id = "'.( ( $_SESSION['id'] != 1 ) ? mysqli_real_escape_string( $connect, $_SESSION['id']) : mysqli_real_escape_string( $connect, $_POST['user']) ).'",
      name = "'.mysqli_real_escape_string( $connect, $_POST['name'] ).'",
      percent = "'.mysqli_real_escape_string( $connect, $_POST['percent'] ).'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query( $connect, $query );
    
    set_message( 'Skill has been updated' );
    
  }

  header( 'Location: skills.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT *
    FROM skills
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {
    
    header( 'Location: skills.php' );
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

<h2>Edit Skill</h2>

<form method="post">
  
  <?php

  if ( $_SESSION['id'] == 1 ) {
    echo '<label for="type">User:</label>';
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

  <label for="name">Name:</label>
  <input type="text" name="name" id="name" value="<?php echo htmlentities( $record['name'] ); ?>">
    
  <br>
  
  <label for="percent">Percent:</label>
  <input type="text" name="percent" id="percent" value="<?php echo htmlentities( $record['percent'] ); ?>">
    
  <br>
  
  <input type="submit" value="Edit Skill">
  
</form>

<p><a href="skills.php"><i class="fas fa-arrow-circle-left"></i> Return to Skill List</a></p>


<?php

include( 'includes/footer.php' );

?>