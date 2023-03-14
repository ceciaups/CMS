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
    
    set_message( 'Career has been updated' );
    
  }

  header( 'Location: career.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT *
    FROM career
    WHERE career_id = '.$_GET['id'].'
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
  
  <input type="submit" value="Edit Career">
  
</form>

<p><a href="career.php"><i class="fas fa-arrow-circle-left"></i> Return to Career List</a></p>


<?php

include( 'includes/footer.php' );

?>