<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Titik Lokasi Siswa Sman4 Aceh Barat Daya</title>
    <link href="<?= base_url('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="<?= base_url('css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Pastikan Bootstrap JS juga dimuat jika modal menggunakan fungsi Bootstrap -->
    <script src="path/to/your/bootstrap.bundle.min.js"></script>
    </style>

    <style>
        /* Variabel CSS untuk tema yang lebih mudah */
        :root {
            --warna-utama: #4e73df;
            /* Primary color */
            --warna-utama-rgb: 78, 115, 223;
            --warna-terang: #ffffff;
            /* Light color */
            --warna-teks-gelap: #5a5c69;
            /* Dark text color */
            --sidebar-bg-aktif: rgba(255, 255, 255, 0.15);
            --sidebar-collapse-bg-aktif: var(--warna-utama);
            --sidebar-collapse-warna-aktif: var(--warna-terang);
            --hover-bg-terang: #f8f9fc;
            --warna-border-terang: #e3e6f0;
            --transisi-default: all 0.25s ease-in-out;
            --box-shadow-sm: 0 .125rem .25rem rgba(0, 0, 0, .075);
            --box-shadow-md: 0 .5rem 1rem rgba(0, 0, 0, .15);
            --box-shadow-lg-utama: 0 0.5rem 1.5rem rgba(var(--warna-utama-rgb), 0.25);
            --border-radius-sm: 0.2rem;
            --border-radius-md: 0.35rem;

            /* Variabel untuk sidebar resizable */
            --lebar-sidebar-default: 224px;
            /* Default width dari SB Admin 2 */
            --lebar-sidebar-minimal: 180px;
            --lebar-sidebar-maksimal: 400px;
            --lebar-sidebar-saat-ini: var(--lebar-sidebar-default);
            /* Akan diupdate oleh JS */
        }

        body {
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
            /* Mencegah scroll horizontal saat resize sidebar */
        }

        /* Penyesuaian untuk Page Wrapper dan Content Wrapper terkait sidebar resizable */
        #wrapper {
            display: flex;
        }

        #accordionSidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
        }

        #content-wrapper {
            margin-left: var(--lebar-sidebar-saat-ini, 225px);
        }

        /* Handle untuk Resize Sidebar */
        .sidebar-resize-handle {
            position: absolute;
            top: 0;
            right: -5px;
            /* Sedikit keluar agar mudah di-klik */
            width: 10px;
            /* Lebar area klik */
            height: 100%;
            cursor: col-resize;
            z-index: 1031;
            /* Di atas sidebar, di bawah elemen topbar jika overlap */
            /* background-color: rgba(0,0,0,0.1); */
            /* Opsional: untuk melihat handle saat development */
        }

        .sidebar-resize-handle::after {
            /* Visual handle (garis tipis) */
            content: "";
            position: absolute;
            top: 0;
            left: 4px;
            /* Posisi garis di tengah handle */
            width: 1.5px;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.2);
            transition: background-color 0.2s ease;
        }

        .sidebar-resize-handle:hover::after {
            background-color: var(--warna-utama);
        }

        body.sedang-resize-sidebar {
            cursor: col-resize;
            user-select: none;
            /* Mencegah seleksi teks saat dragging */
        }

        body.sedang-resize-sidebar #accordionSidebar,
        body.sedang-resize-sidebar #content-wrapper {
            transition: none !important;
            /* Nonaktifkan semua transisi saat resizing */
        }


        /* Peningkatan Sidebar */
        .sidebar .nav-item .nav-link {
            padding: 0.85rem 1rem;
            width: 100%;
            transition: var(--transisi-default);
            border-radius: var(--border-radius-md);
        }

        .sidebar .nav-item .nav-link:hover {
            background-color: var(--sidebar-bg-aktif);
        }

        .sidebar .nav-item.active>.nav-link:not([data-toggle="collapse"]) {
            background-color: var(--sidebar-bg-aktif);
            font-weight: 600;
        }

        .sidebar .nav-item.active-parent>.nav-link {
            background-color: var(--sidebar-bg-aktif);
        }

        .sidebar .nav-item .collapse .collapse-inner .collapse-item {
            padding: 0.6rem 1rem;
            transition: var(--transisi-default);
            white-space: normal;
            /* Memungkinkan teks wrap jika sidebar sempit */
        }

        .sidebar .nav-item .collapse .collapse-inner .collapse-item.active,
        .sidebar .nav-item .collapse .collapse-inner .collapse-item:active {
            background-color: var(--sidebar-collapse-bg-aktif);
            color: var(--sidebar-collapse-warna-aktif) !important;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            box-shadow: var(--box-shadow-lg-utama);
            transform: translateX(5px);
        }

        .sidebar .nav-item .collapse .collapse-inner .collapse-item:hover {
            background-color: var(--hover-bg-terang);
            border-radius: var(--border-radius-md);
            transform: translateX(3px);
        }

        .sidebar-brand-text {
            font-weight: 700;
        }

        .sidebar-brand-icon img {
            transition: transform 0.3s ease;
        }

        .sidebar-brand:hover .sidebar-brand-icon img {
            transform: rotate(-10deg) scale(1.1);
        }

        /* Styling Paginasi Kustom - Disesuaikan */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 25px 0;
            gap: 8px;
            padding-left: 0;
        }

        .pagination li {
            list-style: none;
        }

        .pagination li a,
        .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            padding: 0 12px;
            background: var(--warna-terang);
            border: 1px solid var(--warna-utama);
            border-radius: 50%;
            color: var(--warna-utama);
            font-weight: 500;
            text-decoration: none;
            transition: var(--transisi-default), transform 0.15s ease-out, box-shadow 0.15s ease-out;
        }

        .pagination li a:hover {
            background: var(--warna-utama);
            color: var(--warna-terang);
            transform: translateY(-3px);
            box-shadow: var(--box-shadow-lg-utama);
        }

        .pagination li.active span {
            background: var(--warna-utama);
            color: var(--warna-terang);
            border-color: var(--warna-utama);
            transform: translateY(-3px);
            box-shadow: var(--box-shadow-lg-utama);
            font-weight: 700;
        }

        .pagination li.disabled span {
            background: var(--hover-bg-terang);
            color: #b0b2c3;
            border-color: var(--warna-border-terang);
            cursor: not-allowed;
        }

        .pagination .previous a,
        .pagination .next a {
            font-size: 1.1rem;
            padding: 0;
        }

        /* Efek Hover untuk Baris Tabel - Lebih Halus */
        .table-hover tbody tr {
            transition: var(--transisi-default);
        }

        .table-hover tbody tr:hover {
            background-color: #f5f7fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        /* Animasi untuk Elemen Aktif - Pulse Lebih Halus */
        .pulse-animation {
            animation: subtlePulse 2s infinite;
        }

        @keyframes subtlePulse {
            0% {
                box-shadow: 0 0 0 0 rgba(var(--warna-utama-rgb), 0.3);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(var(--warna-utama-rgb), 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(var(--warna-utama-rgb), 0);
            }
        }

        .sidebar .nav-item.active>.nav-link:not([data-toggle="collapse"])>i {
            /* animation: subtlePulse 2s infinite; */
            /* Contoh: pulse hanya ikon */
        }

        /* Penyempurnaan Topbar */
        .topbar {
            box-shadow: var(--box-shadow-md);
            height: auto;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .topbar .nav-item .nav-link {
            height: auto;
            padding: 0.5rem 1rem;
        }

        #sidebarToggleTop {
            color: var(--warna-utama);
        }

        /* Footer */
        .sticky-footer {
            box-shadow: 0 -0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        }

        .copyright {
            font-size: 0.85rem;
            color: var(--warna-teks-gelap);
        }

        /* Scrollbar Kustom (Opsional) */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body id="page-top fixed-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <div class="sidebar-resize-handle" id="sidebarResizeHandle"></div>

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard') ?>">
                <div class="sidebar-brand-icon">
                    <img src="<?= base_url('images/logosekolah.png') ?>" alt="Logo" class="img-fluid" style="height: 45px; margin-right: 10px;">
                </div>
                <div class="sidebar-brand-text">SMAN 4 Abdya</div>
            </a>
            <hr class="sidebar-divider my-0">

            <li class="nav-item <?= current_url() == base_url('dashboard') ? 'active' : '' ?>">
                <a class="nav-link <?= current_url() == base_url('dashboard') ? 'pulse-animation' : '' ?>" href="<?= base_url('dashboard') ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">
                Manajemen Siswa
            </div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDataSiswa" aria-expanded="false" aria-controls="collapseDataSiswa">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Siswa</span>
                </a>
                <div id="collapseDataSiswa" class="collapse" aria-labelledby="headingDataSiswa" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Kelola Siswa:</h6>
                        <a class="collapse-item <?= current_url() == base_url('siswa') ? 'active' : '' ?>" href="<?= base_url('siswa') ?>">
                            <i class="fas fa-users fa-fw mr-2 text-gray-400"></i> Daftar Siswa
                        </a>
                        <a class="collapse-item <?= current_url() == base_url('siswa/create') ? 'active' : '' ?>" href="<?= base_url('siswa/create') ?>">
                            <i class="fas fa-user-plus fa-fw mr-2 text-gray-400"></i> Tambah Siswa
                        </a>
                        <a class="collapse-item <?= current_url() == base_url('siswa/laporan_siswa') ? 'active' : '' ?>" href="<?= base_url('siswa/laporan_siswa') ?>">
                            <i class="fas fa-file-alt fa-fw mr-2 text-gray-400"></i> Laporan Siswa
                        </a>
                        <a class="collapse-item <?= (isset($active_submenu) && $active_submenu == 'peta_sebaran') ? 'active' : '' ?>"
                            href="<?= site_url('peta-sebaran') // <-- UBAH MENJADI ROUTE BARU 
                                    ?>">
                            <i class="fas fa-map-marked-alt fa-fw mr-2 text-gray-400"></i>Peta Siswa
                        </a>
                    </div>
                </div>
            </li>

            <li class=" nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseManajemen" aria-expanded="false" aria-controls="collapseManajemen">
                    <i class="fas fa-fw fa-columns"></i>
                    <span>Manajemen</span>
                </a>
                <div id="collapseManajemen" class="collapse" aria-labelledby="headingManajemen" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Administrasi:</h6>
                        <a class="collapse-item <?= current_url() == base_url('kelas') ? 'active' : '' ?>" href="<?= base_url('kelas') ?>">
                            <i class="fas fa-chalkboard fa-fw mr-2 text-gray-400"></i> Kelas
                        </a>
                        <a class="collapse-item <?= current_url() == base_url('ortu') ? 'active' : '' ?>" href="<?= base_url('ortu') ?>">
                            <i class="fas fa-user-shield fa-fw mr-2 text-gray-400"></i> Orang Tua
                        </a>
                        <?php if (session()->get('role') === 'operator'): ?>
                            <a class="collapse-item <?= current_url() == base_url('user') ? 'active' : '' ?>" href="<?= base_url('user') ?>">
                                <i class="fas fa-user-cog fa-fw mr-2 text-gray-400"></i> User
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>

            <?php if (session()->get('role') === 'operator'): ?>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">
                </div>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePengaturan" aria-expanded="false" aria-controls="collapsePengaturan">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                    <div id="collapsePengaturan" class="collapse" aria-labelledby="headingPengaturan" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Konfigurasi:</h6>
                            <a class="collapse-item <?= current_url() == base_url('pengaturan/lokasi') ? 'active' : '' ?>" href="<?= base_url('pengaturan/lokasi') ?>">
                                <i class="fas fa-map-marked-alt fa-fw mr-2 text-gray-400"></i> Lokasi
                            </a>
                            <a class="collapse-item <?= current_url() == base_url('pengaturan/log') ? 'active' : '' ?>" href="<?= base_url('pengaturan/log') ?>">
                                <i class="fas fa-history fa-fw mr-2 text-gray-400"></i> Log Aktivitas
                            </a>
                        </div>
                    </div>
                </li>
            <?php endif; ?>

            <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                    <i class="fas fa-sign-out-alt fa-fw"></i>
                    <span>Logout</span>
                </a>
            </li>

            <div class="text-center d-none d-md-inline mt-3">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">

                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <a class="dropdown-item" href="user">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Pengaturan Akun
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>



            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Hak Cipta &copy; <?= date('Y') ?> SMAN 4 ACEH BARAT DAYA. Semua hak dilindungi.</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Siap untuk Keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah jika Anda siap untuk mengakhiri sesi Anda saat ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="<?= base_url('auth/logout') ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <script src="<?= base_url('js/sb-admin-2.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            const KUNCI_STATUS_SIDEBAR = 'sidebar_modern_status_';
            const KUNCI_LEBAR_SIDEBAR = 'sidebar_modern_lebar';
            const sidebar = $('#accordionSidebar');
            const contentWrapper = $('#content-wrapper');
            const resizeHandle = $('#sidebarResizeHandle');
            const minWidth = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--lebar-sidebar-minimal'));
            const maxWidth = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--lebar-sidebar-maksimal'));
            let isResizing = false;

            function terapkanLebarSidebar(lebar) {
                let lebarValid = Math.max(minWidth, Math.min(lebar, maxWidth));
                document.documentElement.style.setProperty('--lebar-sidebar-saat-ini', lebarValid + 'px');
                if ($('body').hasClass('sidebar-toggled')) {}
            }

            // Muat lebar sidebar yang tersimpan
            const lebarTersimpan = localStorage.getItem(KUNCI_LEBAR_SIDEBAR);
            if (lebarTersimpan) {
                terapkanLebarSidebar(parseInt(lebarTersimpan));
            } else {
                terapkanLebarSidebar(parseInt(getComputedStyle(document.documentElement).getPropertyValue('--lebar-sidebar-default')));
            }

            resizeHandle.on('mousedown', function(e) {
                e.preventDefault();
                isResizing = true;
                $('body').addClass('sedang-resize-sidebar');
                sidebar.css('transition', 'none');
                contentWrapper.css('transition', 'none');


                $(document).on('mousemove.resizeSidebar', function(ev) {
                    if (!isResizing) return;
                    let lebarBaru = ev.clientX - $('#wrapper').offset().left;
                    terapkanLebarSidebar(lebarBaru);
                });

                $(document).on('mouseup.resizeSidebar', function() {
                    if (!isResizing) return;
                    isResizing = false;
                    $('body').removeClass('sedang-resize-sidebar');
                    $(document).off('mousemove.resizeSidebar mouseup.resizeSidebar');

                    sidebar.css('transition', '');
                    contentWrapper.css('transition', '');


                    localStorage.setItem(KUNCI_LEBAR_SIDEBAR, sidebar.width());
                });
            });

            $('#sidebarToggle, #sidebarToggleTop').on('click', function(e) {
                $("body").toggleClass("sidebar-toggled");
                $(".sidebar").toggleClass("toggled");
                if ($(".sidebar").hasClass("toggled")) {
                    $('.sidebar .collapse').collapse('hide');
                } else {
                    document.documentElement.style.setProperty('--lebar-sidebar-saat-ini', sidebar.width() + 'px');
                }
            });


            // --- Fungsionalitas Status Collapse Menu Sidebar ---
            function updateStatusIndukAktif() {
                $('.sidebar .nav-item').removeClass('active-parent');
                $('.sidebar .collapse-item.active').each(function() {
                    $(this).closest('.nav-item').addClass('active-parent');
                    $(this).closest('.collapse').addClass('show');
                });
            }

            $('.sidebar .nav-link[data-toggle="collapse"]').on('click', function(e) {
                let target = $(this).attr('data-target');
                let sedangTerbuka = $(target).hasClass('show');
                if (sedangTerbuka) {
                    localStorage.setItem(KUNCI_STATUS_SIDEBAR + target, 'collapsed');
                } else {
                    localStorage.setItem(KUNCI_STATUS_SIDEBAR + target, 'expanded');
                }
            });

            $('.sidebar .nav-link[data-toggle="collapse"]').each(function() {
                let target = $(this).attr('data-target');
                let statusTersimpan = localStorage.getItem(KUNCI_STATUS_SIDEBAR + target);

                if (statusTersimpan === 'expanded') {
                    $(target).addClass('show');
                    $(this).attr('aria-expanded', 'true').removeClass('collapsed');
                } else if (statusTersimpan === 'collapsed') {
                    $(target).removeClass('show');
                    $(this).attr('aria-expanded', 'false').addClass('collapsed');
                }
            });

            updateStatusIndukAktif();
            $('.sidebar .collapse-item.active').closest('.nav-item').find('> .nav-link[data-toggle="collapse"]').addClass('pulse-animation');


        });
    </script>

</body>

</html>