@extends('layouts.admin')
@section('header', 'Publisher' )

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            @if ($errors->any())
            <div class="alert-default-danger">
                <ol><b>ERROR!</b>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ol>
            </div>
            @endif
            <div class="card-header">
                <h3 class="card-title">Create New Publisher</h3>
            </div>



            <form action="{{ route('publishers.store') }}" method="post">
                @csrf{{--  token untuk post data  --}}
                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        {{--  <input type="text" name="name" class="form-control" placeholder="Enter name" required="" value="{{@old('name')}}">  --}}
                        <input type="text" name="name" class="form-control" placeholder="Enter name" value="{{@old('name')}}" required="">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        {{--  <input type="email" name="email" class="form-control" placeholder="Enter email" required="" value="{{@old('email')}}">  --}}
                        <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{@old('email')}}" required="">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        {{--  <input type="number" name="phone_number" class="form-control" placeholder="Enter phone number" required="" value="{{@old('phone_number')}}">  --}}
                        <input type="number" name="phone_number" class="form-control" placeholder="Enter phone number" value="{{@old('phone_number')}}" required="">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        {{--  <input type="text" name="address" class="form-control" placeholder="Enter address" required="" value="{{@old('address')}}">  --}}
                        <input type="text" name="address" class="form-control" placeholder="Enter address" value="{{@old('address')}}" required="">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
