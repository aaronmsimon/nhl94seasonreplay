$(document).ready(function() {
  $("#player").keyup(function(e) {
    if (e.which == 13) {
      $("#search").click();
    }
  });
  $("#search").click(function() {
    $.post("../players/search_player",{
      name: $("#player").val()
    }, function(data) {
      var jsonData = JSON.parse(data);
      for (var player in jsonData) {
        var prop = jsonData[player];
        var searchresult = '<tr><td><a href="' + window.location.href + "/profile/" + prop.id + '">' + prop.lastname + ", " + prop.firstname + "</a></td><td>" + prop.pos + "</td><td>" + prop.abbr + "</td></tr>"
        $("#searchresults").append(searchresult);
      }
    });
  });
});
