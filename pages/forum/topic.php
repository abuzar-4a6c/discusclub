<?php
$levels = ["gebruiker", "lid"];
require_once("../../includes/tools/security.php");

$categorieenSql = "SELECT * FROM category";
$categorieenResult = $dbc->prepare($categorieenSql);
$categorieenResult->execute();
$results = $categorieenResult->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Discusclub Holland</title>

    <!-- custom css -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/nieuws.css">

    <!-- font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!-- bootstrap style -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- summernote css -->
    <link rel="stylesheet" href="/css/summernote.css">
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

<div class="container main">
    <div class="row columns">
        <div class="col-md-12">
                <div class="">
                    <?php
                    $sub_categorieen = "SELECT * FROM sub_category WHERE id = ?";
                    $subResult = $dbc->prepare($sub_categorieen);
                    $subResult->bindParam(1, $_GET['id']);
                    $subResult->execute();
                    $results2 = $subResult->fetchAll(PDO::FETCH_ASSOC);


                    $sql = "SELECT * FROM topic WHERE sub_category_id = ?";
                    $result = $dbc->prepare($sql);
                    $result->bindParam(1, $_GET['id']);
                    $result->execute();
                    $results3 = $result->fetchAll(PDO::FETCH_ASSOC);
        ?>
                    <br><br>
                    <ol class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li><a href="/">Forum</a></li>
                        <li class="active"><?php echo $results2[0]['name']; ?></li>
                    </ol>
                </div>
        <br>
            <div class="panel panel-primary ">
                <?php foreach ($results2 as $subRow) : ?>
                <div class="panel-heading border-colors"><?php echo $subRow['name']; ?></div>
            <?php endforeach; ?>
                <div class="panel-body padding-padding table-responsive">
                    <table>
                        <tr>
                            <th> #</th>
                            <th> Titel</th>
                            <th>Auteur</th>
                            <th>Berichten</th>
                            <th>Bekeken</th>
                            <th>Laatste bericht</th>
                            <th>Admin tools</th>
                        </tr>
                        <?php foreach ($results3 as $topic) : ?>
                            <?php
                                $sql3 = "SELECT COUNT(id) AS i FROM reply WHERE topic_id = ?";
                                $result3 = $dbc->prepare($sql3);
                                $result3->bindParam(1, $topic['id']);
                                $result3->execute();
                                $i = $result3->fetchAll(PDO::FETCH_ASSOC);

                                $x_berichten = $i[0]['i'] +1;


                                $sql4 = "SELECT COUNT(id) AS x FROM ips WHERE topic_id = ?";
                                $result4 = $dbc->prepare($sql4);
                                $result4->bindParam(1, $topic['id']);
                                $result4->execute();
                                $x = $result4->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <tr>
                                <td><?php echo "<span class='glyphicon glyphicon-file'></span>"; ?></td>
                                <td><a href="/forum/post/<?php echo $topic['id']; ?>"><?php echo $topic['title']; ?></a></td>
                                <?php
                                    $userSql = "SELECT * FROM user WHERE id = ?";
                                    $userResult = $dbc->prepare($userSql);
                                    $userResult->bindParam(1, $topic['user_id']);
                                    $userResult->execute();
                                    $users = $userResult->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <?php foreach ($users as $user) : ?>
                                    <td><a href="/user/<?php echo $user["id"]; ?>"><?php echo $user['first_name'].' '.$user['last_name']; ?></a></td>
                                    <td><?php echo $x_berichten; ?></td>
                                    <td><?php echo $x[0]['x']; ?></td>
                                    <td><?php echo $topic['created_at']; ?>, <br> Door <a href="/user/<?php echo $user["id"]; ?>"><?php echo $user['first_name'].' '.$user['last_name']; ?></a></td>
                                <?php endforeach; ?>
                                <td>
                                    <a  title="Pinnen" href="" type="button" class="btn btn-primary " name="button"> <i class="glyphicon glyphicon-pushpin"></i></a>
                                    <a  title="Locken" href="" type="button" class="btn btn-primary " name="button"> <i class="glyphicon glyphicon-lock" ></i></a>
                                    <a title="Bewerken" href="" type="button" class="btn btn-primary " name="button"> <i class="glyphicon glyphicon-edit" ></i></a>
                                    <a title="Verwijderen" href="" type="button" class="btn btn-primary " name="button"> <i class="glyphicon glyphicon-remove-sign" ></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </div>
                </table>
            </div>
        </div>
    </div>
    <?php if ($logged_in) : ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Topic toevoegen</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" action="/forum/add-topic" method="post">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="add_topic_title" minlength="3" maxlength="50" placeholder="Topic Titel (max. 50 characters)">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea required class="form-control editor" col="8" rows="8" name="add_topic_content"
                                          style="resize: none !important;" placeholder="Uw bericht.."></textarea>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="hidden" name="subId" value="<?php echo $_GET['id']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-primary" class="form-control" name="post_add_topic"
                                       value="Toevoegen">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    </div>
        </div>
    <footer>
<?php require_once("../../includes/components/footer.php") ; ?>
    </footer>
    <!-- bootstrap script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- summernote js -->
    <script type="text/javascript" src="/js/summernote.min.js"></script>
    <script>
        $('.editor').summernote({
            codemirror: {
                theme: 'yeti'
            }
        });
    </script>
</body>
</html>