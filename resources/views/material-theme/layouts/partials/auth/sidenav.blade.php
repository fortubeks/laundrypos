<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand px-4 py-3 m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard "
            target="_blank">
            <img src="assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
            <span class="ms-1 text-sm text-dark">{{config('app.name')}}</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active bg-gradient-dark text-white" href="{{route('dashboard')}}">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#users" class="nav-link text-dark" aria-controls="projectsExamples" role="button" aria-expanded="true">
                    <i class="material-symbols-rounded opacity-5 {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">widgets</i>
                    <span class="nav-link-text ms-1 ps-1">Orders</span>
                </a>
                <div class="collapse" id="users">
                    <ul class="nav ">
                        <li class="nav-item ">
                            <a class="nav-link text-dark " href="{{route('orders.index')}}">
                                <span class="sidenav-mini-icon"> G </span>
                                <span class="sidenav-normal  ms-1  ps-1"> Orders </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-dark " href="{{route('orders.create')}}">
                                <span class="sidenav-mini-icon"> G </span>
                                <span class="sidenav-normal  ms-1  ps-1"> Create New </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{route('customers.index')}}">
                    <i class="material-symbols-rounded opacity-5">shopping_cart</i>
                    <span class="nav-link-text ms-1">Customers</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{route('laundry-items.index')}}">
                    <i class="material-symbols-rounded opacity-5">shopping_cart</i>
                    <span class="nav-link-text ms-1">Laundry Items</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{route('service-items.index')}}">
                    <i class="material-symbols-rounded opacity-5">shopping_cart</i>
                    <span class="nav-link-text ms-1">Service Items</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#">
                    <i class="material-symbols-rounded opacity-5">receipt_long</i>
                    <span class="nav-link-text ms-1">Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#">
                    <i class="material-symbols-rounded opacity-5">view_in_ar</i>
                    <span class="nav-link-text ms-1">Send Whatsapp Message</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Settings</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link text-dark" href="#">
                    <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
                    <span class="nav-link-text ms-1">Support</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#">
                    <i class="material-symbols-rounded opacity-5">person</i>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>

        </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
        <div class="mx-3">

        </div>
    </div>
</aside>