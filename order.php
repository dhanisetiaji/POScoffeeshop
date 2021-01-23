<?php
session_start();
error_reporting(0);
include('./include/koneksi.php');
if(strlen($_SESSION['user_level'])==0){
  header('location:index.php');
}
if($_SESSION['user_level']==2){
  ?> <script language="JavaScript">alert('Anda tidak memiliki akses');</script>
  <a href="dashboard.php">Go Back</a>
    <?php
    // header('Location:./dashboard.php');
}
else{
    // echo print_r($keterangan);
    if(isset($_GET['del'])){
      $id=$_GET['del'];
      $sql = "delete from keranjang_tmp  WHERE id_produk=:id";
      $query = $dbh->prepare($sql);
      $query -> bindParam(':id',$id, PDO::PARAM_STR);
      $query -> execute();
    }
    if(isset($_POST['pesan'])){
      $id_produk = $_POST['id_produk'];
      $getnama = "select nama_produk,price from produk where id_produk=:id_produk";
      $namaprod = $dbh->prepare($getnama);
      $namaprod->bindParam(':id_produk',$id_produk);
      $namaprod->execute();
      $getprodukname = $namaprod->fetch();
      $nama_produk = $getprodukname['nama_produk'];
      $price = $getprodukname['price'];
      $qty = $_POST['qty'];
      // $total = $price*$qty;
      // var_dump($nama_produk);
      // var_dump($qty);
      // die();
      $sql ="SELECT * FROM keranjang_tmp WHERE id_produk=:id_produk";
      $query = $dbh->prepare($sql);
      $query->bindParam(':id_produk',$id_produk,PDO::PARAM_STR);
      $query->execute();    
        if ($query->rowCount()==0){
          $queryadd = "INSERT INTO keranjang_tmp(id_produk,nama_produk,qty,price) VALUES(:id_produk,:nama_produk,:qty,:price)";
          $add = $dbh->prepare($queryadd);
          $add->bindParam(':id_produk',$id_produk);
          $add->bindParam(':nama_produk',$nama_produk);
          $add->bindParam(':qty',$qty);
          $add->bindParam(':price',$price);
          $add->execute();
        }else{
          $update=$dbh->prepare("UPDATE keranjang_tmp SET qty=qty+:qty where id_produk=:id_produk");
          $update ->bindParam(':id_produk',$id_produk, PDO::PARAM_STR);
          $update ->bindParam(':qty',$qty, PDO::PARAM_STR);
          $update -> execute(); 
        }
    }
    if(isset($_POST['simpan'])){
      $username = $_POST['username'];
      $total_bayar = $_POST['total'];
      // $keterangan = $_POST['keterangan'];
      // var_dump($keterangan);
      // die();
      if($total_bayar==0){
        header('Location:./order.php');
      }else{
        $qtransaksi = "INSERT INTO transaksi(username,total_bayar) VALUES(:username,:total_bayar)";
        $transaksi = $dbh -> prepare($qtransaksi);
        $transaksi->bindParam(':username',$username);
        $transaksi->bindParam(':total_bayar',$total_bayar);
        $transaksi->execute();
        $msg="Order Berhasil!!!";
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId){
          $qdel = "DELETE FROM keranjang_tmp";
          $del = $dbh->prepare($qdel);
          $del -> execute();
        }
      }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Coffe Shop | Order</title>
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/chosen.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <?php include('./include/sidebar.php');?>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Menu Order</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Admin</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
          <div class="col-12">
          <div class="card">
              <div class="card-header">
              <a type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#AddPesan"><i class="fas fa-plus"></i> Pesan</a><br>
                <?php 
                    if($error){
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo htmlentities($error); ?> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } 
				    else if($msg){
                ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert"><?php echo htmlentities($msg); ?> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php }?>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                        function rupiah($angka){
	
                            $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
                            return $hasil_rupiah;
                         
                        }
                        $sql = "select * from keranjang_tmp";
                        $query = $dbh -> prepare($sql);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        $nmr=1;
                        $totalbelanja = 0;
                        // $ket = array();
                        if($query->rowCount() > 0){
                            foreach($results as $res){
                            $total = $res->qty*$res->price;
                            $totalbelanja += $total;
                            // $ket[] = "$res->nama_produk ($res->qty)";                    
                    ?>
                  <tr>
                      
                    <td><?php echo htmlentities($nmr);?></td>
                    <td><?php echo htmlentities($res->nama_produk);?></td>
                    <td><?php echo htmlentities($res->qty);?></td>
                    <td><?php echo htmlentities(rupiah($res->price));?></td>
                    <td><?php echo htmlentities(rupiah($total));?></td>
                    <td>
                        <!-- <a type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#MyModal<?php echo $res->id_pasien;?>"><i class="fas fa-edit"></i></a> -->
                         <a href="order.php?del=<?php echo $res->id_produk;?>" onclick="return confirm('Do you want to delete');" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                    </td>
                  </tr>
                  <?php $nmr=$nmr+1; } } ?>
                  </tbody>
                </table>
                <form action="" method="post">
            <table>
                <tr>
                    <td style="width:760px;" rowspan="2"></td>
                    <th style="width:140px;">Total Belanja(Rp)</th>
                    <th style="text-align:right;width:140px;"><input type="text" name="total2" value="<?= $totalbelanja?>" class="form-control input-sm" style="text-align:right;margin-bottom:5px;" readonly></th>
                    <input type="hidden" id="total" name="total" value="<?= $totalbelanja?>" class="form-control input-sm" style="text-align:right;margin-bottom:5px;" readonly>
                    <!-- <input type="hidden" name="keterangan[]" value="<?= $keterangan; ?>" > -->
                    
                </tr>
                <tr>
                    <th>Tunai(Rp)</th>
                    <th style="text-align:right;"><input type="text" id="jml_uang" name="jml_uang" class="jml_uang form-control input-sm" style="text-align:right;margin-bottom:5px;" required></th>
                    <input type="hidden" id="jml_uang2" name="jml_uang2" class="form-control input-sm" style="text-align:right;margin-bottom:5px;" required>
                </tr>
                <tr>
                    <td></td>
                    <th>Kembalian(Rp)</th>
                    <th style="text-align:right;"><input type="text" id="kembalian" name="kembalian" class="form-control input-sm" style="text-align:right;margin-bottom:5px;" readonly></th>
                </tr>
                <tr>
                    <td></td>
                    <th>Pelanggan</th>
                    <th style="text-align:right;">
                      <select name="username" class="form-control" required>
                        <?php 
                            $sql1 = "select username from pelanggan";
                            $query1 = $dbh -> prepare($sql1);
                            $query1->execute();
                            $results1=$query1->fetchAll(PDO::FETCH_OBJ);
                            if($query1->rowCount() > 0){
                                foreach($results1 as $res1){
                        ?>
                        <option value="<?= $res1->username?>"><?= $res1->username?></option>
                        <?php }}?>
                      </select>      
                    </th>
                </tr>
                <tr>
                <td></td>
                <th></th>
                <th style="text-align:right;"><button type="submit" name="simpan" class="btn btn-info btn-lg"> Simpan</button></th>
                </tr>
                    

            </table>
            </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
        </div>
        <div class="modal fade" id="AddPesan">
          <div class="modal-dialog" >
              <div class="modal-content">
                  <div class="modal-header">
                  <h4 class="modal-title">Order!</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                  </div>
                  <div class="modal-body">
                      <form method="post">
                          <div class="form-group">
                              <label for="">Produk</label>
                              <select name="id_produk" class="form-control" required>
                              <?php 
                                  $sql = "select * from produk";
                                  $query = $dbh -> prepare($sql);
                                  $query->execute();
                                  $results=$query->fetchAll(PDO::FETCH_OBJ);
                                  if($query->rowCount() > 0){
                                      foreach($results as $res){
                              ?>
                              <option value="<?= $res->id_produk?>"><?= $res->nama_produk?></option>
                              <?php }}?>
                              </select>
                          </div>
                          <div class="form-group">
                              <label for="">Qty</label>
                              <!-- <input type="hidden" name="id" class="form-control" value="<?php echo htmlentities($res->id)?>"> -->
                              <input type="text" name="qty" class="form-control" required>
                              <button Type="submit" name="pesan" class="btn btn-primary mt-4">Pesan</button>
                              <!-- <button Type="submit" name="updatestok" class="btn btn-primary mt-4">Update</button> -->
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  
                  </div>
              </div>
              <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.1.0-rc
    </div>
    <strong>Copyright &copy; 2014-2020 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page specific script -->
<script src="plugins/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
        $('document').ready(function(){
            $(".chosen-select").chosen();
        })
        $(function(){
            $('#jml_uang').on("input",function(){
                var total=$('#total').val();
                var jumuang=$('#jml_uang').val();
                var hsl=jumuang.replace(/[^\d]/g,"");
                $('#jml_uang2').val(hsl);
                $('#kembalian').val(hsl-total);
            })
            
        });
    </script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</body>
</html>
<?php } ?>