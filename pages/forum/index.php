<?php
    $levels = ["gast", "lid", "gebruiker"];
    require_once("../../includes/tools/security.php");

    if(isset($_POST['add_new_category']) && !empty($_POST['new_category']) && $logged_in && in_array($current_level, $admin_levels))
    {
        $sql = "INSERT INTO category (name, created_at) VALUES (:name, NOW())";
        $query = $dbc->prepare($sql);
        $query->execute([":name" => htmlentities($_POST["new_category"])]);
        $id = $dbc->lastInsertId();

        $categoryPermissionSql = "INSERT INTO category_permission (category_id, role_id) SELECT :id, id FROM role";
        $categoryPermissionQuery = $dbc->prepare($categoryPermissionSql);
        $categoryPermissionQuery->execute([":id" => $id]);
    }

    if(isset($_POST['add_new_sub_category']) && !empty($_POST['new_sub_category']) && $logged_in && in_array($current_level, $admin_levels))
    {
        $sql = "INSERT INTO sub_category (category_id, name, created_at) VALUES (:category_id, :name, NOW())";
        $query = $dbc->prepare($sql);
        $query->execute([":category_id" => $_POST['cat_id'], ":name" => htmlentities($_POST["new_sub_category"])]);
    }

    //Categorieen
    $categorieenSql = "SELECT * FROM category JOIN category_permission AS cp ON cp.category_id = category.id WHERE deleted_at IS NULL AND cp.role_id = :role_id";
    $categorieenResult = $dbc->prepare($categorieenSql);
    $categorieenResult->execute([":role_id" => $_SESSION['user']->role_id]);
    $results = $categorieenResult->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($_POST['bevestig_category'])) {


        $wijzigpermissieSQL = "DELETE FROM category_permission WHERE category_id = :id";
        $wijzigpermissieResult = $dbc->prepare($wijzigpermissieSQL);
        $wijzigpermissieResult->execute([':id' => $_POST['id']]);
        $bindings = [':id' => $_POST['id']];
        $wijzigpermissieSQL = "INSERT INTO category_permission (category_id, role_id) VALUES ";
        $wijzigpermissieSQLS = [];
        foreach ($_POST['role'] as $key => $role) {
            $wijzigpermissieSQLS[] .= "(:id, :role_$key)";
            $bindings[":role_$key"] = $role;
        }
        $wijzigpermissieSQL .= implode(", ", $wijzigpermissieSQLS);
        $wijzigpermissieResult = $dbc->prepare($wijzigpermissieSQL);
        $wijzigpermissieResult->execute($bindings);
    }
    if(!empty($_POST['bevestig_sub_category'])) {


        $wijzigpermissieSQL = "DELETE FROM sub_category_permission WHERE sub_category_id = :id";
        $wijzigpermissieResult = $dbc->prepare($wijzigpermissieSQL);
        $wijzigpermissieResult->execute([':id' => $_POST['sub_id']]);
        $bindings = [':id' => $_POST['sub_id']];
        $wijzigpermissieSQL = "INSERT INTO sub_category_permission (sub_category_id, role_id) VALUES ";
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
    ;(function (d, s, id) {
        var js,
            fjs = d.getElementsByTagName(s)[0]
        if (d.getElementById(id)) return
        js = d.createElement(s)
        js.id = id
        js.src = '//connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.10'
        fjs.parentNode.insertBefore(js, fjs)
    })(document, 'script', 'facebook-jssdk')
</script>
<?php require_once("../../includes/components/nav.php"); ?>
<div class="container main">
    <div class="row">
        <br><br>
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Forum</li>
        </ol>
        <br>
        <div class="panel panel-primary">
            <div class="panel-heading border-colors">
                <h3 class="panel-title">Zoek op forum</h3>
            </div>
            <div class="panel-body">
                <form method="get" action="/forum/search">
                    <input type="text" class="form-control" name="q" placeholder='Zoek op het forum..' maxlength="155" required ><br>
                    <button type="submit" class="form-control btn btn-primary">Zoek op forum</button>
                </form>
            </div>
        </div>
        <?php if($logged_in && in_array($current_level, $admin_levels)) : ?>
            <div class="panel panel-primary">
                <div class="panel-heading border-colors">Voeg nieuwe categorie toe</div>
                <div class="panel-body">
                    <form class="row" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST">
                        <div class="col-md-9 col-sm-8 col-xs-7">
                            <input type="text" class="form-control " placeholder="Voer nieuwe categorienaam" name="new_category" minlength="3" maxlength="85" required>
                        </div>
                        <input type="submit" class="col-md-3 col-sm-4 col-xs-5 btn btn-primary" name="add_new_category" value="Toevoegen">
                    </form>
                </div>
            </div>
        <?php endif; ?>
        <?php foreach ($results as $categorie) : ?>
            <?php
                $id = $categorie['id'];
                $subCategorieenSql = "SELECT * FROM sub_category JOIN sub_category_permission as scp ON scp.sub_category_id = sub_category.id WHERE category_id = :topic_id AND deleted_at IS NULL AND scp.role_id = :role_id";
                $subCategorieenResult = $dbc->prepare($subCategorieenSql);
                $subCategorieenResult->execute(["role_id" => $_SESSION["user"]->role_id, ":topic_id" => $id]);
                $results2 = $subCategorieenResult->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if (sizeof($results2) > 0 || in_array($current_level, $admin_levels)) : ?>
            <div class="panel panel-primary">
                <div class="panel-heading border-colors">
                    <?php echo $categorie['name']; ?>
                    <?php if(in_array($current_level, $admin_levels)) : ?>
                        <?php $id = $categorie['id']; ?>
                        <td>
                            <!-- <a title="Wijzig permissie" href="/includes/tools/category/wijzig.php?id=<?php //echo $categorie['id']; ?>" class="buttonDelete btn-primary" name="button" style="background-color: #0ba8ec;"> <i class="buttonDelete glyphicon glyphicon-pencil"></i></a> -->

                            <!-- Button trigger modal -->
                            <button type="button" href="/includes/tools/category/wijzig.php?id=<?php echo $id; ?>" class="btn btn-primary change-button">
                              <i class="buttonDelete glyphicon glyphicon-pencil"></i>
                          </button>

                            <a title="Verwijder" href="/includes/tools/category/del.php?id=<?php echo $categorie['id']; ?>" class="buttonDelete btn-primary" name="button" style="background-color: #0ba8ec;"> <i class="buttonDelete glyphicon glyphicon-remove-sign"></i></a>
                        </td>
                    <?php endif; ?>
                </div>
                <div class="panel-body padding-padding table-responsive">
                    <?php if($logged_in && in_array($current_level, $admin_levels)) : ?>
                        <form class="row" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST">
                            <br>
                            <div class="col-md-9 col-sm-8 col-xs-7">
                                <input type="hidden" name="cat_id" value="<?php echo $categorie['id']; ?>">
                                <input type="text" class="form-control form-input" placeholder="Voer nieuwe subcategorienaam in.." name="new_sub_category" minlength="3" maxlength="83" required>
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-5">
                                <input type="submit" value="Toevoegen" name="add_new_sub_category" class="form-btn form-control btn btn-primary" required>
                            </div>
                            <br><br>
                        </form>
                    <?php endif; ?>
                    <table>
                        <thead>
                            <tr>
                                <th> #</th>
                                <th>Forum</th>
                                <th>Topics</th>
                                <th>Berichten</th>
                                <th>Laatste bericht</th>
                                <?php if(in_array($current_level, $admin_levels)) : ?>
                                    <th>Admin tools</th>
                                <?php endif; ?>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($results2 as $subCat) : ?>
                            <?php
                                $sql = "SELECT *, COUNT(*) as aantal_topics FROM topic WHERE sub_category_id = ? AND deleted_at IS NULL";
                                $q = $dbc->prepare($sql);
                                $q->bindParam(1, $subCat['id']);
                                $q->execute();
                                $results3 = $q->fetchAll(PDO::FETCH_ASSOC);

                                $query2 = $dbc->prepare('SELECT COUNT(reply.id) as x FROM sub_category LEFT JOIN topic ON topic.sub_category_id = sub_category.id LEFT JOIN reply ON reply.topic_id = topic.id WHERE sub_category.id = ? AND reply.deleted_at IS NULL AND topic.deleted_at IS NULL');
                                $query2->bindParam(1, $subCat['id']);
                                $query2->execute();
                                $berichten = $query2->fetch(PDO::FETCH_ASSOC);

                                $query3 = $dbc->prepare('SELECT COUNT(topic.id) as x FROM `topic` WHERE sub_category_id = ? AND deleted_at IS NULL');
                                $query3->bindParam(1, $subCat['id']);
                                $query3->execute();
                                $topic_x = $query3->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <tr>
                                <td> &#128193;</td>
                                <td>
                                    <a href="/forum/topic/<?php echo $subCat['id']; ?>"><?php echo $subCat['name']; ?></a>
                                </td>
                                <td><?php echo $results3[0]['aantal_topics']; ?></td>
                                <td><?php echo $berichten['x'] + $topic_x['x']; ?></td>
                                <?php
                                    $laasteberichtSql = "SELECT *, r.last_changed AS reply_last_changed, t.last_changed AS topic_last_changed, r.user_id AS reply_user_id, t.user_id AS topic_user_id, u.first_name AS reply_first_name, u.last_name AS reply_last_name, u2.first_name AS topic_first_name, u2.last_name AS topic_last_name FROM topic AS t LEFT JOIN reply AS r ON r.topic_id = t.id LEFT JOIN user AS u ON u.id = r.user_id LEFT JOIN user AS u2 ON u2.id = t.user_id WHERE t.sub_category_id = :sub_id AND t.deleted_at IS NULL AND r.deleted_at IS NULL AND u.deleted_at IS NULL AND u2.deleted_at IS NULL ORDER BY COALESCE(GREATEST(r.last_changed, t.last_changed), r.last_changed, t.last_changed) DESC LIMIT 1";
                                    $laasteberichtResult = $dbc->prepare($laasteberichtSql);
                                    $laasteberichtResult->execute([":sub_id" => $subCat["id"]]);

                                    $laatsteBericht = $laasteberichtResult->fetch(PDO::FETCH_ASSOC);

                                if ($laatsteBericht) : ?>
                                        <td>
                                            <?php echo isset($laatsteBericht["reply_last_changed"]) ? $laatsteBericht["reply_last_changed"] : $laatsteBericht["topic_last_changed"]; ?>,
                                            <br>Door <a href="/user/<?php echo isset($laatsteBericht["reply_user_id"]) ? $laatsteBericht["reply_user_id"] : $laatsteBericht["topic_user_id"]; ?>">
                                                <?php echo isset($laatsteBericht["reply_first_name"]) ? $laatsteBericht["reply_first_name"] . " " . $laatsteBericht["reply_last_name"] : $laatsteBericht["topic_first_name"] . " " . $laatsteBericht["topic_last_name"] ?>
                                            </a>
                                        </td>
                                    <?php else : ?>
                                        <td>Niks gevonden</td>
                                <?php endif; ?>
                                    <?php if($logged_in && in_array($current_level, $admin_levels)) : ?>
                                        <td>
                                            <a title="Verwijder" href="/includes/tools/sub-category/del.php?id=<?php echo $categorie['id']; ?>&sub_id=<?php echo $subCat['id']; ?>" type="button" class="btn btn-primary " name="button"><i class="glyphicon glyphicon-remove-sign"></i></a>

                                            <button title="Wijzig permissie" href="/includes/tools/sub-category/wijzig.php?id=<?php echo $categorie['id']; ?>&sub_id=<?php echo $subCat['id']; ?>" type="button" class="btn btn-primary change-button" name="button"><i class="glyphicon glyphicon-pencil"></i></button>
                                        </td>
                                    <?php endif; ?>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
            <?php require ('../../includes/components/advertentie.php'); ?>

<?php endif; ?>
<?php endforeach; ?>
</div>
</div>

<footer>
    <?php require_once("../../includes/components/footer.php"); ?>
</footer>
<!-- bootstrap script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
