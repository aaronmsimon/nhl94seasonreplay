{% extends 'layouts/base.php' %}

{% block css %}<link href="css/stats.css" rel="stylesheet" />{% endblock %}
{% block js %}
  <!--script src="js/stats.js" type="text/javascript"></script-->
  {% block subjs %}{% endblock %}
{% endblock %}

{% block subnav %}
<div id="nav-sub" class="nav nhl94">
  <a href="{{ base_url }}stats/team-stats">TEAM STATS</a>
  <a href="{{ base_url }}stats/leaders">LEADERS</a>
</div>
{% endblock %}

{% block content %}
{% block subcontent %}{% endblock %}
{% endblock %}
