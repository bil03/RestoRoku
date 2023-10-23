<?php 
    session_start();


    $conn = mysqli_connect("localhost", "root", "", "restoroku");

        //nambah barang baru
    if (isset($_POST['tambahbarang'])) {
        $namabarang = $_POST['namabarang'];
        $satuan = $_POST['satuan'];
        $stock = $_POST['stock'];

        $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, satuan, stock) VALUES('$namabarang', '$satuan', '$stock')");
        if($addtotable){
            header('location:index.php');
        } else {
            echo 'gagal';
            header('location:index.php');
        }
    }

        //nambah barang masuk
    if(isset($_POST['barangmasuk'])) {
        $barangnya = $_POST['barangnya'];
        $keterangan = $_POST['keterangan'];
        $qty = $_POST['qty'];

        $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$barangnya'");
        $ambildatanya = mysqli_fetch_array($cekstocksekarang);

        $stocksekarang = $ambildatanya['stock'];
        $tambahstock = $stocksekarang + $qty;

        $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, keterangan, qty) VALUES('$barangnya', '$keterangan','$qty')");
        $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$tambahstock' WHERE idbarang='$barangnya'");


        if($addtomasuk && $updatestockmasuk){
            header('location:masuk.php');
        } else {
            echo 'gagal';
            header('location:masuk.php');
        }
}

//nambah barang keluar
    if(isset($_POST['barangkeluar'])) {  
        $barangnya = $_POST['barangnya'];
        $penerima = $_POST['penerima'];
        $qty = $_POST['qty'];

        $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$barangnya'");
        $ambildatanya = mysqli_fetch_array($cekstocksekarang);

        $stocksekarang = $ambildatanya['stock'];

        if ($stocksekarang >= $qty){
            //jika barang cukup
            $tambahstock = $stocksekarang - $qty;

            $addtomasuk = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty) VALUES('$barangnya', '$penerima','$qty')");
            $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$tambahstock' WHERE idbarang='$barangnya'");


            if($addtomasuk && $updatestockmasuk){
                header('location:keluar.php');
            } else {
                echo 'gagal';
                header('location:keluar.php');
            }
        } else {
            //jika barang tidak cukup
            echo '
                <script>
                    alert("Stock saat ini tidak mencukupi");
                    window.location = "keluar.php";
                </script>
            ';
        }
    }

    //update info barang 
    if(isset($_POST['updatebarang'])){
        $idb = $_POST['idb'];
        $namabarang = $_POST['namabarang'];
        $satuan = $_POST['satuan'];

        $update = mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', satuan='$satuan' WHERE idbarang='$idb'");

        if ($update){
            header('location:index.php');
        } else {
            echo 'gagal';
            header('location:index.php');
        }

    }

    // hapus barang 
    if (isset($_POST['hapusbarang'])){
        $idb = $_POST['idb'];

        $hapus = mysqli_query($conn, "DELETE FROM stock WHERE idbarang = '$idb'");
        if ($hapus){
            header('location:index.php');
        } else {
            echo 'gagal';
            header('location:index.php');
        }
    }

    //edit barang masuk
    if (isset($_POST['updatebarangmasuk'])){
        $idb = $_POST['idb'];
        $idm = $_POST['idm'];
        $keterangan = $_POST['keterangan'];
        $qty = $_POST['qty'];

        $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
        $stocknya = mysqli_fetch_array($lihatstock); 
        $stockskrg = $stocknya['stock'];

        $qtyskrng = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idm'");
        $qtynya = mysqli_fetch_array($qtyskrng);
        $qtyskrng = $qtynya['qty'];

        if ($qty>$qtyskrng){
            $selisih = $qty - $qtyskrng;
            $kurangin = $stockskrg - $selisih;
            $kuranginstocknya = mysqli_query($conn, "UPDATE stock SET stock ='$kurangin' WHERE idbarang='$idb'");
            $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$keterangan' WHERE idmasuk='$idm'"); 
            if ($kuranginstocknya && $updatenya){
                header('location:masuk.php');
            } else {
                echo 'gagal';
                header('location:masuk.php');
            }
        }else {
            $selisih = $qtyskrng - $qty;
            $kurangin = $stockskrg + $selisih;
            $kuranginstocknya = mysqli_query($conn, "UPDATE stock SET stock ='$kurangin' WHERE idbarang='$idb'");
            $updatenya = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$keterangan' where idmasuk='$idm'"); 
            if ($kuranginstocknya && $updatenya){
                header('location:masuk.php');
            } else {
                echo 'gagal';
                header('location:masuk.php');
            }
        }
    }

    //menghapus barang masuk
    if(isset($_POST['hapusbarangmasuk'])) {
        $idb = $_POST['idb'];
        $qty = $_POST['kty'];
        $idm = $_POST['idm'];

        $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
        $data = mysqli_fetch_array($getdatastock);
        $stock = $data['stock'];

        $selisih = $stock - $qty;

        $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");
        $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idm'");

        if ($update && $hapusdata){
            header('location:masuk.php');
        } else {
            header('location:masuk.php');
        }
    }

    //edit barang keluar
    if (isset($_POST['updatebarangkeluar'])){
        $idb = $_POST['idb'];
        $idk = $_POST['idk'];
        $penerima = $_POST['penerima'];
        $qty = $_POST['qty']; //qty inputan user

        //stock saat ini
        $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
        $stocknya = mysqli_fetch_array($lihatstock); 
        $stockskrg = $stocknya['stock'];

        //qty barang keluar 
        $qtyskrng = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idk'");
        $qtynya = mysqli_fetch_array($qtyskrng);
        $qtyskrng = $qtynya['qty'];

        if ($qty>$qtyskrng){
            $selisih = $qty - $qtyskrng;
            $kurangin = $stockskrg - $selisih;

            if ($selisih <= $stockskrg){
                $kuranginstocknya = mysqli_query($conn, "UPDATE stock SET stock ='$kurangin' WHERE idbarang='$idb'");
                $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'"); 
                if ($kuranginstocknya && $updatenya){
                    header('location:keluar.php');
                } else {
                    echo 'gagal';
                    header('location:keluar.php');
                }
            }else {
                echo '
                    <script>
                        alert("Stock saat ini tidak mencukupi");
                        window.location = "keluar.php";
                    </script>
                ';
            }
        }else {
            $selisih = $qtyskrng - $qty;
            $kurangin = $stockskrg + $selisih;
            $kuranginstocknya = mysqli_query($conn, "UPDATE stock SET stock ='$kurangin' WHERE idbarang='$idb'");
            $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'"); 
            if ($kuranginstocknya && $updatenya){
                header('location:keluar.php');
            } else {
                echo 'gagal';
                header('location:keluar.php');
            }
        }
    }

    //menghapus barang keluar
    if(isset($_POST['hapusbarangkeluar'])) {
        $idb = $_POST['idb'];
        $qty = $_POST['kty'];
        $idk = $_POST['idk'];

        $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
        $data = mysqli_fetch_array($getdatastock);
        $stock = $data['stock'];

        $selisih = $stock + $qty;

        $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");
        $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idk'");

        if ($update && $hapusdata){
            header('location:keluar.php');
        } else {
            header('location:keluar.php');
        }
    }

    //menambah users
    if(isset($_POST['adduser'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $queryinsert = mysqli_query($conn, "INSERT INTO login (email, password, role) VALUES ('$email', '$password', '$role')");

        if ($queryinsert){
            header('location:users.php');
        } else {
            echo 'gagal';
            header('location:users.php');
        }
    }

    //edit data user
    if(isset($_POST['updateuser'])) {
        $emailbaru = $_POST['emailuser'];
        $passwordbaru = $_POST['passwordbaru']; 
        $idnya = $_POST['id'];

        $updateuser = mysqli_query($conn, "UPDATE login set email='$emailbaru', password='$passwordbaru' WHERE iduser='$idnya'");

        if ($updateuser){
            header('location:users.php');
        } else {
            echo 'gagal';
            header('location:users.php');
        }
    }

    //delete users
    if (isset($_POST['hapususer'])) {
        $id = $_POST['id'];

        $deleteuser = mysqli_query($conn, "DELETE FROM login WHERE iduser='$id'");

        if ($deleteuser){
            header('location:users.php');
        }else {
            echo 'gagal';
            header('location:users.php');
        }
    }
?>