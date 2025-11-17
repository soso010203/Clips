<?php
// array posts anlegen --> später durch Datenbank ersetzt
$posts = [
    1 => [
        "image" => "pics/Tier01.jpg",
        "description" => "Today I saw an interesting bird. Look at the photo!"
    ],
    2 => [
        "image" => "pics/Landschaft.png",
        "description" => "Look at the beautiful landscape!"
    ],
    3 => [
        "image" => "pics/Winter.jpg",
        "description" => "What a beautiful winter day."
    ],
     101 => [
        "image" => "pics/Bild.jpg",
        "description" => "Enjoying the view from a rocky cliff with a breathtaking landscape behind me."
    ],
    102 => [
        "image" => "pics/Feld.jpg",
        "description" => "A peaceful field stretches out to the horizon."
    ],
    103 => [
        "image" => "pics/Hase.jpg",
        "description" => "This little bunny was hopping around, so cute!"
    ]
];

$id = intval($_GET['id'] ?? 0);

// here you find the post
$post = $posts[$id] ?? null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'parts/navbar.php'; ?>

<div class="container mt-5">

    <?php if ($post): ?>

        <div class="d-flex align-items-center mb-4">
            <div>
                <p  style="font-weight: bold;">posted by: sophia010203</p>
                <p> <?php echo date("d.m.Y H:i") ?></p>
            </div>
        </div>

        <div class="text-center mb-4">
            <img src="<?php echo $post["image"]; ?>" style="max-width: 700px; height:auto;">
        </div>

        <div class="mx-auto" style="max-width:700px;">
            <p>
                <?php echo $post["description"]; ?>
            </p>
        </div>

    <?php endif; ?>

</div>

</body>
</html>