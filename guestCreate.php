<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        
        #content {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .error {
            color: #ff0000;
            font-weight: bold;
        }
        
        table {
            margin-bottom: 20px;
        }
        
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        }
        
        input[type="submit"] {
            width: 100%;
            padding: 6px 12px;
            font-size: 14px;
            font-weight: bold;
            color: #ffffff;
            background-color: #5cb85c;
            border: 1px solid #4cae4c;
            border-radius: 4px;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
        }
        
        input[type="submit"]:hover {
            background-color: #449d44;
            border-color: #398439;
        }
    </style>
</head>
<body>
<div id="content">
    <?php
    //include_once('database/dbPersons.php');
    include_once('database/persondb.php');
    include_once('domain/Person.php');
    if (($_SERVER['PHP_SELF']) == "/logout.php") {
        //prevents infinite loop of logging in to the page which logs you out...
        echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
    }
    if (!array_key_exists('_submit_check', $_POST)) {
    ?>
        <div class="login-form">
            <h3>Login</h3>
            <p>Access to Homebase requires a Username and a Password.</p>
            <ul>
                <li>If you are applying for a volunteer position, enter the Username 'guest' and a blank Password.</li>
                <li>If you are a volunteer logging in for the first time, your Username is your first name followed by your ten digit phone number. After you have logged in, you can change your password.</li>
                <li>If you are having difficulty logging in or have forgotten your Password, please contact either the
                    <a href="mailto:allen@npfi.org"><i>Portland House Manager</i></a>
                    or the <a href="mailto:allen@npfi.org"><i>Bangor House Manager</i></a>.</li>
            </ul>
            <form method="post">
                <input type="hidden" name="_submit_check" value="true">
                <div class="mb-3">
                    <label for="user" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="user" name="user" tabindex="1">
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="pass" name="pass" tabindex="2">
                </div>
                <div class="d-grid">
                    <input type="submit" name="Login" value="Login" class="btn btn-primary" tabindex="3">
                </div>
            </form>
        </div>
    <?php
    } else {
        //check if they logged in as a guest:
        if ($_POST['user'] == "guest" && $_POST['pass'] == "") {
            $_SESSION['logged_in'] = 1;
            $_SESSION['access_level'] = 0;
            $_SESSION['venue'] = "";
            $_SESSION['type'] = "";
            $_SESSION['_id'] = "guest";
            echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
        }
        //otherwise authenticate their password
        else {
            $db_pass = md5($_POST['pass']);
            $db_id = $_POST['user'];
            $person = retrieve_person_by_username($db_id);
            if ($person) { //avoids null results
                if ($person->get_password() == $db_pass) { //if the passwords match, login
                    $_SESSION['logged_in'] = 1;
                    date_default_timezone_set ("America/New_York");
                    if ($person->get_status() == "applicant")
                        $_SESSION['access_level'] = 0;
                    else if (in_array('manager', $person->get_type()))
                        $_SESSION['access_level'] = 2;
                    else
                        $_SESSION['access_level'] = 1;
                    $_SESSION['f_name'] = $person->get_first_name();
                    $_SESSION['l_name'] = $person->get_last_name();
                    $_SESSION['venue'] = $person->get_venue();
                    $_SESSION['type'] = $person->get_type();
                    $_SESSION['_id'] = $_POST['user'];
                    echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
                }
                else {
                    echo('<div align="left"><p class="error">Error: invalid username/password<br />if you cannot remember your password, ask either the 
        		<a href="mailto:allen@npfi.org"><i>Portland House Manager</i></a>
        		or the <a href="mailto:allen@npfi.org"><i>Bangor House Manager</i></a>. to reset it for you.</p><p>Access to Homebase requires a Username and a Password. <p>For guest access, enter Username <strong>guest</strong> and no Password.</p>');
                    echo('<p>If you are a volunteer, your Username is your first name followed by your phone number with no spaces. ' .
                    'For instance, if your first name were John and your phone number were (207)-123-4567, ' .
                    'then your Username would be <strong>John2071234567</strong>.  ');
                    echo('If you do not remember your password, please contact either the 
        		<a href="mailto:allen@npfi.org"><i>Portland House Manager</i></a>
        		or the <a href="mailto:allen@npfi.org"><i>Bangor House Manager</i></a>.');
                    echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td><td><input type="text" name="user" tabindex="1"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2" align="center"><input type="submit" name="Login" value="Login"></td></tr></table>');
                }
            } else {
                //At this point, they failed to authenticate
                echo('<div align="left"><p class="error">Error: invalid username/password<br />if you cannot remember your password, ask the House Manager to reset it for you.</p><p>Access to Homebase requires a Username and a Password. <p>For guest access, enter Username <strong>guest</strong> and no Password.</p>');
                echo('<p>If you are a volunteer, your Username is your first name followed by your phone number with no spaces. ' .
                'For instance, if your first name were John and your phone number were (207)-123-4567, ' .
                'then your Username would be <strong>John2071234567</strong>.  ');
                echo('If you do not remember your password, please contact either the 
        		<a href="mailto:allen@npfi.org"><i>Portland House Manager</i></a>
        		or the <a href="mailto:allen@npfi.org"><i>Bangor House Manager</i></a>.');
                echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td><td><input type="text" name="user" tabindex="1"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2" align="center"><input type="submit" name="Login" value="Login"></td></tr></table>');
            }
        }
    }
    ?>
</div>
    <?PHP include('footer.php'); ?>
</div>
</body>
</html>
