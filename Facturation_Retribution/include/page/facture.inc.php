<div id="container">
  <div id="header">

      <div class="topnav">
         <a class="active" href="index.php?page=0">Facture</a>
         <a href="index.php?page=1">Rétribution</a>
         <a href="index.php?page=2">Etat des ventes</a>
      </div>
    <div id="CoordEntreprise">
      <p> Mon entreprise </p>
      <p> 15 Rue de l'IUT, Limoges </p>
      <p> 05-55-55-55-55 </p>
      <p> mon.entreprise@gmail.com </p>
    </div>
    <div class="Titres">
        <h1>Facture</h1>
        <h2 id="dateFacture"> Date de la livraison : </h2>
        <h2 id="dateFinPaiement"> Date de fin de paiement : </h2>
    </div>

    <div id="CoordClient">
      <p id="nom"> Nom </p> <!--<p id="prenom"> Prenom </p>-->
      <p id="adr"> Adresse  </p>
    </div>
  </div>
  <div id="body">
    <div id="tableauFacture">
      <h3 id="numLivraison"> Livraison : </h3>
    <table border="1" id="tableau">
      <thead>
        <tr>
          <th> Produit </th>
          <th> Quantité </th>
          <th> Prix à l'unité </th>
          <th> Prix total </th>
        </tr>
     </thead>
    </table>
    <div id="zone">
    </div>
    <script src="jquery.min.js" type="text/javascript"></script>
    <script src="bootstrap.min.js" type="text/javascript"></script>
    <script>

    function getParams() {
    var url = window.location.href;
    var splitted = url.split("?");
    if(splitted.length === 1) {
       return {};
    }
    var paramList = decodeURIComponent(splitted[1]).split("&");
    var params = {};
    for(var i = 0; i < paramList.length; i++) {
        var paramTuple = paramList[i].split("=");
        params[paramTuple[0]] = paramTuple[1];
    }
    return paramTuple[2];
    }



    function ExportFactureToJSON(){
      //Coordonnees clients, produits, ligne de produits, remise, montant HT, montant TTC
      var numLivraison = 1;

      var client= new Array();
      var nomClient= $('#nom').text();
      var adresseClient= $('#adr').text();

      client.push({
        'nom': nomClient,
        'adresse': adresseClient
      });

      var produits=new Array();

      $('#tableau').find("tbody").find("tr").each(function(){
          var nomProduit=$(this).find("#nomProduit").text();
          var quantiteProduit= $(this).find("#quantiteProduit").text();
          var prixProduit= $(this).find("#prixProduit").text();
          var quantiteTotalProduit= $(this).find("#quantiteTotalProduit").text();
          produits.push({
            'nom': nomProduit,
            'quantite': quantiteProduit,
            'prix': prixProduit,
            'quantiteTotale': quantiteTotalProduit
          });
      });
      var montantHT= $('#montantHT').text();
      var montantTTC= $('#montantTTC').text();


      var arr=new Array();
      arr.push(
        {"id": numLivraison,
          "client": client,
          "produits": produits,
          "montantHT": montantHT,
          "montantTTC": montantTTC
        });

        var client= JSON.stringify(arr[0]["client"]);
        var produits= JSON.stringify(arr[0]["produits"]);
        var montantHT= JSON.stringify(arr[0]["montantHT"]);
        var montantTTC= JSON.stringify(arr[0]["montantTTC"]);
          $.ajax({
          url: "encodeWithPHP.php",
            type: "GET",
            data: {
              "client" : client,
              "produits": produits,
              "montantHT": montantHT,
              "montantTTC": montantTTC
            },
            datatype: "JSON",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                alert("La facture a bien été créée !")
            }
          })
        return false;
    }

        $(function() {
          $('#nom').html(getParams());
          $.getJSON('livraisons.json',function(data){
            var montantHT=0;
            $.each(data,function(index,d){
              if(d.commande.client.raisonSociale == getParams()){
                $('#adr').html(d.commande.adresseLivraison.rueNumero);
                $('#adr').append(" " + d.commande.adresseLivraison.rueType);
                $('#adr').append(" " +d.commande.adresseLivraison.rueNom + "</br>");
                $('#adr').append(" " +d.commande.adresseLivraison.codePostal);
                $('#adr').append(" " +d.commande.adresseLivraison.ville);
                $('#numLivraison').append(d.id);
                  $('#tableau').append("<tbody>");
                $.each(d.lignes,function(index,l){
                  $('#tableau').append('<tr> <td id="nomProduit">'+l.produit.libelle+'</td> <td id="quantiteProduit">'+ l.quantite+' kg </td> <td id="prixProduit">'+l.produit.prixVente+'€</td> <td id="quantiteTotalProduit">'+ l.quantite*l.produit.prixVente + '€ </td> </tr>');
                  montantHT +=l.produit.prixVente*l.quantite;
                });
                $('#tableau').append("</tbody>");

                  var date = new Date(d.commande.dateLivraison);
                  var dateFinPaiement= new Date();
                  var numberOfDaysToAdd = 90;
                  dateFinPaiement.setDate(date.getDate() + numberOfDaysToAdd);

                  var d = date.getDate();
                  var m =  date.getMonth();
                  m += 1;  // JavaScript months are 0-11
                  var y = date.getFullYear();

                  var dd = dateFinPaiement.getDate();
                  var mm =  dateFinPaiement.getMonth();
                  mm += 1;  // JavaScript months are 0-11
                  var yy = dateFinPaiement.getFullYear();

                  $("#dateFacture").append(d + "/" + m + "/" + y);
                  $("#dateFinPaiement").append(dd + "/" + mm + "/" + yy);
                return false;
              }

          });

          var tva=20;

          $('#HT').append("<b id='montantHT'> " + montantHT + "</b> €")
          $('#tva').append("<b id='montantTVA'> " + tva + "</b>" + " % " );
          montantTTC=montantHT+(montantHT*tva*0.01);
          $('#TTC').append( "<b id='montantTTC'> " + montantTTC + "</b> " + " € ")
        });

        $('#envoyerFacture').click(function(){
          ExportFactureToJSON();
        })
      });

      </script>
</div>

<div id="montant">
  <p id="HT"> Montant total à payer HT : </p>
  <p id="tva"> TVA : </p>
  <p id="TTC"> Montant TTC: </p>
</div>

<div id="Bouton">
  <input type="button" id="envoyerFacture" value="Envoyer la facture">
</div>

</div>
</div>

</div>
