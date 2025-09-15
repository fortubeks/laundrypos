@extends('material-theme.layouts.app')
@section('content')
<div class="container-fluid py-2">

    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">
                            Service Items
                            <p class="text-sm mb-0">
                                View all the service items from here.
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
                                    Service Item
                                </h5>
                                <p class="mb-0">
                                    Service Item
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 mt-lg-0 mt-4">
                        <div class="card">
                            <form method="POST"
                                action="{{ isset($serviceItem) ? route('service-items.update', $serviceItem->id) : route('service-items.store') }}">
                                @csrf
                                @if(isset($serviceItem))
                                @method('PUT')
                                @endif

                                <div class="card-body">
                                    <h5 class="font-weight-bolder">Service Item Information</h5>

                                    {{-- Name & Price --}}
                                    <div class="row mt-4">
                                        <div class="col-12 col-sm-6">
                                            <div class="input-group input-group-dynamic">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="name"
                                                    value="{{ old('name', $serviceItem->name ?? '') }}"
                                                    class="form-control w-100" required
                                                    onfocus="focused(this)" onfocusout="defocused(this)">
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <div class="input-group input-group-dynamic">
                                                <label class="form-label">Price</label>
                                                <input type="number" step="0.01" name="price"
                                                    value="{{ old('price', $serviceItem->price ?? '') }}"
                                                    class="form-control w-100" required
                                                    onfocus="focused(this)" onfocusout="defocused(this)">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Service Category & Laundry Item --}}
                                    <div class="row mt-4">
                                        <div class="col-12 col-sm-6">
                                            <div class="input-group input-group-dynamic">
                                                <select name="service_category_id" class="form-select" required>
                                                    <option value="">-- Select Service Category --</option>
                                                    @foreach(getModelList('service-categories') as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('service_category_id', $serviceItem->service_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <div class="input-group input-group-dynamic">
                                                <select name="laundry_item_id" class="form-select">
                                                    <option value="">-- Select Laundry Item --</option>
                                                    @foreach(getModelList('laundry-items') as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('laundry_item_id', $serviceItem->laundry_item_id ?? '') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Unit Type & Turnaround Time --}}
                                    <div class="row mt-4">
                                        <div class="col-12 col-sm-6">
                                            <div class="input-group input-group-dynamic">
                                                <select name="unit_type" class="form-select" required>
                                                    <option value="">-- Select Unit Type --</option>
                                                    @foreach(getModelList('unit-types') as $unitType => $value)
                                                    <option value="{{ $value }}"
                                                        {{ old('unit_type', $serviceItem->unit_type ?? '') == $value ? 'selected' : '' }}>
                                                        {{ $unitType }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                            <div class="input-group input-group-dynamic">
                                                <label class="form-label">Turnaround Time (Hours)</label>
                                                <input type="number" name="turnaround_time"
                                                    value="{{ old('turnaround_time', $serviceItem->turnaround_time ?? '') }}"
                                                    class="form-control w-100"
                                                    onfocus="focused(this)" onfocusout="defocused(this)">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                {{ isset($serviceItem) ? 'Update Service Item' : 'Create Service Item' }}
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