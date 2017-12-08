<?php
session_start();

// Load the file which handles loading the actual webpage.
include 'helpers/ranks.php';
include 'helpers/tabs.php';
include 'helpers/rendering.php';
include 'helpers/database.php';
include 'helpers/email.php';
include 'helpers/validators.php';
//Set default title and setup body
$pagetitle = "Kraton Rosbeijer";
$body = renderPage();

if (isset($_GET['no_layout'])) {
	echo $body;
	return;
}

$tabs = getTabsForCurrentUser();

?>

<!-- PAGE SETUP -->

<!DOCTYPE html>
<html>
<head>
<!-- css files, etc -->
<!-- Bootstrap connection link -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<link rel="stylesheet" href="css-style-sheet.css" type="text/css">
<title><?php echo htmlentities($pagetitle); ?> </title>

<style>
	body {
		background-color: #E6E6E6;
	}
	
	.container {
		width:100%;
		height:100%;
		position:absolute;
		left:0;
		top:0;
	}

	.sidebar {
		width:300px;
		transform: translateX(-270px);
		top:0;
		bottom:0;
		position:fixed;
		will-change:transform;
		transition: transform 0.4s ease, background-color 0.4s ease;
		z-index:9999999999;
	}

	.sidebar > .nav-icon {
		position: absolute;
		right:2px;
		font-size: 30px;
		cursor:pointer;
		z-index:9999999999999;
	}

	.sidebar:not(.sidebar-open) > div:last-child {
		display:none;
	}

	.sidebar-open {
		background-color:#6e6fc2;
		transform: translateX(0px);
	}

	.content {
		top:25px;
		bottom:25px;
		position:relative;
		background-color: #FFFFFF;
		width: 66%;
		margin:0 auto;
		border: 1px solid #8c8c8c;
		border-radius: 5px;
		min-height:400px;
		padding:1px;
	}

	.sidebar-open > div:last-child {
		display:flex;
		flex-direction: column;
	}
	.sidebar-open > div:last-child > a > span {
		height:45px;
		line-height:40px;
		display: inline-block;
		position:relative;
		left:0;
		right:0;
		border-bottom:1px solid #4e4e4e;
		background-color:#5a5bA3;
		transition:background-color 0.1s linear;
		width:297px;
		font-family:'Raleway', sans-serif;
		padding-left:3px;
		color: white;
	}

	.sidebar-open > div:last-child > a > span:hover {
		background-color:#4a4b93;
	}

	.sidebar-open > div:last-child > a {
		color: #232323;
		text-decoration:none;
	}

	.overlay {
		z-index:9999999999998;
		position:fixed;
		width:100%;
		height:100%;
		top:0;
		left:0;
		bottom:0px;
		display:none;
		background-color: rgba(0, 0, 0, 0.2);
	}

	.overlay-visible {
		display:block;
	}
</style>

<script>
	function OpenSidebar(event) {
		document.querySelector(".sidebar").classList.toggle("sidebar-open")
		document.querySelector(".overlay").classList.toggle("overlay-visible")
	}
</script>

</head>
	<body>
		<div class="overlay" onclick="OpenSidebar(event)"></div>
		<div class="sidebar">
			<div onclick="OpenSidebar(event)" class="nav-icon">
				&#9776;
			</div>
			<div>
				<?php foreach ($tabs as $tab) { ?>
					<a href="<?= $tab['href'] ?>"><span><?= $tab['title'] ?></span></a>
				<?php } ?>
				<!-- <a href="?p=managevacancies"><span>Beheer vacatures</span></a>
				<a href="?p=insight_vacancies"><span>Inzien vacatures</span></a>
				<a href="?p=infopage"><span>Restaurant info pagina</span></a>
				<a href="?p=restaurantedit"><span>Verander restaurant info</span></a>
        		<a href="?p=managemenu"><span>Beheer het menu</span></a>
			  	<a href="?p=insight_menu"><span>Inzien menukaart</span></a>
			  	<a href="?p=inlogpage"><span>Inloggen</span></a>
			  	<a href="?p=register"><span>Registreren</span></a>
			  	<a href="?p=reservetable"><span>Tafel reserveren</span></a> -->
			</div>
		</div>
		<div class="content">
		<!-- <ul>
			<li><a href="?p=admin_managevacancies">Beheer vacatures</a></li>
			<li><a href="?p=insight_vacancies">Inzien vacatures</a></li>
			<li><a href="?p=admin_managevacancies">Beheer vacatures</a></li>
			<li><a href="?p=infopage">Restaurant info pagina</a></li>
			<li><a href="?p=restaurantedit">Verander restaurant info</a></li>
    -->
	<?php
	
	echo $body;
	
	?>
	</div>
	</body>

</html>