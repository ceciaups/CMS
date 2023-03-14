<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM career
    WHERE career_id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
    
  set_message( 'Career has been deleted' );
  
  header( 'Location: career.php' );
  die();
  
}

include( 'includes/header.php' );

$query = 'SELECT c.career_id, c.career, c.location, c.start_date, c.end_date, ct.career_type
  '.( ( $_SESSION['id'] != 1 ) ? '' : ', u.first, u.last' ).'
  FROM career c
  JOIN career_type ct
  ON c.career_type_id = ct.career_type_id
  '.( ( $_SESSION['id'] != 1 ) ? 'WHERE c.user_id = '.$_SESSION['id'].' ' : 'JOIN users u 
  ON c.user_id = u.id' ).'
  ORDER BY c.career_type_id';
$result = mysqli_query( $connect, $query );

?>

<script src="includes/functions.js"></script>

<h2>Manage Career</h2>

<table>
  <tr>
    <th align="center">ID</th>
    <?php 
      if ( $_SESSION['id'] == 1 ) {
        echo '<th align="center">User</th>';
      }
    ?>
    <th align="left">Career</th>
    <th align="left">Location</th>
    <th align="center">Start Date</th>
    <th align="center">End Date</th>
    <th align="center">Career Type</th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td align="center"><?php echo $record['career_id']; ?></td>
      <?php 
        if ( $_SESSION['id'] == 1 ) {
          echo '<td align="center">'.$record['first'].' '.$record['last'].'</td>';
        }
      ?>
      <td align="left">
        <?php echo $record['career']; ?>
      </td>
      <td align="left">
        <?php echo $record['location']; ?>
      </td>
      <td align="center">
        <?php echo $record['start_date']; ?>
      </td>
      <td align="center">
        <?php echo ( ( $record['end_date'] == "0000-00-00") ? "N/A" : $record['end_date'] ); ?>
      </td>
      <td align="center">
        <?php echo $record['career_type']; ?>
      </td>
      <td align="center"><a href="career_edit.php?id=<?php echo $record['career_id']; ?>">Edit</i></a></td>
      <td align="center">
        <?php

        echo '<a href="career.php?delete='.$record['career_id'].'" onclick="confirmDelete('."'career'".')">Delete</i></a>';

        ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="career_add.php"><i class="fas fa-plus-square"></i> Add Career</a></p>


<?php

include( 'includes/footer.php' );

?>
          