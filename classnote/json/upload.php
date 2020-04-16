<?php
    //var_dump($_FILES);
    if (!empty($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] == 0){

        //check the size and type of file
        $filename = basename($_FILES['uploaded_file']['name']);
        $ext = substr($filename,strrpos($filename,".")+1);

        if ($ext == "xls" && 
        ($_FILES['uploaded_file']['type'] == "application/vnd.ms-excel" || 
        $_FILES['uploaded_file']['type'] == "application/xls" ||
        $_FILES['uploaded_file']['type'] == "application/octet-stream") && 
        $_FILES['uploaded_file']['size'] < 350000){
            //where move to?
            $newname = "./files/$filename";
            //attempt to move the file
            if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$newname)){
                chmod($newname,0644);
                $msg = "Success: File has been uploaded.";
                $json = array();
                $json['msg'] = $msg;
                $json['error'] = "";
                echo json_encode($json);
            } else {
                $msg = "Error: Problem saving file.";
                $json = array();
                $json['msg'] = $msg;
                $json['error'] = 3;
                echo json_encode($json);
            }



        } else {
            $msg = "Error: Only .xls files under 350k are allowed.";
            $json = array();
            $json['msg'] = $msg;
            $json['error'] = 2;
            echo json_encode($json);
        }

    } else {
        $msg = "Error: No file uploaded";
        $json = array();
        $json['msg'] = $msg;
        $json['error'] = 1;
        echo json_encode($json);
    }


?>