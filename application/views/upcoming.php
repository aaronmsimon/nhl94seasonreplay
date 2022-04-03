{% extends 'layouts/base.php' %}

{% block title %}Upcoming Games{% endblock %}

{% block css %}<link href="css/schedule.css" rel="stylesheet" />{% endblock %}
{% block js %}<script src="js/schedule.js" type="text/javascript"></script>{% endblock %}

{% block content %}
  <div id="page-header">NHL Schedule</div>

  <div id="daterange" class="flex-container">
    <a class="day" href="../schedule/upcoming-games/{{ currentdate | date_modify("-3 day") | date("Y-m-d") }}">
        <div class="date-details-day">{{ currentdate | date_modify("-3 day") | date("D") | upper }}</div>
        <div class="date-details-date">{{ currentdate | date_modify("-3 day") | date("M j") | upper }}</div>
    </a>
    <a class="day" href="../schedule/upcoming-games/{{ currentdate | date_modify("-2 day") | date("Y-m-d") }}">
        <div class="date-details-day">{{ currentdate | date_modify("-2 day") | date("D") | upper }}</div>
        <div class="date-details-date">{{ currentdate | date_modify("-2 day") | date("M j") | upper }}</div>
    </a>
    <a class="day" href="../schedule/upcoming-games/{{ currentdate | date_modify("-1 day") | date("Y-m-d") }}">
        <div class="date-details-day">{{ currentdate | date_modify("-1 day") | date("D") | upper }}</div>
        <div class="date-details-date">{{ currentdate | date_modify("-1 day") | date("M j") | upper }}</div>
    </a>
    <a class="day" href="../schedule/upcoming-games/{{ currentdate | date("Y-m-d") }}">
        <div class="date-details-day">{{ currentdate | date("D") | upper }}</div>
        <div class="date-details-date">{{ currentdate | date("M j") | upper }}</div>
    </a>
    <a class="day" href="../schedule/upcoming-games/{{ currentdate | date_modify("1 day") | date("Y-m-d") }}">
        <div class="date-details-day">{{ currentdate | date_modify("1 day") | date("D") | upper }}</div>
        <div class="date-details-date">{{ currentdate | date_modify("1 day") | date("M j") | upper }}</div>
    </a>
    <a class="day" href="../schedule/upcoming-games/{{ currentdate | date_modify("2 day") | date("Y-m-d") }}">
        <div class="date-details-day">{{ currentdate | date_modify("2 day") | date("D") | upper }}</div>
        <div class="date-details-date">{{ currentdate | date_modify("2 day") | date("M j") | upper }}</div>
    </a>
    <a class="day" href="../schedule/upcoming-games/{{ currentdate | date_modify("3 day") | date("Y-m-d") }}">
        <div class="date-details-day">{{ currentdate | date_modify("3 day") | date("D") | upper }}</div>
        <div class="date-details-date">{{ currentdate | date_modify("3 day") | date("M j") | upper }}</div>
    </a>
  </div>

  {% for date in dates %}
  <div class="date-heading">{{ date.gamedate | date("l, F j, Y") }}</div>
  {% if date.games.unplayed is not empty %}
  <table class="games">
    <tr class="games-heading">
        <th style="width:150px;">MATCHUP</th>
        <th style="width:150px;"></th>
        <th style="width:75px;">GAME #</th>
        <th style="width:50px;">TV</th>
        <th style="width:250px;">PLAY GAME</th>
    </tr>
    {% for game in date.games.unplayed %}
      <tr class="game-row">
          <td><img src="images/teamlogos/{{ game.awaylogo }}.png" height="15" /> {{ game.away }}</td>
          <td>@ <img src="images/teamlogos/{{ game.homelogo }}.png" height="15" /> {{ game.home }}</td>
          <td class="gameid center">{{ game.id }}</td>
          <td><img alt="ESPN+" class="Image Logo__Network network-espn+" data-mptype="image" src="https://a.espncdn.com/redesign/assets/img/logos/espnplus/ESPN+.svg"></td>
          <td class="actions center">
            {% if gamestatus == 0 %}
              {% if game.id == currentgame %}
                <button id="switchsides">Switch Sides</button>
                <button id="finishgame">Finish Game</button>
                <button id="cancelgame">Cancel Game</button>
                <a id="editgoals" href="{{ base_url }}games/editgoals">Edit Goals</a>
              {% endif %}
            {% else %}
              <button class="playgame">Play Game</button>
            {% endif %}
          </td>
      </tr>
    {% endfor %}
  </table>
  {% endif %}
  {% if date.games.played is not empty %}
  <table class="games">
    <tr class="games-heading">
        <th style="width:150px;">MATCHUP</th>
        <th style="width:150px;"></th>
        <th style="width:75px;">GAME #</th>
        <th style="width:100px;">RESULT</th>
        <th style="width:150px;">TOP PLAYER</th>
        <th style="width:150px;">WINNING GOALIE</th>
    </tr>
    {% for game in date.games.played %}
      <tr class="game-row">
          <td><img src="images/teamlogos/{{ game.awaylogo }}.png" height="15" /> {{ game.away }}</td>
          <td>@ <img src="images/teamlogos/{{ game.homelogo }}.png" height="15" /> {{ game.home }}</td>
          <td class="gameid center">{{ game.id }}</td>
          <td>
            <a href="{{ base_url }}games/boxscore/{{ game.id }}">
              {% if game.homegoals > game.awaygoals %}
                {{ game.homelogo }} {{ game.homegoals }}, {{ game.awaylogo }} {{ game.awaygoals }}
              {% else %}
                {{ game.awaylogo }} {{ game.awaygoals }}, {{ game.homelogo }} {{ game.homegoals }}
              {% endif %}
            </a>
          </td>
          <td class="actions center">{{ game.topplayer }}</td>
      </tr>
    {% endfor %}
  </table>
  {% endif %}
  {% endfor %}

{% endblock %}