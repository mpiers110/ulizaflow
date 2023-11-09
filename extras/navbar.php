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
          <div class="image"><img src="<?php if (isset($_SESSION['profilepic'])) { echo "../file-uploads/".$_SESSION['profilepic']; } else {
          echo "../dist/img/user-default.jpg";
          } ?>" class="brand-image img-circle elevation-2" alt="User Image" width="70px">
            <span>Hello, <?php echo $_SESSION['username'];?> </span>
          </div>            
          <div class="dropdown-divider"></div>
            <a href="profile/" class="dropdown-item">
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