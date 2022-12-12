<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");

session_start();

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Inscrivez vous aux meilleurs formations et améliorez votre compétence | Gehant</title>
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/css.php"); ?>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
		<link rel="stylesheet" href="/inc/css/index.css" type="text/css" />
	</head>

	<body>

		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/header.php"); ?>

		<div class="container-fluid">
		
				<?php
                    
				    require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/index/carrousel.php");
                    require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/index/best-domaines.php");
                   require_once ($_SERVER["DOCUMENT_ROOT"] ."/inc/other/index/best-courses.php");
                    require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/index/partenaires.php");
                    require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/index/about.php");
                ?>

			
			
		</div>

		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/footer.php"); ?>

		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/js.php"); ?>
		<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
		<script src="/inc/js/index.js" type="text/javascript"></script>
	</body>

</html>