@extends('material-theme.layouts.app')
@section('content')
<div class="container-fluid py-2">

    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">
                            {{ isset($laundryItem) ? $laundryItem->name : 'New Laundry Item' }}
                            <p class="text-sm mb-0">
                                Manage your laundry item from here.
                            </p>
                        </h6>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-4">
                        <div class="card mt-4" data-animation="true">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <a class="d-block blur-shadow-image">
                                    <img src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/adidas-hoodie.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-lg">
                                </a>
                                <div class="colored-shadow" style="background-image: url(&quot;https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/adidas-hoodie.jpg&quot;);"></div>
                            </div>
                            <div class="card-body text-center">
                                <div class="mt-n6 mx-auto">
                                    <button class="btn bg-gradient-dark btn-sm mb-0 me-2" type="button" name="button">Edit</button>
                                    <button class="btn btn-outline-dark btn-sm mb-0" type="button" name="button">Remove</button>
                                </div>
                                <h5 class="font-weight-normal mt-4">
                                    laundry Item
                                </h5>
                                <p class="mb-0">
                                    laundry Item
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 mt-lg-0 mt-4">
                        <div class="card">
                            <form method="POST"
                                action="{{ isset($laundryItem) ? route('laundry-items.update', $laundryItem->id) : route('laundry-items.store') }}">
                                @csrf
                                @if(isset($laundryItem))
                                @method('PUT')
                                @endif

                                <div class="card-body">
                                    <h5 class="font-weight-bolder">Laundry Item Information</h5>

                                    {{-- Name & Price --}}
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="input-group input-group-dynamic">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="name"
                                                    value="{{ old('name', $laundryItem->name ?? '') }}"
                                                    class="form-control w-100" required
                                                    onfocus="focused(this)" onfocusout="defocused(this)">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                {{ isset($laundryItem) ? 'Update laundry Item' : 'Create laundry Item' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection