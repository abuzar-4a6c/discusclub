<?php require_once("../../includes/tools/security.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Discusclub Holland</title>
    <?php require_once("../../includes/components/head.php"); ?>
</head>

<body><!-- Global site tag (gtag.js) - Google Analytics --><script async src="https://www.googletagmanager.com/gtag/js?id=UA-110090721-1"></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-110090721-1');</script>
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
    <br><br>
    <div class="container main">
      <div class="row columns">
          <div class="col-md-12">
              <ol class="breadcrumb">
                  <li><a href="/">Home</a></li>
                  <li class="active">Sponsoren</li>
              </ol>
          </div>
      <div class="col-md-8">

        <div class="panel panel-primary">
          <div class="panel-heading border-color-blue">Sponsoren</div>
          <div class="panel-body padding-padding">
            <?php
              $haal_sponsor = "SELECT * FROM sponsor WHERE approved = 1";
              $sponsorResult = $dbc->prepare($haal_sponsor);
              $sponsorResult->execute();
              $sponsoren = $sponsorResult->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php if(!empty($sponsoren)) : ?>
            <?php foreach ($sponsoren as $sponsor) : ?>
            <?php
              $image_id = $sponsor['image_id'];
              $haal_image = "SELECT * FROM image WHERE id = ?";
              $imageResult = $dbc->prepare($haal_image);
              $imageResult->bindParam(1, $image_id);
              $imageResult->execute();
              $images = $imageResult->fetchAll(PDO::FETCH_ASSOC);
            ?>
              <div class="col-md-6 col-sm-12 ruimte">
                <a  title="<?php echo $sponsor['name']; ?>" href="<?php echo $sponsor['url'] ?>">
                    <?php foreach ($images as $image) : ?>
                <img alt="Sponsor" class="sponsor_vak" src="/images<?php echo $image['path'] ?>">
                    <?php endforeach; ?>
                </a>
              </div>
            <?php endforeach; ?>
            <?php else : ?>
            <tr><td>Geen sponsoren gevonden</td></tr>
            <?php endif ;?>
        </div>
      </div>
      </div>

      <div class="col-md-4">
          <div class="panel panel-primary">
              <div class="panel-heading border-color-blue">Advertentie</div>
              <div class="panel-body">
                  <div class="col-md-12 col-sm-12 ruimte"><a href="/wordlid"><img alt="Advertentie" src="/images/ad/advertentie.jpg"></a> </div>
              </div>
          </div>
          <?php require_once('../../includes/components/advertentie.php'); ?>

      </div>
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
