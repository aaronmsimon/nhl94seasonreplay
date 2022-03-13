{% extends 'layouts/base.php' %}

{% block css %}<link href="css/schedule_orig.css" rel="stylesheet" />{% endblock %}
{% block js %}
  <script src="js/schedule.js" type="text/javascript"></script>
  {% block subjs %}{% endblock %}
{% endblock %}

{% block subnav %}
<div id="nav-sub" class="nav nhl94">
  <a href="{{ base_url }}schedule/upcoming-games">UPCOMING GAMES</a>
  <a href="{{ base_url }}schedule/full-schedule">FULL SCHEDULE</a>
</div>
{% endblock %}

{% block content %}
{% block subcontent %}{% endblock %}
{% endblock %}
