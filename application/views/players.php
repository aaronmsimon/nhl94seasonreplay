{% extends 'layouts/base.php' %}

{% block title %}Players{% endblock %}

{% block css %}<link href="css/players.css" rel="stylesheet" />{% endblock %}
{% block js %}<script src="js/players.js" type="text/javascript"></script>{% endblock %}

{% block content %}
<div id="playersearch">
  <label for="player">Search for a Player</label>
  <input type="text" id="player" />
  <button id="search">Search</button>
</div>
<table id="searchresults">
</table>
{% endblock %}
