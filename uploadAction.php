 <?php

session_start();

require_once  'config/db.php';

$caption = ""; // variable caption (= name in the form)
$fileInput = ""; // variable fileInput
$errors = []; // empty array for errors
$success = ""; 

$user_id = $_SESSION['user']['id'];

$allowedExtensions = ["jpg", "jpeg", "png", "pdf", "txt", "mp4", "mov"]; 



if ($_SERVER["REQUEST_METHOD"] === "POST") 
    {
    // verifys if the formular contains a caption
    if (isset($_POST["caption"]) && !empty(trim($_POST["caption"]))) 
        {
            $caption = htmlspecialchars(trim($_POST["caption"]));
        } 
    else   
        {
            $caption = "No description available.";
        }
 

   
    if (isset($_FILES["fileInput"]) && $_FILES["fileInput"]["error"] === UPLOAD_ERR_OK) 
        {
            $fileInput = $_FILES["fileInput"];
            $fileExtension = strtolower(pathinfo($fileInput["name"], PATHINFO_EXTENSION));

        //checks if the file extension is not listed in the allowed extension list
        if (!in_array($fileExtension, $allowedExtensions)) 
            {
                $errors[] = "Invalid file format. Allowed are: jpg, jpeg, png, pdf, txt, mp4, mov";
            } 
        //if the file extension is allowed, the file is saved in the right folder
        else 
            {
                $uploadFolder = "uploads/";
                $filename = uniqid() . '_' . basename($fileInput["name"]);
                $destPath = $uploadFolder . $filename;

            // moves the uploaded file from the temporary location to the right folder
            if (move_uploaded_file($fileInput["tmp_name"], $destPath))
            {
                $file_path = "uploads/" . $filename;
            } 
            else 
            {
                    $errors[] = "Error saving file.";
            }
            }
        }


    if (empty($errors)) 
        {
            $stmt = $pdo->prepare("INSERT INTO posts (user_id, text, file_path) VALUES (:user_id, :text, :file_path)");
        
            // data is saved in the database with the data tipped in by the user 
            $stmt->execute([
                ':user_id' => $user_id,  
                ':text' => $caption,
                ':file_path' => $file_path
            ]);
            $success = "The post is saved in the database";
        }
    }

    //checks if there are any errors
    if (!empty($errors)) 
        {
            foreach ($errors as $error)
                {
                    echo "<p>$error</p>";
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
