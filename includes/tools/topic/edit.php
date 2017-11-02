<?php
$levels = [];
require_once("../../../includes/tools/security.php");

$sql = "UPDATE ";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"><link rel="shortcut icon" href="/favicon.ico" />
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
require_once("../../components/nav.php");
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
                    $sql = "SELECT * FROM topic WHERE sub_category_id = ? AND topic.deleted_at IS NULL";
                    $result = $dbc->prepare($sql);
                    $result->bindParam(1, $_GET['id']);
                    $result->execute();
                    $results3 = $result->fetchAll(PDO::FETCH_ASSOC);
                }
                ?>
                <br><br>
            </div>
            <br>

        </div>
    </div>
    <?php if ($logged_in && $results2) : ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Bewerk topic</h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="/includes/tools/edit-topic" method="post">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="edit_topic_title" minlength="3" maxlength="50" value="<?php echo isset($results3[0]['title']) ? $results3[0]['title'] : ''?>" placeholder="Topic Titel (max. 50 tekens)">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                <textarea required class="form-control editor" col="8" rows="8" name="edit_topic_content"
                                          style="resize: none !important;" placeholder="Uw bericht.."><?php echo isset($results3[0]['content']) ? $results3[0]['content'] : ''?></textarea>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="hidden" name="subId" value="<?php echo $_GET['id']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-primary" class="form-control" name="post_edit_topic"
                                           value="Bewerk topic">
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
    <?php require_once("../../components/footer.php") ; ?>
</footer>
<!-- bootstrap script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- summernote js -->
<script type="text/javascript" src="/js/summernote.min.js"></script>
<script>
    $('.editor').summernote({
        disableResizeEditor: true,
        codemirror: {
            theme: 'yeti'
        }

    });
</script>
</body>
</html>
