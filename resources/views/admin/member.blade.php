@extends('layouts.admin')
@section('header')
    <div class="col-sm-6 text-white">
        <h4>Member</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active text-white text-bold">Data Master</li>
            <li class="breadcrumb-item active text-white">Member</li>
        </ol>
    </div>
@endsection
@section('css')
<!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
<div id="controller">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <a href="#" @click="addData()" class="btn btn-sm btn-primary pull-right">Create New Member</a>
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="gender">
                        <option value="0">All Gender</option>
                        <option value="F">Female</option>
                        <option value="M">Male</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle" width="30px"><b>No</b></th>
                        <th class="text-center align-middle"><b>Name</b></th>
                        <th class="text-center align-middle"><b>Gender</b></th>
                        <th class="text-center align-middle"><b>Phone Number</b></th>
                        <th class="text-center align-middle"><b>Address</b></th>
                        <th class="text-center align-middle"><b>Email</b></th>
                        <th class="text-center align-middle"><b>Created At</b></th>
                        {{--  <td><b>Book</b></td>  --}}
                        <th class="text-center align-middle"><b>Action</b></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" :action="actionUrl" autocomplete="off" @submit="submitForm($event, data.id)">
                    <div class="modal-header">

                        <h4 class="modal-title">Create New Member</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf

                        <input type="hidden" name="_method" value="PUT" v-if="editStatus">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" placeholder="Enter name" name="name" :value="data.name" required="">
                        </div>
                        <div class="form-group">
                            <label>Gender (M/F)</label>
                            <input type="text" class="form-control" placeholder="Enter M(male) or F(female)" name="gender" :value="data.gender" required="" pattern="[M|F]" minlength="1" maxlength="1">
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="number" name="phone_number" class="form-control" placeholder="Enter phone number" :value="data.phone_number" required="">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" placeholder="Enter address" :value="data.address" required="">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email" :value="data.email" required="">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js') {{--  memanggil @yield('js') di admin.blade  --}}
<!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script type="text/JavaScript" >
    var actionUrl = '{{ url('members') }}';
    var apiUrl = '{{ url('api/members') }}';

    {{--  Menyimpan dan menata data dari api ke dalam variable column  --}}
    var columns = [
        {data: 'DT_RowIndex', class: 'text-center', orderable: true},
        {data: 'name', orderable: true},
        {data: 'gender', class: 'text-center', orderable: true},
        {data: 'phone_number', class: 'text-center', orderable: true, width: '120px'},
        {data: 'address', orderable: true},
        {data: 'email', orderable: true},
        {data: 'dateBy_helpers', class: 'text-center', orderable: true, width: '130px'},
        {render: function (index, row, data, meta) {
            return `
            <a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event, ${meta.row})">
                <i class="fas fa-edit"></i>
            </a>
            <a href="#" class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})">
                <i class="fas fa-trash"></i>
            </a>`;
        }, orderable: false, width: '150px', class: 'text-center'},
    ];
</script>
{{--  memanggil file dengan isi variabel dengan method crud  --}}
<script src="{{ asset('js/data.js') }}"></script>
<script type="text/javascript">
    $('select[name=gender]').on('change', function() {
        gender = $('select[name=gender]').val();

        if (gender == 0) {
            controller.table.ajax.url(apiUrl).load();
        } else {
            controller.table.ajax.url(apiUrl+'?gender='+gender).load();
        }
    });
</script>
@endsection
