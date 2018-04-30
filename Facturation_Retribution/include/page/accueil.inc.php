

<div id="container">
  <div id="header">
    <div id="CoordEntreprise">
      <p> Mon entreprise </p>
      <p> 15 Rue de l'IUT, Limoges </p>
      <p> 05-55-55-55-55 </p>
      <p> mon.entreprise@gmail.com </p>
    </div>
    <div class="Titres">
        <h1>Facture</h1>
        <h2 id="dateFacture"> Date de la facture : </h2>
        <h2 id="dateFinPaiement"> Date de fin de paiement : </h2>
    </div>

    <div id="CoordClient">
      <p id="nom"> Nom </p> <!--<p id="prenom"> Prenom </p>-->
      <p id="adr"> Adresse  </p>
      <p id="tel"> Téléphone </p>
      <p id="mail"> Email </p>
    </div>
  </div>
  <div id="body">
    <div class="topnav">
       <a class="active" href="index.php?page=0">Facture</a>
       <a href="index.php?page=1">Rétribution</a>
       <a href="index.php?page=2">Etat des ventes</a>
    </div>
    <div id="tableau">
      <h1> Liste des livraisons </h1>

        <table border="1" id="tableauLivraison">
          <tr>
            <th> Numéro </th>
            <th> Client </th>
            <th> Adresse de livraison </th>
            <th> Facture </th>
          </tr>
        </table>
        <input type="button" id="generer" value="Récupérer les livraisons">

      <script src="jquery.min.js" type="text/javascript"></script>
      <script src="bootstrap.min.js" type="text/javascript"></script>
      <script>

        $(function() {

          var noms=new Array();
          var adresses=new Array();

          $('#generer').on('click', function(){

            $.getJSON('livraisons.json',function(data){
              $.each(data,function(index,d){
                $('#tableauLivraison').append('<tr> <td name="numero">'+d.id+'</td> <td>'+d.commande.client.raisonSociale+'</td> <td>'+d.commande.adresseLivraison.rueNumero +" "+ d.commande.adresseLivraison.rueType +" "+ d.commande.adresseLivraison.rueNom +" "+ d.commande.adresseLivraison.codePostal +" "+ d.commande.adresseLivraison.ville+'</td> <td> <input type="button" id="boutonFacture" value="Facturer"> </td> </tr>');
                noms.push(d.commande.client.raisonSociale);
              });
            });
          });

          $('#tableauLivraison').on('click', '#boutonFacture', function(){
              var $this = $(this);
               var col   = $this.index();
               var row   = $this.closest('tr').index();
              window.location="http://localhost:8080/Facturation_Retribution/index.php?page=3"+"/name="+noms[row-1];
          });
        });
      </script>
    </div>

</div>
<!--Questions sur les paramètres d'ajax (get,post...), sur les selecteurs, des listes chainees
-->


























<!-- Rajouter les remises par client -->
