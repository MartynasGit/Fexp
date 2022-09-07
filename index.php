<?php

declare(strict_types=1);
require_once('lib.php');
$uri = $_SERVER['REQUEST_URI'];
$dir = isset($_GET['path']) ? "./" . $_GET['path'] : "./";
$errorMsg = "";
$errorMsg2 = "";

if (isset($_POST['delete']) && $_POST['delete'] !== "") {
    $delItem = $_POST['delete'];
    if ($delItem !== "./index.php" && $delItem !== "./README.md" && $delItem !== "./lib.php") {
        unlink($delItem);
    } else {
        $errorMsg2 = '<p class="text-danger text-center m-0">You can\'t delete this directory</p>';
    }
};

if (isset($_POST['newFolder']) && $_POST['newFolder'] !== "") {
    $newFold = $_POST['newFolder'];
    $allFolders = scandir($dir);
    if (!in_array($newFold, $allFolders)) {
        mkdir($dir . '/' . $newFold);
    } else {
        $errorMsg = '<span class="text-danger">Directory name already exist</span>';
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
                            <button class="btn btn-primary" value="' . $dir . $value . '" name="delete" >Delete </button>
                            </form></td>')
                            : print("<td></td>");
                        print('</tr>');
                    }
                };
                ?>
            </tbody>
        </table>
        <?php
        (print ($errorMsg2) ?? "");
        if (isset($_GET['path'])) {
            $paths = explode('/', $_GET['path']);
            $lastPath = end($paths);
            if (count($paths) > 1) {
                print('<div class="my-2"><a href="' . str_replace('/' . $lastPath, "", $uri) . '">back</a></div>');
            } else {
                print('<div class="my-2"><a href="' . $files[0] . '">back</a></div>');
            }
        }
        ?>
        <form method="post" action="<?php echo $uri ?>">
            <?php print ($errorMsg) ?? ""; ?>
            <div class="col-2 mb-2">
                <input type="text" name="newFolder" placeholder="Make new directory" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

</body>

</html>

<?php
// print('<br>');
// print('Key path: ' . ($_GET['path'] ?? "empty"));
// print('<br>');
// print('POST: ' . ($_POST['newFolder'] ?? "empty"));
// print('<br>');
// print('Uri: ' . $uri);
// print('<br>');
// print('<br>');
// print_r('Dir: ' . $dir);
// print('<br>');
print('<pre>');
// $_SERVER['QUERY_STRING'];
// print_r($_SERVER);
// print_r($_GET['path']);
// print(isset($_GET[‘x’]));
print('</pre>');
// onsubmit=createFolder(  php tag print($_POST('newFolder') . ',' . $dir);
?>