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
            width: 100%; /* Make the image responsive */
            min-width: 400px; /* Set a max width to prevent it from being too large */
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
            <img src="https://pkl.smkprestasiprima.sch.id/storage/absensis/header-2.png">
        </div>

        <!-- Content Section -->
        <p>Dengan Hormat,</p>
        <p>Kami ucapkan terimakasih banyak atas kontribusi Anda dalam memajukan perusahaan.</p>
        <p>Berikut kami kirimkan slip gaji Anda, periode {{ $first }} {{ $year }} s/d {{ $end }} {{ ($end == 'Januari' ? $year + 1 : $year) }}.</p>
        <p> Gaji Anda telah kami transfer melalui Nomor Rekening yang terdaftar dalam data karyawan.</p>
        <br><br>
        <!-- Quoted Text Section -->
        <div class="quote">
            <p>Guna menjaga kerahasiaan data slip gaji Anda, Anda harus membuka data slip gaji dengan format password sebagai berikut:</p>
            <p><strong>Format password adalah xxxx-DDMMYY, terdiri dari : </strong></p>
            <b>xxxx- :</b> 3108-
            <p>
                <b>DD : </b> Dua digit tanggal lahir, contoh : tanggal 1 ditulis 01.
            </p>
            <p>
                <b>MM : </b> Dua digit bulan lahir dalam angka, contoh : Desember ditulis 12.
            </p>

            <p>
                <b>YY : </b> Dua digit tahun lahir, contoh : 1990 ditulis 90.
            </p>
            {{-- <p>DDMMYY: Tanggal lahir Anda (untuk tanggal lahir hanya menggunakan 2 angka terakhir)</p> --}}
            <p>
                <b>Contoh : </b>3108-112233</p>
        </div>

        <br><br>

        <p>Jika ada sesuatu hal yang perlu ditanyakan, silahkan dapat sampaikan melalui Personalia.</p>
        <p>Terimakasih atas kerjasamanya.</p>

        <br><br><br><br>

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
