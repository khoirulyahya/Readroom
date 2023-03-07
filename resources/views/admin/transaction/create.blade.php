@extends('layouts.admin')
@section('notification')
<!-- Notifications Dropdown Menu -->
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        Notifications <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">{{ $transactions_notif->count() }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
        @for ( $key=0 ;  $key<$transactions_notif->count() ; $key++)
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
            <i class="fas fa-user mr-2"></i>
            {{ $transactions_notif[$key]->name }} past
            {{--  @foreach ($transactions_notif[$key]->date_notif as $date_late)  --}}
            {{--  @if ($transactions_notif[$key]->date_notif == 'Late')  --}}
            {{ $transactions_notif[$key]->duration_late }}
            {{--  @endif  --}}
            {{--  @endforeach   --}}
            day {{ $transactions_notif[$key]->date_notif }}

        </a>
        @endfor
    </div>
</li>
@endsection
@section('header')
    <div class="col-sm-6">
        <h4>Transaction</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Transaction</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </div>
@endsection
@section('css')
<!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('assets//plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets//plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="row d-flex justify-content-center">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Create New Transaction</h3>
            </div>

            <form action="{{ route('transactions.store') }}" method="post">
                @csrf{{--  token untuk post data  --}}
                <div class="card-body">
                    <div class="row form-group">
                        <div class="col-3">
                            <label>Member</label>
                        </div>
                        <div class="col-9">
                            <select name="member_id" class="form-control" required>
                                <option value="">Select Member --</option>
                                @foreach ($members as $member)
                                <option value="{{ $member->id }}">{{ $member->id.'. '.$member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3">
                            <label>Date</label>
                        </div>
                        <div class="col-4">
                            <input type="date" name="date_start" class="form-control" placeholder="Enter name" required="">
                        </div>
                            ___
                        <div class="col-4">
                            <input type="date" name="date_end" class="form-control" placeholder="Enter name" required="">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3">
                            <label>Book</label>
                        </div>
                        <div class="select2-primary col-9">
                            <select name="book[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Book" style="width: 100%;" required>
                                @foreach ($books as $book)
                                <option value="{{ $book->id }}">{{ $book->id.'. '.$book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-4">
                            <input hidden type="number" name="status" value="1" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-4">
                            <input hidden type="number" name="category_code" value="1" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Select2 -->
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })
</script>
@endsection
