<?php
include 'components/connect.php';


if(isset($_POST['submit'])){

    $id= create_unique_id();

    $name=$_POST['name'];
    $name=filter_var($name,FILTER_SANITIZE_STRING);

    $email=$_POST['email'];
    $email=filter_var($email,FILTER_SANITIZE_STRING);

    $pass=password_hash($_POST['pass'],PASSWORD_DEFAULT);
    $pass=filter_var($pass,FILTER_SANITIZE_STRING);

    $c_pass=password_verify($_POST['c_pass'],$pass);
    $c_pass=filter_var($c_pass,FILTER_SANITIZE_STRING);

    $image=$_FILES['image']['name'];
    $image=filter_var($image,FILTER_SANITIZE_STRING);
    $ext=pathinfo($image,PATHINFO_EXTENSION);
    $rename=create_unique_id().'.'.$ext;
    $image_size=$_FILES['image']['size'];
    $image_tmp_name=$_FILES['image']['tmp_name'];
    $image_folder='uploaded_files/'.$rename;

    if(!empty($image)){
        if($image_size>2000000){
            $warning_msg[]='Dosya boyutu çok büyük';
        }else{
            move_uploaded_file($image_tmp_name,$image_folder);
        }
    }else{
        $rename="";
    }


    $verify_email=$conn->prepare("SELECT * FROM `users` WHERE email=?");
    $verify_email->execute([$email]);
    if($verify_email->rowCount()>0){
        $warning_msg[]="Bu email daha önce alınmış";
    }else{
        if($c_pass==1){
            $insert_user=$conn->prepare
                        ("INSERT INTO `users`
                         (id,name,email,password,image)
                            VALUES(?,?,?,?,?)");
            $insert_user->execute([$id,$name,$email,$pass,$rename]);
            $success_msg[]='Kayıt oluşturuldu';
        }else{
            $warning_msg[]="Şifre doğrulanmadı";
        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt</title>

    <!--Css dosya linki-->
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
<!--header -->
<?php

include 'components/header.php';
?>

<!--header -->

<section class="account-form">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>yeni bir hesap oluştur!</h3>

        <p class="placeholder">kullanıcı adı  <span>*</span> </p>
        <input type="text" name="name" required maxlength="50"
               placeholder="kullanıcı adınızı girin" class="box">

        <p class="placeholder">email adresi  <span>*</span> </p>
        <input type="email" name="email" required maxlength="50"
               placeholder="mailinizi girin" class="box">

        <p class="placeholder">şifre  <span>*</span> </p>
        <input type="password" name="pass" required maxlength="50"
               placeholder="şifrenizi girin" class="box">

        <p class="placeholder">şifre  <span>*</span> </p>
        <input type="password" name="c_pass" required maxlength="50"
               placeholder="şifrenizi tekrar girin" class="box">

        <p class="placeholder">profil  <span>*</span> </p>
        <input type="file" name="image" placeholder="Dosya seç" class="box" accept="image/*">

        <p class="link">hesabın varsa?<a href="login.php"> giriş yap</a> </p>
        <input type="submit" value="kayıt ol" name="submit" class="btn">
    </form>
</section>


<!--Sweetalert cdn dosya linki-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<!--Js dosya linki-->
<script src="js/script.js"></script>

<?php include 'components/alers.php';?>
</body>
</html>
