<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM projects
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
    
  set_message( 'Project has been deleted' );
  
  header( 'Location: projects.php' );
  die();
  
}

include( 'includes/header.php' );

$query = 'SELECT p.id, p.user_id, p.title, p.content, p.photo
  '.( ( $_SESSION['id'] != 1 ) ? '' : ', u.first, u.last' ).'
  FROM projects p
  '.( ( $_SESSION['id'] != 1 ) ? 'WHERE user_id = '.$_SESSION['id'].' ' : 'LEFT JOIN users u ON p.user_id = u.id' );
$result = mysqli_query( $connect, $query );

?>

<script src="includes/functions.js"></script>

<h2>Manage Projects</h2>

<table>
  <tr>
    <th align="center">ID</th>
    <?php 
      if ( $_SESSION['id'] == 1 ) {
        echo '<th align="center">User</th>';
      }
    ?>
    <th align="left">Description</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td align="center"><?php echo $record['id']; ?></td>
      <?php 
        if ( $_SESSION['id'] == 1 ) {
          echo '<td align="center">'.$record['first'].' '.$record['last'].'</td>';
        }
      ?>
      <td align="left">
        <?php echo htmlentities( $record['title'] ); ?>
        <small><?php echo $record['content']; ?></small>
        <img src="image.php?type=project&id=<?php echo $record['id']; ?>&width=300&height=300&format=inside">
      </td>
      <td align="center"><a href="projects_photo.php?id=<?php echo $record['id']; ?>">Photo</i></a></td>
      <td align="center"><a href="projects_edit.php?id=<?php echo $record['id']; ?>">Edit</i></a></td>
      <td align="center">
        <?php

        echo '<a href="projects.php?delete='.$record['id'].'" onclick="confirmDelete('."'project'".')">Delete</i></a>';

        ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="projects_add.php"><i class="fas fa-plus-square"></i> Add Project</a></p>


<?php

include( 'includes/footer.php' );

?>