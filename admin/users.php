<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM users
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
  
  set_message( 'User has been deleted' );
  
  header( 'Location: users.php' );
  die();
  
}

include( 'includes/header.php' );

$query = 'SELECT *
  FROM users 
  '.( ( $_SESSION['id'] != 1 and $_SESSION['id'] != 4 ) ? 'WHERE id = '.$_SESSION['id'].' ' : '' ).'
  ORDER BY last,first';
$result = mysqli_query( $connect, $query );

?>

<h2>Manage Users</h2>

<table>
  <tr>
    <th align="center">ID</th>
    <th align="left">Name</th>
    <th align="left">Details</th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td align="center"><?php echo $record['id']; ?></td>
      <td align="left"><?php echo $record['first']; ?> <?php echo htmlentities( $record['last'] ); ?></td>
      <td align="left">
        Email: <a href="mailto:<?php echo $record['email']; ?>"><?php echo $record['email']; ?></a><br>
        <?php if( $record['id'] != 1 ): ?>
          Location: <?php echo $record['location']; ?><br>
          Mobile: <?php echo $record['mobile']; ?><br>
          LinkedIn: <a href="<?php echo $record['linkedin']; ?>"><?php echo $record['linkedin']; ?></a><br>
          GitHub: <a href="<?php echo $record['github']; ?>"><?php echo $record['github']; ?></a><br>
        <?php endif; ?>
      </td>
      <td align="center">
        <?php if( $record['id'] != 1 ): ?>
          <a href="users_pdf.php?id=<?php echo $record['id']; ?>">Resumé</a>
        <?php endif; ?>
      </td>
      <td align="center"><a href="users_edit.php?id=<?php echo $record['id']; ?>">Edit</a></td>
      <td align="center">
        <?php if( $_SESSION['id'] != $record['id'] ): ?>
          <a href="users.php?delete=<?php echo $record['id']; ?>" onclick="javascript:confirm('Are you sure you want to delete this user?');">Delete</a>
        <?php endif; ?>
      </td>
      <td align="center">
        <?php echo $record['active']; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="users_add.php"><i class="fas fa-plus-square"></i> Add User</a></p>


<?php

include( 'includes/footer.php' );

?>