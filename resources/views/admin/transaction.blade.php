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
            {{ $transactions_notif[$key]->duration_late }}
            day {{ $transactions_notif[$key]->date_notif }}

        </a>
        @endfor
    </div>
</li>
@endsection
@section('header')
    <div class="col-sm-6 text-white">
        <h4>Transaction</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active text-white">Transaction</li>
        </ol>
    </div>
@endsection
@section('css')
<!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('assets//plugins/daterangepicker/daterangepicker.css') }}">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{ asset('assets//plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{ asset('assets//plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('assets//plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('assets//plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets//plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="{{ asset('assets//plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="{{ asset('assets//plugins/bs-stepper/css/bs-stepper.min.css') }}">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="{{ asset('assets//plugins/dropzone/min/dropzone.min.css') }}">
  <!-- Theme style -->
@endsection

@section('content')

{{--  @can('index transactions')  --}}
@role('Officer')
<div id="controller">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary pull-right">Create New Transaction</a>
                </div>

                <div class="col-md-3 row">
                    <div class="input-group-prepend col-m">
                        <span class="input-group-text"><i class="fas fa-solid fa-filter"></i></span>
                    </div>
                    <div class="input-group col-9">
                        <select class="form-control" name="status">
                            <option value="reset">Filter Status</option>
                            <option value="2">Returned</option>
                            <option value="1">Borrowed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 row">
                    <div class="input-group-prepend col-m">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <div class="input-group col-10">
                        <select class="form-control" name="date_start">
                            <option value="reset">Filter Date Borrow</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="30px"><b>#</b></th>
                        <th class="text-center align-middle"><b>Date Borrowing</b></th>
                        <th class="text-center align-middle"><b>Date Returning</b></th>
                        <th class="text-center align-middle"><b>Name Borrower</b></th>
                        <th class="text-center align-middle"><b>Duration</b></th>
                        <th class="text-center align-middle"><b>Total Book</b></th>
                        <th class="text-center align-middle"><b>Total Cost</b></th>
                        <th class="text-center align-middle"><b>Status</b></th>
                        <th class="text-center align-middle"><b>Action</b></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endrole
{{--  @endcan  --}}

@endsection

@section('js')
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
<!-- Select Date Range plugin -->
    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{ asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Bootstrap Switch -->
    <script src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- BS-Stepper -->
    <script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <!-- dropzonejs -->
    <script src="{{ asset('assets/plugins/dropzone/min/dropzone.min.js') }}"></script>

<script type="text/JavaScript" >
    var actionUrl = '{{ url('transactions') }}';
    var apiUrl = '{{ url('api/transactions') }}';

    {{--  Menyimpan dan menata data dari api ke dalam variable column  --}}
    var columns = [
        {data: 'DT_RowIndex', class: 'text-center', orderable: true},
        {data: 'date_start', class: 'text-center', orderable: true},
        {data: 'date_end', class: 'text-center', orderable: true},
        {data: 'name', orderable: true, width: '150px'},
        {data: 'duration', class: 'text-right', orderable: true},
        {data: 'jumlah_buku', class: 'text-center', orderable: true},
        {data: 'harga', class: 'text-right', orderable: true, width: '80px'},
        {data: 'detail_status', class: 'text-center', orderable: true},
        {render: function (index, row, data, meta) {
             return `
             <a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event, ${meta.row})">
                 Edit
            </a>
            <a href="#" class="btn btn-primary btn-sm" onclick="controller.detailData(event, ${meta.row})">
                Detail
            </a>
            <a href="#" class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})">
                Delete
            </a>`;
        }, orderable: false, width: '190px', class: 'text-center'},

    ];

    var controller = new Vue({
        el: '#controller',
        data: {
            datas: [],
            data: {},
            actionUrl,
            apiUrl,
            editStatus: false,
        },
        mounted: function () {
            this.datatable();
        },
        methods: {
            datatable() {
                const _this = this;
                _this.table = $('#datatable').DataTable({
                    ajax: {
                        url: _this.apiUrl,
                        type: 'GET',
                    },
                    columns: columns
                }).on('xhr', function () {
                    _this.datas = _this.table.ajax.json().data;
                });
            },
            editData(event, row) {
                this.data = this.datas[row];
                this.editStatus = true;
                window.location = '{{ url('transactions') }}'+'/'+this.data.id+'/edit';
            },
            detailData(event, row) {
                this.data = this.datas[row];
                this.editStatus = true;
                window.location = '{{ url('transactions') }}'+'/'+this.data.id;
            },
            deleteData(event, id) {
                event.preventDefault();
                const _this = this;
                this.actionUrl = '{{ url('transactions') }}'+'/'+id;
                if (confirm("Are you sure ?")) {
                    $(event.target).parents('tr').remove();
                    axios.post(this.actionUrl, {_method: 'DELETE'}).then(response => {
                        _this.table.ajax.reload();
                        alert('Data has been removed');
                    });
                }
            },
        }
    });

</script>
{{--  memanggil file dengan isi variabel dengan method crud  --}}
{{--  <script src="{{ asset('js/data.js') }}"></script>  --}}
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
        //Initialize Select2 Elements
        $('.select2bs4').select2({
        theme: 'bootstrap4'
        })
            //Date picker
        $('#reservationdate').datetimepicker({
            format: 'yyyy-MM-DD'
        });
            //Date range picker
        $('#reservation').daterangepicker()
            //Date range as a button
        $('#daterange-btn').daterangepicker(
        {
            ranges   : {
            'Today'       : [moment(), moment()],
            'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month'  : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate  : moment()
        },
        function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
        )

    })
</script>
<script type="text/javascript">
    {{--  document.querySelector("#LastRenovationDate").onchange = function(){

        // selected value
        var select_val = this.value;

        // enter your date components
        year = '2022',
        month = select_val, // zeor indexed
        day = '06';
        dayy = '28';

        var js_date_object = year+"-"+month+"-"+day ;
        var js_date_objectt = year+"-"+month+"-"+dayy;
        console.log( js_date_object );
        console.log( js_date_objectt );

        // Then, format js_date_object to match your database
    }  --}}
    $('select[name=status]').on('change', function() {
        status = $('select[name=status]').val();

        if (status == 'reset') {
            controller.table.ajax.url(apiUrl).load();
        } else {
            controller.table.ajax.url(apiUrl+'?status='+status).load();
        }
    });

    $('select[name=date_start]').on('change', function() {
        date_start = $('select[name=date_start]').val();
        console.log(date_start);

        if (date_start == 'reset') {
            controller.table.ajax.url(apiUrl).load();
        } else {
            controller.table.ajax.url(apiUrl+'?date_start='+date_start).load();
        }
    });

</script>
@endsection
