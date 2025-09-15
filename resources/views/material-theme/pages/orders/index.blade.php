@extends('material-theme.layouts.app')
@section('content')
<div class="container-fluid py-2">

    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">
                            Orders
                            <p class="text-sm mb-0">
                                View all the orders from here.
                            </p>
                        </h6>
                    </div>
                    <div class="d-sm-flex align-items-center mt-3">
                        <div class="pe-3" style="width: 90%;">
                            <div class="">
                                <form id="userSearchForm" action="{{ route('orders.index') }}" method="GET">
                                    <div class="input-group input-group-outline">
                                        <label id="query" class="form-label">Search here...</label>
                                        <input name="query" type="text" class="form-control" onfocus="focused(this)" onfocusout="defocused(this)" id="userSearchInput">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <a href="{{route('orders.create')}}" class="btn bg-gradient-dark btn-sm mb-0">+&nbsp; New Order</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Items</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                <!-- <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Last Login</th>
                                <th class="text-secondary opacity-7"></th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="customCheck1">
                                        </div>
                                        <p class="text-xs font-weight-normal ms-2 mb-0">#10421</p>
                                    </div>
                                </td>
                                <td class="font-weight-normal">
                                    <span class="my-2 text-xs">1 Nov, 10:20 AM</span>
                                </td>
                                <td class="text-xs font-weight-normal">
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-2 btn-sm d-flex align-items-center justify-content-center"><i class="material-symbols-rounded text-sm" aria-hidden="true">done</i></button>
                                        <span>Paid</span>
                                    </div>
                                </td>
                                <td class="text-xs font-weight-normal">
                                    <div class="d-flex align-items-center">
                                        <img src="../../../assets/img/team-2.jpg" class="avatar avatar-xs me-2" alt="user image">
                                        <span>Orlando Imieto</span>
                                    </div>
                                </td>
                                <td class="text-xs font-weight-normal">
                                    <span class="my-2 text-xs">Nike Sport V2</span>
                                </td>
                                <td class="text-xs font-weight-normal">
                                    <span class="my-2 text-xs">$140,20</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <div class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection