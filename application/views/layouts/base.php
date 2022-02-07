<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		<title>{% block title %}{% endblock %} | {{ site_name }}</title>
		<base href="{{ base_url }}www/" />
		<link rel="icon" type="image/png" href="images/favicon.ico" />
		<link href="css/font.css" rel="stylesheet" />
		<link href="css/base.css" rel="stylesheet" />
		<link href="css/jqueryui/custom-{{ jqueryui_theme }}/jquery-ui.min.css" rel="stylesheet" />
		<!--script src="js/googleanalytics.js" type="text/javascript"></script-->
		<script src="js/jquery-{{ jquery_vers }}.min.js" type="text/javascript"></script>
		<script src="js/jquery-ui.min.js" type="text/javascript"></script>
		<script src="js/zamboni.js" type="text/javascript"></script>
		{% block js %}{% endblock %}
		{% block css %}{% endblock %}
	</head>
	<body>
		<div id="allwrapper">
			<div id="header">
				<div id="header-items">
					<div id="leftgif" class="gif"></div>
					<span>NHL 1993-94 Season Replay</span>
					<div id="rightgif" class="gif"></div>
				</div>
			</div>
			<div id="center">
				<div id="nav-main" class="nav nhl94">
				  <a href="{{ base_url }}schedule">SCHEDULE</a>
				  <a href="{{ base_url }}standings">STANDINGS</a>
				  <a href="#">TEAMS</a>
				  <a href="{{ base_url }}players">PLAYERS</a>
					<a href="{{ base_url }}stats">STATS</a>
				</div>
				{% block subnav %}{% endblock %}
				<div id="content">
				{% block content %}{% endblock %}
				</div>
			</div>
			<div id="footer" class="hidden">
			</div>
		</div>
		<div id="zamboni-area"><div id="zamboni"></div></div>
	</body>
</html>
