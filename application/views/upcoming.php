{% extends 'layouts/schedule.php' %}

{% block title %}Upcoming Games{% endblock %}

{% block subcontent %}
<table id="upcoming-games">
  <tr>
    <th>Game #</th>
    <th>Date</th>
    <th>Teams</th>
  </tr>
  {% for game in games %}
  <tr>
    <td class="gameid right">{{ game.id }}</td>
    <td class="gamedate right">{{ game.gamedate | date("m/j/y") }}</td>
    <td>{{ game.gamedesc }}</td>
    <td class="actions">
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
{% endblock %}
