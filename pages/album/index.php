<?php
require_once("../../includes/tools/security.php");

//echo "<pre>";
//var_dump($_SERVER);
//exit;

$page = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
if (false === intval($page)) {
    exit;
}
$perPage = 9;

function custom_echo($x, $length)
{
  if(strlen($x)<=$length)
  {
    echo $x;
  }
  else
  {
    $y=substr($x,0,$length) . '...';
    echo $y;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Discusclub Holland</title>
    <?php require_once("../../includes/components/head.php"); ?>
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
    <?php require_once("../../includes/components/nav.php"); ?>

    <br><br>
    <div class="container main">
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <ol class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li class="active">Albums</li>
                    </ol>
                </div>
                <div class="panel panel-primary">
                  <div class="panel-heading border-color-blue">
                    <h3 class="panel-title">Album toevoegen</h3>
                  </div>
                  <div class="panel-body">
                    <a href="/album/upload" type="button" class="btn btn-primary col-md-12 " name="button">Upload een album</a>
                  </div>
                </div>
            </div>

            <?php
                $a = $page * $perPage - $perPage;
                $haal_albums = "SELECT *, a.created_at AS album_created_at, count(i.album_id) as aantal_fotos, u.id as user_id, a.created_at as created_at FROM image as i JOIN album as a ON a.id = i.album_id JOIN user as u ON u.id = a.user_id WHERE i.album_id IS NOT NULL GROUP BY i.album_id ORDER BY album_created_at DESC LIMIT {$perPage} OFFSET {$a}";
                $albumResult = $dbc->prepare($haal_albums);
                $albumResult->execute();
                $albums = $albumResult->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php
            $a = (int)ceil(count($albums)/4);
            $arr = array_chunk($albums, $a ? $a : 1);
            foreach($arr as $alb) : ?>
                <div class="col-md-4 col-sm-6 col-xs-12">
                <?php foreach ($alb as $album) : ?>
                        <div class="panel panel-primary">
                            <div class="panel-heading border-color-blue">
                                <h3 class="panel-title"><?php custom_echo($album['title'], 25); ?></h3>
                            </div>
                            <div class="panel-body">
                                <div class="media">
                                    <div class="media-body">
                                        <h4 class="media-heading"><b>Geplaatst door: </b><a href="/user/<?php echo $album["user_id"]; ?>"><i> <?php custom_echo($album['first_name'], 10) .' '?> <?php echo $album['last_name']; ?> </i></a></h4>
                                        <p>
                                            Aantal foto's: <i><?php echo $album['aantal_fotos']; ?></i><br>
                                            Datum: <i><?php echo $album['created_at']; ?></i><br>
                                        </p>
                                        <div class="text-center"><img alt='album-img' class="text-center imgAlbum" src="/images<?php echo $album['path'] ?>" alt=""></div><br><br>
                                        <a href="/album/post/<?php echo $album['album_id'] ?>"><button type="button" class="btn btn-primary" name="button">Bekijken</button></b></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        $path = "/album/:page";
        $sql = "SELECT DISTINCT COUNT(album.id) as x FROM album WHERE id IN (SELECT album_id FROM image)";
        require_once("../../includes/components/pagination.php");
        ?>
    </div>
    <?php require ('../../includes/components/advertentie.php'); ?>
  </div>
</div>

    <footer>
    <?php require_once("../../includes/components/footer.php") ; ?>
    </footer>
    <!-- bootstrap script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
