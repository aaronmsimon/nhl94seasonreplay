{% extends 'layouts/base.php' %}

{% block title %}{{ team.city }} {{ team.name }}{% endblock %}

{% block content %}
<div id="teamheading">
  <img src="images/teamlogos/{{ team.abbr }}.png" />
  {{ team.city }} {{ team.name }}
</div>
<div id="nav-sub" class="nhl94">
  <a href="{{ base_url }}teams/home/{{ team.abbr | lower }}">HOME</a>
  <a href="{{ base_url }}teams/roster/{{ team.abbr | lower }}">ROSTER</a>
  <a href="{{ base_url }}teams/schedule/{{ team.abbr | lower }}">SCHEDULE</a>
  <a href="{{ base_url }}teams/stats/{{ team.abbr | lower }}">STATS</a>
</div>
{% block subcontent %}
{% endblock %}
{% endblock %}
