<?php
//Levels var
$levels = ["lid"];

//Security
require_once("../../includes/tools/security.php");

//Pagination variables
$page = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$perPage = 10;

//Select query for sub_category, topics, users, replies and roles
$sql = "SELECT *, user.id AS user_id, role.name AS role_name, user.created_at AS user_created_at, topic.id as topic_id, topic.content AS topic_content, topic.created_at AS topic_created_at, sub_category.id AS sub_category_id, sub_category.name AS sub_category_name FROM topic LEFT JOIN sub_category ON topic.sub_category_id = sub_category.id LEFT JOIN user ON topic.user_id = user.id LEFT JOIN image ON user.profile_img = image.id LEFT JOIN role ON user.role_id = role.id WHERE topic.id = ?";
$result = $dbc->prepare($sql);
$result->bindParam(1, $_GET['id']);
$result->execute();
$rows = $result->fetch(PDO::FETCH_ASSOC);

$fullName = $rows['first_name'].' '.$rows['last_name'];

if($rows) {

    $subcat_id = $rows['sub_category_id'];
    $subSql = "SELECT * FROM sub_category WHERE id = ?";
    $subResult = $dbc->prepare($subSql);
    $subResult->bindParam(1, $subcat_id);
    $subResult->execute();
    $subId = $subResult->fetchAll(PDO::FETCH_ASSOC);

    $sql = "INSERT INTO view (topic_id, ip_id) VALUES (:id, :ip_id)";
    $result = $dbc->prepare($sql);
    $result->execute([":id" => $_GET["id"], ":ip_id" => $_SESSION["ip_id"]]);

}

//Antwoord toevoegen
require_once("../../includes/tools/berichtParse.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicon.ico"/>
    <title>Discusclub Holland</title>

    <!-- custom css -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/bericht.css">
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
    (function (d, s, id) {
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

<div class="container main" style="margin-top:25px;">

    <!-- Start topic -->
    <div class="row">
        <div class="col-xs-12">
            <?php if(!$rows) : ?>
                <div class="message error">Deze pagina bestaat niet, <a href="/forum/"> ga terug</a></div>
            <?php else : ?>
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/forum/">Forum</a></li>
                <li><a href="/forum/topic/<?php echo $rows['sub_category_id']; ?>"><?php echo $rows['sub_category_name']; ?></a></li>
                <li class="active"><?php echo $rows['title']; ?></li>
            </ol>

            <div class="panel panel-primary">
                <div class="panel-heading border-color-blue">
                    <h3 class="panel-title text-left"><?php echo $rows['title']; ?></h3>
                </div>

                <div class="panel-body padding-padding ">
                    <div class="col-md-12 ">
                        <div class="col-md-3">
                            <div class="col-md-12">
                                <img class="img" src="/images/profiel/<?php echo $rows['path']; ?>">
                            </div>
                            <?php
                            $replySql = "SELECT COUNT(id) AS x_reply FROM reply WHERE user_id = ? AND deleted_at IS NULL";
                            $replyResult = $dbc->prepare($replySql);
                            $replyResult->bindParam(1, $_SESSION['user']->id);
                            $replyResult->execute();
                            $replyCount = $replyResult->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <div class="col-md-12">
                                <br><b>Rol: </b><?php echo $rows['role_name']; ?>
                                <br><b>Aantal berichten: </b><?php echo $replyCount['x_reply']; ?>
                                <br><b>Lid sinds: </b> <?php echo $rows['user_created_at']; ?><br><br>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <p><?php echo html_entity_decode($rows['topic_content']); ?></p>
                            <p>
                            <hr>
                            <br>
                            <?php echo $rows['signature']; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="panel-footer">
                    <b>Geplaatst door: </b>
                    <a href="/user/<?php echo $rows['user_id']; ?>"><?php echo $fullName; ?></a>
                    op
                    <?php echo $rows['topic_created_at']; ?>
                    <div class="text-right">
                        <?php
                        $sth = $dbc->prepare("SELECT * FROM favorite WHERE user_id = :user_id AND topic_id = :topic_id");
                        $sth->execute([":user_id" => $_SESSION["user"]->id, "topic_id" => $_GET["id"]]);
                        $favorite = $sth->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <?php if($favorite) : ?>
                            <a href="/includes/tools/user-un-favorite?id=<?php echo $_GET['id']; ?>" class="glyphicon glyphicon-star GlyphSize " style="text-decoration: none; color: gold;"></a>
                        <?php else :?>
                            <a href="/includes/tools/user-favorite?id=<?php echo $_GET['id']; ?>" class="glyphicon glyphicon-star-empty GlyphSize " style="text-decoration: none; color: gold;"></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quoting system -->

    <?php
    $aantal = $page * $perPage - $perPage;
    $replySql = "SELECT *, reply.id, reply.last_changed, reply.created_at FROM reply JOIN user as u ON u.id = reply.user_id WHERE topic_id = ? AND reply.deleted_at IS NULL ORDER BY reply.last_changed ASC LIMIT {$perPage} OFFSET {$aantal}";
    $replyResult = $dbc->prepare($replySql);
    $replyResult->bindParam(1, $_GET['id']);
    $replyResult->execute();
    $replies = $replyResult->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php foreach ($replies as $reply) : ?>
        <?php

        $matches = [
            [],
            [1]
        ];

        while ($matches[1]) {
            preg_match_all('/\[quote\s(\d+)\]/', $reply['content'], $matches);

            foreach ($matches[1] as $match) {
                $sql = "SELECT *, reply.id FROM reply JOIN user as u ON u.id = reply.user_id WHERE reply.id = :id AND reply.deleted_at IS NULL";
                $query = $dbc->prepare($sql);
                $query->execute([
                    ':id' => $match
                ]);
                $results = $query->fetch(PDO::FETCH_ASSOC);

                $naam = $results["first_name"] . " " . $results["last_name"];

                if (!isset($results)) {
                    $replace = 'Oops, deze post bestaat niet meer';
                } else {
                    $replace = $naam . ' schreef:<br>' . $results['content'];
                }

                $reply['content'] = str_replace('[quote ' . $match . ']', '<div style="background-color: lightgray; padding: 10px;border:1px solid black">' . $replace . '</div>', $reply['content']);
            }
        }
        $naam = $reply["first_name"] . " " . $reply["last_name"];
        ?>

        <!-- Replies -->
        <div class="col-xs-12">
            <div class="panel panel-primary" id="post-<?php echo $reply['id']; ?>">
                <div class="panel-heading border-color-blue">
                    <h3 class="panel-title text-left">Geplaatst door: <b><a
                                    style="color: #fff; text-decoration: underline"
                                    href="/user/<?php echo $reply["user_id"]; ?>"><?php echo $naam; ?></a></b>
                    </h3>
                    <?php if (in_array($current_level, $admin_levels)) : ?>
                        <span style="float: right; margin-top: -23px;"><a title="Verwijderen" href="/includes/tools/reply/del.php?topic_id=<?php echo $_GET["id"]; ?>&id=<?php echo $reply['id']; ?>" type="button" class="btn" name="button" style="color: #fff;"> <i class="glyphicon glyphicon-remove-sign" ></i></a></span>
                    <?php endif; ?>
                </div>
                <div class="panel-body padding-padding ">
                    <div class="wrapper-box col-xs-12">
                        <div class="col-md-2">
                            <img src='http://via.placeholder.com/130x130' alt="x">
                        </div>

                        <div class="col-md-10">
                            <p><?php echo wordwrap($reply["content"], 70, "<br>", true); ?></p>
                        </div>

                    </div>
                </div>
                <div class="panel-footer">
                    <b>Geplaatst op </b><?php echo $reply['last_changed']; ?>

                    <div class="pull-right">

                        <button class="btn btn-primary quote-btn" data-id="<?php echo $reply['id']; ?>">
                            Quote deze post
                        </button>
                    </div>

                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    <?php endforeach; ?>


    <!-- Pagination system -->
    <div class="col-xs-12">

        <?php

        $query = $dbc->prepare('SELECT COUNT(*) AS x FROM reply WHERE topic_id = :id AND reply.deleted_at IS NULL');
        $query->execute([
            ':id' => $_GET['id']
        ]);
        $results = $query->fetchAll()[0];
        $count = ceil($results['x'] / $perPage);
        ?>

        <?php if ($results['x'] > $perPage) : ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li>
                        <a href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($x = ($count - 4 < 1 ? 1 : $count - 4); $x < ($count + 1); $x++) : ?>
                        <li<?php echo ($x == $page) ? ' class="active"' : ''; ?>><a
                                    href="/forum/post/<?php echo $rows['topic_id']; ?>/<?php echo $x; ?>"><?php echo $x; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li>
                        <a href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <?php if ($logged_in) : ?>

        <!-- Add reply -->
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Antwoord toevoegen</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea required class="form-control editor" col="8" rows="8" name="reply_content"
                                          style="resize: none;" maxlength="50" placeholder="Uw bericht.."></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="hidden" name="bericht_id" value="<?php echo $_GET['id']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-primary" class="form-control" name="post_reply"
                                       value="Plaats reactie">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
</div>
</div>

<footer>
    <?php require_once("../../includes/components/footer.php"); ?>
</footer>
<!-- bootstrap script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- summernote js -->
<script type="text/javascript" src="/js/summernote.min.js"></script>
<script>
    $('.editor').summernote({
        disableResizeEditor: true,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });

    $(document).ready(function () {
        $('.quote-btn').on('click', function () {
            $('.editor').summernote('insertText', '[quote ' + ($(this).attr('data-id')) + ']')//.disabled = true
        });
    });
</script>
</body>

</html>