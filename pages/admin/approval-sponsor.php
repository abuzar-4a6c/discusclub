<?php
$levels= [];
require_once("../../includes/tools/security.php");
if(isset($_GET["id"]) && isset($_GET["new"])) {
    $sth = $dbc->prepare("UPDATE sponsor SET approved = :new WHERE id = :id");
    $sth->execute([":id" => $_GET["id"], ":new" => $_GET["new"]]);
}

$page = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
if (false === intval($page)) {
    exit;
}
$perPage = 10;
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="/css/gebruiker.css">
<link rel="stylesheet" href="/css/nieuws.css">
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
    <?php
        require_once("../../includes/components/nav.php");
    ?>
<br><br>
    <div class="container main">
        <div class="row columns">
            <div class="col-md-12">
                <div class="">
                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/admin">Admin</a></li>
                    <li class="active">Sponsoren</li>
                </ol>
            </div>
                <div class="panel panel-primary ">
                    <div class="panel-heading border-colors">Inschrijvingen Sponsoren</div>
                    <div class="panel-body padding-padding table-responsive">
                        <table>
                            <tr>
                                <th>Status</th>
                                <th>Naam</th>
                                <th>Email</th>
                                <th>Tel.</th>
                                <!-- <th>Url</th> -->
                                <th>Inschrijfdatum</th>
                                <th>Banner</th>
                                <th>Optie</th>
                                <th>Iban</th>
                                <th>Tools</th>
                            </tr>
                            <?php
                                $a = $page * $perPage - $perPage;
                                $sql = "SELECT *, sponsor.id FROM sponsor JOIN image ON sponsor.image_id = image.id ORDER BY approved, created_at DESC LIMIT {$perPage} OFFSET {$a}";
                                $result = $dbc->prepare($sql);
                                $result->execute();
                                $rows = $result->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php if(!empty($rows)) :
                            foreach ($rows as $sponsor) :
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                        switch ($sponsor['approved']) {
                                            case 0:
                                            echo "<div class='status-block text-center'><span class='open-eye glyphicon glyphicon-eye-open'></span></div>";
                                            break;
                                            case 1:
                                            echo "<div class='status-block text-center'><span class='ok glyphicon glyphicon-ok'></span></div>";
                                            break;
                                            case 2:
                                            echo "<div class='status-block text-center'><span class='remove glyphicon glyphicon-remove '></span></div>";
                                            break;
                                        }?>
                                    </td>
                                    <td>
                                        <a target="_blank" href="<?php echo $sponsor['url']; ?>">
                                            <?php echo $sponsor['name']; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo $sponsor["email"]; ?>
                                    </td>
                                    <td>
                                        <?php echo $sponsor["phone"]; ?>
                                    </td>
                                    <!-- <td>
                                    <a target="_blank" href="<?php //echo $sponsor['url'];?>"><?php //echo $sponsor['url'];?></a>
                                    </td> -->
                                    <td>
                                    <?php
                                     echo $sponsor['created_at'];
                                    ?>
                                    </td>
                                    <td>
                                        <!-- <div alt='Banner' class="sponsor_vak" style="background-image: url(/images<?php //echo $sponsor['path'];?>);" ></div> -->
                                        <a target="_blank" href="/images<?php echo $sponsor['path'];?>">
                                            <i class="glyphicon glyphicon-picture"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo $sponsor["option"]; ?>
                                    </td>
                                    <td>
                                        <?php echo $sponsor["iban"]; ?>
                                    </td>
                                    <td>
                                        <a title="wijs af" href="<?php echo  "/admin/approval-sponsor?id=" . $sponsor["id"]; ?>&new=2" class="btn btn-danger" name="button">
                                             <i class="glyphicon glyphicon-remove"></i></a>
                                        <a title="accepteer" href="<?php echo "/admin/approval-sponsor?id=" . $sponsor["id"]; ?>&new=1" class="btn btn-success" name="button">
                                         <i class="glyphicon glyphicon-ok"></i></a>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                            <?php else : ?>
                            <tr><td>Geen sponsoren gevonden</td></tr>
                            <?php endif ;?>
                        </table>
                    </div>
                    <?php
                    $path = "/admin/approval-sponsor/:page";
                    $sql = "SELECT COUNT(*) AS x FROM sponsor";
                    require_once("../../includes/components/pagination.php");
                    ?>
                </div>
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
