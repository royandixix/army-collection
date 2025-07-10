{{-- <!-- Main Content -->
<div class="cx-main-content">
    <div class="cx-breadcrumb">
        <div class="cx-page-title">
            <h5>Dashboard</h5>
            <ul>
                <li><a href="index.html">Corex</a></li>
                <li><a href="index.html">Modern</a></li>
                <li>Dashboard</li>
            </ul>
        </div>
        <div class="cx-tools">
            <a href="javascript:void(0)" class="refresh" data-bs-toggle="tooltip" aria-label="Refresh"
                data-bs-original-title="Refresh"><i class="ri-refresh-line"></i></a>
            <div class="filter m-l-10">
                <div class="dropdown" data-bs-toggle="tooltip" data-bs-original-title="Filter">
                    <a class="dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-sound-module-line"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Deal</a></li>
                        <li><a class="dropdown-item" href="#">Revenue</a></li>
                        <li><a class="dropdown-item" href="#">Expense</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12">
            <div class="cx-statistics">
                <div class="owl-carousel label-cards">
                    <div class="cx-card card-1 cx-label-card">
                        <div class="cx-card-content">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h5>Users</h5>
                                    <h4>56.2k</h4>
                                </div>
                                <span class="icon"><i class="ri-exchange-dollar-line"></i></span>
                            </div>
                            <p class="card-groth up">
                                <i class="ri-arrow-up-line"></i>
                                9%
                                <span>Last Month</span>
                            </p>
                            <div class="mini-chart">
                                <div id="userNumbers"></div>
                            </div>
                        </div>
                    </div>
                    <div class="cx-card card-2 cx-label-card">
                        <div class="cx-card-content">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h5>Campaign</h5>
                                    <h4>$98k</h4>
                                </div>
                                <span class="icon"><i class="ri-shield-user-line"></i></span>
                            </div>
                            <p class="card-groth up">
                                <i class="ri-arrow-up-line"></i>
                                25%
                                <span>Last Month</span>
                            </p>
                            <div class="mini-chart">
                                <div id="campaignNumbers"></div>
                            </div>
                        </div>
                    </div>
                    <div class="cx-card card-3 cx-label-card">
                        <div class="cx-card-content">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h5>Lead</h5>
                                    <h4>76%</h4>
                                </div>
                                <span class="icon"><i class="ri-shopping-bag-3-line"></i>
                                </span>
                            </div>
                            <p class="card-groth down">
                                <i class="ri-arrow-down-line"></i>
                                .5%
                                <span>Last Month</span>
                            </p>
                            <div class="mini-chart">
                                <div id="leadNumbers"></div>
                            </div>
                        </div>
                    </div>
                    <div class="cx-card card-4 cx-label-card">
                        <div class="cx-card-content">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h5>Revenue</h5>
                                    <h4>$84k</h4>
                                </div>
                                <span class="icon"><i class="ri-money-dollar-circle-line"></i></span>
                            </div>
                            <p class="card-groth down">
                                <i class="ri-arrow-down-line"></i>
                                2.1%
                                <span>Last Month</span>
                            </p>
                            <div class="mini-chart">
                                <div id="revenueNumbers"></div>
                            </div>
                        </div>
                    </div>
                    <div class="cx-card card-5 cx-label-card">
                        <div class="cx-card-content">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h5>Expenses</h5>
                                    <h4>$25k</h4>
                                </div>
                                <span class="icon"><i class="ri-exchange-dollar-line"></i></span>
                            </div>
                            <p class="card-groth up">
                                <i class="ri-arrow-up-line"></i>
                                9%
                                <span>Last Month</span>
                            </p>
                            <div class="mini-chart">
                                <div id="expensesNumbers"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-8">
            <div class="col-md-12">
                <div class="cx-card revenue-overview">
                    <div class="cx-card-header border-0">
                        <h4 class="cx-card-title">Overview</h4>
                        <div class="header-tools">
                            <div class="cx-date-range date" title="Date">
                                <span></span>
                            </div>
                        </div>
                        <div class="name"></div>
                    </div>
                    <div class="cx-card-content">
                        <div class="cx-chart-header">
                            <div class="block">
                                <h6>Orders</h6>
                                <h5>825
                                    <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
                                </h5>
                            </div>
                            <div class="block">
                                <h6>Revenue</h6>
                                <h5>$89k
                                    <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
                                </h5>
                            </div>
                            <div class="block">
                                <h6>Expense</h6>
                                <h5>$68k
                                    <span class="down"><i class="ri-arrow-down-line"></i>24%</span>
                                </h5>
                            </div>
                            <div class="block">
                                <h6>Profit</h6>
                                <h5>$21k
                                    <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
                                </h5>
                            </div>
                        </div>
                        <div class="cx-chart-content">
                            <div id="overviewChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="cx-card revenue-overview">
                    <div class="cx-card-header">
                        <h4 class="cx-card-title">Deals</h4>
                        <div class="header-tools">
                            <div class="cx-date-range dots">
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="cx-card-content card-default">
                        <div class="deal-table">
                            <div class="table-responsive">
                                <table id="lead-data-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Team</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="token">2650</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="cat-thumb" src="assets/img/clients/1.jpg" alt="clients Image">
                                                    <span class="name">Monsy Pvt.</span>
                                                </span>
                                            </td>
                                            <td>15/01/2023</td>
                                            <td>Zara nails</td>
                                            <td class="active">ACTIVE</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">2650</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="cat-thumb" src="assets/img/clients/2.jpg"
                                                        alt="clients Image">
                                                    <span class="name">Capital Mines Pvt.</span>
                                                </span>
                                            </td>
                                            <td>15/01/2023</td>
                                            <td>Zara nails</td>
                                            <td class="pending">Pending</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">2365</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="cat-thumb" src="assets/img/clients/3.jpg"
                                                        alt="clients Image">
                                                    <span class="name">Myras infitech.</span>
                                                </span>
                                            </td>
                                            <td>02/08/2022</td>
                                            <td>Olive Yew</td>
                                            <td class="close">Close</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">2205</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="cat-thumb" src="assets/img/clients/4.jpg"
                                                        alt="clients Image">
                                                    <span class="name">Gujarat agro LLP.</span>
                                                </span>
                                            </td>
                                            <td>23/02/2023</td>
                                            <td>Allie Grater</td>
                                            <td class="success">Success</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">2187</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="cat-thumb" src="assets/img/clients/5.jpg"
                                                        alt="clients Image">
                                                    <span class="name">KK Food LLC.</span>
                                                </span>
                                            </td>
                                            <td>17/01/2022</td>
                                            <td>Stanley Knife</td>
                                            <td class="active">ACTIVE</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">2050</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="cat-thumb" src="assets/img/clients/6.jpg"
                                                        alt="clients Image">
                                                    <span class="name">Maruti agro.</span>
                                                </span>
                                            </td>
                                            <td>09/09/2022</td>
                                            <td>Zara nails</td>
                                            <td class="close">Close</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">1995</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="cat-thumb" src="assets/img/clients/7.jpg"
                                                        alt="clients Image">
                                                    <span class="name">Monsy pvt.</span>
                                                </span>
                                            </td>
                                            <td>11/08/2022</td>
                                            <td>Ivan Itchinos</td>
                                            <td class="success">success</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">1985</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="cat-thumb" src="assets/img/clients/8.jpg"
                                                        alt="clients Image">
                                                    <span class="name">Trinity info.</span>
                                                </span>
                                            </td>
                                            <td>19/12/2021</td>
                                            <td>Wiley Waites</td>
                                            <td class="success">success</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">1945</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="cat-thumb" src="assets/img/clients/9.jpg"
                                                        alt="clients Image">
                                                    <span class="name">Miletone Gems.</span>
                                                </span>
                                            </td>
                                            <td>06/05/2021</td>
                                            <td>Sarah Moanees</td>
                                            <td class="pending">pending</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">1865</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="cat-thumb" src="assets/img/clients/10.jpg"
                                                        alt="clients Image">
                                                    <span class="name">Lightbeam Pvt.</span>
                                                </span>
                                            </td>
                                            <td>01/01/2021</td>
                                            <td>Anne Ortha</td>
                                            <td class="active">ACTIVE</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="cx-card revenue-overview">
                        <div class="cx-card-header border-0">
                            <h4 class="cx-card-title">Sessions</h4>
                            <div class="header-tools">
                                <div class="header-tools">
                                    <div class="dropdown" title="Settings">
                                        <a class="dropdown-toggle icon" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Today</a></li>
                                            <li><a class="dropdown-item" href="#">Yesterday</a></li>
                                            <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                                            <li><a class="dropdown-item" href="#">This Month</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cx-card-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="cx-chart-content">
                                        <div id="sessionsChart"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <ul class="cx-chart-detail">
                                        <li><span class="name">Firewalls - 5</span> <span
                                                class="data">10/100%</span></li>
                                        <li><span class="name">Servers - 535</span> <span
                                                class="data">58/100%</span></li>
                                        <li><span class="name">Request - 548k</span> <span
                                                class="data">67/100%</span></li>
                                        <li><span class="name">Response - 530k</span> <span
                                                class="data">59/100%</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="cx-card revenue-overview">
                        <div class="cx-card-header border-0">
                            <h4 class="cx-card-title">Activity</h4>
                            <div class="header-tools">
                                <a href="#" class="link icon" title="View More">
                                    <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>
                        <div class="sub-card-body">
                            <div class="activity-list">
                                <ul>
                                    <li>
                                        <span class="date-time"><span class="date">8 Thu</span><span
                                                class="time">11:30 AM - 05:10 PM
                                            </span></span>
                                        <p class="title">Project Submitted from Smith</p>
                                        <p class="detail">Lorem Ipsum is simply dummy text of the printing
                                            and lorem is industry.</p>
                                    </li>
                                    <li>
                                        <span class="date-time warn"><span class="date">7 Wed</span><span
                                                class="time">1:30 PM - 02:30 PM
                                            </span></span>
                                        <p class="title">Morgus pvt - project due</p>
                                        <p class="detail">Project modul delay for some bugs.</p>
                                    </li>
                                    <li>
                                        <span class="date-time"><span class="date">6 Tue</span><span
                                                class="time">9:30 AM - 11:00 AM
                                            </span></span>
                                        <p class="title">Interview for management dept.</p>
                                        <p class="detail">There are many variations of passages of Lorem
                                            Ipsum available.</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4">
            <div class="cx-sticky">
                <div class="row">
                    <div class="col-xxl-12 col-xl-6 col-md-12">
                        <div class="sub-card m-b-30">
                            <div class="cx-card-header border-0">
                                <h4 class="cx-card-title">Profit</h4>
                                <div class="header-tools">
                                    <div class="dropdown" title="Settings">
                                        <a class="dropdown-toggle icon" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Today</a></li>
                                            <li><a class="dropdown-item" href="#">Yesterday</a></li>
                                            <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                                            <li><a class="dropdown-item" href="#">This Month</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-card-body">
                                <div class="cx-chart-content mt-m-24">
                                    <div id="profitChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-xl-6 col-md-12">
                        <div class="sub-card m-b-30">
                            <div class="cx-card-header border-0">
                                <h4 class="cx-card-title">Campaigns</h4>
                                <div class="header-tools">
                                    <div class="dropdown" title="Settings">
                                        <a class="dropdown-toggle icon" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Today</a></li>
                                            <li><a class="dropdown-item" href="#">Yesterday</a></li>
                                            <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                                            <li><a class="dropdown-item" href="#">This Month</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-card-body">
                                <div class="cx-chart-content">
                                    <div id="campaignsChart" class="mb-m-20"></div>
                                </div>
                                <div class="cx-chart-header-2">
                                    <div class="block">
                                        <h6>Reached</h6>
                                        <h5><span class="up">96%<i class="ri-arrow-up-line"></i></span>825k</h5>
                                    </div>
                                    <div class="block">
                                        <h6>Expense</h6>
                                        <h5><span class="down">96%<i class="ri-arrow-down-line"></i></span>$84k
                                        </h5>
                                    </div>
                                    <div class="block">
                                        <h6>Deals</h6>
                                        <h5><span class="up">72%<i class="ri-arrow-up-line"></i></span>2.5k</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-xl-6 col-md-12">
                        <div class="sub-card m-b-30">
                            <div class="cx-card-header border-0">
                                <h4 class="cx-card-title">Events</h4>
                                <div class="header-tools">
                                    <a href="#" class="link icon" title="View More"><i
                                            class="ri-arrow-right-line"></i></a>
                                </div>
                            </div>
                            <div class="sub-card-body">
                                <div class="cx-calendar">
                                    <div id="eventCalendar" class="calendar-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-xl-6 col-md-12">
                        <div class="sub-card m-b-30">
                            <div class="cx-card-header border-0">
                                <h4 class="cx-card-title">Todo</h4>
                                <div class="header-tools">
                                    <div class="dropdown" title="Settings">
                                        <a class="dropdown-toggle icon" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Rename</a></li>
                                            <li><a class="dropdown-item" href="#">Add note</a></li>
                                            <li><a class="dropdown-item" href="#">Remove</a></li>
                                            <li><a class="dropdown-item" href="#">edit note</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-card-body">
                                <div class="todo-list">
                                    <form>
                                        <div class="form-group">
                                            <input type="checkbox" id="todo1" name="todo" value="todo">
                                            <label for="todo1">
                                                <span>Schedule team meeting for the next admin project and
                                                    assign
                                                    task.</span>
                                                <span class="date-time">6:30AM, Today</span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" id="todo2" name="todo" value="todo" checked>
                                            <label for="todo2">
                                                <span>Create employee list with role for upcoming event.</span>
                                                <span class="date-time">8AM, Today</span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" id="todo3" name="todo" value="todo">
                                            <label for="todo3">
                                                <span>Meeting with lorence company for marketing and
                                                    campaign.</span>
                                                <span class="date-time">10:45AM, Today</span>
                                            </label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}