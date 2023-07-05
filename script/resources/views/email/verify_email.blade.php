<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        body {
            width: 100%;
        }

        .content {
            color: #000;
        }

        .container {
            color: #000;
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 10px 20px;
        }

    </style>
</head>

<body style="background-color:#f8f8f8;">
    <div class="container">
        <p style="color: #000"><b>Verification email</b></p>

        <a href="{{ $data['link'] }}">Please click here to verify your email</a>

    </div>
</body>

</html>
