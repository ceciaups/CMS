<?php

include( 'php-cms/includes/database.php' );
include( 'php-cms/includes/config.php' );
include( 'php-cms/includes/functions.php' );

?>
<!doctype html>
<html>
<head>
  
  <meta charset="UTF-8">
  <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Ceci Au</title>
  
  <link rel="icon" type="image/png" href="public/logo.png" >
  <link href="public/style.css" type="text/css" rel="stylesheet">
  <link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">

  <script src="https://cdn.ckeditor.com/ckeditor5/12.4.0/classic/ckeditor.js"></script>
  <script src="public/script.js"></script>
  
</head>
<body>

  <header>
    <div><a href="#sec-home"><img id="logo" src="public/logo.png" alt="Ceci Au's Logo"></a></div>
    <nav>
      <ul id="nav-bar" class="nav-bar">
        <li id="nav-toggle"><i class="fa-solid fa-bars"></i></li>
        <li class="nav-item"><a href="#sec-home">HOME</a></li>
        <li class="nav-item"><a href="#sec-about">ABOUT</a></li>
        <li class="nav-item"><a href="#sec-projects">PROJECTS</a></li>
        <li class="nav-item"><a href="#sec-contact">CONTACT</a></li>
      </ul>
    </nav>
  </header>

  <main>

    <?php
    $query = 'SELECT * FROM users
      WHERE id = 2
      LIMIT 1';
    $result = mysqli_query( $connect, $query );
    $record = mysqli_fetch_assoc( $result );
    ?>
    <section id="sec-home" class="reveal">
      <div class="home-content">
        <h1>Hi, I'm <span class="highlight-text">Ceci Au</span> !</h1>
        <div class="mono-text">I am a <br class="mobile-new-line">full-stack developer.</div>
        <div class="home-container">
          <div class="home-detail"><i class="fa-solid fa-location-dot"></i><?=$record['location']?></div>
          <div class="home-detail"><i class="fa-solid fa-phone"></i><?=$record['mobile']?></div>
          <div class="home-detail"><i class="fa-solid fa-envelope"></i><?=$record['email']?></div>
        </div>
        <div id="home-link">
          <a class="button" href="data:application/pdf;base64,<?=base64_encode( $record['resume'] )?>" target="_blank">My Resumé</a>
          <a href="<?=$record['linkedin']?>" target="_blank"><i class="fa-brands fa-linkedin-in home-linkedin"></i></a>
          <a href="<?=$record['github']?>" target="_blank"><i class="fa-brands fa-github home-github"></i></a>
        </div>
      </div>
      <img id="home-myimage" src="public/myself.png" alt="portrait of myself">
    </section>
  </main>


  <p>There are <?php echo mysqli_num_rows($result); ?> projects in the database!</p>

  <hr>

  <?php while($record = mysqli_fetch_assoc($result)): ?>

    <div>

      <h2><?php echo $record['title']; ?></h2>
      <?php echo $record['content']; ?>

      <?php if($record['photo']): ?>

        <p>The image can be inserted using a base64 image:</p>

        <img src="<?php echo $record['photo']; ?>" width="500" height="300">

        <p>Or by streaming the image through the image.php file:</p>

        <img src="php-cms/image.php?type=project&id=<?php echo $record['id']; ?>&width=100&height=100">

      <?php else: ?>

        <p>This record does not have an image!</p>

      <?php endif; ?>

    </div>

    <hr>

  <?php endwhile; ?>


  <?php

  $query = 'SELECT *
    FROM skills
    ORDER BY percent DESC';
  $result = mysqli_query($connect, $query);

  ?>

  <?php while($record = mysqli_fetch_assoc($result)): ?>

    <h2><?php echo $record['name']; ?></h2>

    <p>Percent: <?php echo $record['percent']; ?>%</p>

    <div style="background-color: grey;">
      <div style="background-color: red; width:<?php echo $record['percent']; ?>%; height: 20px;"></div>
    </div>

  <?php endwhile; ?>

</body>
</html>