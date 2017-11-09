<?php
$levels = [];
require_once("../../includes/tools/security.php");

if(isset($_POST['start_contest']))
{
    $date = explode("-", $_POST['daterange']);
    $begin = trim(date("Y-m-d H:i", strtotime($date[0])));
    $end = trim(date("Y-m-d H:i", strtotime($date[1])));

    $sql = "INSERT INTO contest (start_at, end_at) VALUES (:start_at, :end_at)";
    $result = $dbc->prepare($sql);
    $result->execute([":start_at" => $begin, ":end_at" => $end]);
}

    $stm = $dbc->prepare("SELECT * FROM contest");
    $stm->execute();
    $contests = $stm->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="/css/overons.css">
    <!-- font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!-- bootstrap style -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- daterangepicker -->
    <!-- Include Required Prerequisites -->

    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

</head>

<body>
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
        <div class="row">

            <div class="col-md-6">
                <form class="" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                    <div class="col-md-12">
                        <h2>Selecteer een begin en eind datum</h2>
                        <h5>Dit word de begin en einddatum van de beste aquarium wedstrijd</h5>
                        <br>
                    </div>
                    <div class="col-md-12">
                        <input type="text" class="form-control" name="daterange" value="" id="create"/>
                        <br>
                        <input type="submit" class="btn btn-primary" name="start_contest" value="Verzend!">
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <h2>Aankomende wedstijden</h2>
                <hr>
                <div class="panel panel-primary">
                  <div class="panel-heading">
                    <h3 class="panel-title">Aankomende wedstijden</h3>
                  </div>
                  <div class="panel-body">
                      <table class="col-md-12">
                          <tr>
                              <th>start/einddatum</th>
                              <th>Verwijder</th>
                          </tr>
                          <?php foreach($contests as $contest) : ?>
                              <?php
//                                $time = array(date('d/m/Y H:i', strtotime($contest['start_at'])), date( 'd/m/Y H:i', strtotime($contest['end_at'])));
//                                $date = implode(' - ', $time);
//                                echo "'".$date."'";
                              ?>
                          <tr class="contest-box">
                              <td>
                                  <form class="" action="#" method="post">
                                      <input type="text" id="contest-<?php echo $contest['id']; ?>" class="form-control" name="daterange" data-start="<?php echo date('d/m/Y H:i', strtotime($contest['start_at'])); ?>" data-end="<?php echo date( 'd/m/Y H:i', strtotime($contest['end_at'])); ?>"/>
                                  </form>
                              </td>
                              <td>
                                  <button class="status-block btn btn-danger" type="button" name="button"><span class="glyphicon glyphicon-remove"></span></button>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                      </table>
                  </div>
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

    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#create').daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 5,
                minDate: new Date(),
                applyClass: "btn-primary",
                cancelClass: "btn-danger",
                locale: {
                    format: 'DD/MM/YYYY HH:mm',
                },
            });
        });

        <?php foreach($contests as $contest): ?>
            $(function() {
                $('#contest-<?php echo $contest['id']; ?>').daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerIncrement: 5,
    //            minDate: new Date(),
                    applyClass: "btn-primary",
                    cancelClass: "btn-danger",
                    locale: {
                        format: 'DD/MM/YYYY HH:mm',
                    },
                    startDate: '<?php echo date('d/m/Y H:i', strtotime($contest['start_at'])); ?>',
                    endDate: '<?php echo date('d/m/Y H:i', strtotime($contest['end_at'])); ?>'
                });
            });
        <?php endforeach; ?>
    </script>
</body>
</html>
