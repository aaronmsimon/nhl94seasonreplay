{% extends 'layouts/base.php' %}

{% block title %}Upcoming Games{% endblock %}

{% block css %}<link href="css/schedule.css" rel="stylesheet" />{% endblock %}

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
  <table class="games">
      <tr class="games-heading">
          <th style="width:150px;">MATCHUP</th>
          <th style="width:150px;"></th>
          <th style="width:75px;">GAME #</th>
          <th style="width:50px;">TV</th>
          <th style="width:250px;">PLAY GAME</th>
      </tr>
      {% for game in date.games %}
      <tr class="game-row">
          <td><img src="images/teamlogos/{{ game.awaylogo }}.png" height="15" /> {{ game.away }} {{game.awaygoals}}</td>
          <td>@ <img src="images/teamlogos/{{ game.homelogo }}.png" height="15" /> {{ game.home }} {{game.homegoals}}</td>
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
              {% if game.homegoals is null %}
                  <button class="playgame">Play Game</button>
              {% endif %}
            {% endif %}
          </td>
      </tr>
      {% endfor %}
  </table>
  {% endfor %}

{% endblock %}