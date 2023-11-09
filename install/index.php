<!doctype html>
  <html lang="en">
    <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
      <title>UlizaFlow | Installer</title>
      <style type="text/css">
        body{
          background-color: #292929;
          font-size: 1.3rem;
        }
        /*Section Header */
        .section-header{
          background-color: #2ecc71;
          color: #fff;
          padding: 20px 20px;
          overflow: hidden;
        }
        .section-header p{
          display: inline-block;
          float: left;
          text-transform: uppercase;
          font-size: 24px;
          margin-bottom: 0px;
          font-weight: 700;
        }
        .section-header a{
          float: right;
          text-transform:uppercase;
          font-size: 24px;
          padding: 5px 25px;
          background-color: #fff;
          color: #2ecc71;
          border-radius: 5px;
          text-align: center;
          text-decoration: none;
          -webkit-transition: all 0.5s ease-in-out;
          -moz-transition: all 0.5s ease-in-out;
          -o-transition: all 0.5s ease-in-out;
          transition: all 0.5s ease-in-out;
        }
        .section-header a:hover{
          background-color: #404040;
          color: #fff;
        }
        .section-padding{
          padding:80px 0px;

        }
        .step-installer{
          overflow: hidden;
          background-color: #3a3a3a;
          min-width: 650px;
          -webkit-border-radius: 10px; 
          -moz-border-radius: 10px;
          border-radius: 10px; 
          text-align: center;
          border:2px solid #2ecc71;
          padding: 20px 20px;
        }
        /*Second Installer*/
        .first-installer h1{
          margin:20px 0px;
          color: #fff;
          font-weight: 700;
        }

        .first-installer h4{
          margin:20px 0px;
          color: #fff;
          text-align: left;
        }
         .first-installer p{
          margin-bottom:30px;
          color: #fff;
        }
         .first-installer .form-check{
          float: left;
          margin-top:15px;
        }
         .first-installer .button {
          float: right;
        }
         .first-installer .button a{
          padding:10px 30px;
          font-size: 20px;
          text-transform: capitalize;
          text-decoration: none;
          background-color:#2ecc71;
          border:none; 
        }
         .first-installer .form-check label{
          color: #fff;
        }
        /*Second Installer*/
        .second-installer .installer-content table{
          color: #fff;
        }
        /*Third Installer*/
        .third-installer .installer-content form button{
          background-color: #2ecc71;
          outline: none;
          border:none;
          font-size: 25px;
          padding:10px 20px;
          margin-top: 30px;
           -webkit-transition: all 0.5s ease-in-out;
          -moz-transition: all 0.5s ease-in-out;
          -o-transition: all 0.5s ease-in-out;
          transition: all 0.5s ease-in-out;
        }
        .third-installer .installer-content form button:hover{
          background-color: #292929;
        }
        .installer-header {
          border-bottom: 1px solid #2ecc71;
          margin-bottom: 24px;
        }

      </style>
    </head>
    <body>
      <header>
          <div class="section-header">
            <p>software setup wizard</p>
            <a href="http://NullJungle.com" target="_blank">NJ Website</a>
          </div>
      </header>
      <!-- First Section Start -->
      <section class="section-padding" id="section-first">
        <div class="container">
          <div class="row">
            <div class="col-xl-12">
                
<?php 
  error_reporting(0);

function extension_check($name){
    if (!extension_loaded($name)) {
        $response = false;
    } else {
        $response = true;
    }
    return $response;
}  
function folder_permission($name){
    $perm = substr(sprintf('%o', fileperms($name)), -4);
    if ($perm >= '0775') {
        $response = true;
    } else {
        $response = false;
    }
    return $response;
}
function importDatabase($mysql_host,$mysql_database,$mysql_user,$mysql_password){
    $db = new mysqli($hostname=$mysql_host,$username=$mysql_user,$password=$mysql_password,$database=$mysql_database);
    $query = file_get_contents("ulizaflow.sql");
    $stmt = $db->prepare($query);
    if ($stmt->execute())
        return true;
    else 
        return false;
}
function home_base_url(){   
    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off') ? 'https://' : 'http://';
    $tmpURL = dirname(__FILE__);
    $tmpURL = str_replace(chr(92),'/',$tmpURL);
    $tmpURL = str_replace($_SERVER['DOCUMENT_ROOT'],'',$tmpURL);
    $tmpURL = ltrim($tmpURL,'/');
    $tmpURL = rtrim($tmpURL, '/');
    $tmpURL = str_replace('install','',$tmpURL);
    $base_url .= $_SERVER['HTTP_HOST'].'/'.$tmpURL;
    return $base_url; 
}
$base_url = home_base_url();
if (substr("$base_url", -1=="/")) {
    $base_url = substr("$base_url", 0, -1);
}
function createTable($name, $details, $status){
    if ($status=='1') {
        $pr = '<i class="fa fa-check"><i>';
    }else{
        $pr = '<i class="fa fa-times" style="color:red;"><i>';
    }
        echo "<tr><td>$name</td><td>$details</td><td>$pr</td></tr>";
}
////####################################################
$extensions = [
    'openssl' ,'pdo', 'mbstring', 'tokenizer', 'JSON', 'cURL', 'XML', 'fileinfo'
];
$folders = [
'../core/bootstrap/cache/', '../core/storage/', '../core/storage/app/', '../core/storage/framework/', '../core/storage/logs/'
];
////####################################################
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}else {
    $action = "";
}
if ($action=='install') {
?>
<div class="step-installer first-installer second-installer third-installer">
  <div class="installer-header"><h1 style="text-transform: uppercase;">Result</h1></div>
  <div class="installer-content">
  <?php
  if ($_POST) {
    $user = $_POST['user'];
    $code = $_POST['code'];
    $db_name = $_POST['db_name'];
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $status = true;
    if ($status==false) {
        echo "<h2 class='text-center' style='color:red;'>$status->message<h2>";
    }else{
        if(importDatabase($db_host,$db_name,$db_user,$db_pass)){
            echo '<div style="text-align:center; text-transform:uppercase;">
            <h1>Installed Successfully </h1><br>
            <a href="'.$base_url.'" class="btn btn-success btn-sm">Go to Website</a> 
            <br><br><b style="color:red;">Please Delete The Install Folder</b><br><br>
            <br></div>';
            ////////////////////// UPDATE CONFIG
            $key = base64_encode(random_bytes(32));
            $output = <<<EOD
            <?php
              define('BASE_URL', '.$base_url.');
              define('BASE_DIR', 'echo dirname(__FILE__);C:/xampp/htdocs/ulizaflow/');
              define('APP_URL', '.$base_url.');
              define('DB_SERVER', '.$db_host.');
              define('DB_USERNAME', '.$db_user.');
              define('DB_PASSWORD', '.$db_pass.');
              define('DB_NAME', '.$db_name.');
              define('APP_MAIlHOST', '.$mail_host.');
              define('APP_CLIENTID', '.$client_id.');
              define('APP_CLIENTSECRET', '.$client_secret.');
            ?>
            EOD;
            $output1 = <<<EOD
            const appUrl = 'http://localhost/ulizaflow/';
            ?>
            EOD;
            $file = fopen('config.php', 'w');
            $file1 = fopen('config.js', 'w');
            fwrite($file, $output);
            fwrite($file1, $output1);
            fclose($file);
            fclose($file1);
        }else{
            echo "<h2 class='text-center' style='color:red;'>Please Check Your Database Credentials!<h2>";
        }
    }
  }
  ?>
  </div>
  </div>
  <?php
  }elseif($action=='config') {
  ?>
  <div class="step-installer first-installer second-installer third-installer">
  <div class="installer-header"><h1 style="text-transform: uppercase;">Information</h1></div>
  <div class="installer-content">
  <form action="?action=install" method="post">
  <h4>APP URL</h4>
  <input class="form-control" name="app_url" value="<?php echo $base_url; ?>" type="text"><br>
  <hr style="background: #777; height: 1px;">
  <h4 style="text-transform: uppercase;">PURCHASE VERIFICATION</h4>
  <input class="form-control input-lg" name="user" placeholder="Username" type="text" value="NullJungle" required=""><br>
  <input class="form-control input-lg" name="code" placeholder="Purchase Code" type="text" value="NullJungle.com" required=""><br>
  <hr style="background: #777; height: 1px;">
  <h4 style="text-transform: uppercase;">Database Details</h4>
  <input class="form-control input-lg" name="db_name" placeholder="Database Name" type="text" required=""><br>
  <input class="form-control input-lg" name="db_host" placeholder="Database Host" type="text" required=""><br>
  <input class="form-control input-lg" name="db_user" placeholder="Dabatabe User" type="text" required=""><br>
  <input class="form-control input-lg" name="db_pass" placeholder="Password" type="text" required=""><br>
  <button class="btn btn-primary" type="submit">INSTALL NOW</button>
  </form>
  </div>
  </div>
  <?php
  }elseif ($action=='requirements') {
  ?>
  <div class="step-installer first-installer second-installer">
  <div class="installer-header" style="text-transform: uppercase;"><h1>Server Requirments</h1></div>
  <div class="installer-content table-responsive">
  <table class="table table-striped" style="text-align: left;">
  <tbody>
  <?php
  $error = 0;
  $phpversion = version_compare(PHP_VERSION, '7.1.3', '>=');
  if ($phpversion==true) {
  $error = $error+0;
  createTable("PHP", "Required PHP version 7.1.3 or higher",1);
  }else{
  $error = $error+1;
  createTable("PHP", "Required PHP version 7.1.3 or higher",0);
  }
  foreach ($extensions as $key) {
  $extension = extension_check($key);
  if ($extension==true) {
  $error = $error+0;
  createTable($key, "Required ".strtoupper($key)." PHP Extension",1);
  }else{
  $error = $error+1;
  createTable($key, "Required ".strtoupper($key)." PHP Extension",0);
  }
  }
  foreach ($folders as $key) {
  $folder_perm = folder_permission($key);
  if ($folder_perm==true) {
  $error = $error+0;
  createTable(str_replace("../", "", $key)," Required permission: 0775 ",1);
  }else{
  $error = $error+1;
  createTable(str_replace("../", "", $key)," Required permission: 0775 ",0);
  }
  }
  $envCheck = is_writable('config.php');
  if ($envCheck==true) {
  $error = $error+0;
  createTable('env'," Required config.php to be writable",1);
  }else{
  $error = $error+1;
  createTable('env'," Required config.php to be writable",0);
  }
  $database = file_exists('plp-devs-forum.sql');
  if ($database==true) {
  $error = $error+0;
  createTable('Database',"  Required plp-devs-forum.sql available",1);
  }else{
  $error = $error+1;
  createTable('Database'," Required plp-devs-forum.sql available",0);
  }
  echo '</tbody></table><div class="button">';
  if ($error==0) {
  echo '<a class="btn btn-primary anchor" href="?action=config">Next Step <i class="fa fa-angle-double-right"></i></a>';
  }else{
  echo '<a class="btn btn-info anchor" href="?action=requirements">ReCheck <i class="fa fa-sync-alt"></i></a>';
  }
  ?>
  </div>
  </div>
  </div>
  <?php
  }else{
  ?>
  <div class="step-installer first-installer">
  <div class="installer-header" style="text-transform: uppercase;"><h1> Terms of use</h1></div>
  <div class="installer-content">
  <p style="text-align: left;">
  <?php echo str_rot13('<n uers="uggcf://AhyyWhatyr.pbz">Ahyyrq Ol AhyyWhatyr.pbz</n>');?> 
  </p>
  <div class="button">
  <a class="btn btn-primary anchor" href="?action=requirements">I Agree. Next Step <i class="fa fa-angle-double-right"></i></a>
  </div>
  </div>
  </div>
  <?php
  }
  ?>
  </div>
  </div>
  </div>
  </section>
  <!-- Optional JavaScript -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
  </html>