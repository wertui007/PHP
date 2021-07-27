<?php
include_once('dbh.inc.php');


if (isset($_POST["submit"])) {
    if (isset($_POST['name'], $_POST['username'], $_POST['email'], $_POST['password']) && !empty($_POST['name']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {

        $name = trim($_POST['name']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $options = array("cost" => 4);
        $hashPassword = password_hash($password, PASSWORD_BCRYPT, $options);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = 'select * from users.users where email = :email';
            $stmt = $pdo->prepare($sql);
            $p = ['email' => $email];
            $stmt->execute($p);

            if ($stmt->rowCount() == 0) {
                $sql = "insert into users.users(name, username, email, 'password') values(:name,:username,:email,:pass)";
                try {
                    $handle = $pdo->prepare($sql);
                    $params = [
                        ':name' => $name,
                        ':username' => $username,
                        ':email' => $email,
                        ':pass' => $hashPassword
                    ];
                    $handle->execute($params);

                    $success = 'User has been created successfully';
                } catch (PDOException $e) {
                    $errors[] = $e->getMessage();
                }
            } else {
                $valName = $name;
                $valUsername = $username;
                $valEmail = '';
                $valPassword = $password;

                $errors[] = 'Email address already registered';
            }
        } else {
            $errors[] = "Email address is not valid";
        }
    } else {
        if (!isset($_POST['name']) || empty($_POST['name'])) {
            $errors[] = 'name is required';
        } else {
            $valName = $_POST['name'];
        }
        if (!isset($_POST['username']) || empty($_POST['username'])) {
            $errors[] = 'username is required';
        } else {
            $valUsername = $_POST['username'];
        }
        if (!isset($_POST['email']) || empty($_POST['email'])) {
            $errors[] = 'email is required';
        } else {
            $valEmail = $_POST['email'];
        }
        if (!isset($_POST['password']) || empty($_POST['password'])) {
            $errors[] = 'password is required';
        } else {
            $valPassword = $_POST['password'];
        }
    }
}
?>
<html>
<head>
    <title>Registrace</title>
</head>
<body>

<div id="login-box">
    <div class="left">
        <h1>Registrace</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" id='register' method='post'
              accept-charset='UTF-8'>
            <input type="text" name="name" placeholder="Jméno a příjmení"/>
            <input type="text" name="username" placeholder="Uživatelské jméno"/>
            <input type="text" name="email" placeholder="E-mail"/>
            <input type="password" name="password" placeholder="Heslo"/>
            <input type="password" name="password2" placeholder="Heslo znovu"/>

            <input type="submit" name="submit" value="Zaregistrovat"/>
    </div>

    <div class="right">

    </div>
</body>
</html>
