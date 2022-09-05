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
                $uri = $_SERVER['REQUEST_URI'];
                $dir = isset($_GET['path']) ? "./" . $_GET['path'] : "./";
                $files = scandir($dir);

                foreach ($files as $value) {
                    if ($value != "." and $value != "..") {

                        is_dir($value) ? $fileType = "Directory" : $fileType = "File";
                        print("<tr><td>$fileType</td>");
                        print("<td>");
                        is_dir($value) ? print ('<a href="' . $dir . '?path=/' . $value . '">' . $value . '</a>') : print($value);
                        print("</td>");

                        print('<td><button class="btn btn-primary">Delete</button></td>');
                        print('</tr>');
                    }
                };
                ?>
            </tbody>
        </table>
        <?php 
         print('<a href="' . $files[0] . '">back</a>');
        ?>
    </div>

</body>

</html>

<?php
print('<br>');
print_r($uri);
print('<br>');
print('<br>');
print_r($dir);
print('<br>');
print('<pre>');
// $_SERVER['QUERY_STRING'] = "/?xx";
// print_r($_SERVER);
print_r($_GET['path']);
// print(isset($_GET[‘x’]));
print('</pre>');
?>