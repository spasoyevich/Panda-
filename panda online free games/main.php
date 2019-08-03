<?php
session_start();
$titre = "home";
include_once("pages/header.php");

?>

      <article>
        <h2 style="margin-top: 30px;">Jouer de Jeux Gratuitement Online</h2>
        <p><img style="width: 200px; height: 200px;" src="img/memory.png"></p>
        <p><img style="width: 200px; height: 200px;" src="img/snake.png"></p>

        <h2 style="margin-top: 30px;">Regarder le Stream Live Twitch</h2>
       <!-- Add a placeholder for the Twitch embed -->
          <div style="margin-left: 30%; margin-top: 20px" id="twitch-embed"></div>

          <!-- Load the Twitch embed script -->
          <script src="https://embed.twitch.tv/embed/v1.js"></script>

          <!-- Create a Twitch.Embed object that will render within the "twitch-embed" root element. -->
          <script type="text/javascript">
            new Twitch.Embed("twitch-embed", {
              width: 854,
              height: 480,
              channel: "fortnite"
            });
          </script>

        <h2 style="margin-top: 30px;">Dernier Topic Forum </h2>
        <p><a href="includes/register.php"> Rejoindre nous</a></p>
        <div class="card text-center">
            <div class="card-header">
              Featured
            </div>
            <div class="card-body">
                 <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
            <div class="card-footer text-muted">
                 2 days ago
            </div>
        </div>

      </article>

  <?php
    include_once("pages/footer.php")
  ?>
