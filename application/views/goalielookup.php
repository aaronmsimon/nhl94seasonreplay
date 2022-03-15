{% extends 'layouts/base.php' %}

{% block title %}{{ player.firstname }} {{ player.lastname }}{% endblock %}

{% block css %}<link href="css/players.css" rel="stylesheet" />{% endblock %}
{% block js %}<script src="js/players.js" type="text/javascript"></script>{% endblock %}

{% block content %}
<div id="player-header">
  <img src="images/teamlogos/{{ stats.abbr }}.png" width="125" height="125" />
  <div id="player-info">
    <div id="name-team">
      <p class="name-team">{{ player.firstname }} {{ player.lastname }}</p>
      <p class="name-team">#{{ player.num }} {{ player.pos }} {{ stats.city }} {{ stats.name }}</p>
      <p>Weight: {{ player.weight }} [pounds, add later]</p>
      <label for="handedness">Handedness:</label>{{ player.handedness }}
    </div>
    <div id="player-attributes">
      <div id="attributes-finesse" class="attribute-meters">
        <p><label for="agility">Agility</label><meter id="agility" value="{{ player.agility }}" min="0" max="6">Agility</meter></p>
        <p><label for="speed">Speed</label><meter id="speed" value="{{ player.speed }}" min="0" max="6">Speed</meter></p>
        <p><label for="stickhandling">Stick Handling</label><meter id="stickhandling" value="{{ player.stickhandling }}" min="0" max="6">Stick Handling</meter></p>
        <p><label for="passaccuracy">Pass Accuracy</label><meter id="passaccuracy" value="{{ player.passaccuracy }}" min="0" max="6">Pass Accuracy</meter></p>
        <p><label for="offawareness">Offensive Awareness</label><meter id="offawareness" value="{{ player.offawareness }}" min="0" max="6">Offensive Awareness</meter></p>
        <p><label for="defawareness">Defensive Awareness</label><meter id="defawareness" value="{{ player.defawareness }}" min="0" max="6">Defensive Awareness</meter></p>
      </div>
      <div id="attributes-power" class="attribute-meters">
        <p><label for="shotpower">Shot Power</label><meter id="shotpower" value="{{ player.shotpower }}" min="0" max="6">Shot Power</meter></p>
        <p><label for="shotaccuracy">Shot Accuracy</label><meter id="shotaccuracy" value="{{ player.shotaccuracy }}" min="0" max="6">Shot Accuracy</meter></p>
        <p><label for="endurance">Endurance</label><meter id="endurance" value="{{ player.endurance }}" min="0" max="6">Endurance</meter></p>
        <p><label for="aggressiveness">Aggressiveness</label><meter id="aggressiveness" value="{{ player.aggressiveness }}" min="0" max="6">Aggressiveness</meter></p>
        <p><label for="checking">Checking</label><meter id="checking" value="{{ player.checking }}" min="0" max="6">Checking</meter></p>
        <p><label for="fighting">Fighting</label><meter id="fighting" value="{{ player.fighting }}" min="0" max="6">Fighting</meter></p>
      </div>
    </div>
  </div>
</div>
<div id="player-stats">
  <table>
    <tr><td>GP</td><td>{{ stats.gp }}</td></tr>
    <tr><td>W</td><td>{{ stats.w }}</td></tr>
    <tr><td>L</td><td>{{ stats.l }}</td></tr>
    <tr><td>T</td><td>{{ stats.t }}</td></tr>
    <tr><td>GA</td><td>{{ stats.ga }}</td></tr>
    <tr><td>GAA</td><td>{{ (stats.ga / stats.soi * 1800) | number_format(2) }}</td></tr>
    <tr><td>SA</td><td>{{ stats.sa }}</td></tr>
    <tr><td>SV</td><td>{{ stats.sa - stats.ga }}</td></tr>
    <tr><td>SV%</td><td>{{ ((stats.sa - stats.ga) / stats.sa) | number_format(3) }}</td></tr>
    <tr><td>SO</td><td>{{ stats.so }}</td></tr>
    <tr><td>Average Time on Ice</td><td>{{ stats.toi }}</td></tr>
  </table>
</div>
<div id="by-game">
  <table>
    <tr>
      <th>Opponent</th>
      <th>W</th>
      <th>L</th>
      <th>T</th>
      <th>GA</th>
      <th>GAA</th>
      <th>SA</th>
      <th>SV</th>
      <th>SV%</th>
      <th>SO</th>
      <th>TOI</th>
    </tr>
    {% for game in games %}
    <tr>
      <td><a href="{{ base_url }}games/boxscore/{{ game.scheduleid }}">{{ game.gamedesc }}</a></td>
      <td class="right">{{ game.w }}</td>
      <td class="right">{{ game.l }}</td>
      <td class="right">{{ game.t }}</td>
      <td class="right">{{ game.ga }}</td>
      <td class="right">{% if game.soi > 0 %}{{ (game.ga / game.soi * 1800) | number_format(2) }}{% endif %}</td>
      <td class="right">{{ game.sa }}</td>
      <td class="right">{{ game.sa - game.ga }}</td>
      <td class="right">{% if game.soi > 0 %}{{ ((game.sa - game.ga) / game.sa) | number_format(3) }}{% endif %}</td>
      <td class="right">{{ game.so }}</td>
      <td class="right">{{ game.toi }}</td>
    </tr>
    {% endfor %}
  </table>
</div>
<button id="injury">Report Injury</button>
{% endblock %}
