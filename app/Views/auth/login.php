<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMAN 4 Abdya</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= base_url('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <style>
        :root {
            /* Define color variables for easier management */
            --primary-blue: #4A90E2;
            --secondary-blue: #63B8F5;
            /* A slightly lighter blue for gradients */
            --dark-blue: #2E6DA4;
            --text-color: #333;
            --border-color: #EBF4FF;
            /* Light blue for input borders */
        }

        body {
            font-family: 'Poppins', sans-serif;
            /* Gradient background for the entire body, similar to Canva design */
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-color);
        }

        .login-main-card {
            background: #fff;
            /* Solid white background for the card */
            border-radius: 1.5rem;
            /* Rounded corners for the card */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            /* Soft shadow for depth */
            padding: 3.5rem 3rem;
            /* Generous padding inside the card */
            max-width: 900px;
            /* Adjusted max-width to match Canva's wider layout */
            width: 100%;
            /* Removed the border, as per Canva design */
            animation: fadeIn 0.8s ease-out;
            /* Smooth entrance animation */
            text-align: center;
            /* Center content within the card */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-logo-img {
            width: 200px;
            /* Adjusted logo size */
            height: auto;
            display: block;
            /* Ensures centering with margin auto */
            margin: 0 auto 1.5rem auto;
            /* Center and provide spacing below logo */
            transition: transform 0.3s ease;
        }

        .login-logo-img:hover {
            transform: scale(1.05);
        }

        .login-title {
            font-size: 1.8rem;
            /* Larger font size for the title */
            font-weight: 700;
            /* Bold title */
            text-align: center;
            margin-top: 1.5rem;
            /* Spacing above title */
            margin-bottom: 2.5rem;
            /* Spacing below title before form */
            color: var(--primary-blue);
            letter-spacing: 0.5px;
        }

        .login-form {
            max-width: 450px;
            /* Constrain the form width for aesthetic */
            margin: 0 auto;
            /* Center the form within the card */
        }

        .login-form-label {
            /* Visually hidden label for accessibility, as placeholder is visible */
            display: none;
        }

        .login-input {
            /* Perbaikan utama untuk bentuk pill dan teks rata tengah */
            border-radius: 999px !important;
            /* Nilai sangat besar untuk menjamin bentuk pill */
            border: 2px solid var(--border-color) !important;
            /* Light border color */
            padding: 12px 25px;
            /* Ample padding for taller inputs */
            font-weight: 500;
            color: var(--text-color);
            transition: all 0.3s ease;
            text-align: center;
            /* Memusatkan teks input dan placeholder */
        }

        .login-input::placeholder {
            color: #999;
            /* Placeholder text color */
            text-align: center;
            /* Memastikan placeholder rata tengah */
        }

        .login-input:focus {
            border-color: var(--primary-blue) !important;
            /* Primary blue border on focus */
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
            /* Subtle shadow on focus */
            background-color: #fcfdff;
            /* Slightly lighter background on focus */
        }

        .login-btn {
            /* Perbaikan utama untuk bentuk pill */
            border-radius: 999px;
            /* Nilai sangat besar untuk menjamin bentuk pill */
            font-weight: 700;
            font-size: 1.2rem;
            padding: 12px 25px;
            /* Gradient background for button, slightly different from body for contrast */
            background: linear-gradient(90deg, var(--primary-blue), var(--dark-blue));
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            width: 100%;
            /* Make button span the width of its container */
        }

        .login-btn:hover {
            background: linear-gradient(90deg, var(--dark-blue), var(--primary-blue));
            /* Reverse gradient on hover */
            transform: translateY(-2px);
            /* Slight lift on hover */
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .alert {
            border-radius: 0.75rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            animation: slideIn 0.5s ease-out;
            max-width: 450px;
            /* Align alert with form width */
            margin-left: auto;
            margin-right: auto;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Perbaikan styling checkbox */
        .form-group.form-check {
            /* Target parent form-group to center the custom-control */
            text-align: center;
            /* Pusatkan konten di dalam form-group */
            margin-bottom: 2rem;
            /* Spacing below checkbox */
        }

        /* Menggunakan kembali custom-control dari sb-admin-2 untuk konsistensi */
        .custom-control-label {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0;
            /* Remove default margin */
            /* Pastikan ada jarak antara checkbox dan teks */
            padding-left: 0.5rem;
        }

        .custom-control-input:checked~.custom-control-label::before {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .custom-control-input {
            border: 2px solid var(--primary-blue);
            /* Ensure checkbox border is visible */
            width: 1.25rem;
            /* Standard checkbox size */
            height: 1.25rem;
        }


        /* Responsive Adjustments */
        @media (max-width: 767px) {
            .login-main-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .login-logo-img {
                width: 150px;
                margin-bottom: 1rem;
            }

            .login-title {
                font-size: 1.4rem;
                margin-top: 1rem;
                margin-bottom: 2rem;
            }

            .login-form {
                max-width: 100%;
                /* Allow form to take full width on small screens */
            }

            .login-input {
                padding: 10px 20px;
                /* Adjust padding for smaller screens */
            }

            .login-btn {
                padding: 10px 20px;
                font-size: 1.1rem;
            }

            .form-group.form-check {
                margin-bottom: 1.5rem;
                /* Adjust margin for small screens */
            }
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
        <div class="login-main-card w-100">
            <img src="<?= base_url('images/logosekolah.png') ?>" alt="Logo Sekolah" class="login-logo-img">
            <div class="login-title">SELAMAT DATANG DI APLIKASI TITIK KOORDINAT SMAN 4 ABDYA</div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('login') ?>" method="post" class="login-form">
                <?= csrf_field() ?>
                <div class="form-group mb-4">
                    <label for="username" class="sr-only">Email</label>
                    <input type="text" name="username" id="username" class="form-control login-input" placeholder="Enter Your Name" required autofocus>
                </div>
                <div class="form-group mb-4">
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" name="password" id="password" class="form-control login-input" placeholder="Enter Your Password" required>
                </div>
                <div class="form-group form-check mb-4">
                    <div class="custom-control custom-checkbox d-inline-block"> <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                        <label class="custom-control-label" for="remember">Ingat Saya</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary login-btn">Login</button>
            </form>
        </div>
    </div>
</body>

</html>