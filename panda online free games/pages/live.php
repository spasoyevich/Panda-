
<?php
session_start();
$titre = "Live";
include_once("../pages/header.php")
?>


    <!-- Add a placeholder for the Twitch embed -->
    <div style="margin-left: 10%; margin-top: 20px" id="twitch-embed"></div>

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


<article style="margin-left: 5%">

    <iframe
           width="560"
           height="315"
           src="https://www.youtube.com/embed/2FdBmfeZU8Y"
           frameborder="0"
           allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
           allowfullscreen>

    </iframe>

   <iframe

       src="https://clips.twitch.tv/embed?clip=IncredulousAbstemiousFennelImGlitch"
       height="315"
       width="560"
       frameborder="0"
       scrolling="no"
       allowfullscreen="true">
    </iframe>

</article>



 <?php
 include_once("../pages/footer.php")
 ?>
