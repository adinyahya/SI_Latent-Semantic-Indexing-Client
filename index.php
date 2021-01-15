<!DOCTYPE html>
<html lang="en">

  <head>

   <?php
        include "include/head.php";
    ?>

  </head>
<body>

    <!-- Navigation -->
  <?php
include "include/header.php";
  ?>

    <!-- Page Content -->
    <div class="container">

      <div class="row">

        <div class="col-lg-3">
          <h1 class="my-3">Tahun</h1>
          <div class="list-group">
            <a href="#" class="list-group-item active">1995</a>
            <a href="#" class="list-group-item">1996</a>
            <a href="#" class="list-group-item">1997</a>
          </div>
        </div>
        <!-- /.col-lg-3 -->

        <div class="col-lg-9">

          <div class="card mt-4">
            
    <form method="post">      
 <input type="text" name="keyword" class="form-control form-control-lg" placeholder="Search....">
 <!-- <button type="submit" class="form-control-lg">Search</button> -->
 </form>

 
          </div>
          <!-- /.card -->
                      <style>
                       .jud {
                         color : blue;

                         font-size : 19px;
                         padding:.75rem 1.25rem;margin-bottom:0;
                        
                       }
                     </style> 
          <div class="card card-outline-secondary my-4">

            <?php

            include 'koneksi.php';
            include 'fungsi/similarity.php';

            $keyword="";
            if(!empty($_POST["keyword"]))
            {
            $keyword = $_POST["keyword"];
            }
              if ($keyword)  {
                $keyword = preproses($keyword);   
                
                echo ' <div class="card-header">';
                print('Hasil pencarian untuk <font color=blue><b>' . $_POST["keyword"]  . '</b></font> Ditemukan sebanyak 0 dokumen. dengan waktu 0 detik.  '); 
                echo ' </div>';
                echo ' <div class="card-body">';

                        echo "<p>";
                        ambilcache($keyword);
                           echo "</p>";
                         }




?>
            </div>
          </div>
          <!-- /.card -->

        </div>
        <!-- /.col-lg-9 -->

      </div>

    </div>
    <!-- /.container -->

    <!-- Footer -->
   <?php
      include "include/footer.php";
   ?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>

