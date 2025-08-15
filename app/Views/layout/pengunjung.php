<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SISTEM INFORMASI TITIK KOORDINAT SISWA KURANG MAMPU SMAN 4 ABDYA' ?></title>
    <link href="<?= base_url('css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #2e59d9;
        --accent-color: #f8f9fc;
        --text-color: #2d3748;
        --light-text: #f8f9fa;
        --dark-bg: #1a1a2e;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: var(--text-color);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        line-height: 1.6;
    }

    /* Header Styles */
    .navbar {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        padding: 1rem 2rem;
        box-shadow: var(--shadow);
        position: sticky;
        top: 0;
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .navbar-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .logo-container {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .logo {
        height: 50px;
        width: auto;
    }

    .app-title {
        color: var(--light-text);
        font-size: 1.2rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .nav-menu {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .nav-menu a {
        color: var(--light-text);
        text-decoration: none;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: var(--transition);
        position: relative;
    }

    .nav-menu a:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .nav-menu a.active {
        background-color: rgba(255, 255, 255, 0.3);
    }

    .nav-menu a.active::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 60%;
        height: 3px;
        background-color: white;
        border-radius: 3px;
    }

    .login-btn {
        background-color: white;
        color: var(--primary-color);
        padding: 0.5rem 1.5rem;
        border-radius: 2rem;
        font-weight: 600;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .hamburger {
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 0.5rem;
        padding: 0.5rem;
    }

    .hamburger span {
        display: block;
        width: 24px;
        height: 3px;
        background: white;
        margin: 3px 0;
        border-radius: 2px;
        transition: var(--transition);
    }

    /* Hero Section */
    .hero {
        background: linear-gradient(rgba(78, 115, 223, 0.9), rgba(46, 89, 217, 0.9)), url('<?= base_url('images/school-bg.jpg') ?>');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 5rem 2rem;
        text-align: center;
    }

    .hero-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .hero h1 {
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
    }

    .hero p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .cta-buttons {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.8rem 2rem;
        border-radius: 2rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-primary {
        background-color: white;
        color: var(--primary-color);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-secondary {
        background-color: transparent;
        color: white;
        border: 2px solid white;
    }

    .btn-secondary:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-3px);
    }

    /* Features Section */
    .features {
        padding: 5rem 2rem;
        background-color: white;
    }

    .section-title {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-title h2 {
        font-size: 2rem;
        color: var(--text-color);
        margin-bottom: 1rem;
        position: relative;
        display: inline-block;
    }

    .section-title h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: var(--primary-color);
        border-radius: 2px;
    }

    .section-title p {
        color: #6c757d;
        max-width: 700px;
        margin: 0 auto;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .feature-card {
        background: white;
        border-radius: 0.8rem;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: var(--transition);
        text-align: center;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
    }

    .feature-card h3 {
        font-size: 1.3rem;
        margin-bottom: 1rem;
        color: var(--text-color);
    }

    .feature-card p {
        color: #6c757d;
    }

    /* Stats Section */
    .stats {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        padding: 4rem 2rem;
        color: white;
    }

    .stats-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        text-align: center;
    }

    .stat-item {
        padding: 1.5rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    /* Footer */
    .footer {
        background-color: var(--dark-bg);
        color: white;
        padding: 4rem 2rem 2rem;
        margin-top: auto;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .footer-logo img {
        height: 40px;
    }

    .footer-logo-text {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .footer-about p {
        margin-bottom: 1.5rem;
        opacity: 0.8;
    }

    .social-links {
        display: flex;
        gap: 1rem;
    }

    .social-links a {
        color: white;
        background: rgba(255, 255, 255, 0.1);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .social-links a:hover {
        background: var(--primary-color);
        transform: translateY(-3px);
    }

    .footer-links h3 {
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.5rem;
    }

    .footer-links h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: var(--primary-color);
    }

    .footer-links ul {
        list-style: none;
    }

    .footer-links li {
        margin-bottom: 0.8rem;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .footer-links a:hover {
        color: white;
        transform: translateX(5px);
    }

    .footer-links a i {
        font-size: 0.8rem;
    }

    .footer-contact h3 {
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.5rem;
    }

    .footer-contact h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: var(--primary-color);
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.2rem;
    }

    .contact-icon {
        font-size: 1.2rem;
        color: var(--primary-color);
        margin-top: 0.2rem;
    }

    .contact-text {
        opacity: 0.8;
    }

    .footer-bottom {
        text-align: center;
        padding-top: 2rem;
        margin-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        opacity: 0.7;
        font-size: 0.9rem;
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
        .app-title {
            font-size: 1rem;
        }

        .hero h1 {
            font-size: 2rem;
        }

        .hero p {
            font-size: 1rem;
        }
    }

    @media (max-width: 768px) {
        .navbar-container {
            flex-direction: column;
            align-items: flex-start;
        }

        .nav-menu {
            flex-direction: column;
            width: 100%;
            background: var(--secondary-color);
            position: absolute;
            top: 100%;
            left: 0;
            padding: 1rem;
            box-shadow: var(--shadow);
            display: none;
        }

        .nav-menu.show {
            display: flex;
        }

        .hamburger {
            display: flex;
            position: absolute;
            right: 2rem;
            top: 1rem;
        }

        .hero {
            padding: 4rem 1rem;
        }

        .hero h1 {
            font-size: 1.8rem;
        }

        .cta-buttons {
            flex-direction: column;
            gap: 1rem;
        }

        .btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .navbar {
            padding: 1rem;
        }

        .logo {
            height: 40px;
        }

        .hero h1 {
            font-size: 1.5rem;
        }

        .section-title h2 {
            font-size: 1.5rem;
        }

        .feature-card {
            padding: 1.5rem;
        }

        .footer-container {
            grid-template-columns: 1fr;
        }
    }
</style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo-container">
                <img src="<?= base_url('images/logosekolah.png') ?>" alt="Logo SMAN 4 ABDYA" class="logo">
                <span class="app-title">SISTEM INFORMASI TITIK KOORDINAT<br>SISWA KURANG MAMPU SMAN 4 ABDYA</span>
            </div>

            <div class="hamburger" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="nav-menu" id="mainMenu">
                <?php $uri = service('uri'); ?>
                <a href="<?= site_url('pengunjung') ?>" class="<?= ($uri->getSegment(1) == 'pengunjung' && ($uri->getSegment(2) == '' || $uri->getSegment(2) == 'index')) ? 'active' : '' ?>">BERANDA</a>
                <a href="<?= site_url('pengunjung/list-siswa') ?>" class="<?= ($uri->getSegment(1) == 'pengunjung' && $uri->getSegment(2) == 'list-siswa') ? 'active' : '' ?>">DAFTAR SISWA</a>
                <a href="<?= site_url('pengunjung/petasiswa') ?>" class="<?= ($uri->getSegment(1) == 'pengunjung' && $uri->getSegment(2) == '' && strpos(current_url(), 'petasiswa') !== false) ? 'active' : '' ?>">PETA SEBARAN</a>
                <a href="<?= site_url('pengunjung/hubungi') ?>" class="<?= ($uri->getSegment(1) == 'pengunjung' && $uri->getSegment(2) == 'hubungi') ? 'active' : '' ?>">HUBUNGI KAMI</a>
                <a href="<?= site_url('/login') ?>" class="login-btn"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Sistem Informasi Geografis Siswa Kurang Mampu</h1>
            <p>Memetakan lokasi siswa kurang mampu di SMAN 4 ABDYA untuk memudahkan pendataan dan penyaluran bantuan</p>
            <div class="cta-buttons">
                <a href="<?= site_url('pengunjung/list-siswa') ?>" class="btn btn-primary">Lihat Daftar Siswa</a>
                <a href="<?= site_url('pengunjung/petasiswa') ?>" class="btn btn-secondary">Lihat Peta Sebaran</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="section-title">
            <h2>Fitur Utama Sistem</h2>
            <p>Sistem ini menyediakan berbagai fitur untuk memudahkan pendataan dan pemetaan siswa kurang mampu</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3>Pemetaan Siswa</h3>
                <p>Visualisasi lokasi siswa kurang mampu dalam bentuk peta interaktif untuk analisis geografis</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Manajemen Data</h3>
                <p>Sistem terpusat untuk mengelola data siswa kurang mampu dengan mudah dan aman</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Analisis Data</h3>
                <p>Statistik dan visualisasi data untuk mendukung pengambilan keputusan</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number">150+</div>
                <div class="stat-label">Siswa Terdata</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">10+</div>
                <div class="stat-label">Desa/Kelurahan</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">5+</div>
                <div class="stat-label">Jenis Bantuan</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100%</div>
                <div class="stat-label">Akurasi Data</div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main style="flex: 1;">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-about">
                <div class="footer-logo">
                    <img src="<?= base_url('images/logosekolah.png') ?>" alt="Logo SMAN 4 ABDYA">
                    <span class="footer-logo-text">SMAN 4 ABDYA</span>
                </div>
                <p>Sistem Informasi Geografis untuk memetakan lokasi siswa kurang mampu di SMAN 4 Aceh Barat Daya.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-links">
                <h3>Tautan Cepat</h3>
                <ul>
                    <li><a href="<?= site_url('pengunjung') ?>"><i class="fas fa-chevron-right"></i> Beranda</a></li>
                    <li><a href="<?= site_url('pengunjung/list-siswa') ?>"><i class="fas fa-chevron-right"></i> Daftar Siswa</a></li>
                    <li><a href="<?= site_url('pengunjung/petasiswa') ?>"><i class="fas fa-chevron-right"></i> Peta Sebaran</a></li>
                    <li><a href="<?= site_url('pengunjung/hubungi') ?>"><i class="fas fa-chevron-right"></i> Hubungi Kami</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h3>Kontak Kami</h3>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt contact-icon"></i>
                    <div class="contact-text">
                        JL. NASIONAL MEULABOH-BLANG PIDIE Km.16<br>
                        ALUE PADEE, KEC. KUALA BATEE<br>
                        KAB. ACEH BARAT DAYA
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone-alt contact-icon"></i>
                    <div class="contact-text">0813xxxxxx</div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope contact-icon"></i>
                    <div class="contact-text">sman4@gmail.com</div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2024 SMAN 4 ABDYA. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const mainMenu = document.getElementById('mainMenu');

        menuToggle.addEventListener('click', function() {
            mainMenu.classList.toggle('show');
            menuToggle.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mainMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                mainMenu.classList.remove('show');
                menuToggle.classList.remove('active');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>