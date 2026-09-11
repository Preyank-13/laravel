<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Date & Time</title>
</head>
<body>
    <h1>Hello, today is <?php echo date("l"); ?></h1>

    <p>Current Date: <?php echo date("d-m-Y"); ?></p>

    <p>Current Time: <?php echo date("h:i:s A"); ?></p>

    <p>Date & Time: <?php echo date("d-m-Y h:i:s A"); ?></p>
</body>
</html>
