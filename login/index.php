<?php 
session_start();
  if ( isset($_SESSION['msg']) ) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <link href="../dist/img/help.png" rel="icon" type="image">
	<title>Uliza FLOW | Login</title>
	<!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
  <div class="login-box">
  <div class="login-logo">
    <img src="../dist/img/help.png" height="150px"/><br>
    <a><b>Uliza </b>FLOW</a>
  </div>
  <!-- /.login-logo -->
  <?php 
    if ( isset($msg) ) { ?>
      <p class="login-box-msg" style="color:red;"><?=$msg; ?></p><?php 
    } ?>
	<div class="card">
    <div class="card-body login-card-body">     
      <p class="login-box-msg">Sign in to start your session</p>
      <form method="post">
        <div class="input-group mb-3">
          <input name="username" id="username" class="form-control" placeholder="Username/Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text" onClick="showPass()">
              <span id="show" class="fas fa-eye"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <button type="button" onClick="login()" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mb-1">
        <a href="../forgot-password/">Forgot Password?</a>
      </p>
      <p class="mb-0">
        <a href="../register/" class="text-center">Register</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<?php include('../extras/scripts.php');?>
<script src="login.js"></script>
</body>
</html>