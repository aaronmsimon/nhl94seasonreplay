{% extends 'layouts/schedule.php' %}

{% block title %}Upcoming Games{% endblock %}

{% block subcontent %}
    <ul id="daterange">
        <li><a href="../schedule/upcoming-games/{{ currentdate | date_modify("-3 day") | date("Y-m-d") }}">{{ currentdate | date_modify("-3 day") | date("D M j") | upper }}</a></li>
        <li><a href="../schedule/upcoming-games/{{ currentdate | date_modify("-2 day") | date("Y-m-d") }}">{{ currentdate | date_modify("-2 day") | date("D M j") | upper }}</a></li>
        <li><a href="../schedule/upcoming-games/{{ currentdate | date_modify("-1 day") | date("Y-m-d") }}">{{ currentdate | date_modify("-1 day") | date("D M j") | upper }}</a></li>
        <li style="font-weight: bold;">{{ currentdate | date("D M j") | upper }}</li>
        <li><a href="../schedule/upcoming-games/{{ currentdate | date_modify("+1 day") | date("Y-m-d") }}">{{ currentdate | date_modify("+1 day") | date("D M j") | upper }}</a></li>
        <li><a href="../schedule/upcoming-games/{{ currentdate | date_modify("+2 day") | date("Y-m-d") }}">{{ currentdate | date_modify("+2 day") | date("D M j") | upper }}</a></li>
        <li><a href="../schedule/upcoming-games/{{ currentdate | date_modify("+3 day") | date("Y-m-d") }}">{{ currentdate | date_modify("+3 day") | date("D M j") | upper }}</a></li>
    </ul>
    {% for date in dates %}
    <div>
        {{ date.gamedate | date("l, F j") }}
    </div>
    <table>
        <tr>
            <th>MATCHUP</th>
            <th>ID</th>
            <th>PLAY GAME</th>
        </tr>
        {% for game in date.games %}
        <tr>
            <td><img src="images/teamlogos/{{ game.awaylogo }}.png" height="20" />{{ game.away }}{{game.awaygoals}} @ <img src="images/teamlogos/{{ game.homelogo }}.png" height="20" />{{ game.home }}{{game.homegoals}}</td>
            <td class="gameid right">{{ game.id }}</td>
            <td class="actions">
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
