{% extends 'layouts/team.php' %}

{% block subcontent %}
<table>
  <tr>
    <th>DATE</th>
    <th>OPPONENT</th>
    <th>RESULT</th>
    <th>RECORD</th>
    <th>GOALIE</th>
    <th>TOP PERFORMER</th>
  </tr>
  {% for game in results %}
  <tr">
    <td>{{ game.gamedate }}</td>
    <td><a href="{{ base_url }}teams/home/{{ game.abbr }}">{{ game.opponent }}</a></td>
    <td><a href="{{ base_url }}games/boxscore/{{ game.schedule_id }}">
        <span class="{% if game.GameResult == 'W' %}green{% endif %}{% if game.GameResult == 'L' %}red{% endif %}" style="font-weight:bold;">{{ game.GameResult }}</span> {{ max(game.goals_this,game.goals_opp) }}-{{ min(game.goals_this,game.goals_opp) }}</a></td>
    <td>{{ game.record }}</td>
    <td><a href="{{ base_url }}players/profile/{{ game.goalieid }}">{{ game.goalie }}</a></td>
    <td>{{ game.topplayer }}</td>
  </tr>
  {% endfor %}
</table>
{% endblock %}