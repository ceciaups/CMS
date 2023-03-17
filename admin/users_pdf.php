<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: users.php' );
  die();
  
}

if( isset( $_FILES['resume'] ) )
{
  
  if( isset( $_FILES['resume'] ) )
  {
  
    if( $_FILES['resume']['error'] == 0 )
    {
      
      $query = 'UPDATE users SET
        resume = '.file_get_contents( $_FILES['resume']['tmp_name'] ).'
        WHERE id = '.$_GET['id'].'
        LIMIT 1';
      mysqli_query( $connect, $query );

    }
    
  }
  
  set_message( 'User resumé has been updated' );

  header( 'Location: users.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  if( isset( $_GET['delete'] ) )
  {
    
    $query = 'UPDATE users SET
      resume = ""
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    $result = mysqli_query( $connect, $query );
    
    set_message( 'Users resumé has been deleted' );
    
    header( 'Location: users.php' );
    die();
    
  }
  
  $query = 'SELECT *
    FROM users
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {
    
    header( 'Location: users.php' );
    die();
    
  }
  
  $record = mysqli_fetch_assoc( $result );
  
}

include( 'includes/header.php' );

?>

<h2>Edit Useer</h2>

<?php if( $record['resume'] ): ?>

  <?php

  $data = base64_encode( $record['resume'] );

  ?>
<p><iframe src="data:application/pdf;base64,<?php echo $data; ?>" width="210" height="297"></iframe></p>
<p><a href="users_pdf.php?id=<?php echo $_GET['id']; ?>&delete"><i class="fas fa-trash-alt"></i> Delete this Resumé</a></p>

<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  
  <label for="resume">Resumé:</label>
  <input type="file" name="resume" id="resume">
  
  <br>
  
  <input type="submit" value="Save Resume">
  
</form>

<p><a href="users.php"><i class="fas fa-arrow-circle-left"></i> Return to Users List</a></p>


<?php

include( 'includes/footer.php' );

?>