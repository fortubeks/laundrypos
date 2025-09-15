@extends('material-theme.layouts.app')
@section('content')
<div class="container-fluid py-2">

    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">
                            Laundry Items
                            <p class="text-sm mb-0">
                                View all the Laundry items from here.
                            </p>
                        </h6>
                    </div>
                    <div class="d-sm-flex align-items-center mt-3">
                        <div class="pe-3" style="width: 90%;">
                            <div class="">
                                <form id="userSearchForm" action="{{ route('orders.index') }}" method="GET">
                                    <div class="input-group input-group-outline">
                                        <label id="query" class="form-label">Search items here...</label>
                                        <input name="query" type="text" class="form-control" onfocus="focused(this)" onfocusout="defocused(this)" id="userSearchInput">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <a href="{{route('laundry-items.create')}}" class="btn bg-gradient-dark btn-sm mb-0">+&nbsp; New Item</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Item</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                                <!-- <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Last Login</th>-->
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laundryItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <img class="w-10 ms-3" src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/adidas-hoodie.jpg" alt="hoodie">
                                        <h6 class="ms-3 my-auto">{{$item->name}}</h6>
                                    </div>
                                </td>
                                <td class="text-sm">
                                    <a href="javascript:;" data-bs-toggle="tooltip" data-bs-original-title="Preview product">
                                        <i class="material-symbols-rounded text-secondary position-relative text-lg">visibility</i>
                                    </a>
                                    <a href="javascript:;" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit product">
                                        <i class="material-symbols-rounded text-secondary position-relative text-lg">drive_file_rename_outline</i>
                                    </a>
                                    <a href="javascript:;" data-bs-toggle="tooltip" data-bs-original-title="Delete product">
                                        <i class="material-symbols-rounded text-secondary position-relative text-lg">delete</i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $laundryItems->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection