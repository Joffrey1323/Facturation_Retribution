<h2>Retribution</h2>
<script src="jquery.min.js" type="text/javascript"></script>
<script src="jquery-ui.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<div class="clients inline">
  <p>Règlement client A</p>
      <ul>
        <li id="draggable">1800</li>
      </ul>
</div>


<div class="fournisseurs inline">
    <ul class="sortable">
      <li class="ui-state-default nonSortable nonDropable fournisseur">Fournisseur 1</li>
      <li class="ui-state-default nonSortable fournisseur">Fournisseur 2</li>
      <li class="ui-state-default nonSortable fournisseur">Fournisseur 3</li>
      <li class="ui-state-default nonSortable fournisseur">Fournisseur 4</li>
      <li class="ui-state-default nonSortable fournisseur">Fournisseur 5</li>
    </ul>
</div>


<div class="valider">
  <button type="button" name="envoyer">Valider</button>
</div>

<style>
li{
        list-style-type: none;
        width: 300px;
      }
.fournisseur{
        border-radius: 5px;
        text-align: center;
        border-color: black;
      }

      .fournisseurs{
        margin-top: -17px;
      }

      .clients{
        border: 2px solid black;
        vertical-align: top;
        padding-left: 10px;
        border-radius: 5px;
        width: 200px;
      }

      #draggable{
        font-size: 1.6em;
      }

      .inline{
        display: inline-block;
      }

      .valider{
        display: block;
      }
</style>


      <script src="jquery.min.js" type="text/javascript"></script>
      <script src="bootstrap.min.js" type="text/javascript"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      <script>

          $(function() {

              //Plugin pour savoir si un noeud jquery est vide ou non
              $.fn.exists = function () {
                return this.length !== 0;
              }

              $("#draggable").draggable();

              $(".sortable").sortable({
                revert: true,
                items: "li:not(.nonDropable)",//Pour empécher de mettre un réglement client avant le premier fournisseur
                cancel: ".nonSortable"//Pour empécher les fournisseurs d'être déplacés
              });

              $("#draggable").draggable({
                connectToSortable: ".sortable",//Permet de déplacer des réglement clients vers le tableau de fournisseur
                helper: "clone",//En choissisant un réglement, créer une "copie" visuelle de l'élément que l'on déplace
                revert: "invalid",//Si les réglement client n'est pas drop à un endroit valide, revient à sa position initiale
              });


              $(".sortable").droppable({
                  drop: function( event, ui ) {
                      console.log("drop");
                      mettreAJourDraggables();

                      if($(ui.draggable).find(".ui-progressbar-value").exists()){//Vérification à faire car l'event "drop" s'effectue toujours deux fois
                        console.log("ProgressBar existe");
                      }else{
                        console.log("ProgressBar n'existe pas");

                        //Défini un montant pour chaque réglements
                        $(ui.draggable).attr("montantFournisseur", $("#draggable").text());

                        $(ui.draggable).addClass("clientA");//TODO à changer pour avoir plusieur clients


                        verrouillerProgressBars(ui);
                        annulerReglementsEnDouble();

                        var valueProgressBar = $("#draggable").text()/montant *100;//Permet de définir la value de la nouvelle progressBar en pourcentage
                        var largeurProgressBar = valueProgressBar*3;//Permet de définir la largeur de la progressBar en pixel



                        $(ui.draggable).progressbar({
                          value: valueProgressBar
                        }).height(40);

                        $("#draggable").text(0);//La première fois que l'on déplace un réglement client vers un fournisseur, on définit le réglement client à 0€


                        //On ajoute resizable sur la value de progressbar (qui est la barre en elle-même)
                        $(ui.draggable).find(".ui-progressbar-value").resizable({
                          handles: 'e',//L'utilisateur ne peut modifier la progressBar en partant du côté droit de la progressbar
                          maxWidth: largeurProgressBar,//maxWidth de base : 300, donc 100%*3 pour le premier réglement "droppé"
                          minWidth: 0.1,//Limite minimum que la progressebar ne peut pas dépasser, si on met 0, la progressbar peut être négative

                          //A chaque fois que l'on change la taille de la progressbar
                          resize: function( event, ui ) {

                            //Permet de récupérer le total du montant régler pour un client jusqu'a lors sauf le montant que l'on est en train de modifier
                            let montantRegle = Number(0);
                            $(".clientA").not($(this).parent()).each(function(){
                              montantRegle += Number($(this).text());
                            })

                            console.log("montant total : "+ montant);
                            console.log("montant regle : "+montantRegle);

                            var montantFournisseur = $(ui.element).parent().attr("montantFournisseur");//On récupère le montant défini de base sur un réglement
                            console.log("montant fournisseur : "+montantFournisseur);
                            // if(ui.originalSize.width > ui.size.width){
                            //    console.log("montant baisse");
                            // }else{
                            //    console.log("montant augmente");
                            // }

                            //console.log("ancien montant "+$(this).parent().prop("childNodes")[0].nodeValue);
                            console.log("largeur progress bar : "+largeurProgressBar);
                            console.log($(ui.element).resizable("option", "maxWidth"));
                            var nouveauMontant = montantFournisseur * (ui.size.width/$(ui.element).resizable("option", "maxWidth"));//1800* 250/300
                            console.log("nouveauMontant : "+nouveauMontant);
                            console.log(Number(montantRegle + nouveauMontant.toFixed(0)));
                            $(this).parent().prop("childNodes")[0].nodeValue =  nouveauMontant.toFixed(0);
                            //$("#draggable").text(montantFournisseur - nouveauMontant.toFixed(0));
                            var montantRestant = Number(Number(montant) - (Number(montantRegle) + Number(nouveauMontant.toFixed(0))));
                            console.log("montant restant : "+montantRestant);
                            $("#draggable").text(montantRestant);
                            //console.log("nouveau montant "+nouveauMontant.toFixed(0));

                            mettreAJourDraggables();
                          }
                        })

                        /*
                        //Fonction qui permet de mettre à jour une progressBar quand on clique dessus
                        $(".ui-progressbar-value").on( "click", function(event) {

                          //On récupère la position de la progressBar et du clique de la souris pour pouvoir calculer la nouvelle value de la progressBar
                          var positionProgressBar = $(this).parent().offset();
                          var positionSourisX = event.pageX - positionProgressBar.left;
                          var nouvelleValueProgressBar = valueProgressBar *(positionSourisX/largeurProgressBar);

                          //On récupère le montant attribué à l'event drop pour calculer le nouveau montant
                          var montantFournisseur = $(this).parent().attr("montantFournisseur");
                          var nouveauMontant = montantFournisseur * (positionSourisX/largeurProgressBar);

                          //Met à jour le montant (texte)
                          $(this).parent().prop("childNodes")[0].nodeValue =  nouveauMontant.toFixed(0);
                          $("#draggable").text(montantFournisseur - nouveauMontant.toFixed(0));

                          //Met à jour la taille de la progressBar
                          $(this).parent().progressbar("option", "value", nouvelleValueProgressBar);

                          mettreAJourDraggables();
                        });
                        */
                      }
                  }
              });

              $( "ul, li" ).disableSelection();


              //Permet d'activer ou désactiver le déplacement d'un montant selon s'il est infèrieur à zéro ou non
              function mettreAJourDraggables(){
                if($("#draggable").text() <= 0 ){
                  $("#draggable").draggable("disable");
                }else{
                  $("#draggable").draggable("enable");
                }
              }

              //Permet de bloquer les progressBars selon leur montant actuel
              function verrouillerProgressBars(ui){
                //Pour chaque progressBar hormis celle que l'on vient de drop dans la liste
                $(".clientA").not($(ui.draggable)).each(function(){//TODO à changer pour plusieurs clients

                  //On redéfini son montant fournisseur
                  let montantBar = $(this).prop("childNodes")[0].nodeValue;
                  $(this).attr("montantFournisseur", montantBar);

                  //On redéfini la largeur max selon son montant
                  let valueProgressBar = montantBar/montant *100;
                  var largeurProgressBar = valueProgressBar*3;
                  $(this).find(".ui-progressbar-value").resizable("option", "maxWidth", largeurProgressBar);
                });
              }

              //Permet de fusionner deux règlements d'un même client pour un fournisseur lors d'un drop
              function annulerReglementsEnDouble(){
                $(".fournisseur").each(function(){
                  //On gère le cas où il y a deux réglements du même client pour un fournisseur
                  if($(this).next().not(".fournisseur").next().not(".fournisseur").exists()){

                    //On redéfini le nouveau montant à partir des deux réglements dans le même fournisseur
                    let montantBar = Number($(this).next().prop("childNodes")[0].nodeValue) + Number($("#draggable").text());
                    $(this).next().attr("montantFournisseur", montantBar);

                    //On redéfini la taille max de la progressBar
                    let valueProgressBar = montantBar/montant *100;
                    let largeurProgressBar = valueProgressBar*3;
                    $(this).next().find(".ui-progressbar-value").resizable("option", "maxWidth", largeurProgressBar);
                    $(this).next().progressbar("option", "value", valueProgressBar);

                    //On réactualise le montant de la barre
                    $(this).next().prop("childNodes")[0].nodeValue =  montantBar;

                    //On supprime enfin la deuxième progressBar
                    $(this).next().next().remove();
                  }
                });
              }

            });

            var montant = $("#draggable").text();

        </script>
