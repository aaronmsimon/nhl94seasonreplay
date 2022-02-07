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
        <th>Team</th>
        <th>GP</th>
        <th>W</th>
        <th>L</th>
        <th>T</th>
        <th>Pts</th>
        <th>GF</th>
        <th>GA</th>
      </tr>
      {% for team in div.teams %}
      <tr>
        <td><a href="{{ base_url }}teams/roster/{{ team.abbr | lower }}"><img src="images/teamlogos/{{ team.abbr }}.png" height="20" /><span> {{ team.city }} {{ team.name }}</span></a></td>
        <td class="right" width="20">{{ team.gamesplayed }}</td>
        <td class="right" width="20">{{ team.wins }}</td>
        <td class="right" width="20">{{ team.losses }}</td>
        <td class="right" width="20">{{ team.ties }}</td>
        <td class="right" width="20">{{ team.points }}</td>
        <td class="right" width="20">{{ team.goalsfor }}</td>
        <td class="right" width="20">{{ team.goalsagainst }}</td>
      </tr>
      {% endfor %}
    </table>
  </div>
  {% endfor %}
</div>
{% endfor %}
</div>
{% endblock %}
