<?php
    require '../function.php';
    require '../cek.php'
?>
<html>
<head>
  <title>Laporan barang keluar</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
    <div class="container">
		<h2>Barang keluar</h2>
		<div class="data-tables datatable-dark">
            <table id="mauexport">
                <thead>
                    <tr>
                        <th>tanggal</th>
                        <th>nama barang</th>
                        <th>penerima</th>
                        <th>jumlah</th>
                        </tr>
                </thead>
                <tbody>    
                    <?php
                    $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM keluar k, stock s WHERE s.idbarang = k.idbarang");
                    while ($data=mysqli_fetch_array($ambilsemuadatastock)){
                        $tanggal = $data['tanggal'];
                        $namabarang = $data['namabarang'];
                        $qty = $data['qty'];
                        $penerima = $data['penerima'];
                        $idb = $data['idbarang'];
                        $idk = $data['idkeluar'];
                                        
                    ?>
                    <tr>
                        <td><?=$tanggal;?></td>
                        <td><?=$namabarang;?></td>
                        <td><?=$penerima?></td>
                        <td><?=$qty;?></td>
                    </tr>

                    <?php
                    };
                    ?>

                </tbody>
            </table>
		</div>
    </div>
	
<script>
$(document).ready(function() {
    $('#mauexport').DataTable( {
        dom: 'Bfrtip',
        buttons: [
             'print'
        ]
    } );
} );

</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

	

</body>

</html>