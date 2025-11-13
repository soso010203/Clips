<!-- ich möchte erreichen, dass die Post die hochgeladen werden, gespeichert werden-->  
 <?php

$caption = ""; // variable caption 
$fileInput = ""; // variable fileInput
$errors = []; 
$success = "";

$allowedExtensions = ["jpg", "jpeg", "png", "pdf"]; // !!Ergänze hier weitere Formate die wir brauchen!!!

if ($_SERVER["REQUEST_METHOD"] === "POST") 
    {

    if (isset($_POST["caption"]) && !empty(trim($_POST["caption"]))) 
        {
            $caption = htmlspecialchars(trim($_POST["caption"]));
        } 
    else   
        {
            $caption = "No description available.";
        }
    echo "Caption: $caption<br>";

   
    if (isset($_FILES["fileInput"]) && $_FILES["fileInput"]["error"] === UPLOAD_ERR_OK) 
        {
            $fileInput = $_FILES["fileInput"];
            $fileExtension = pathinfo(trim($fileInput["name"]), PATHINFO_EXTENSION); // keine strtolower()

        if (!in_array($fileExtension, $allowedExtensions)) 
            {
                $errors[] = "Invalid file format. Allowed formats are: " . implode(", ", $allowedExtensions);
            } 
        else 
            {
                $uploadFolder = "uploads/";

                $destPath = $uploadFolder . basename($fileInput["name"]);

            if (move_uploaded_file($fileInput["tmp_name"], $destPath)) 
                {
                    $success = "<p>The upload was successful! File: " . htmlspecialchars($fileInput["name"]) . "</p>";
                } 
            else 
                {
                    $errors[] = "Error saving file.";
                }
            }

    } 
    else 
        {
            $errors[] = "Upload failed.";
        }
}


if (!empty($errors)) 
    {
        foreach ($errors as $error)
             {
                echo "<p style='color:red;'>$error</p>";
             }
    }

if (!empty($success)) 
    {
        session_start();
        $_SESSION['successfulUpload'] = $success;
        header("Location: successUpload.php");
        exit(); 
    }

?>
