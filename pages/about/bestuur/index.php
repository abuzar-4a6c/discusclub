<?php require_once("../../../includes/tools/security.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Discusclub Holland</title>
    <?php require_once("../../../includes/components/head.php"); ?>
</head>

<body><!-- Global site tag (gtag.js) - Google Analytics --><script async src="https://www.googletagmanager.com/gtag/js?id=UA-110090721-1"></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-110090721-1');</script>
    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.10";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <?php
    require_once("../../../includes/components/nav.php");
    ?>

    <br><br>
    <div class="container main">
      <div class="row columns">
          <div class="col-md-12">
              <ol class="breadcrumb">
                  <li><a href="/">Home</a></li>
                  <li><a href="/about/">Over ons</a></li>
                  <li class="active">Het bestuur</li>
              </ol>
          </div>
      <div class="col-md-12">
        <div class="panel panel-primary ">
          <div class="panel-heading border-colors">Het bestuur</div>
          <div class="panel-body padding-padding space">
            <a href="/about/bestuur/jan"><img title="Jan Verkaik" class="col-md-4 bestuur" src="/images/bestuur/jan.PNG"></a>
            <a href="/about/bestuur/john"><img title="John Maas" class="col-md-4 bestuur" src="/images/bestuur/john.jpg"></a>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading border-colors">Bekijk de nieuwste albums</div>
            <div class="panel-body">
            <?php
            $sth = $dbc->prepare("SELECT *, album.id AS album_id FROM album JOIN image ON image.album_id = album.id ORDER BY created_at DESC LIMIT 6");
            $sth->execute();
            $res = $sth->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($res)) :
              foreach($res as $key => $value) : ?>
              <div class=" col-md-4 col-sm-4 ruimte"><a href="/album/post/<?php echo $value["album_id"]; ?>"><div  title="image album" class="imgThumbnail" style="background-image: url('/images<?php echo $value['path']?>');"></div></a><br><?php echo $value['created_at']?></div>
            <?php endforeach; ?>
            <?php else : ?>
            <tr><td>Geen albums gevonden</td></tr>
        <?php endif ;?>
        </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading border-colors">Laatste reacties op topics</div>
            <div class="panel-body">
                  <?php
                  $sth = $dbc->prepare("SELECT * FROM topic JOIN topic_permission AS tp ON tp.topic_id = topic.id WHERE tp.role_id = :role_id ORDER BY last_changed DESC LIMIT 5");
                  $sth->execute([":role_id" => $_SESSION['user']->role_id]);
                  $res = $sth->fetchAll(PDO::FETCH_ASSOC);

                  if(!empty($res)) :
                  foreach($res as $key => $value) : ?>
                  <a href="/forum/post/<?php echo $value['id']; ?>" class="blauwtxt"><div class="col-md-12 col-sm-12 laastenieuws"><?php echo $value['title'] ?></a><br><?php echo $value['created_at'] ?></div>
                  <?php endforeach; ?>
                  <?php else : ?>
                  <tr><td>Geen reacties op topics gevonden</td></tr>
                  <?php endif ;?>
              </div>
        </div>
      </div>
      <div class="col-md-6">

              <div class="panel panel-primary">
                  <div class="panel-heading border-colors">Laatste reacties op albums</div>
                  <div class="panel-body">
                      <?php
                      $sth = $dbc->prepare("SELECT *, album_reply.created_at AS album_reply_created_at FROM album_reply JOIN album ON album_reply.album_id = album.id ORDER BY album_reply.created_at DESC LIMIT 5");
                      $sth->execute();
                      $res = $sth->fetchAll(PDO::FETCH_ASSOC);

                      if(!empty($res)) :
                          foreach($res as $key => $value) : ?>
                          <a href="/album/post/<?php echo $value['album_id']; ?>" class="blauwtxt"><div class="col-md-12 col-sm-12 laastenieuws"><?php echo $value['title'] ?></a><br><?php echo $value['album_reply_created_at'] ?></div>
                      <?php endforeach; ?>
                  <?php else : ?>
                      <tr><td>Geen reacties op albums gevonden</td></tr>
                  <?php endif ;?>
              </div>
      </div>
          <div class="panel panel-primary">
              <div class="panel-heading border-colors">Laatste reacties op nieuws</div>
              <div class="panel-body">
                  <?php
                  $sth = $dbc->prepare("SELECT *, news_reply.created_at AS news_reply_created_at FROM news_reply JOIN news ON news_reply.news_id = news.id JOIN news_permission AS np ON np.news_id = news.id WHERE np.role_id = :role_id ORDER BY news_reply.created_at DESC LIMIT 5");
                  $sth->execute([":role_id" => $_SESSION['user']->role_id]);
                  $res = $sth->fetchAll(PDO::FETCH_ASSOC);

                  if(!empty($res)) :
                      foreach($res as $key => $value) : ?>
                      <a href="/news/post/<?php echo $value['id']; ?>" class="blauwtxt"><div class="col-md-12 col-sm-12 laastenieuws"><?php echo $value['title'] ?></a><br><?php echo $value['news_reply_created_at'] ?></div>
                  <?php endforeach; ?>
              <?php else : ?>
                  <tr><td>Geen reacties op nieuws gevonden</td></tr>
              <?php endif ;?>
          </div>
  </div>
      </div>


      </div>
      <?php require ('../../../includes/components/advertentie.php'); ?>
    </div>

  </div>
    <footer>
<?php require_once("../../../includes/components/footer.php") ; ?>
    </footer>
    <!-- bootstrap script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
