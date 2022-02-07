{% extends 'layouts/schedule.php' %}

{% block title %}Play Game{% endblock %}

{% block subcontent %}
<div id="game">
  <div id="side-{{ gameinfo.awayabbr }}" class="side {% if playing_as == '02' %}active{% endif %}">
    <a href="{{ base_url }}teams/roster/{{ gameinfo.awayabbr }}"><img src="images/teamlogos/{{ gameinfo.awayabbr }}.png" /></a>
    <div>{{ away.record.wins }}-{{ away.record.losses }}-{{ away.record.ties }}</div>
    <div class="team-info">G: {{ away.goalies.firstname }} {{ away.goalies.lastname }}</div>
    <div class="team-info">PP: {{ away.specialteams.PPpct * 100 }}% ({{ away.specialteams.PPrank }})</div>
    <div class="team-info">PK: {{ away.specialteams.PKpct * 100 }}% ({{ away.specialteams.PKrank }})</div>
  </div>
  AT
  <div id="side-{{ gameinfo.homeabbr }}" class="side {% if playing_as == '01' %}active{% endif %}">
    <a href="{{ base_url }}teams/roster/{{ gameinfo.homeabbr }}"><img src="images/teamlogos/{{ gameinfo.homeabbr }}.png" /></a>
    <div>{{ home.record.wins }}-{{ home.record.losses }}-{{ home.record.ties }}</div>
    <div class="team-info">G: {{ home.goalies.firstname }} {{ home.goalies.lastname }}</div>
    <div class="team-info">PP: {{ home.specialteams.PPpct * 100 }}% ({{ home.specialteams.PPrank }})</div>
    <div class="team-info">PK: {{ home.specialteams.PKpct * 100 }}% ({{ home.specialteams.PKrank }})</div>
  </div>
</div>
<div id="controls">
  <div class="button-row"><button id="switchsides">SWITCH SIDES</button></div>
  <div class="button-row"><button id="editgoals">EDIT GOALS</button></div>
  <div class="button-row"><button id="finishgame">FINISH GAME</button></div>
  <div class="button-row"><button id="cancelgame">CANCEL GAME</button></div>
</div>
{% endblock %}
