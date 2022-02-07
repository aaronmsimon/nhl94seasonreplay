{% extends 'layouts/team.php' %}

{% block subcontent %}
<table>
  <tr>
    <th>Num</th>
    <th>Last Name</th>
    <th>First Name</th>
    <th>Pos</th>
    <th>Weight</th>
    <th>Agility</th>
    <th>Speed</th>
  </tr>
  {% for player in roster %}
  <tr data-playerid="{{ player.id }}b">
    <td class="right">{{ player.num }}</td>
    <td><a href="{{ base_url }}players/profile/{{ player.id }}">{{ player.lastname }}</a></td>
    <td>{{ player.firstname }}</td>
    <td>{{ player.pos }}</td>
    <td class="right">{{ player.weight }}</td>
    <td class="right">{{ player.agility }}</td>
    <td class="right">{{ player.speed }}</td>
  </tr>
  {% endfor %}
</table>
{% endblock %}
