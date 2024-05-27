<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Astra Daido Steel Indonesia</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/logo-menu.png') }}" rel="icon">
    <link href="{{ asset('assets/img/logo-menu.png') }}" rel="apple-touch-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    {{-- datatable --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    {{-- sweet alert --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <!-- *Note: You must have internet connection on your laptop or pc other wise below code is not working -->
    <!-- CSS for full calender -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" />

    {{-- DatePicker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    {{-- Excel --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    {{-- PDF --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    {{-- ChartJS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- Select Search --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"
        integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css"
        integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

<body>

    <!-- Buat Header -->
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <h3 class="fw-bold mt-2 ps-5">DMS Adasi DS8</h3>
            <i class="bi bi-list toggle-sidebar-btn mx-5"></i>
        </div><!-- End Logo -->
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->

                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        <img src="assets/img/user.png" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block ps-2">{{ Auth::user()->name }} <br>
                            {{ Auth::user()->roles->role }}</span>
                    </a><!-- End Profile Image Icon -->
                </li><!-- End Profile Nav -->
            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                <!-- CSRF token untuk keamanan -->
            </form>
            <li class="nav-heading">Dashboard</li>
            <li class="nav-item">
            </li><!-- End Logout Nav -->
            @if (Auth::user()->role_id == 1)
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-toggle="collapse" href="#dashboard-admin-nav">
                        <i class="bi bi-person-circle"></i>
                        <span>Dashboard Admin</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="dashboard-admin-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                        <li>
                            <a class="nav-link collapsed" href="{{ route('dashboardusers') }}">
                                <i class="bi bi-list-check fs-6"></i>
                                <span>Daftar Pengguna</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link collapsed" href="{{ route('dashboardcustomers') }}">
                                <i class="bi bi-list-check fs-6"></i>
                                <span>Daftar Pelanggan</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#dashboard-menu-nav">
                    <i class="bi bi-gear"></i>
                    <span>Dashboard Menu</span>
                    <i class="bi bi-chevron-down ms-auto fs-6"></i>
                </a>
                <ul id="dashboard-menu-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a class="nav-link collapsed" href="{{ route('dashboardHandling') }}">
                            <i class="bi bi-list-check fs-6"></i>
                            <span>Dashboard DMS</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('showDataDiri') }}">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
            </li><!-- End Profile Page Nav -->
            <li class="nav-heading">Suggestion System</li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="">
                    <i class="bi bi-person"></i>
                    <span>Dashboard SS</span>
                </a>
            </li><!-- End Profile Page Nav -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('showSS') }}">
                    <i class="bi bi-journal-text fs-6"></i>
                    <span>Form SS</span>
                </a>
            </li><!-- End Profile Page Nav -->
            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 18)
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('showKonfirmasiForeman') }}">
                        <i class="bi bi-clipboard-check-fill"></i>
                        <span>Konfirmasi SS by Sec. Head</span>
                    </a>
                </li><!-- End Profile Page Nav -->
                @endif @if (Auth::user()->role_id == 1 ||
                        Auth::user()->role_id == 2 ||
                        Auth::user()->role_id == 7 ||
                        Auth::user()->role_id == 11 ||
                        Auth::user()->role_id == 14 ||
                        Auth::user()->role_id == 15)
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('showKonfirmasiDeptHead') }}">
                            <i class="bi bi-clipboard-check-fill"></i>
                            <span>Konfirmasi SS by Dept. Head</span>
                        </a>
                    </li><!-- End Profile Page Nav -->
                @endif
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{route ('showKonfirmasiKomite')}}">
                        <i class="bi-person-lines-fill"></i>
                        <span>PIC Penilai SS | Komite</span>
                    </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{route ('showKonfirmasiHRGA')}}">
                        <i class="bi-person-lines-fill"></i>
                        <span>PIC Penilai SS | HRGA</span>
                    </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-heading">Safety Patrol</li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('listpatrol') }}">
                        <i class="bi bi-person"></i>
                        <span>Form Safety Patrol</span>
                    </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('listpatrolpic') }}">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>PIC Form</span>
                    </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('reportpatrol') }}">
                        <i class="bi bi-bar-chart-steps"></i>
                        <span>Report Form Safety Patrol</span>
                    </a>
                </li><!-- End Profile Page Nav -->
                @if (Auth::check())
                    @if (Auth::user()->role_id == 7 ||
                            Auth::user()->role_id == 8 ||
                            Auth::user()->role_id == 9 ||
                            Auth::user()->role_id == 1)
                        <li class="nav-heading">Productions</li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#prod-forms-nav">
                                <i class="bi bi-journal-text fs-6"></i>
                                <span>Form Permintaan Perbaikan</span>
                                <i class="bi bi-chevron-down ms-auto fs-6"></i>
                            </a>
                            <ul id="prod-forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                                <li>
                                    <a class="nav-link collapsed" href="{{ route('fpps.index') }}">
                                        <i class="bi bi-list-check fs-6"></i>
                                        <span>Data Form FPP</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link collapsed" href="{{ route('fpps.history') }}">
                                        <i class="bi bi-list-check fs-6"></i>
                                        <span>Riwayat FPP</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (Auth::user()->role_id == 14)
                        <li class="nav-heading">Productions</li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#prod-forms-nav">
                                <i class="bi bi-journal-text"></i>
                                <span>Form Permintaan Perbaikan</span>
                                <i class="bi bi-chevron-down ms-auto"></i>
                            </a>
                            <ul id="prod-forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                                <li>
                                    <a class="nav-link collapsed" href="{{ route('sales.index') }}">
                                        <i class="bi bi-list-check fs-6"></i>
                                        <span>Data Form FPP</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link collapsed" href="{{ route('fpps.history') }}">
                                        <i class="bi bi-list-check fs-6"></i>
                                        <span>Riwayat FPP</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (Auth::user()->role_id == 6 || Auth::user()->role_id == 1)
                        <li class="nav-heading">Maintenance</li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-target="#maint-received-nav"
                                data-bs-toggle="collapse" href="#">
                                <i class="bi bi-journal-text"></i><span>Received FPP & Jadwal Preventive</span><i
                                    class="bi bi-chevron-down ms-auto"></i>
                            </a>
                            <ul id="maint-received-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                                <li class="nav-item">
                                    <a class="nav-link collapsed" href="{{ asset('dashboardmaintenance') }}">
                                        <i class="bi bi-file-earmark-text-fill fs-6"></i>
                                        <span>Data Received FPP</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link collapsed" href="{{ route('fpps.history') }}">
                                        <i class="bi bi-list-check fs-6"></i>
                                        <span>Riwayat FPP</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link collapsed"
                                        href="{{ route('dashboardPreventiveMaintenance') }}">
                                        <i class="bi bi-check2 fs-6"></i>
                                        <span>Tabel Preventif</span>
                                    </a>
                                </li>
                            </ul>
                        </li><!-- End Maint Received Nav -->
                    @endif
                    @if (Auth::user()->role_id == 14)
                        <li class="nav-heading">Maintenance</li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-target="#maint-received-nav"
                                data-bs-toggle="collapse" href="#">
                                <i class="bi bi-journal-text"></i><span>Received FPP & Jadwal Preventive</span><i
                                    class="bi bi-chevron-down ms-auto"></i>
                            </a>
                            <ul id="maint-received-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                                <li class="nav-item">
                                    <a class="nav-link collapsed" href="{{ route('ga.dashboardga') }}">
                                        <i class="bi bi-file-earmark-text-fill fs-6"></i>
                                        <span>Data Received FPP</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link collapsed" href="{{ route('fpps.history') }}">
                                        <i class="bi bi-list-check fs-6"></i>
                                        <span>Riwayat FPP</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link collapsed"
                                        href="{{ route('dashboardPreventiveMaintenanceGA') }}">
                                        <i class="bi bi-check2 fs-6"></i>
                                        <span>Tabel Preventif</span>
                                    </a>
                                </li>
                            </ul>
                        </li><!-- End Maint Received Nav -->
                    @endif
                    <!-- End Prod Forms Nav -->
                    <ul class="sidebar-nav">
                        @if (Auth::user()->role_id == 5 || Auth::user()->role_id == 1)
                            {{-- Role ID untuk Maintenance --}}
                            {{-- Tampilkan sidebar untuk Maintenance --}}
                            <li class="nav-heading">Engineering</li>

                            <li class="nav-item">
                                <a class="nav-link collapsed" data-bs-target="#dept-maint-nav"
                                    data-bs-toggle="collapse" href="#">
                                    <i class="bi bi-journal-text"></i><span>Mesin & Approve FPP</span><i
                                        class="bi bi-chevron-down ms-auto"></i>
                                </a>
                                <ul id="dept-maint-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="{{ route('dashboardmesins') }}">
                                            <i class="bi bi-gear fs-6"></i>
                                            <span>Data Mesin</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="{{ route('deptmtce.index') }}">
                                            <i class="bi bi-check2 fs-6"></i>
                                            <span>Data Approved FPP</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="nav-link collapsed" href="{{ route('fpps.history') }}">
                                            <i class="bi bi-list-check fs-6"></i>
                                            <span>Riwayat FPP</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="{{ route('dashboardPreventive') }}">
                                            <i class="bi bi-check2 fs-6"></i>
                                            <span>Tabel Preventif</span>
                                        </a>
                                    </li>
                                </ul>
                            </li><!-- End Dept Maint Nav -->
                            <li class="nav-item">
                                <a class="nav-link collapsed" data-bs-target="#dept-complain-nav"
                                    data-bs-toggle="collapse" href="#">
                                    <i class="bi bi-journal-text"></i><span>Handling Klaim dan Komplain</span><i
                                        class="bi bi-chevron-down ms-auto"></i>
                                </a>
                                <ul id="dept-complain-nav" class="nav-content collapse "
                                    data-bs-parent="#sidebar-nav">
                                    <li>
                                        <a href="{{ route('submission') }}">
                                            <i class="bi bi-list-check fs-6"></i><span>Form Tindak Lanjut</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('showHistoryCLaimComplain') }}">
                                            <i class="bi bi-list-check fs-6"></i><span>Riwayat Klaim & Komplain</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('scheduleVisit') }}">
                                            <i class="bi bi-list-check fs-6"></i><span>Jadwal Kunjungan</span>
                                        </a>
                                    </li>
                                </ul>
                            </li><!-- End Dept Complain & Claim Nav -->
                        @endif
                        @if (Auth::user()->role_id == 14)
                            {{-- Role ID untuk GA --}}
                            {{-- Tampilkan sidebar untuk Engineering --}}
                            <li class="nav-heading">Engineering</li>

                            <li class="nav-item">
                                <a class="nav-link collapsed" data-bs-target="#dept-maint-nav"
                                    data-bs-toggle="collapse" href="#">
                                    <i class="bi bi-journal-text fs-6"></i><span>Mesin & Approve FPP</span>
                                </a>
                                <ul id="dept-maint-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="{{ route('dashboardgamesin') }}">
                                            <i class="bi bi-gear fs-6"></i>
                                            <span>Data Mesin</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="{{ route('ga.approvedfpp') }}">
                                            <i class="bi bi-check2 fs-6"></i>
                                            <span>Data Approved FPP</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="nav-link collapsed" href="{{ route('fpps.history') }}">
                                            <i class="bi bi-list-check fs-6"></i>
                                            <span>Riwayat FPP</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed"
                                            href="{{ route('dashboardPreventiveMaintenanceGA') }}">
                                            <i class="bi bi-check2 fs-6"></i>
                                            <span>Tabel Preventif</span>
                                        </a>
                                    </li>
                                </ul>
                            </li><!-- End Dept Maint Nav -->
                            <li class="nav-item">
                                <a class="nav-link collapsed" data-bs-target="#dept-complain-nav"
                                    data-bs-toggle="collapse" href="#">
                                    <i class="bi bi-journal-text"></i><span>Handling Klaim dan Komplain</span><i
                                        class="bi bi-chevron-down ms-auto"></i>
                                </a>
                                <ul id="dept-complain-nav" class="nav-content collapse "
                                    data-bs-parent="#sidebar-nav">
                                    <li>
                                        <a href="{{ route('submission') }}">
                                            <i class="bi bi-list-check fs-6"></i><span>Form Tindak Lanjut</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('showHistoryCLaimComplain') }}">
                                            <i class="bi bi-list-check fs-6"></i><span>Riwayat Klaim & Komplain</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('scheduleVisit') }}">
                                            <i class="bi bi-list-check fs-6"></i><span>Jadwal Kunjungan</span>
                                        </a>
                                    </li>
                                </ul>
                            </li><!-- End Dept Complain & Claim Nav -->
                        @endif
                        @if (Auth::user()->role_id == 1 ||
                                Auth::user()->role_id == 2 ||
                                Auth::user()->role_id == 3 ||
                                Auth::user()->role_id == 4 ||
                                Auth::user()->role_id == 11 ||
                                Auth::user()->role_id == 12 ||
                                Auth::user()->role_id == 13 ||
                                Auth::user()->role_id == 14)
                            {{-- Role ID untuk Sales --}}
                            <li class="nav-heading">Sales</li>
                            <li class="nav-item">
                                <a class="nav-link collapsed" data-bs-target="#sales-fpp-nav"
                                    data-bs-toggle="collapse" href="#">
                                    <i class="bi bi-journal-text"></i><span>Form Permintaan Perbaikan</span><i
                                        class="bi bi-chevron-down ms-auto"></i>
                                </a>
                                <ul id="sales-fpp-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="{{ route('sales.index') }}">
                                            <i class="bi bi-list-check fs-6"></i>
                                            <span>Data Form FPP</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="nav-link collapsed" href="{{ route('fpps.history') }}">
                                            <i class="bi bi-list-check fs-6"></i>
                                            <span>Riwayat FPP</span>
                                        </a>
                                    </li>
                                </ul>
                            </li><!-- End Sales FPP Nav -->
                            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse"
                                href="#">
                                <i class="bi bi-journal-text"></i><span>Handling Klaim dan Komplain</span><i
                                    class="bi bi-chevron-down ms-auto"></i>
                            </a>
                            <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                                <li>
                                    <a href="{{ route('index') }}">
                                        <i class="bi bi-list-check fs-6"></i><span>Form Pengajuan Klaim dan
                                            Komplain</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('showHistoryCLaimComplain') }}">
                                        <i class="bi bi-list-check fs-6"></i><span>Riwayat Klaim dan Komplain</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('scheduleVisit') }}">
                                        <i class="bi bi-list-check fs-6"></i><span>Jadwal Kunjungan</span>
                                    </a>
                                </li>
                            </ul>
                            </li><!-- End Forms Nav -->
                        @endif
                @endif
                <hr>
                <li class="nav-heading">LOGOUT</li>
                <li class="nav-item mb-5">
                    <a class="nav-link collapsed" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Logout</span>
                    </a>
                </li><!-- End Profile Page Nav -->
        </ul>
        </ul>
        <!-- Footer Sidebar -->
        <ul class="sidebar-nav fixed-bottom ps-3">

        </ul>
        <!-- End Footer Sidebar -->
    </aside><!-- End Sidebar-->

    @yield('content');

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>ADASI</span></strong>. All Rights Reserved
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- JS Search DropDown --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    {{-- datatable --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    {{-- searchdropdownJS --}}
    <!-- Tambahkan library Select2 -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> --}}
    {{-- JSSweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    {{-- Datatble --}}
    <script src="js/datatables-simple-demo.js"></script>

    {{-- DateRangePicker --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS for jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- JS for full calender -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Menghubungkan ke jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    @yield('scripts')
    <script>
        //datepickerExcel
        // Fungsi untuk mendapatkan nilai tanggal dari input dan mengatur tautan tombol eksport
        // Mengambil nilai tanggal mulai dan tanggal selesai
        function exportToExcel() {
            // Mengambil nilai tanggal mulai dan tanggal selesai
            var startDate = document.getElementById("start-date").value;
            var endDate = document.getElementById("end-date").value;

            // Memeriksa apakah kedua tanggal sudah dipilih
            if (!startDate || !endDate) {
                // Menampilkan pesan SweetAlert untuk validasi
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Select the start date and end date first'
                });
                return; // Berhenti dari fungsi jika salah satu atau kedua tanggal belum dipilih
            }

            // Mengambil semua baris data dari tabel
            var tableRows = document.querySelectorAll("tbody tr");

            // Membuat objek workbook Excel
            var wb = XLSX.utils.book_new();
            var ws_name = "Data"; // Nama sheet Excel

            // Membuat array untuk menyimpan data
            var data = [];

            // Mengambil header dari tabel untuk judul kolom
            var tableHeader = [];
            document.querySelectorAll("thead th").forEach(function(th) {
                tableHeader.push(th.textContent.trim());
            });
            data.push(tableHeader);

            // Melakukan iterasi melalui setiap baris tabel
            tableRows.forEach(function(row) {
                // Mengambil tanggal due_date dari kolom 'Due Date'
                var dueDate = row.cells[19].innerText.trim();

                // Memeriksa apakah tanggal 'Due Date' berada dalam rentang yang dipilih
                if (dueDate >= startDate && dueDate <= endDate) {
                    // Jika dalam rentang tanggal, tambahkan data baris ke dalam array
                    var rowData = [];
                    row.querySelectorAll('td').forEach(function(cell) {
                        rowData.push(cell.innerText.trim());
                    });
                    data.push(rowData);
                }
            });

            // Membuat worksheet Excel dari data yang dipilih
            var ws = XLSX.utils.aoa_to_sheet(data);

            // Menambahkan header autofilter
            ws['!autofilter'] = {
                ref: XLSX.utils.encode_range(XLSX.utils.decode_range(ws['!ref']))
            };

            // Menambahkan worksheet ke dalam workbook
            XLSX.utils.book_append_sheet(wb, ws, ws_name);

            // Membuat file Excel dari workbook
            XLSX.writeFile(wb, 'History_Claim_Complain.xlsx');
        }

        // Menentukan objek jsPDF di window
        window.jsPDF = window.jspdf.jsPDF;

        // EksportPDF
        document.addEventListener('DOMContentLoaded', function() {
            // Kode JavaScript Anda di sini akan dijalankan setelah seluruh dokumen HTML telah dimuat

            // Misalnya, Anda dapat menambahkan kode untuk menangani klik tombol export PDF di sini:
            document.querySelectorAll('.export-pdf-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    var rowId = this.getAttribute(
                        'data-row-id'); // Mendapatkan ID baris dari atribut data-row-id tombol
                    exportRowToPDF(
                        rowId); // Memanggil fungsi exportRowToPDF dengan ID baris yang diberikan
                });
            });
        });

        function exportRowToPDF(rowId) {
            // Logika untuk mengekstrak data dari baris yang ditentukan menggunakan rowId
            var rowData = getRowDataById(rowId);

            // Logika untuk memformat rowData dan membuat dokumen PDF
            var doc = new jsPDF();

            // Menambahkan judul
            doc.setFontSize(22);
            doc.setTextColor(44, 62, 80); // Warna judul
            doc.text("History Claim & Complain", 105, 20, {
                align: "center"
            });

            // Menambahkan garis pembatas
            doc.setLineWidth(0.5);
            doc.setDrawColor(44, 62, 80); // Warna garis pembatas
            doc.line(20, 25, 190, 25);

            // Menambahkan data ke dokumen PDF
            doc.setFontSize(12);
            doc.setTextColor(44, 62, 80); // Warna teks
            var startY = 35;
            var lineHeight = 10;
            var marginLeft = 20;
            rowData.forEach(function(data, index) {
                doc.text(marginLeft, startY + index * lineHeight, data);
            });

            // Simpan dokumen PDF
            doc.save("invoice.pdf");
        }

        function getRowDataById(rowId) {
            console.log("Row ID:", rowId); // Tambah consol log untuk rowId
            // Logika untuk mengekstrak data dari baris yang ditentukan berdasarkan ID-nya
            // Anda dapat menggunakan metode manipulasi DOM untuk mengambil data dari baris tabel dengan ID yang diberikan
            // Contoh:
            var row = document.getElementById("row_" + rowId);
            console.log("Row Element:", row); // Tambah consol log untuk row
            var rowData = [];
            // Ekstrak data dari setiap sel dalam baris
            row.querySelectorAll('td').forEach(function(cell) {
                rowData.push(cell.innerText.trim());
            });
            return rowData;
        }


        // imageModal
        $(document).ready(function() {
            $('.clickable-image').click(function() {
                var imageUrl = $(this).attr('src');
                $('#modalImage').attr('src', imageUrl);
                $('#imageModal').modal('show');
            });
        });

        function SaveAndUpdate() {
            // Lakukan sesuatu saat tombol "Save" atau "Finish" ditekan
            // Contoh: Validasi form, kemudian kirimkan data melalui AJAX jika valid
            // Untuk contoh sederhana, saya hanya menampilkan pesan
            alert('Save or Finish button clicked');
        }

        function FinishAndUpdate() {
            // Lakukan sesuatu saat tombol "Back" ditekan
            // Contoh: Kembali ke halaman sebelumnya atau lakukan navigasi lainnya
            // Untuk contoh sederhana, saya hanya menampilkan pesan
            alert('Back button clicked');
        }
        //sweetalertSave
        function validateAndSubmit() {
            var formData = new FormData(document.getElementById('formInputHandling'));

            var no_wo = formData.get('no_wo');
            var image = formData.get('image');
            var customerName = formData.get('name_customer');
            var customerCode = formData.get('customer_id');
            var area = formData.get('area');
            var qty = formData.get('qty');
            var pcs = formData.get('pcs');
            var category = formData.get('category');
            var process_type = formData.get('process_type');
            var type_1 = formData.getAll('type_1');

            // Memeriksa apakah ada input yang kosong
            if (!no_wo || !image || !customerName || !customerCode || !area || !qty || !pcs || !category || !process_type ||
                type_1.length === 0) {
                // Menampilkan sweet alert error jika ada input yang kosong
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please fill all the fields before saving.',
                });
            } else {
                // Simulasi validasi
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Data has been saved successfully.',
                    showConfirmButton: false
                });
            }
        }

        //datatabelSales
        $(document).ready(function() {
            new DataTable('#viewSales');

        });

        //datatableSubmision
        $(document).ready(function() {
            new DataTable('table.display');
        });

        //COnfrimDelete
        document.addEventListener('DOMContentLoaded', function() {
            // Menggunakan event listener untuk menangkap klik pada tombol delete
            document.querySelectorAll('.delete-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Menampilkan SweetAlert untuk konfirmasi penghapusan
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You sure delete this data?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika pengguna menekan tombol konfirmasi pada SweetAlert,
                            // maka arahkan ke rute penghapusan
                            window.location.href = button.getAttribute(
                                'data-url');
                        }
                    });
                });
            });
        });

        // //jsDropdownCLaim-cutting
        document.getElementById('process_type').addEventListener('change', function() {
            var dropdownValue = this.value;
            var checkBox1 = document.getElementById('type_2');

            if (dropdownValue === 'Cutting') {
                checkBox1.checked = true;
            } else {
                checkBox1.checked = false;
            }
        });

        //RefreshFromPageInputCreateHandling
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            // Check if the page was accessed from the index page
            const fromIndex = document.referrer.includes("index");

            if (fromIndex) {
                // Set initial values for form elements if coming from index page
                resetFormValues();
            }

            const resetButton = document.querySelector('button[type="reset"]');

            resetButton.addEventListener('click', function() {
                // Reset values to default or empty
                resetFormValues();

                // Hide cancel upload button and error message
                document.getElementById('cancelUpload').style.display = 'none';
                document.getElementById('fileError').style.display = 'none';
            });

            function resetFormValues() {
                // Reset values to default or empty for text inputs
                const textInputs = form.querySelectorAll('input[type="text"]');
                textInputs.forEach(input => {
                    input.value = '';
                });

                // Reset selected values for dropdowns
                const selects = form.querySelectorAll('select');
                selects.forEach(select => {
                    select.value = '';
                });

                // Reset checkboxes to default state (checked or unchecked)
                const checkboxes = form.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = checkbox.defaultChecked;
                });
            }
        });
        // readimageform
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);

            // Show the preview image div
            document.getElementById('imagePreview').style.display = 'block';
        }

        //upload fileJS
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('formFile');
            const cancelUploadButton = document.getElementById('cancelUpload');
            const fileError = document.getElementById('fileError');

            fileInput.addEventListener('change', handleFileSelection);
            cancelUploadButton.addEventListener('click', cancelFileUpload);

            function handleFileSelection() {
                const allowedFormats = ['image/jpeg', 'image/png',
                    'image/gif'
                ]; // Add more formats if needed
                const selectedFile = fileInput.files[0];

                if (selectedFile) {
                    if (allowedFormats.includes(selectedFile.type)) {
                        // Valid image format
                        fileError.style.display = 'none';
                        cancelUploadButton.style.display = 'inline-block';
                    } else {
                        // Invalid image format
                        fileError.style.display = 'block';
                        cancelUploadButton.style.display = 'none';
                        resetFileInput();
                    }
                }
            }


            function cancelFileUpload() {
                resetFileInput();
                fileError.style.display = 'none';
                cancelUploadButton.style.display = 'none';

                // Hide the preview image div
                document.getElementById('imagePreview').style.display = 'none';
                // Hide the cancel upload button
                document.getElementById('cancelUpload').style.display = 'none';
                // Clear the file input value
                document.getElementById('formFile').value = '';
            }

            function resetFileInput() {
                // Reset file input by cloning and replacing it
                const newFileInput = fileInput.cloneNode(true);
                fileInput.parentNode.replaceChild(newFileInput, fileInput);
                newFileInput.addEventListener('change', handleFileSelection);
            }
        });

        //reset
        document.addEventListener('DOMContentLoaded', function() {
            const resetButton = document.querySelector('button[type="submit"][name="reset"]');
            const form = document.querySelector('form');

            resetButton.addEventListener('click', function() {
                // Reset values to default or empty
                form.reset();

                // Reset checkboxes to default state (checked or unchecked)
                const checkboxes = form.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = checkbox.defaultChecked;
                });

                // Hide cancel upload button and error message
                document.getElementById('cancelUpload').style.display = 'none';
                document.getElementById('fileError').style.display = 'none';
            });
        });

        //backButonSales
        function goToIndex() {
            window.location.href = "{{ route('index') }}"; // Ganti 'index' dengan nama rute halaman index Anda
        }

        // searchdropdown
        // Inisialisasi Select2 pada semua dropdown dengan class "select2"
        $(document).ready(function() {
            $('.select2').select2();
        });

        //backButonDeptMan
        function goToSubmission() {
            window.location.href =
                "{{ route('submission') }}"; // Ganti 'index' dengan nama rute halaman index Anda
        }

        // searchdropdown
        // Inisialisasi Select2 pada semua dropdown dengan class "select2"
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    <style>
        #footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            box-shadow: 0px -5px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        #footer .copyright,
        #footer .credits {
            color: #343a40;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all the accordion elements
            var accordions = document.querySelectorAll('.accordion');

            // Add click event listener to each accordion
            accordions.forEach(function(accordion) {
                // Toggle the 'show' class on collapse element when the accordion title is clicked
                accordion.querySelector('.card-title').addEventListener('click', function() {
                    accordion.querySelector('.collapse').classList.toggle('show');
                });
            });
        });
    </script>


</body>

</html>
