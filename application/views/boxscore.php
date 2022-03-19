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
      <table>
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
        {% for player in team.playerstats %}
        <tr>
          <td>{{ player.num }}</td>
          <td>{{ player.firstname }} {{ player.lastname }}</td>
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
    </div>
    {% endfor %}
</div>
<div id="col2" class="col">
    <div class="section">
      <table>
          <thead>
              <th></th>
              <th>1st</th>
              <th>2nd</th>
              <th>3rd</th>
          </thead>
          <tbody>
              {% for team in periodstats %}
              <tr>
                  <td><img src="images/teamlogos/{{ team.abbr }}.png" height="30" /></td>
                  {% for period in team.periodstats %}
                      <td>{{ period.goals }}</td>
                  {% endfor %}
              </tr>
              {% endfor %}
          </tbody>
      </table>
    </div>
    <div class="section subtle">Scoring</div>
    <div id="scoring-summary" class="section">
      {% for goal in scoringsummary %}
      <div class="goal">
        <table>
          <tr>
            <td style="width:60px;"><img src="images/teamlogos/{{ goal.abbr }}.png" height="30" /></td>
            <td>
              <div class="goal-scorer left">{{ goal.goal }} ({{ goal.goalnum }})</div>
              <div class="assists left">{{ goal.assists }}</div>
               <table class="goal-details">
                  <tr>
                    <td class="goal-time">{{ goal.timeelapsed }} / {{ goal.period }}</td>
                    {% if goal.goalsuffix != "" %}
                    <td class="goal-suffix">{{ goal.goalsuffix }}</td>
                    {% endif %}
                  </tr>
                </table>
            </td>
          </tr>
        </table>
      </div>
      {% endfor %}
    </div>
</div>


<!--
<h3>Team Stats</h3>
<div id="gamestats">
  <table>
    <tr>
      <td>{{ gamestats.1.abbr }}</td>
      <td>Team</td>
      <td>{{ gamestats.0.abbr }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.goals }}</td>
      <td>Score</td>
      <td>{{ gamestats.0.goals }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.shots }}</td>
      <td>Shots</td>
      <td>{{ gamestats.0.shots }}</td>
    </tr>
    <tr>
      <td>{{ (gamestats.1.goals / gamestats.1.shots * 100) | number_format(0) }}%</td>
      <td>Shooting Pct</td>
      <td>{{ (gamestats.0.goals / gamestats.0.shots * 100) | number_format(0) }}%</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.ppgoals }}/{{ gamestats.1.ppattempts }}</td>
      <td>Power Play</td>
      <td>{{ gamestats.0.ppgoals }}/{{ gamestats.0.ppattempts }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.pptime }}</td>
      <td>PP Minutes</td>
      <td>{{ gamestats.0.pptime }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.ppshots }}</td>
      <td>PP Shots</td>
      <td>{{ gamestats.0.ppshots }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.shgoals }}</td>
      <td>SH Goals</td>
      <td>{{ gamestats.0.shgoals }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.breakawaygoals }}/{{ gamestats.1.breakawayattempts }}</td>
      <td>Breakaways</td>
      <td>{{ gamestats.0.breakawaygoals }}/{{ gamestats.0.breakawayattempts }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.onetimergoals }}/{{ gamestats.1.onetimerattempts }}</td>
      <td>One-Timers</td>
      <td>{{ gamestats.0.onetimergoals }}/{{ gamestats.0.onetimerattempts }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.penaltyshotgoals }}/{{ gamestats.1.penaltyshotattempts }}</td>
      <td>Penalty Shots</td>
      <td>{{ gamestats.0.penaltyshotgoals }}/{{ gamestats.0.penaltyshotattempts }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.faceoffswon }}</td>
      <td>Faceoffs Won</td>
      <td>{{ gamestats.0.faceoffswon }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.bodychecks }}</td>
      <td>Body Checks</td>
      <td>{{ gamestats.0.bodychecks }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.penalties }}/{{ gamestats.1.pim }}</td>
      <td>Penalties</td>
      <td>{{ gamestats.0.penalties }}/{{ gamestats.0.pim }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.attackzonetime }}</td>
      <td>Attack Zone</td>
      <td>{{ gamestats.0.attackzonetime }}</td>
    </tr>
    <tr>
      <td>{{ gamestats.1.passsuccess }}/{{ gamestats.1.passattempts }} ({{ (gamestats.1.passsuccess / gamestats.1.passattempts * 100) | number_format(0) }}%)</td>
      <td>Pass Attempts</td>
      <td>{{ gamestats.0.passsuccess }}/{{ gamestats.0.passattempts }} ({{ (gamestats.0.passsuccess / gamestats.0.passattempts * 100) | number_format(0) }}%)</td>
    </tr>
  </table>
</div>

<h3>Penalty Summary</h3>
<div id="penaltysummary">
  {% for penalty in penaltysummary %}
  <div>
    {{ penalty.period }} {{ penalty.timeelapsed }} {{ penalty.abbr }} {{ penalty.player }} - {{ penalty.penalty }} ({{ penalty.minutes }} min)
  </div>
  {% endfor %}
</div>

-->

{% endblock %}
