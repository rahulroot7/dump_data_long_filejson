<?php
    
    ini_set('max_execution_time', '-1');
    

        // Get Variables
        $dbname = 'd144821_hotel_dump';
        $dbusername = 'root';
        $dbpass = '';
        $dbhost = 'localhost';
        $log_file = "script-errors.log";

    if(!empty($_POST['submit'])){

        if($_POST['submit'])
        {
            //storing all necessary data into the respective variables.
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $file_type = $file ['type'];
            $file_size = $file ['size'];
            $file_path = $file ['tmp_name'];

            // print_r($file_path);die('=')
            if($file_type == 'application/json'){
            
                $connection = mysqli_connect("$dbhost", "$dbusername", "$dbpass","$dbname");
                if (!$connection) {
                    // die('Could not connect: ' . mysql_error());
                } else {
                    // echo "Connected";
                }

                $i =1;

                if ($file = fopen($file_name, "r")) {
                    while(!feof($file)) {
                        $line = fgets($file);
                        
                   
                            $row = json_decode( $line, true );
                            if(!empty($row['id'])){


                                $hid = $row['id'];

                                $str =$connection->real_escape_string($line);
                                $sql = "INSERT INTO static_hotel (hotel_id, hotel_data)
                                VALUES ('".$hid."','".$str."') ON DUPLICATE KEY UPDATE hotel_data=values(hotel_data);";
                                // print_r($sql);die;
                                if ($connection->query($sql) == TRUE) {
                                    echo "Record - ".$i;
                                } else {
                                    $error_message = date('Y-m-d h:i:s a')." Line: ". $i ." Error: " . $sql . " - " . $connection->error."\r\n\r\n";
                                    //echo $error_message;
                                    error_log($error_message, 3, $log_file);
                                }    
                                // file_put_contents('jsondata.json', $line, FILE_APPEND);
                             $i++;
                            } 

                    }
                    echo 'Data Upload completed !';
                    fclose($file);
                }
            }else{
                echo 'only json file upload';
            }
        }
    }


    ?>



<html>
<body>
    <form action="dump.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" name="submit" value="UPDATE">
</form>
</body>
</html>