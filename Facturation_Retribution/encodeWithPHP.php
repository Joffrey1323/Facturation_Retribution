<?php

  $client = $_GET["client"];
  $produits = $_GET["produits"];
  $montantHT= $_GET["montantHT"];
  $montantTTC= $_GET["montantTTC"];

 $data = array(
   "client" => $client,
   "produits" => $produits,
   "montant HT" => $montantHT,
   "montant TTC" => $montantTTC
 );
  $contenu_json=json_encode($data);

  $nom_du_fichier = 'facture.json';

  $fichier = fopen($nom_du_fichier, 'w+');

  fwrite($fichier, $contenu_json);
  fclose($fichier);
 ?>
