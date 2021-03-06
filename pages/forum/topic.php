<?php
$levels = ["gebruiker", "lid", "gast"];
require_once("../../includes/tools/security.php");

//Pagination variables
$page = intval(isset($_GET['pagina']) ? $_GET['pagina'] : 1);
$perPage = 10;
$offset = $page * $perPage - $perPage;

if(!empty($_POST['bevestig_topic'])) {

    $wijzigpermissieSQL = "DELETE FROM topic_permission WHERE topic_id = :id";
    $wijzigpermissieResult = $dbc->prepare($wijzigpermissieSQL);
    $wijzigpermissieResult->execute([':id' => $_POST['id']]);
    $bindings = [':id' => $_POST['id']];
    $wijzigpermissieSQL = "INSERT INTO topic_permission (topic_id, role_id) VALUES ";
    $wijzigpermissieSQLS = [];
    foreach ($_POST['role'] as $key => $role) {
        $wijzigpermissieSQLS[] .= "(:id, :role_$key)";
        $bindings[":role_$key"] = $role;
    }
    $wijzigpermissieSQL .= implode(", ", $wijzigpermissieSQLS);
    $wijzigpermissieResult = $dbc->prepare($wijzigpermissieSQL);
    $wijzigpermissieResult->execute($bindings);
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

                        if($results2){
                            $results3 = [];
                            if((isset($_GET["pagina"]) && $_GET["pagina"] == 1) || !isset($_GET["pagina"])) {
                                $sql = "SELECT *, topic.id, topic.last_changed AS topic_last_changed FROM topic JOIN sub_category ON topic.sub_category_id = sub_category.id JOIN category_permission AS cp ON cp.category_id = sub_category.category_id JOIN sub_category_permission AS scp ON scp.sub_category_id = sub_category.id JOIN topic_permission AS tp ON tp.topic_id = topic.id JOIN user as u ON u.id = topic.user_id WHERE topic.sub_category_id = :id AND state_id = 3 AND topic.deleted_at IS NULL AND cp.role_id = :role_id AND scp.role_id = :role_id AND tp.role_id = :role_id ORDER BY topic.last_changed DESC LIMIT {$perPage} OFFSET {$offset}";
                                $result = $dbc->prepare($sql);
                                $result->execute([":id" => $_GET["id"], ":role_id" => $_SESSION['user']->role_id]);
                                $results3 = $result->fetchAll(PDO::FETCH_ASSOC);
                            }

                            $sql = "SELECT *, topic.id, topic.last_changed AS topic_last_changed FROM topic JOIN sub_category ON topic.sub_category_id = sub_category.id JOIN category_permission AS cp ON cp.category_id = sub_category.category_id JOIN sub_category_permission AS scp ON scp.sub_category_id = sub_category.id JOIN topic_permission AS tp ON tp.topic_id = topic.id JOIN user as u ON u.id = topic.user_id WHERE topic.sub_category_id = :id AND state_id <> 3 AND topic.deleted_at IS NULL AND cp.role_id = :role_id AND scp.role_id = :role_id AND tp.role_id = :role_id ORDER BY topic.last_changed DESC LIMIT {$perPage} OFFSET {$offset}";
                            $result = $dbc->prepare($sql);
                            $result->execute([":id" => $_GET["id"], ":role_id" => $_SESSION['user']->role_id]);
                            $results3 = array_merge($results3, $result->fetchAll(PDO::FETCH_ASSOC));
                        }
                    ?>
                    <br><br>
                </div>
        <br>
        <?php if(!$results2) :?>
            <div class="message error">Deze pagina bestaat niet, <a href="/forum/"> ga terug</a></div>
        <?php else : ?>
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/forum/">Forum</a></li>
                <?php if ($results2): ?>
                    <li class="active"><?php echo $results2[0]['name']; ?></li>
                <?php endif; ?>
            </ol>
            <div class="panel panel-primary">
                <div class="panel-heading border-colors">
                    <h3 class="panel-title">Zoek op topics</h3>
                </div>
                <div class="panel-body">
                    <form method="get" action="/forum/topic_search">
                        <input type="text" class="form-control" name="q" placeholder='Zoek op topics..' maxlength="155" required ><br>
                        <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
                        <input type="hidden" name="name" value="<?php echo $results2[0]['name']; ?>">
                        <button type="submit" class="form-control btn btn-primary">Zoek op topics</button>
                    </form>
                </div>
            </div>
            <div class="panel panel-primary ">
                <?php foreach ($results2 as $subRow) : ?>
                <div class="panel-heading border-colors"><?php echo $subRow['name']; ?></div>
            <?php endforeach; ?>
                <div class="panel-body padding-padding table-responsive">
                    <table>
                        <tr>
                            <th>#</th>
                            <th>Titel</th>
                            <th>Auteur</th>
                            <th>Berichten</th>
                            <th>Bekeken</th>
                            <th>Laatste bericht</th>
                            <?php if (in_array($current_level, $admin_levels)) : ?>
                                <th>Admin tools</th>
                            <?php endif; ?>
                        </tr>
                        <?php foreach ($results3 as $topic) : ?>
                            <?php
                                $sql3 = "SELECT COUNT(id) AS i FROM reply WHERE topic_id = ? AND reply.deleted_at IS NULL";
                                $result3 = $dbc->prepare($sql3);
                                $result3->bindParam(1, $topic['id']);
                                $result3->execute();
                                $i = $result3->fetchAll(PDO::FETCH_ASSOC);

                                $x_berichten = $i[0]['i'] +1;


                                $sql4 = "SELECT COUNT(id) AS x FROM view WHERE topic_id = ?";
                                $result4 = $dbc->prepare($sql4);
                                $result4->bindParam(1, $topic['id']);
                                $result4->execute();
                                $x = $result4->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <tr>
                                <td>
                                <?php
                                switch ($topic['state_id']) {
                                case 1:
                                    echo "<span class='glyphicon glyphicon-file'></span>";
                                    break;
                                case 2:
                                    echo "<span class='glyphicon glyphicon-lock'></span>";
                                    break;
                                case 3:
                                    echo "<span class='glyphicon glyphicon-pushpin'></span>";
                                    break;
                                }
                                    ?>
                                </td>
                                <td><a href="/forum/post/<?php echo $topic['id']; ?>"><?php echo $topic['title']; ?></a></td>
                                <td><a href="/user/<?php echo $topic["user_id"]; ?>"><?php echo $topic['first_name'].' '.$topic['last_name']; ?></a></td>
                                <td><?php echo $x_berichten; ?></td>
                                <td><?php echo $x[0]['x']; ?></td>
                                <?php
                                    $userResult = $dbc->prepare("SELECT *, u.id as user_id, r.last_changed, r.created_at FROM reply as r JOIN topic as t ON t.id = r.topic_id JOIN user as u ON u.id = r.user_id WHERE t.id = :id AND t.deleted_at IS NULL AND u.deleted_at IS NULL AND r.deleted_at IS NULL ORDER BY r.created_at DESC LIMIT 1 ");
                                    $userResult->execute([":id" => $topic["id"]]);
                                    $user = $userResult->fetch(PDO::FETCH_ASSOC);
                                    if($user) :
                                ?>
                                    <td><?php echo $user['created_at']; ?>, <br> Door <a href="/user/<?php echo $user["user_id"]; ?>"><?php echo $user['first_name'].' '.$user['last_name']; ?></a></td>
                                    <?php else : ?>
                                    <td><?php echo $topic['topic_last_changed']; ?>, <br> Door <a href="/user/<?php echo $topic["user_id"]; ?>"><?php echo $topic['first_name'].' '.$topic['last_name']; ?></a></td>
                                    <?php endif;if (in_array($current_level, $admin_levels)) : ?>
                                <td>
                                    <a  title="Open" href="/includes/tools/topic/default.php?id=<?php echo $topic['id']; ?>&sub_id=<?php echo $subRow['id']; ?>" type="button" class="btn btn-primary" name="button"><i class="glyphicon glyphicon-file"></i></a>
                                    <a  title="Pinnen" href="/includes/tools/topic/pin.php?id=<?php echo $topic['id']; ?>&sub_id=<?php echo $subRow['id']; ?>" type="button" class="btn btn-primary " name="button"> <i class="glyphicon glyphicon-pushpin"></i></a>
                                    <a  title="Locken" href="/includes/tools/topic/lock.php?id=<?php echo $topic['id']; ?>&sub_id=<?php echo $subRow['id']; ?>" type="button" class="btn btn-primary " name="button"> <i class="glyphicon glyphicon-lock" ></i></a>
                                    <a title="Bewerken" href="/includes/tools/topic/edit.php?id=<?php echo $topic['id']; ?>" type="button" class="btn btn-primary " name="button"> <i class="glyphicon glyphicon-edit" ></i></a>
                                    <a title="Verwijderen" href="/includes/tools/topic/del.php?id=<?php echo $topic['id']; ?>" type="button" class="btn btn-primary " name="button"> <i class="glyphicon glyphicon-remove-sign" ></i></a>
                                    <!-- Button trigger modal -->
                                    <button type="button" href="/includes/tools/topic/wijzig.php?id=<?php echo $topic['id']; ?>&sub_id=<?php echo $subRow['id']; ?>" class="btn btn-primary change-button">
                                        <i class="buttonDelete glyphicon glyphicon-pencil"></i>
                                    </button>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </div>
                </table>
            </div>
        </div>
    </div>

    <?php
    $path = "/forum/topic/".$_GET["id"]."/:page";
    $sql = "SELECT COUNT(*) as x FROM topic JOIN sub_category ON topic.sub_category_id = sub_category.id JOIN category_permission AS cp ON cp.category_id = sub_category.category_id JOIN sub_category_permission AS scp ON scp.sub_category_id = sub_category.id JOIN topic_permission AS tp ON tp.topic_id = topic.id JOIN user as u ON u.id = topic.user_id WHERE topic.sub_category_id = :id AND topic.deleted_at IS NULL AND cp.role_id = :role_id AND scp.role_id = :role_id AND tp.role_id = :role_id ORDER BY topic.last_changed DESC";
    $pagination_bindings = [":id" => $_GET["id"], ":role_id" => $_SESSION['user']->role_id];
    require_once("../../includes/components/pagination.php");
    ?>

    <?php if ($logged_in && $results2) : ?>
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
                                <input type="text" class="form-control" name="add_topic_title" minlength="3" maxlength="50" placeholder="Topic Titel (max. 50 tekens)" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea required class="form-control editor" col="8" rows="8" name="add_topic_content"
                                          style="resize: none !important;" placeholder="Uw bericht.." required></textarea>
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
            <?php
            // $ad_in_row = true;
            require_once('../../includes/components/advertentie.php'); ?>
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
    <?php require_once("../../includes/components/summernote.php"); ?>

    <script>
        $(".change-button").on("click", function () {
            var href = $(this).attr('href');
            $('#myModal').modal('show');

            fetch(href)
                .then(res => res.text())
        .then(res => $(".modal-dialog").html(res))
        });
    </script>
</body>
</html>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">

    </div>
</div>
