<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM skills
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
    
  set_message( 'Skill has been deleted' );
  
  header( 'Location: skills.php' );
  die();
  
}

include( 'includes/header.php' );

$query = 'SELECT s.id, s.name, s.percent, s.photo
  '.( ( $_SESSION['id'] != 1 ) ? '' : ', u.first, u.last' ).'
  FROM skills s
  '.( ( $_SESSION['id'] != 1 ) ? 'WHERE user_id = '.$_SESSION['id'].' ' : 'LEFT JOIN users u ON s.user_id = u.id' );
$result = mysqli_query( $connect, $query );

?>

<script src="includes/functions.js"></script>

<h2>Manage Skill</h2>

<table>
  <tr>
    <th></th>
    <th align="center">ID</th>
    <?php 
      if ( $_SESSION['id'] == 1 ) {
        echo '<th align="center">User</th>';
      }
    ?>
    <th align="left">Name</th>
    <th align="center">Percent</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td align="center">
        <img src="image.php?type=skill&id=<?php echo $record['id']; ?>&width=50&height=50&format=inside">
      </td>
      <td align="center"><?php echo $record['id']; ?></td>
      <?php 
        if ( $_SESSION['id'] == 1 ) {
          echo '<td align="center">'.$record['first'].' '.$record['last'].'</td>';
        }
      ?>
      <td align="left">
        <?php echo htmlentities( $record['name'] ); ?>
      </td>
      <td align="center"><?php echo htmlentities( $record['percent'] ); ?>%</td>
      <td align="center"><a href="skills_photo.php?id=<?php echo $record['id']; ?>">Photo</i></a></td>
      <td align="center"><a href="skills_edit.php?id=<?php echo $record['id']; ?>">Edit</i></a></td>
      <td align="center">
        <?php

        echo '<a href="skills.php?delete='.$record['id'].'" onclick="confirmDelete('."'skill'".')">Delete</i></a>';

        ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="skills_add.php"><i class="fas fa-plus-square"></i> Add Skill</a></p>


<?php

include( 'includes/footer.php' );

?>
          