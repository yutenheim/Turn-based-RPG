<!DOCTYPE HTML>
<html>
 <head>
  <meta charset="utf-8">
  <title>MMORPG v.0.1</title>
  <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js' type='text/javascript'></script>
  <script>
  	function send()
  	{
  			
         	$.ajax({
                  type: "POST",
                  url: "controller.php",
                  data: $('#myform').serialize(),
                  success: function(html) {
                          $("#result").empty();
                          $("#result").append(html);
                  }
          });
          $("input:radio").attr("checked", false);
  	}

    function potion()
    {
        
          $.ajax({
                  type: "POST",
                  url: "potion.php",
                  data: $('#potion').serialize(),
                  success: function(html) {
                          $("#result").empty();
                          $("#result").append(html);
                  }
          });
          $("input:radio").attr("checked", false);
    }
  </script>
 </head>
 <body>

 <div id="wrapper" style="margin: 30px;">
 <form action="" id="myform">
  <p><b>Куда бьем соперника?</b></p>
  <input type="radio" name="kick" value="kick-in-head" id="kick-in-head">В голову<Br>
  <input type="radio" name="kick" value="kick-in-chest" id="kick-in-chest">В корпус<Br>
  <input type="radio" name="kick" value="kick-in-legs" id="kick-in-legs">В ноги</p>
  <p><b>Куда ставим защиту?</b></p>
  <input type="radio" name="block" value="block-head" id="block-head">Защищаем голову<Br>
  <input type="radio" name="block" value="block-chest" id="block-chest">Защищаем корпус<Br>
  <input type="radio" name="block" value="block-legs" id="block-legs">Защищаем ноги</p>
  <input onclick="send()" type="button" value="Сделать ход"></p>
 </form>
 <!--<form action="" id="potion">
  <input type="hidden" name="potion" value="potion">
  <input onclick="potion()" type="button" value="Take a potion"></p>
 </form>-->
 <div id="result"></div>
 </body>
</div>
</html>
