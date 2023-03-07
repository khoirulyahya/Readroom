@extends('layouts.admin')
@section('header')
    <div class="col-sm-6 text-white">
        <h4>Catalog</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active text-white text-bold">Data Master</li>
            <li class="breadcrumb-item active text-white">Catalog</li>
        </ol>
    </div>
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        <a href="{{ route('catalogs.create') }}" class="btn btn-sm btn-primary pull-right">Create New Catalog</a>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center align-middle" width="10px">#</th>
                    <th class="text-center align-middle">Name</th>
                    <th class="text-center align-middle">Total Books</th>
                    <th class="text-center align-middle">Created At</th>
                    <th class="text-center align-middle" colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($catalogs as $key => $catalog)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $catalog->name }}</td>
                    <td class="text-center">{{ count($catalog->books) }} item</td>
                    <td class="text-center">{{ convert_date($catalog->created_at) }}</td>
                    <td class="text-right">
                        <a href="{{ route('catalogs.edit', $catalog->id) }}" class="btn btn-sm btn-warning pull-right"><i class="fas fa-edit"></i></a>
                    </td>
                    <td class="text-left">
                        <form action="{{ route('catalogs.destroy', $catalog->id) }}" method="post">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                            @method('delete'){{--  untuk delete data dan routenya delete  --}}
                            @csrf{{--  token untuk post data  --}}
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
