{% extends 'layouts/base.php' %}

{% block title %}Standings{% endblock %}

{% block css %}<link href="css/standings.css" rel="stylesheet" />{% endblock %}

{% block content %}
<div id="standings">
{% for conf in standings %}
<div class="conference">
  <div class="confname blockname"><span>{{ conf.name | upper }} CONFERENCE</span></div>
  {% for div in conf.divisions %}
  <div class="division">
    <div class="divname blockname"><span>{{ div.name | upper }} DIVISION</span></div>
    <table>
      <tr>
        <th style="width: 225px;">Team</th>
        <th>GP</th>
        <th>W</th>
        <th>L</th>
        <th>T</th>
        <th>PTS</th>
        <th>HOME</th>
        <th>AWAY</th>
        <th>GF</th>
        <th>GA</th>
        <th>DIFF</th>
        <th>LAST10</th>
        <th>STRK</th>
      </tr>
      {% for team in div.teams %}
      <tr>
        <td><a href="{{ base_url }}teams/roster/{{ team.abbr | lower }}"><img src="images/teamlogos/{{ team.abbr }}.png" height="20" /><span> {{ team.city }} {{ team.name }}</span></a></td>
        <td class="right" width="20">{{ team.gamesplayed }}</td>
        <td class="right" width="20">{{ team.wins }}</td>
        <td class="right" width="20">{{ team.losses }}</td>
        <td class="right" width="20">{{ team.ties }}</td>
        <td class="right" width="20">{{ team.points }}</td>
        <td class="right" width="20">{{ team.homewins }}-{{ team.homelosses }}-{{ team.hometies }}</td>
        <td class="right" width="20">{{ team.awaywins }}-{{ team.awaylosses }}-{{ team.awayties }}</td>
        <td class="right" width="20">{{ team.goalsfor }}</td>
        <td class="right" width="20">{{ team.goalsagainst }}</td>
        <td class="right {% if team.goalsfor > team.goalsagainst %}green{% endif %} {% if team.goalsfor < team.goalsagainst %}red{% endif %}" width="20">{% if team.goalsfor > team.goalsagainst %}+{% endif %}{{ team.goalsfor - team.goalsagainst }}</td>
        <td class="right" width="20">{{ team.last10wins }}-{{ team.last10losses }}-{{ team.last10ties }}</td>
        <td class="right" width="20">{{ team.streak }}</td>
      </tr>
      {% endfor %}
    </table>
  </div>
  {% endfor %}
</div>
{% endfor %}
</div>
{% endblock %}
