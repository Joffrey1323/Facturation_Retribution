<?php

  $numero= $_GET["numero"];
  $client = $_GET["client"];
  $produits = $_GET["produits"];
  $montantHT= $_GET["montantHT"];
  $montantTTC= $_GET["montantTTC"];

 $dataFacture = [
   "client" => $client,
   "produits" => $produits,
   "montant HT" => $montantHT,
   "montant TTC" => $montantTTC
 ];

 $dataReglement= [
   "client" => $client,
   "montant_TTC" => $montantTTC
 ];


  $contenuFacture_json=json_encode($dataFacture);
  $contenuReglement_json=json_encode($dataReglement);

  $facture = "factures.json";

  if(file_exists($facture)){
    $fichierFacture = fopen($facture, 'a');

    fwrite($fichierFacture, $contenuFacture_json);
    fclose($fichierFacture);
  }else{
    $fichierFacture = fopen($facture, 'w');

    fwrite($fichierFacture, $contenuFacture_json);
    fclose($fichierFacture);
  }


  $reglement = "reglements.json";

  if(file_exists($reglement)){
    $fichierReglement = fopen($reglement, 'a+');

    fwrite($fichierReglement, $contenuReglement_json);
    fclose($fichierReglement);
  }else{
    $fichierReglement = fopen($reglement, 'w+');

    fwrite($fichierReglement, $contenuReglement_json);
    fclose($fichierReglement);
  }
 ?>
