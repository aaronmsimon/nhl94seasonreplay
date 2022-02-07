{% extends 'layouts/schedule.php' %}

{% block title %}Edit Goals{% endblock %}

{% block subjs %}<script src="js/editgoals.js" type="text/javascript"></script>{% endblock %}

{% block content %}
<table>
<tr>
  <th>Period</th>
  <th>Time</th>
  <th>Team</th>
  <th>Goal Scorer</th>
  <th>Assist 1</th>
  <th>Assist 2</th>
</tr>
{% for goal in goals %}
<tr class="goal" id="{{ goal.hexindex }}">
  <td>{{ goal.period }}</td>
  <td>{{ goal.timeelapsed | date('i:s') }}</td>
  <td>{{ goal.teamabbr }}</td>
  <td>
    <select class="goalscorer">
    {% if goal.team == 'home' %}
      {% for player in homeroster %}
        <option value="{{ player.index }}" {% if goal.goalscorer == player.id %}selected{% endif %}>{{ player.num }}-{{ player.firstname }} {{ player.lastname }}</option>
      {% endfor %}
    {% else %}
      {% for player in awayroster %}
        <option value="{{ player.index }}" {% if goal.goalscorer == player.id %}selected{% endif %}>{{ player.num }}-{{ player.firstname }} {{ player.lastname }}</option>
      {% endfor %}
    {% endif %}
    </select>
  </td>
  <td>
    <select class="assist1">
      <option value="255">None</option>
    {% if goal.team == 'home' %}
      {% for player in homeroster %}
        <option value="{{ player.index }}" {% if goal.assist1 == player.id %}selected{% endif %}>{{ player.num }}-{{ player.firstname }} {{ player.lastname }}</option>
      {% endfor %}
    {% else %}
      {% for player in awayroster %}
        <option value="{{ player.index }}" {% if goal.assist1 == player.id %}selected{% endif %}>{{ player.num }}-{{ player.firstname }} {{ player.lastname }}</option>
      {% endfor %}
    {% endif %}
    </select>
  </td>
  <td>
    <select class="assist2">
      <option value="255">None</option>
    {% if goal.team == 'home' %}
      {% for player in homeroster %}
        <option value="{{ player.index }}" {% if goal.assist2 == player.id %}selected{% endif %}>{{ player.num }}-{{ player.firstname }} {{ player.lastname }}</option>
      {% endfor %}
    {% else %}
      {% for player in awayroster %}
        <option value="{{ player.index }}" {% if goal.assist2 == player.id %}selected{% endif %}>{{ player.num }}-{{ player.firstname }} {{ player.lastname }}</option>
      {% endfor %}
    {% endif %}
    </select>
  </td>
</tr>
{% endfor %}
</table>
<button id="updatefile">Update Save File</button>
{% endblock %}
