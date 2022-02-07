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
    <tr><td>G</td><td>{{ stats.g }}</td></tr>
    <tr><td>A</td><td>{{ stats.a }}</td></tr>
    <tr><td>Pts</td><td>{{ stats.pts }}</td></tr>
    <tr><td>PIM</td><td>{{ stats.pim }}</td></tr>
    <tr><td>SOG</td><td>{{ stats.sog }}</td></tr>
    <tr><td>+/-</td><td>{{ stats.plusminus }}</td></tr>
    <tr><td>PPG</td><td>{{ stats.ppg }}</td></tr>
    <tr><td>SHG</td><td>{{ stats.shg }}</td></tr>
    <tr><td>Checks Given</td><td>{{ stats.chkf }}</td></tr>
    <tr><td>Checks Received</td><td>{{ stats.chka }}</td></tr>
    <tr><td>Average Time on Ice</td><td>{{ stats.toi }}</td></tr>
  </table>
</div>
<div id="by-game">
  <table>
    <tr>
      <th>Opponent</th>
      <th>G</th>
      <th>A</th>
      <th>Pts</th>
      <th>PIM</th>
      <th>+/-</th>
      <th>PPG</th>
      <th>SHG</th>
      <th>SOG</th>
      <th>ChkF</th>
      <th>ChkA</th>
      <th>TOI</th>
    </tr>
    {% for game in games %}
    <tr>
      <td><a href="{{ base_url }}games/boxscore/{{ game.scheduleid }}">{{ game.gamedesc }}</a></td>
      <td class="right">{{ game.g }}</td>
      <td class="right">{{ game.a }}</td>
      <td class="right">{{ game.pts }}</td>
      <td class="right">{{ game.pim }}</td>
      <td class="right">{{ game.plusminus }}</td>
      <td class="right">{{ game.ppg }}</td>
      <td class="right">{{ game.shg }}</td>
      <td class="right">{{ game.sog }}</td>
      <td class="right">{{ game.chkf }}</td>
      <td class="right">{{ game.chka }}</td>
      <td class="right">{{ game.toi }}</td>
    </tr>
    {% endfor %}
  </table>
</div>
<button id="injury">Report Injury</button>
{% endblock %}
