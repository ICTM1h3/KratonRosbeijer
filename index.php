<?php
// Load the file which handles loading the actual webpage.
include 'rendering.php';
include 'database.php';
//Set default title and setup body
$pagetitle = "Kraton Rosbeijer";
$body = renderPage();
?>

<!-- PAGE SETUP -->

<!DOCTYPE html>
<html>
<head>
<!-- css files, etc -->
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
		position:absolute;
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
		transform: translateX(-8px);
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
	}

	.sidebar-open > div:last-child > a > span:hover {
		background-color:#4a4b93;
	}

	.sidebar-open > div:last-child > a {
		color: #232323;
		text-decoration:none;
	}
</style>

<script>
	function OpenSidebar(event) {
		event.target.parentNode.classList.toggle("sidebar-open")
	}
</script>

</head>

	<body>
		<div class="sidebar">
			<div onclick="OpenSidebar(event)" class="nav-icon">
				&#9776;
			</div>
			<div>
				<a href="?p=admin_managevacancies"><span>Beheer vacatures</span></a>
				<a href="?p=insight_vacancies"><span>Inzien vacatures</span></a>
				<a href="?p=infopage"><span>Restaurant info pagina</span></a>
				<a href="?p=restaurantedit"><span>Verander restaurant info</span></a>
			</div>
		</div>
		<div class="content">
		<!-- <ul>
			<li><a href="?p=admin_managevacancies">Beheer vacatures</a></li>
			<li><a href="?p=insight_vacancies">Inzien vacatures</a></li>
			<li><a href="?p=infopage">Restaurant info pagina</a></li>
			<li><a href="?p=restaurantedit">Verander restaurant info</a></li>
		</ul> -->
	<?php
	
	echo $body;
	
	?>
	</div>
	</body>

</html>