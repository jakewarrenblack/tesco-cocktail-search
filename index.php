<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'nav.php' ?>
    <div class="container">
        <form method="POST" action="submit.php">
            <input placeholder="Enter an ingredient" name="ingredients" type="text"></input>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>