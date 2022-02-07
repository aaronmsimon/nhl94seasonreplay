{% extends 'layouts/stats.php' %}

{% block title %}League Leaders{% endblock %}

{% block subjs %}<script src="js/leaders.js" type="text/javascript"></script>{% endblock %}

{% block subcontent %}
<div id="page-selectors">
  <button class="previous round">&#8249;</button>
  <span id="page-area">PAGE <span id="pagenum">{{ page }}</span></span>
  <button class="next round">&#8250;</button>
</div>
<table id="leaders">
  <tr>
    <th data-field="lastname">Player</th>
    <th data-field="abbr">Team</th>
    <th data-field="gp">GP</th>
    <th data-field="g">G</th>
    <th data-field="a">A</th>
    <th data-field="pts">Pts<span class="ui-icon ui-icon-triangle-1-s"></span></th>
    <th data-field="pim">PIM</th>
    <th data-field="plusminus">+/-</th>
    <th data-field="ppg">PPG</th>
    <th data-field="shg">SHG</th>
    <th data-field="sog">SOG</th>
    <th data-field="chkf">ChkF</th>
    <th data-field="chka">ChkA</th>
    <th data-field="toi">TOI</th>
  </tr>
  {% for player in leaders %}
  <tr>
    <td class="player"><a href="{{ base_url}}players/profile/{{ player.id }}">{{ player.lastname }}, {{ player.firstname }}</a></td>
    <td class="team"><a href="{{ base_url }}teams/roster/{{ player.abbr }}">{{ player.abbr }}</a></td>
    <td class="stat-md right">{{ player.gp }}</td>
    <td class="stat-sm right">{{ player.g }}</td>
    <td class="stat-sm right">{{ player.a }}</td>
    <td class="stat-md right">{{ player.pts }}</td>
    <td class="stat-md right">{{ player.pim }}</td>
    <td class="stat-md right">{{ player.plusminus }}</td>
    <td class="stat-lg right">{{ player.ppg }}</td>
    <td class="stat-lg right">{{ player.shg }}</td>
    <td class="stat-lg right">{{ player.sog }}</td>
    <td class="stat-lg right">{{ player.chkf }}</td>
    <td class="stat-lg right">{{ player.chka }}</td>
    <td class="stat-lg right">{{ player.toi }}</td>
  </tr>
  {% endfor %}
</table>
{% endblock %}
