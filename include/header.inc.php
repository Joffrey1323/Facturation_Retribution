<?php
    session_start();
    $title = "Module de facturation";
    //$db = new MyPDO();
?>

<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<title><?php echo $title ?></title>

    <link rel="stylesheet" type="text/css" href="/../css/bootstrap.min.css">
    <style>
      body{
        background-color: white;
        font-family: sans-serif;
      }

      #container{
        margin-left: 300px;
        width: 1000px;
      }

      #header{
        background-color: white;
      }

      #CoordEntreprise{
        display: inline-block;
      }
      #CoordClient{
        display: inline-block;
        margin-left: 50px;
      }

      #container #header .Titres{
        margin-left: 130px;
        display: inline-block;
      }

      #container #body{
        background-color: white;
      }

      #tableauFacture{
        margin-top: 100px;
        margin-left: 150px;
      }

      #montant{
        margin-left: 700px;
      }

      #Bouton{
        margin-left: 450px;
      }

      .topnav {
    background-color: white;
    overflow: hidden;
}

/* Style the links inside the navigation bar */
.topnav a {
    float: left;
    background-color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 17px;
}

/* Change the color of links on hover */
.topnav a:hover {
    background-color: white;
    color: black;
}

/* Add a color to the active/current link */
.topnav a.active {
    background-color:black;
    color: white;
}

  ul { list-style-type: none; margin: 0; padding: 0; margin-bottom: 10px; }
  li { margin: 5px; padding: 5px; width: 150px; }

    </style>

</head>


<body>
    <div class="container">
