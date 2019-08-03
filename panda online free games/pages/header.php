<!DOCTYPE html>
<html lang="fr">
<head>

  <?php
if (!empty($titre))
 {
echo '<title> '.$titre.' </title>';
  }
else //Sinon, on écrit panda par défaut
  {
  echo '<title> Panda </title>';
 }
?>

  <!-- Required meta tags -->
  <meta http-equiv="Content-Type" content="text/html; utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!--CSS Style -->
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <link rel="stylesheet" type="text/css" href="./css/style.css">



  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <!--FONTAWSOME-->
  <script src="https://kit.fontawesome.com/c068eb233e.js"></script>

  <!--FAVICON-->
  <link rel="icon" type="image/png" href="logo/logo.png" />

</head>

<body>

   <header>


    </header>

    <div class="main">
      <nav>
        <ul>
          <li><a href="">Jeux</a></li>
          <li><a href="pages/live.php">Live Stream</a></li>
          <li><a href="./includes/index.php" href="../includes/index.php" >Forum</a></li>
        </ul>
      </nav>
