<script type="text/javascript">
        $(document).ready(function() {

            $(document).mouseup(function() {
				$("#loginform").mouseup(function() {
					return false
				});

				$("a.close").click(function(e){
					e.preventDefault();
					$("#loginform").hide();
                    $(".lock").fadeIn();
				});

                if ($("#loginform").is(":hidden"))
                {
                    $(".lock").fadeOut();
                } else {
                    $(".lock").fadeIn();
                }
				$("#loginform").toggle();
            });


			// I dont want this form be submitted
			$("form#signin").submit(function() {
			  return true;
			});

			// This is example of other button
			$("input#cancel_submit").click(function(e) {
					$("#loginform").hide();
                    $(".lock").fadeIn();
			});


        });
</script>
</head>
<body>

<div id="cont">
  <!--<div style="position:absolute; top: 20px; right: 50px;"><a href="#" style="color:#FFFFFF; font-weight: bold; text-decoration: none;"> ���� ������� ����� ������� </a></div>-->
  <div class="box lock"> </div>
  <div id="loginform" class="box form">
    <h2>Дастурга кириш<a href="" class="close">Close it</a></h2>
    <div class="formcont">
    <fieldset id="signin_menu">
      <span class="message">Фойдаланувчи ва паролни киритинг</span>
        <?php print form_open("login/enter", array('method'=>'post', 'id'=>'signin')) ?>
      
      <label for="username">Фойдаланувчи номи</label>
        <input id="username" name="username" value=""  class="required" tabindex="4" type="text">
        </p>
        <p>
          <label for="password">Пароль</label>
          <input id="password" name="password" value="" class="required" tabindex="5" type="password">
        </p>
        <p class="clear"></p>
        <p class="remember">
          <input id="signin_submit" value="Кириш" tabindex="6" type="submit" name="enter">
          <input id="cancel_submit" value="Ортга қайтиш" tabindex="7" type="button">
        </p>
      </form>
      </fieldset>
    </div>
    <div class="formfooter"></div>
  </div>
</div>
<!-- Begin Full page background technique -->
<div id="bg">
  <div>
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="/images/bg.jpg" alt=""/> </td>
      </tr>
    </table>
  </div>
</div>
<!-- End Full page background technique -->
</body>
</html>
