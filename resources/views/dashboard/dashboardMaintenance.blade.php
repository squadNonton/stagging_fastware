<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard Maintenance</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="{{ asset('assets/img/Logo-adasi-tab.png') }}" rel="icon">
    <link href="{{ asset('assets/img/Logo-adasi-tab.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="" class="logo d-flex align-items-center">
                <img src="assets/img/AdasiLogo.png" style="margin-left: 60px" alt="">
                <span class="d-none d-lg-block"></span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

    </header><!-- End Header -->

    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                <!-- CSRF token untuk keamanan -->
            </form>
            <b>
                <li class="nav-label">DMS Menu</h5>
            </b>
            @if(Auth::user()->role_id == 1)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#dashboard-admin-nav">
                    <i class="bi bi-person-circle"></i>
                    <span>Dashboard Admin</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="dashboard-admin-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a class="nav-link" href="{{ route('dashboardusers') }}">
                            <i class="bi bi-list-check"></i>
                            <span>Daftar Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('dashboardcustomers') }}">
                            <i class="bi bi-list-check"></i>
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
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="dashboard-menu-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a class="nav-link" href="{{ route('dashboardHandling') }}">
                            <i class="bi bi-list-check"></i>
                            <span>Dashboard Handling</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('dashboardMaintenance') }}">
                            <i class="bi bi-list-check"></i>
                            <span>Dashboard Maintenance</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-label">Production</li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#prod-forms-nav">
                    <i class="bi bi-journal-text"></i>
                    <span>Form Permintaan Perbaikan</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="prod-forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a class="nav-link" href="{{ route('fpps.index') }}">
                            <i class="bi bi-list-check"></i>
                            <span>Data Form FPP</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-label">Maintenance</li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#maint-received-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal-text"></i><span>Received FPP & Jadwal Preventive</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="maint-received-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ asset('dashboardmaintenance') }}">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <span>Data Received FPP</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link collapsed" href="">
                            <i class="bi bi-calendar"></i>
                            <span>Data Jadwal Preventive</span>
                        </a>
                    </li>  --}}
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('blokMaintanence') }}">
                            <i class="bi bi-check2"></i>
                            <span>Blok Jadwal Preventive</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Maint Received Nav -->
            <!-- End Prod Forms Nav -->
            <ul class="sidebar-nav">
                @if (Auth::check())
                @if (Auth::user()->role_id == 3 || Auth::user()->role_id == 1)
                {{-- Role ID untuk Maintenance --}}
                {{-- Tampilkan sidebar untuk Maintenance --}}
                <li class="nav-label">Dept. Maintenance</li>

                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#dept-maint-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-journal-text"></i><span>Mesin & Approve FPP</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="dept-maint-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('dashboardmesins') }}">
                                <i class="bi bi-gear"></i>
                                <span>Data Mesin</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('deptmtce.index') }}">
                                <i class="bi bi-check2"></i>
                                <span>Data Approved FPP</span>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ asset('deptmtcepreventive') }}">
                            <i class="bi bi-check2"></i>
                            <span>Data Jadwal Preventive</span>
                        </a>
                    </li> -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('blokDeptMaintenance') }}">
                                <i class="bi bi-check2"></i>
                                <span>Blok Jadwal Preventive</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Dept Maint Nav -->
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#dept-complain-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-journal-text"></i><span>Handling Claim dan Complain</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="dept-complain-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('submission') }}">
                                <i class="bi bi-circle"></i><span>Submission</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('showHistoryCLaimComplain') }}">
                                <i class="bi bi-circle"></i><span>History Complain & Claim</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('scheduleVisit') }}">
                                <i class="bi bi-circle"></i><span>Schedule Visit</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Dept Complain & Claim Nav -->
                @endif
                @if (Auth::user()->role_id == 2 || Auth::user()->role_id == 1)
                {{-- Role ID untuk Sales --}}
                {{-- Tampilkan sidebar untuk Sales --}}
                <li class="nav-label">Sales</li>
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#sales-fpp-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-journal-text"></i><span>Form Permintaan Perbaikan</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="sales-fpp-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('sales.index') }}">
                                <i class="bi bi-list-check"></i>
                                <span>Data Form FPP</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Sales FPP Nav -->
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-journal-text"></i><span>Handling Claim dan Complain</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('index') }}">
                                <i class="bi bi-circle"></i><span>Handling Sales</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('showHistoryCLaimComplain') }}">
                                <i class="bi bi-circle"></i><span>History Complain & Claim</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('scheduleVisit') }}">
                                <i class="bi bi-circle"></i><span>Schedule Visit</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Forms Nav -->
                @endif
                @endif
            </ul>
        </ul>
        <!-- Footer Sidebar -->
        <ul class="sidebar-nav fixed-bottom ps-3">
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </li><!-- End Logout Nav -->
        </ul>
        <!-- End Footer Sidebar -->
    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <section class="section dashboard">
            <div class="row">
                <h2 style="display: flex;
            justify-content: center;">List Form Permintaan Perbaikan</h2>
                <!-- Sales Card Today -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Open <span>| Today</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    @if ($openCount > 0)
                                    <i class="bi bi-cart-check-fill"></i>
                                    @else
                                    <i class="bi bi-cart"></i>
                                    @endif
                                </div>
                                <div class="ps-3">
                                    <h2>{{ $openCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Sales Card Today -->

                <!-- Sales Card This Month -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">On Progress <span>| Today</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    @if ($onProgressCount > 0)
                                    <i class="bi bi-arrow-repeat"></i>
                                    @else
                                    <i class="bi bi-cart"></i>
                                    @endif
                                </div>
                                <div class="ps-3">
                                    <h2>{{ $onProgressCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Sales Card This Month -->

                <!-- Revenue Card Today -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Finish <span>| Today</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    @if ($finishCount > 0)
                                    <i class="bi bi-check-circle-fill"></i>
                                    @else
                                    <i class="bi bi-currency-dollar"></i>
                                    @endif
                                </div>
                                <div class="ps-3">
                                    <h2>{{ $finishCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Revenue Card Today -->

                <!-- Revenue Card This Month -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Closed <span>| Today</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    @if ($closedCount > 0)
                                    <i class="bi bi-lock-fill"></i>
                                    @else
                                    <i class="bi bi-currency-dollar"></i>
                                    @endif
                                </div>
                                <div class="ps-3">
                                    <h2>{{ $closedCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Revenue Card This Month -->
            </div>



        </section>

    </main><!-- End #main -->

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Example JavaScript to simulate data update -->
    <script>
        // Function to update card based on data
        function updateCard(cardId, title, iconId, count) {
            document.getElementById(cardId + 'Title').textContent = title;
            document.getElementById(cardId + 'Icon').className = 'bi bi-' + iconId;
            document.getElementById(cardId + 'Count').textContent = count;
        }

        // Simulate data update every 5 seconds
        setInterval(function() {
            // Simulate new data
            var openCount = Math.floor(Math.random() * 100);
            var onProgressCount = Math.floor(Math.random() * 100);
            var finishCount = Math.floor(Math.random() * 100);
            var closedCount = Math.floor(Math.random() * 100);

            // Update each card
            updateCard('open', 'Open', 'cart', openCount);
            updateCard('onProgress', 'On Progress', 'clock', onProgressCount);
            updateCard('finish', 'Finish', 'currency-dollar', finishCount);
            updateCard('closed', 'Closed', 'check-circle', closedCount);
        }, 5000); // Update every 5 seconds
    </script>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>



<style>
    /* RESET RULES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    :root {
        --white: #fff;
        --divider: lightgrey;
        --body: #f5f7f8;
    }

    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    ul {
        list-style: none;
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    body {
        background: var(--body);
        font-size: 16px;
        font-family: sans-serif;
        padding-top: 40px;
    }

    .chart-wrapper {
        max-width: 1150px;
        padding: 0 10px;
        margin: 0 auto;
    }


    /* CHART-VALUES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    .chart-wrapper .chart-values {
        position: relative;
        display: flex;
        margin-bottom: 20px;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .chart-wrapper .chart-values li {
        flex: 1;
        min-width: 80px;
        text-align: center;
    }

    .chart-wrapper .chart-values li:not(:last-child) {
        position: relative;
    }

    .chart-wrapper .chart-values li:not(:last-child)::before {
        content: '';
        position: absolute;
        right: 0;
        height: 510px;
        border-right: 1px solid var(--divider);
    }


    /* CHART-BARS
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    .chart-wrapper .chart-bars li {
        position: relative;
        color: var(--white);
        margin-bottom: 15px;
        font-size: 16px;
        border-radius: 20px;
        padding: 10px 20px;
        width: 0;
        opacity: 0;
        transition: all 0.65s linear 0.2s;
    }

    @media screen and (max-width: 600px) {
        .chart-wrapper .chart-bars li {
            padding: 10px;
        }
    }

    /* FOOTER
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    .page-footer {
        font-size: 0.85rem;
        padding: 10px;
        text-align: right;
        color: var(--black);
    }

    .page-footer span {
        color: #e31b23;
    }
</style>

<script>
    function createChart(e) {
        const days = document.querySelectorAll(".chart-values li");
        const tasks = document.querySelectorAll(".chart-bars li");
        const daysArray = [...days];

        tasks.forEach(el => {
            const duration = el.dataset.duration.split("-");
            const startDay = duration[0];
            const endDay = duration[1];
            let left = 0,
                width = 0;

            if (startDay.endsWith("½")) {
                const filteredArray = daysArray.filter(day => day.textContent == startDay.slice(0, -1));
                left = filteredArray[0].offsetLeft + filteredArray[0].offsetWidth / 2;
            } else {
                const filteredArray = daysArray.filter(day => day.textContent == startDay);
                left = filteredArray[0].offsetLeft;
            }

            if (endDay.endsWith("½")) {
                const filteredArray = daysArray.filter(day => day.textContent == endDay.slice(0, -1));
                width = filteredArray[0].offsetLeft + filteredArray[0].offsetWidth / 2 - left;
            } else {
                const filteredArray = daysArray.filter(day => day.textContent == endDay);
                width = filteredArray[0].offsetLeft + filteredArray[0].offsetWidth - left;
            }

            // apply css
            el.style.left = `${left}px`;
            el.style.width = `${width}px`;
            if (e.type == "load") {
                el.style.backgroundColor = el.dataset.color;
                el.style.opacity = 1;
            }
        });
    }

    window.addEventListener("load", createChart);
    window.addEventListener("resize", createChart);
</script>