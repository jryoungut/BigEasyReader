<?php ?>

<!DOCTYPE html>

<html ng-app="rayApp">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Reading For Ray</title>

		<!-- SCROLLS -->
		<!-- load bootstrap and fontawesome via CDN -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" />
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" />
		
		<!-- SPELLS -->
		<!-- load angular via CDN -->
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.8/angular.min.js"></script>

        <script src="scripts/ray.js"></script>
    </head>
    <body ng-controller="mainController">
 		<!-- HEADER AND NAVBAR -->
		<header>
			<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="/">Reading For Ray</a>
				</div>

				<ul class="nav navbar-nav navbar-right">
					<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
					<li><a href="#about"><i class="fa fa-shield"></i> About</a></li>
					<li><a href="#contact"><i class="fa fa-comment"></i> Contact</a></li>
				</ul>
			</div>
			</nav>
		</header>

		<!-- MAIN CONTENT AND INJECTED VIEWS -->
		<div id="main">

			<!-- angular templating -->
			<!-- this is where content will be injected -->
			<div ng-view></div>
		</div>

		<!-- FOOTER -->
		<footer class="text-center">
			Prepared by <a href="http://www.zaycounlimited.com">Zayco Unlimited</a>
		</footer>

    </body>
</html>