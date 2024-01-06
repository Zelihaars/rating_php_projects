<?php
    $db_name='mysql:host=localhost;dbname=rewiews_db';
    $db_user='root';
    $db_user_pass='';

    $conn=new PDO($db_name,$db_user,$db_user_pass);
    function create_unique_id(){
        $characters =
            '0123456789abcdefghijklmnopqrsştuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_lenght = strlen($characters);
        $random_string = '';
        for($i = 0; $i < 20; $i++){
            $random_string .= $characters[mt_rand(0, $characters_lenght - 1)];
        }
        return $random_string;
    }

      if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }