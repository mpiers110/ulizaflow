<?php 
require_once('../checklogin.php');
if ( isset($_SESSION['msg']) ) {
  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);
  echo $msg; 
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../../dist/img/help.png" rel="icon" type="image">
  <title>UlizaFlow | Tags</title>
  <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/extra.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <?php 
    if ( isset($_GET['q']) && !empty($_GET['q']) ) { ?>
      <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.min.css">
  <?php  } ?>
</head>
<body class="sidebar-mini layout-fixed">
  <div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown" title="Student Profile">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button">
            <i class="fas fa-user"></i>
        </a>
        <div class="dropdown-menu brandi dropdown-menu-lg dropdown-menu-right">
          <div class="image"><img src="<?php if (isset($_SESSION['profilepic'])) { echo "../../file-uploads/".$_SESSION['profilepic']; } else {
          echo "../../dist/img/user-default.jpg";
          } ?>" class="brand-image img-circle elevation-2" alt="User Image" width="70px">
            <span>Hello, <?php echo $_SESSION['username'];?> </span>
          </div>            
          <div class="dropdown-divider"></div>
            <a href="../profile/" class="dropdown-item">
              <i class="fas fa-user-cog mr-2"></i> Profile
            </a>
            <div class="dropdown-divider"></div>
              <a onclick="logout(<?php echo $_SESSION['id'];?>)" class="dropdown-item">
                <i class="fas fa-power-off mr-2"></i> Log Out
              </a>
            <div class="dropdown-divider"></div>
        </div>
      </li>
    </ul>
  </nav>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="../../dist/img/help.png" alt="UlizaFLOW Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Uliza FLOW</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">PUBLIC</li>
          <li class="nav-item menu-open">
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../" class="nav-link">
                  <i class="fas fa-home nav-icon"></i>
                  <p>Home</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../questions/" class="nav-link">
                  <i class="fas fa-question nav-icon"></i>
                  <p>Questions</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="fas fa-tags nav-icon"></i>
                  <p>Tags</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../users/" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2" id="page-name">
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../">Home</a></li>
              <li class="breadcrumb-item active">Tags</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="container">
          <div class="text-right text-dark" id="askbox">
            <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#answer-question">Ask a Question</button>
          </div>
        </div><br>
        <div class="row" id="row"></div>
        <div class="modal fade" id="answer-question">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Uliza a Question</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="POST" id="ulizaQuestion">
                <div class="modal-body">
                  <div class="form-group">
                    <label for="title">Question Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter your question" onkeyup="checkQuestion()">
                    <input type="hidden" id="user_id" name="user_id" value="<?=$_SESSION['id']; ?>">
                    <small id="titleHelp" class="form-text text-muted">Keep the title as simple as possible.</small>
                  </div>
                  <div class="form-group" id="desc">
                    <label for="description">Question Description</label>
                    <textarea type="text" class="form-control" id="description" name="description" rows="3" placeholder="Enter a description of your problem"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="tag">Tag</label>
                    <select class="form-control" id="tag" name="tag">
                      <option value="">Select Below</option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">Close</button>
                  <button type="submit" id="postQuestion" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include('../../extras/footer.php'); ?>
  </div>
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../../plugins/jquery-validation/additional-methods.min.js"></script>
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
</script>
<script src="tags.js"></script>
<?php 
  if ( isset($_GET['q']) && !empty($_GET['q']) ) { ?>
    <script src="../../plugins/summernote/summernote-bs4.min.js"></script>
    <script>openQuiz(<?php echo $_GET['q']; ?>);</script>
    <script id="answerFX" data-id="<?php echo $_GET['q']; ?>">showAnswers(<?php echo $_GET['q']; ?>);</script>
<?php  } ?>
</body>
</html>