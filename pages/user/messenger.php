<?php
$levels = ["lid", "gebruiker"];
require_once("../../includes/tools/security.php");?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Discusclub Holland</title>

    <!-- custom css -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/message.css">
    <!-- font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!-- bootstrap style -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>

<body>
    <div id="fb-root"></div>
    <script>
    ;(function(d, s, id) {
  var js,
    fjs = d.getElementsByTagName(s)[0]
  if (d.getElementById(id)) return
  js = d.createElement(s)
  js.id = id
  js.src = '//connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.10'
  fjs.parentNode.insertBefore(js, fjs)
})(document, 'script', 'facebook-jssdk')
</script>
    <?php
    require_once("../../includes/components/nav.php");
    ?>
    <br><br><br><br>
    <div class="container main">
        <div class="row">
            <div class="col-md-4">
                <div class="UserTab">
                    <img src="http://via.placeholder.com/500x500" class="ImgUser ImageStatic" />
                    <div class="Username"><b>Gebruikersnaam van user</b></div>
                </div>
                <div class="OtherTab flexcroll">
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Other">
                        <div><img src="http://via.placeholder.com/350x150" class="OtherUsers ImageStatic"></div>
                        <div class="UsernameTab"><b>Gebruikersnaam</b></div>
                        <div>tekst die ze zelf invullen</div>
                    </div>
                </div>
                <div class="SearchUser">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="" aria-describedby="basic-addon1">
                      <span class="input-group-btn " id="basic-addon1"><button class="btn btn-secondary ButtonHeight" type="button"><i class="glyphicon glyphicon-plus icon "></i></button></span>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="UserTab">
                    <img src="http://via.placeholder.com/500x500" class="ImgUser ImageStatic" />
                    <div class="Username"><b>Gebruikersnaam van user</b></div>
                </div>
                <div class="imageBackgroundText flexcroll">
                    <div class="Message">
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Response ">
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Message">
                        <div>tekst die ze zelf invullen</div>
                    </div>
                    <div class="Response ">
                        <div>tekst die ze Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. invullen</div>
                    </div>
                </div>
                <div class="SearchUser">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="" aria-describedby="basic-addon1">
                      <span class="input-group-btn " id="basic-addon1"><button class="btn btn-secondary ButtonHeight" type="button"><i class="glyphicon glyphicon-plus icon "></i></button></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>    <br>
    <footer>
<?php require_once("../../includes/components/footer.php") ; ?>
    </footer>
    <!-- bootstrap script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
<!-- https://twitter.com/DiscusHolland -->
