 <?php

session_start();

require_once 'config/db.php'; // allows to connect to the database

$caption = ""; // variable caption 
$fileInput = ""; // variable fileInput
$errors = []; 
$success = "";

$user_id = $_SESSION['user']['id'];

$allowedExtensions = ["jpg", "jpeg", "png", "pdf", "txt", "mp4", "mov"]; // !!Ergänze hier weitere Formate die wir brauchen!!!



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

   
      if (isset($_FILES["fileInput"]) && $_FILES["fileInput"]["error"] === UPLOAD_ERR_OK) {

        $fileInput = $_FILES["fileInput"];
        $fileExtension = strtolower(pathinfo($fileInput["name"], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors[] = "Invalid file format. Allowed: " . implode(", ", $allowedExtensions);
        } else {
            $uploadFolder = "../uploads/";
            if (!is_dir($uploadFolder)) mkdir($uploadFolder, 0777, true);

            $filename = uniqid() . '_' . basename($fileInput["name"]);
            $destPath = $uploadFolder . $filename;

            if (move_uploaded_file($fileInput["tmp_name"], $destPath)) {
                $file_path = "uploads/" . $filename;
            } else {
                $errors[] = "Error saving file.";
            }
        }
    }


    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, text, file_path) VALUES (:user_id, :text, :file_path)");
        
        // data is saved in the database with the data tipped in by the user 
        $stmt->execute([
                ':user_id' => $user_id,  
                ':text' => $caption,
                ':file_path' => $file_path
            ]);
        $success = "Post erfolgreich erstellt!";
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
