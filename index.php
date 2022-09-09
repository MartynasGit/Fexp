<?php

require_once('lib.php');
session_start();
$uri = $_SERVER['REQUEST_URI'];
$dir = isset($_GET['path']) ? "./" . $_GET['path'] : "./";
$errorMsg; //Error message for form creation imput field.
$errorMsg2; // Error message for Imput fields .
$errorMsg3; // Error message for upload form.
$pageContentVisibility;
$loginVisibility;

//UPLOAD LOGIC
if (isset($_FILES['file'])) {
    $file_name =  $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];
    $file_name_arr = explode('.', $file_name);
    $file_ext = strtolower(end($file_name_arr));
    $extensions = ['jpeg', 'jpg', 'png', 'json', 'pdf', 'zip', 'txt'];
    if (!in_array($file_ext, $extensions) && $file_name !== "") {
        $errorMsg3[] = "File type is not alowed";
    }
    $file_name == "" && $errorMsg3[] = "No file chosen to upload !";
    $file_size > 2097152 && $errorMsg3[] = "File size is too big! File size must be under 2MB";
    empty($errorMsg3) && move_uploaded_file($file_tmp, $dir . "/" . $file_name);
}
//DOWNLOAD LOGIC
if (isset($_POST['download'])) {
    $file = $_POST['download'];
    $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, 0, 'utf-8'));
    downloadHeaders($fileToDownloadEscaped); // Function called form lib.php
}
// LOGOUT LOGIC
if (isset($_POST['logout'])) {
    session_destroy();
    session_start();
}
//DELETE FILE LOGIC
if (isset($_POST['delete']) && $_POST['delete'] !== "" && $_POST['delete'] !== " ") {
    $delItem = $_POST['delete'];
    if ($delItem !== ".//index.php" && $delItem !== ".//README.md" && $delItem !== ".//lib.php") {
        unlink($delItem);
    } else {
        $errorMsg2 = '<h5 class="text-danger text-center m-0 mb-2">You can\'t delete this file</h5>';
    }
};
//CREATE FOLDER LOGIC
if (isset($_POST['newFolder']) && $_POST['newFolder'] !== "") {
    $newFold = $_POST['newFolder'];
    $allFolders = scandir($dir);
    if (!in_array($newFold, $allFolders)) {
        mkdir($dir . '/' . $newFold);
    } else {
        $errorMsg = '<span class="text-danger ps-2">Directory name already exist !</span>';
    }
} else if (isset($_POST['newFolder']) && $_POST['newFolder'] == "") {
    $errorMsg = '<span class="text-danger">Imput field must not be empty !</span>';
};
//LOGIN LOGIC
$loginErrorMessage = '';
if (isset($_POST['login']) && !empty($_POST['userName']) && !empty($_POST['password'])) {
    if ($_POST['userName'] == 'Martin' && $_POST['password'] == '1234') {
        $_SESSION['logged_in'] = 'login';
        $_SESSION['userName'] = $_POST['userName'];
    } else {
        $loginErrorMessage = 'Wrong username or password';
    }
}
// PAGE DISPLAY LOGIC
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == "login") {
    $loginVisibility = "display: none";
} else {
    $pageContentVisibility = "display: none";
}
$files = scandir($dir);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Raleway:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>File Explorer</title>
    <style>
        body {
            font-family: 'Lato', sans-serif;
            font-family: 'Raleway', sans-serif;
        }
    </style>
</head>

<body>
    <!-- LOGIN FORM -->
    <div class="mt-5 ms-5 col-3 ps-3" style="<?php print($loginVisibility) ?>">
        <h1 class="fw-bold mb-4">Welcome to File Explorer</h1>
        <form method="post" action="<?php echo $uri ?>">
            <span class="text-danger"><?php print($loginErrorMessage) ?></span>
            <div>
                <label id="userName">Username</label>
                <input class="form-control" type="text" name="userName" placeholder="User name is Martin" required>
            </div>
            <div>
                <label id="password">Password</label>
                <input class="form-control" type="password" name="password" placeholder="Password is 1234" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3" name="login">Submit</button>
        </form>

    </div>
    <!-- PAGE AFTER SUCCESSFUL LOGIN -->
    <div class="container" style="<?php print($pageContentVisibility) ?>">
        <!-- LOGOUT FORM -->
        <form method="post" action="<?php echo $uri ?>" class="text-end">
            <button class="btn btn-warning mt-2 p-1" type="submit" name="logout" value="logout">LOG OUT</button>
        </form>
        <h1 class="text-start">Directory: <?php print($_SERVER['REQUEST_URI']) ?></h1>
        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th scope="col"> Type</th>
                    <th scope="col">Name</th>
                    <th scope="col"> Actions </th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Table row creation for all files and directories in current directory.
                foreach ($files as $value) {
                    if ($value != "." and $value != "..") {
                        $isDir = is_dir($dir . '/' . $value) ? 'Directory' : 'File';
                        print('<tr> <form method="post"><td>' . $isDir . '</td>');
                        $linkValue = ($_SERVER['QUERY_STRING'] === "")
                            ? '<a href="' . $uri . '?path=' . $value . '">' . $value . '</a>'
                            : '<a href="' . $uri . '/' . $value . '">' . $value . '</a>';
                        print("<td>");
                        is_dir($dir . '/' . $value) ? print($linkValue) : print($value);
                        print("</td>");
                        !is_dir($dir . '/' . $value)
                            ? print('<td>
                            <button class="btn btn-primary" value="' . $dir . "/" . $value . '" name="delete" >Delete </button>
                            <button class="btn btn-primary" value="' . $dir . "/" . $value . '" name="download" >Download </button>
                            </td>')
                            : print("<td> </td>");
                        print('</form></tr>');
                    }
                };
                ?>
            </tbody>
        </table>
        <?php
        (print ($errorMsg2) ?? "");
        //BACK BUTTON
        if (isset($_GET['path'])) {
            $paths = explode('/', $_GET['path']);
            $lastPath = end($paths);
            if (count($paths) > 1) {
                print('<div class="my-2"><a href="' . str_replace('/' . $lastPath, "", $uri) . '"><button class="btn btn-primary">Back</button></a></div>');
            } else {
                print('<div class="my-2"><a href="' . $files[0] . '"><button class="btn btn-primary">Back</button></a></div>');
            }
        }
        ?>
        <div class="d-flex gap-5">
            <!--NEW DIRECTORY CREATION FORM  -->
            <div class="col-3 me-5">
                <p class="fw-bold p-0 m-0 ps-2"> Create new directory</p>
                <form method="post" action="<?php echo $uri ?>" class="text-end">
                    <div class="col-12 mb-2">
                        <input type="text" name="newFolder" placeholder="Your new folder's name" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                <?php print ($errorMsg) ?? ""; ?>
            </div>
            <!-- FILE UPLOAD FORM -->
            <div class="col-3 ms-5">
                <span class="fw-bold">Upload file</span>
                <form action="" method="post" enctype="multipart/form-data" class="text-end">
                    <input type="file" name="file" class="form-control mb-2">
                    <button class="btn btn-primary">Submit</button>
                </form>
                <?php if (!empty($errorMsg3)) {
                    foreach ($errorMsg3 as $error) {
                        print('<p class="text-danger">' . $error . '</p>');
                    }
                } ?>
            </div>
        </div>
    </div>

</body>

</html>