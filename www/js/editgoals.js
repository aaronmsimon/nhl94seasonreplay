$(document).ready(function() {
  $("#updatefile").click(function() {
    var obj = new Array();
    $("tr.goal").each(function() {
      obj.push({"hexindex": $(this).attr("id"),"goal": $(this).find("select.goalscorer").val(),"assist1": $(this).find("select.assist1").val(),"assist2": $(this).find("select.assist2").val()});
    });

    $.post("../games/update_save",{
      goals: obj
    }, function(data) {
      location.reload();
    });
  });
});
