<?php
include('lib/misc.inc.php');
print htmlheader();
?>
    <body>
    <div id="title">Wiki Wide Web: Enter Web 3.0</div>
    
    <?php
    if(!isset($_SESSION['username'])) {
    ?>
    <div class="user"><form action="dosignup.php" method="post">
    Please fill out the information below:<br />
    Username: <input type="text" name="username" /><br />
    Password: <input type="password" name="password" /><br />
    E-mail: <input type="text" name="email" /><br />
    Invite Code: <input type="text" name="invite" value="<?php print isset($_GET['invite']) ? $_GET['invite'] : ''; ?>" /><br />
    <input type="submit" value="Sign Up" /></form>
    </div>
    <?php
    } else {
    ?>
    <div class="user">Silly, you're already logged in!</div>
    <?php
    }
    ?>
    </body>
</html>
