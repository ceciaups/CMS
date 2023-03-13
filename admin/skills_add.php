<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( isset( $_POST['name'] ) )
{
  
  if( $_POST['name'] and $_POST['percent'] )
  {
    
    $query = 'INSERT INTO skills (
        user_id,
        name,
        percent
      ) VALUES (
         "'.( ( $_SESSION['id'] != 1 ) ? mysqli_real_escape_string( $connect, $_SESSION['id']) : mysqli_real_escape_string( $connect, $_POST['user']) ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['name'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['percent'] ).'"
      )';
    mysqli_query( $connect, $query );
    
    set_message( 'Skill has been added' );
    
  }
  
  header( 'Location: skills.php' );
  die();
  
}

include( 'includes/header.php' );

if ( $_SESSION['id'] == 1 ) {
  $query = 'SELECT *
    FROM users';
  $result = mysqli_query( $connect, $query );
}

?>

<h2>Add Skill</h2>

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

  <label for="name">Name:</label>
  <input type="text" name="name" id="name">
    
  <br>
  
  <label for="percent">Percent:</label>
  <input type="text" name="percent" id="percent">
  
  <br>
  
  <input type="submit" value="Add Skill">
  
</form>

<p><a href="skills.php"><i class="fas fa-arrow-circle-left"></i> Return to Skill List</a></p>


<?php

include( 'includes/footer.php' );

?>