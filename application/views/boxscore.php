{% extends 'layouts/base.php' %}

{% block title %}Box Score{% endblock %}

{% block css %}<link href="css/boxscore.css" rel="stylesheet" />{% endblock %}
{% block js %}<script src="js/boxscore.js" type="text/javascript"></script>{% endblock %}

{% block content %}
<div id="col1" class="col">
    <div id="gamedate" class="section">{{ gamestats.0.gamedate | date("F j, Y") }}</div>
    <div class="section">
        <table id="team-stats">
            <thead>
                <tr>
                    <th></th>
                    <th>SOG</th>
                    <th>FO%</th>
                    <th>PP</th>
                    <th>PIM</th>
                    <th>HITS</th>
                    <th>ATTACK</th>
                    <th>PASS</th>
                </tr>
            </thead>
            <tbody>
                {% for team in gamestats %}
                <tr>
                    <td>
                        <div class="box">
                            <img src="images/teamlogos/{{ team.abbr }}.png" height="30" />
                            <span class="teamname" style="padding-left: 10px;">{{ team.name }}</span>
                        </div>
                    </td>
                    <td>{{ team.shots }}</td>
                    <td>{{ team.faceoffswon }}</td>
                    <td>{{ team.ppgoals }}/{{ team.ppattempts }}</td>
                    <td>{{ team.pim }}</td>
                    <td>{{ team.bodychecks }}</td>
                    <td>{{ team.attackzonetime }}</td>
                    <td>{{ (team.passsuccess / team.passattempts * 100) | number_format(0) }}%</td>
                </tr>
                {% endfor %}
            </tbody>
        </table>
    </div>
    {% for team in playerstats %}
    <div class="section">
      <div>{{ team.city }} {{ team.name }}</div>
      <table class="border">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>G</th>
          <th>A</th>
          <th>P</th>
          <th>+/-</th>
          <th>PIM</th>
          <th>SOG</th>
          <th>HITS</th>
          <th>REC'D</th>
          <th>TOI</th>
        </tr>
        {% for player in team.playerstats.skaters %}
        <tr>
          <td>{{ player.num }}</td>
          <td class="left"><a href="{{ base_url }}players/profile/{{ player.id }}">{{ player.firstname }} {{ player.lastname }}</a></td>
          <td>{{ player.g }}</td>
          <td>{{ player.a }}</td>
          <td>{{ player.pts }}</td>
          <td>{{ player.plusminus }}</td>
          <td>{{ player.pim }}</td>
          <td>{{ player.sog }}</td>
          <td>{{ player.chkf }}</td>
          <td>{{ player.chka }}</td>
          <td>{{ player.toi }}</td>
        </tr>
        {% endfor %}
      </table>
      <table class="border">
        <tr>
          <th>#</th>
          <th>Goalie</th>
          <th>SAVE-SHOTS</th>
          <th>SV%</th>
          <th>TOI</th>
        </tr>
        {% for player in team.playerstats.goalies %}
        <tr>
          <td>{{ player.num }}</td>
          <td class="left"><a href="{{ base_url }}players/profile/{{ player.id }}">{{ player.firstname }} {{ player.lastname }}</a></td>
          <td>{{ player.shots - player.ga }}-{{ player.shots }}</td>
          <td>{{ "%0.3f" | format((player.shots - player.ga) / player.shots) }}</td>
          <td>{{ player.toi }}</td>
        </tr>
        {% endfor %}
      </table>
    </div>
    {% endfor %}
</div>
<div id="col2" class="col">
    <div class="section">
      <table class="border">
          <thead>
              <th></th>
              <th>1ST</th>
              <th>2ND</th>
              <th>3RD</th>
              {% if otflag %}<th>OT</th>{% endif %}
              <th>T</th>
          </thead>
          <tbody>
              {% for team in periodstats %}
              {% set totalGoals = 0 %}
              <tr>
                  <td><img src="images/teamlogos/{{ team.abbr }}.png" height="30" /></td>
                  {% for period in team.periodstats %}
                    {% if period.period <= 3 or otflag %}
                      <td>{{ period.goals }}</td>
                      {% set totalGoals = totalGoals + period.goals %}
                    {% endif %}
                  {% endfor %}
                  <td>{{ totalGoals }}</td>
              </tr>
              {% endfor %}
          </tbody>
      </table>
    </div>
    <div class="section subtle">Scoring</div>
    <div id="scoring-summary" class="section">
      {% for goal in scoringsummary %}
      <div class="goal">
        <table class="border">
          <tr>
            <td style="width:60px;"><img src="images/teamlogos/{{ goal.abbr }}.png" height="30" /></td>
            <td>
              <div class="goal-scorer left"><a href="{{ base_url }}players/profile/{{ goal.playerid_g }}">{{ goal.goal }}</a> ({{ goal.goalnum }})</div>
              <div class="assists left">
                {% if goal.assist1 is empty %}
                  Unassisted
                {% else %}
                <a href="{{ base_url }}players/profile/{{ goal.playerid_a1 }}">{{ goal.assist1 }}</a>{% if goal.assist2 is not empty %}, <a href="{{ base_url }}players/profile/{{ goal.playerid_a2 }}">{{ goal.assist2 }}</a>{% endif %}
                {% endif %}
              </div>
               <table style="width:{% if goal.goalsuffix is empty %}200{% else %}225{% endif %}px;">
                  <tr>
                    <td class="goal-time" style="border:1px solid #{{ goal.color }};color:#{{ goal.color }};">{{ goal.timeelapsed }} / {{ goal.period }}</td>
                    <td class="goal-time" style="border:1px solid #{{ goal.color }};background-color:#{{ goal.color }};color:white;">
                      <span style="{% if goal.abbr == goal.away_abbr %}font-weight:bold;{% endif %}">{{ goal.away_abbr }} {{ goal.awaygoal_total }}</span>, 
                      <span style="{% if goal.abbr == goal.home_abbr %}font-weight:bold;{% endif %}">{{ goal.home_abbr }} {{ goal.homegoal_total }}</span>
                    </td>
                    {% if goal.goalsuffix != "" %}
                    <td class="goal-suffix" style="border:1px solid #{{ goal.color }};color:#{{ goal.color }};">{{ goal.goalsuffix }}</td>
                    {% endif %}
                  </tr>
                </table>
            </td>
          </tr>
        </table>
      </div>
      {% endfor %}
    </div>
    <div class="section subtle">Penalties</div>
    <div id="penaltysummary" class="section">
      <table class="border">
      <tr>
        <th>Per / Time</th>
        <th>Team</th>
        <th>Player / Penalty</th>
      </tr>
      {% for penalty in penaltysummary %}
      <tr>
        <td>{{ penalty.period }} / {{ penalty.timeelapsed }}</td>
        <td style="width:60px;"><img src="images/teamlogos/{{ penalty.abbr }}.png" height="30" /></td>
        <td class="left">{{ penalty.firstname }} {{ penalty.lastname }} ({{ penalty.penalty }})</td>
      </tr>
      {% endfor %}
      </table>
    </div>
    <div class="section subtle">Shots On Goal</div>
    <div class="section">
      <table class="border">
      {% for row in sog %}
        {% if row.period != 4 or otflag %}
        <tr>
            <td>{{ row.periodlabel }}</td>
            <td>{{ row.away }}</td>
            <td>{{ row.home }}</td>
        </tr>
        {% endif %}
      {% endfor %}
      </table>
    </div>
</div>

{% endblock %}