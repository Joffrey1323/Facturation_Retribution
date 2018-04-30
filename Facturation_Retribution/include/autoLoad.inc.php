<?php
	function __autoload($nomClasse) {

		$repertoireClasse = //TODO;
		require $repertoireClasse.$nomClasse.".class.php";
	}
?>