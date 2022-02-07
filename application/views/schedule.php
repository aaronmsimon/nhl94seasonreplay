{% extends 'layouts/schedule.php' %}

{% block title %}Full Schedule{% endblock %}

{% block css %}
<style>
  fieldset {
    border: 0;
  }
  label {
    display: block;
  }
  .ui-selectmenu-menu .ui-menu.customicons .ui-menu-item-wrapper {
    padding: 0.5em 0 0.5em 3em;
  }
  .ui-selectmenu-menu .ui-menu.customicons .ui-menu-item .ui-icon {
    height: 24px;
    width: 24px;
    top: 0.1em;
  }
  .ui-icon.nj {background: url("images/teamicons/nj.png") 0 0 no-repeat;}
  .ui-icon.sj {background: url("images/teamicons/sj.png") 0 0 no-repeat;}
  .ui-icon.wsh {background: url("images/teamicons/wsh.png") 0 0 no-repeat;}
</style>
{% endblock %}

{% block content %}
<div id="datepicker"></div>

<fieldset>
  <label for="teamselect">Select a Team</label>
  <select name="teamselect" id="teamselect">
    {% for team in teams %}
    <option data-class="{{ team.abbr | lower }}">{{ team.city }} {{ team.name }}</option>
    {% endfor %}
  </select>
</fieldset>

<table>
  <tr>
    <th>Game #</th>
    <th>Date</th>
    <th>Teams</th>
  </tr>
  {% for game in schedule %}
  <tr>
    <td class="gameid">{{ game.id }}</td>
    <td>{{ game.gamedate | date("m/j/y") }}</td>
    <td>{{ game.gamedesc }}</td>
    {% if game.completed == 1 %}
      <td>{{ game.awaygoals }}-{{ game.homegoals }}</td>
      <td><a href="{{ base_url }}games/boxscore/{{ game.id }}">Box Score</a></td>
    {% endif %}
  </tr>
  {% endfor %}
</table>
{% endblock %}
