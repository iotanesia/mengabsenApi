<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
        }
        .header {
            display: block;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            width: 25%; /* Make the image responsive */
            min-width: 250px; /* Set a max width to prevent it from being too large */
            height: auto; /* Maintain the aspect ratio */
        }

        .clear {
            clear: both;
        }
        h3 {
            color: #27ae60;
            margin: 0;
            padding-bottom: 10px;
        }
        p {
            margin: 10px 0;
            line-height: 1.6;
        }
        .quote {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            font-family: 'Courier New', Courier, monospace;
            color: #555;
        }
        .quote p {
            margin: 5px 0;
        }
        .footer {
            /* text-align: center; */
            font-size: 12px;
            color: #888;
        }
        .footer img {
            width: 100%; /* Make the footer image responsive */
            max-width: 200px; /* Set a reasonable max-width for the image */
            height: auto; /* Keep the aspect ratio intact */
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <img src="{{request()->getSchemeAndHttpHost()}}/storage/files/logo/Logo%20Iota%205.png">
        </div>

        <!-- Content Section -->
         <p>Halo, {{ $email }}!</p>
        <p>Kami menerima permintaan reset password dari akun Anda.</p>
        <p>Untuk melanjutkan proses reset password, silahkan klik tombol di bawah ini:</p>
        <br><br>
        <!-- Quoted Text Section -->
        <div class="quote">
            <p>
                <a href="{{ $url }}" style="background-color: #27ae60; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Reset Password</a> 
            </p>
        </div>

        <br><br>

        <p>Jika Anda tidak merasa melakukan permintaan reset password, abaikan email ini.</p>
        
        <!-- Footer Section -->
        <p>Hormat Kami,</p>
        <br><br>
        <p>PT Iota Cipta Indonesia</p>
        <div class="footer">
            <img src="https://pkl.smkprestasiprima.sch.id/storage/absensis/footer-4.png" alt="Footer"><br>
        </div>
    </div>
</body>
</html>
