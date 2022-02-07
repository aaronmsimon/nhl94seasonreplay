$(document).ready(function() {
  $(".playgame").click(function() {
    $.post("../schedule/log_game",{
      gameid: $(this).parent().parent().find(".gameid").text()
    }, function(data) {
      var myObj = JSON.parse(data);
      window.location = "../games/play-game?playing_as=" + myObj.playingas + "&home=" + myObj.homegoalie + "&away=" + myObj.awaygoalie;
    });
  });
  $("#cancelgame").click(function() {
    $.post("../schedule/log_game",{
      gameid: -1
    }, function(data) {
      window.location = "../schedule";
    });
  });
  $("#finishgame").click(function() {
    $.post("../schedule/finish_game",function(data) {
      window.location = "../games/boxscore/" + data;
    });
  });
  $("#switchsides").click(function() {
    $.post("../schedule/switch_sides",function(data) {
      $(".side").removeClass("active");
      $("#side-" + data).addClass("active");
    });
  });
  $("#editgoals").click(function() {
    window.location = "../games/editgoals";
  });

  $("#datepicker").datepicker();

  $.widget( "custom.iconselectmenu", $.ui.selectmenu, {
    _renderItem: function( ul, item ) {
      var li = $( "<li>" ),
        wrapper = $( "<div>", { text: item.label } );

      if ( item.disabled ) {
        li.addClass( "ui-state-disabled" );
      }

      $( "<span>", {
        style: item.element.attr( "data-style" ),
        "class": "ui-icon " + item.element.attr( "data-class" )
      })
        .appendTo( wrapper );

      return li.append( wrapper ).appendTo( ul );
    }
  });

  $("#teamselect")
    .iconselectmenu()
    .iconselectmenu("menuWidget")
      .addClass("ui-menu-icons customicons");
});
