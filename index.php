<?php

declare(strict_types=1);
require_once('lib.php');
$uri = $_SERVER['REQUEST_URI'];
$dir = isset($_GET['path']) ? "./" . $_GET['path'] : "./";


if (isset($_POST['newFolder']) && $_POST['newFolder'] !== "") {
    mkdir($dir . '/' . $_POST['newFolder']);
    // echo '<h1>' . $_POST['newFolder'] . '</h1>';
};

if (isset($_POST['delete']) && $_POST['delete'] !== "") {
    $delItem = $_POST['delete'];
    echo '<h1> Deleted' . $_POST['delete'] . '</h1>';
    if($delItem !== "./index.php" && $delItem !== "./README.MD" && $delItem !== "./lib.php" ){
        unlink($delItem);
    }
};

$files = scandir($dir);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>File explorer</title>
</head>

<body>
    <div class="container">
        <h1 class="text-center">Directory contens: <?php print($_SERVER['REQUEST_URI']) ?></h1>
        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th scope="col">
                        Type
                    </th>
                    <th scope="col">
                        Name
                    </th>
                    <th scope="col">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($files as $value) {
                    if ($value != "." and $value != "..") {
                        // var_dump($dir . $value);
                        // echo '<br>';
                        $isDir = is_dir($dir . '/' . $value) ? 'Directory' : 'File';
                        print("<tr><td>" . $isDir . "</td>");
                        $linkValue = ($_SERVER['QUERY_STRING'] === "")
                            ? '<a href="' . $uri . '?path=' . $value . '">' . $value . '</a>'
                            : '<a href="' . $uri . '/' . $value . '">' . $value . '</a>';
                        print("<td>");
                        is_dir($dir . '/' . $value) ? print($linkValue) : print($value);
                        print("</td>");
                        !is_dir($dir . '/' . $value)
                            ? print('<td><form method="post">
                            <input class="btn btn-primary" type="submit" value="delete">
                            <input type="hidden" value="' . $dir . $value .'" name="delete">
                            </form></td>')
                            : print("<td></td>");
                        print('</tr>');
                    }
                };
                ?>
            </tbody>
        </table>

        <?php
        //
        if (isset($_GET['path'])) {
            $paths = explode('/', $_GET['path']);
            $lastPath = end($paths);
            if (count($paths) > 1) {
                print('<a href="' . str_replace('/' . $lastPath, "", $uri) . '">back</a>');
            } else {
                print('<a href="' . $files[0] . '">back</a>');
            }
        }
        ?>
        <form method="post" action="<?php echo $uri ?>">
            <div class="col-2 mb-2">
                <input type="text" name="newFolder" placeholder="Make new directory" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

</body>

</html>

<?php
print('<br>');
print('Key path: ' . ($_GET['path'] ?? "empty"));
print('<br>');
print('POST: ' . ($_POST['newFolder'] ?? "empty"));
print('<br>');
print('Uri: ' . $uri);
print('<br>');
print('<br>');
print_r('Dir: ' . $dir);
print('<br>');
print('<pre>');
// $_SERVER['QUERY_STRING'];
// print_r($_SERVER);
// print_r($_GET['path']);
// print(isset($_GET[‘x’]));
print('</pre>');
// onsubmit=createFolder(  php tag print($_POST('newFolder') . ',' . $dir);
?>