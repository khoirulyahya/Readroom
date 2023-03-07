@extends('layouts.admin')
@section('header', 'Publisher' )

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Publisher</h3>
            </div>

            <form action="{{ route('publishers.update', $publisher->id) }}" method="post">
                @csrf{{--  token untuk post data  --}}
                {{ method_field('PUT') }}{{--  untuk UPDATE data dan routenya PUT  --}}
                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter name" required="" value="{{ $publisher->name }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email" required="" value="{{ $publisher->email }}">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="number" name="phone_number" class="form-control" placeholder="Enter phone number" required="" value="{{ $publisher->phone_number }}">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Enter address" required="" value="{{ $publisher->address }}">
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
