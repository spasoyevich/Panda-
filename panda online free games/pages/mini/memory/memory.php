<!DOCTYPE html>
<html lang="fr">
<head>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="script.js" type="text/javascript"></script>
  <meta charset="utf-8">
  <title>Memory</title>
</head>
<body>
  <h1 class="text-center"><strong>Memory</strong></h1>
  <table class="mx-auto" id="tapis">
<tr>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
</tr>
<tr>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
</tr>
<tr>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
</tr>
<tr>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
  <td><img src="fondcarte.png"/></td>
</tr>
</table>
<script>
var motifsCartes=[1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10];
var etatsCartes=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
var cartesRetournees=[];
var nbPairesTrouvees=0;
var imgCartes=document.getElementById("tapis").getElementsByTagName("img");
for(var i=0;i<imgCartes.length;i++){
  imgCartes[i].noCarte=i; //Ajout de la propriété noCarte à l'objet img
  imgCartes[i].onclick=function(){
    controleJeu(this.noCarte);
  }
}
initialiseJeu();
function majAffichage(noCarte){
  switch(etatsCartes[noCarte]){
    case 0:
      imgCartes[noCarte].src="fondcarte.png";
      break;
    case 1:
      imgCartes[noCarte].src="carte"+motifsCartes[noCarte]+".png";
      break;
    case -1:
      imgCartes[noCarte].style.visibility="hidden";
      break;
  }
}
function rejouer(){
  alert("Bravo !");
  location.reload();
}
function initialiseJeu(){
  for(var position=motifsCartes.length-1; position>=1; position--){
    var hasard=Math.floor(Math.random()*(position+1));
    var sauve=motifsCartes[position];
    motifsCartes[position]=motifsCartes[hasard];
    motifsCartes[hasard]=sauve;
  }
}
function controleJeu(noCarte){
  if(cartesRetournees.length<2){
    if(etatsCartes[noCarte]==0){
      etatsCartes[noCarte]=1;
      cartesRetournees.push(noCarte);
      majAffichage(noCarte);
    }
    if(cartesRetournees.length==2){
      var nouveauEtat=0;
      if(motifsCartes[cartesRetournees[0]]==motifsCartes[cartesRetournees[1]]){
        nouveauEtat=-1;
        nbPairesTrouvees++;
      }

      etatsCartes[cartesRetournees[0]]=nouveauEtat;
      etatsCartes[cartesRetournees[1]]=nouveauEtat;
      setTimeout(function(){
        majAffichage(cartesRetournees[0]);
        majAffichage(cartesRetournees[1]);
        cartesRetournees=[];
        if(nbPairesTrouvees==10){
          rejouer();
        }
      },750);
    }
  }
}
</script>


</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>
