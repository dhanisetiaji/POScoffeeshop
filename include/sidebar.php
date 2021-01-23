<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">73 KOPI</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Hai, <?= $_SESSION['username']?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <?php if($_SESSION['user_level']==1){?>
            <li class="nav-item">
              <a href="order.php" class="nav-link ">
                <i class="nav-icon fas fa-cart-plus"></i>
                <p>
                  Order
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="transaksi.php" class="nav-link ">
                <i class="nav-icon fas fa-shopping-cart"></i>
                <p>
                  Transaksi
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="stok.php" class="nav-link ">
                <i class="nav-icon fas fa-plus"></i>
                <p>
                  Stok
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="produk.php" class="nav-link ">
                <i class="nav-icon fas fa-coffee"></i>
                <p>
                  Produk
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pelanggan.php" class="nav-link ">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Pelanggan
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="admin.php" class="nav-link ">
                <i class="nav-icon fas fa-key"></i>
                <p>
                  Admin
                </p>
              </a>
            </li>
          <?php ;};
          if($_SESSION['user_level']==2){?>
            <li class="nav-item">
              <a href="stok.php" class="nav-link ">
                <i class="nav-icon fas fa-plus"></i>
                <p>
                  Stok
                </p>
              </a>
            </li>
          <?php ;};
          if($_SESSION['user_level']==3){?>
            <li class="nav-item">
              <a href="order.php" class="nav-link ">
                <i class="nav-icon fas fa-cart-plus"></i>
                <p>
                  Order
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pelanggan.php" class="nav-link ">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Pelanggan
                </p>
              </a>
            </li>
          <?php ;};?>
          <li class="nav-item">
            <a href="logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
          <!-- <li class="nav-item">
            <a href="produk.php" class="nav-link ">
              <i class="nav-icon fas fa-cart-plus"></i>
              <p>
                Produk
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="sold.php" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Produk Terjual
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="transaksi.php" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Transaksi
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="slider.php" class="nav-link">
              <i class="nav-icon fas fa-images"></i>
              <p>
                Slider
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="users.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="admin.php" class="nav-link ">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>
                Admin
              </p>
            </a>
          </li> -->
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>