$(document).ready(function() {
  $("table *").css("cursor","default");

  function loadStats(statistic,sort,page) {
// console.log("stat=" + statistic + " sort=" + sort + " page=" + page);
    $.post("../stats/load_league_leaders",{
      statistic: statistic,
      sortorder: sort,
      page: page
    }, function(data) {
      $("table").find('tr:gt(0)').remove();
      var jsonData = JSON.parse(data);
      console.log(jsonData);
      for (var player in jsonData) {
        var prop = jsonData[player];
        $("table").append('<tr><td class="player"><a href="../players/profile/' + prop.id + '">' + prop.lastname + ', ' + prop.firstname + '</td>' +
          '<td class="team"><a href="../teams/roster/' + prop.abbr + '"">' + prop.abbr +'</a></td>' +
          '<td class="stat-md right">' + prop.gp + '</td>' +
          '<td class="stat-sm right">' + prop.g + '</td>' +
          '<td class="stat-sm right">' + prop.a + '</td>' +
          '<td class="stat-md right">' + prop.pts + '</td>' +
          '<td class="stat-md right">' + prop.pim + '</td>' +
          '<td class="stat-md right">' + prop.plusminus + '</td>' +
          '<td class="stat-lg right">' + prop.ppg + '</td>' +
          '<td class="stat-lg right">' + prop.shg + '</td>' +
          '<td class="stat-lg right">' + prop.sog + '</td>' +
          '<td class="stat-lg right">' + prop.chkf + '</td>' +
          '<td class="stat-lg right">' + prop.chka + '</td>' +
          '<td class="stat-lg right">' + prop.toi + '</td></tr>'
        );
      }
    });
  }

  $("th").click(function() {
    var sortorder;
    if ($(this).find("span").attr("class") == undefined) {
      $("table *").remove("span");
      $(this).append('<span class="ui-icon ui-icon-triangle-1-s"></span>');
      sortorder = 'DESC';
    } else {
      if ($(this).find("span").attr("class") == "ui-icon ui-icon-triangle-1-n") {
        $(this).find("span").removeClass("ui-icon-triangle-1-n");
        $(this).find("span").addClass("ui-icon-triangle-1-s");
        sortorder = 'DESC';
      } else {
        $(this).find("span").removeClass("ui-icon-triangle-1-s");
        $(this).find("span").addClass("ui-icon-triangle-1-n");
        sortorder = 'ASC';
      }
    }
    $("#pagenum").text(1);
    loadStats($(this).attr("data-field"), sortorder, $("#pagenum").text());
  });

  $("#page-selectors button").click(function() {
    // determine stat
    var stat = $("span[class*='ui-icon']").parent().attr("data-field");
    // determine sort
    var sortorder;
    if ($("th[data-field*='" + stat + "'] span").hasClass("ui-icon-triangle-1-s")) {
      sortorder = 'DESC';
    } else {
      sortorder = 'ASC';
    }
    // determine page
    var currentpage = parseInt($("#pagenum").text());
    var newpage;
    if ($(this).hasClass("previous")) {
      newpage = Math.max(1,currentpage - 1);
    } else {
      newpage = currentpage + 1;
    }
    $("#pagenum").text(newpage);
    loadStats(stat, sortorder, newpage);
  });
});
