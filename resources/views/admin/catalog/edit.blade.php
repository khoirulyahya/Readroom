@extends('layouts.admin')
@section('header')
    <div class="col-sm-6">
        <h4>Catalog</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('catalogs.index') }}">Catalog</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
@endsection

@section('content')
<div class="row d-flex justify-content-center">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Catalog</h3>
            </div>

            <form action="{{ url('catalogs/'.$catalog->id) }}" method="post">
                @csrf{{--  token untuk post data  --}}
                {{ method_field('PUT') }}{{--  untuk UPDATE data dan routenya PUT  --}}
                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter name" required="" value="{{ $catalog->name }}">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
