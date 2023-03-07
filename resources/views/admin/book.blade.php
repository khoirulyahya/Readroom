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
        <h4>Book</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active text-white">Book</li>
        </ol>
    </div>
@endsection
@section('css')
@endsection

@section('content')
<div id="controller">
    <div class="row">
        <div class="col-md-5 offset-md-3">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" class="form-control" autocomplete="off" placeholder="Search from title" v-model="search">
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary" @click="addData">Create New Book</button>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12" v-for="book in filteredList">
            <div class="info-box" v-on:click="editData(book)">
                <div class="info-box-content">
                    <span class="info-box-text h4">@{{ book.title }} (@{{ book.qty }})</span>
                    <span class="info-box-number">Rp. @{{ numberWithSpaces(book.price) }} ,- <small>created at : @{{ book.dateBy_helpers }}</small></span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" :action="actionUrl" autocomplete="off" @submit="submitForm($event, book.id)">
                    <div class="modal-header">
                        <h4 class="modal-title">Book</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf

                        <input type="hidden" name="_method" value="PUT" v-if="editStatus">

                        <div class="form-group">
                            <label>ISBN</label>
                            <input type="number" name="isbn" class="form-control" placeholder="Enter isbn" required="" :value="book.isbn">
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Enter title" required="" :value="book.title">
                        </div>
                        <div class="form-group">
                            <label>Year</label>
                            <input type="number" name="year" class="form-control" placeholder="Enter year" required="" :value="book.year">
                        </div>
                        <div class="form-group">
                            <label>Publisher</label>
                            <select name="publisher_id" class="form-control">
                                <option value="" disabled>-- Select publisher --</option>
                                @foreach ($publishers as $publisher)
                                <option :selected="book.publisher_id == {{ $publisher->id }}" value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Author</label>
                            <select name="author_id" class="form-control">
                                <option value="" disabled>-- Select author --</option>
                                @foreach ($authors as $author)
                                <option :selected="book.author_id == {{ $author->id }}" value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Catalog</label>
                            <select name="catalog_id" class="form-control">
                                <option value="" disabled>-- Select catalog --</option>
                                @foreach ($catalogs as $catalog)
                                <option :selected="book.catalog_id == {{ $catalog->id }}" value="{{ $catalog->id }}">{{ $catalog->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Qty Stock</label>
                            <input type="number" name="qty" class="form-control" placeholder="Enter qty stock" required="" :value="book.qty">
                        </div>
                        <div class="form-group">
                            <label>Price Transaction</label>
                            <input type="number" name="price" class="form-control" placeholder="Enter price transaction" required="" :value="book.price">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default bg-danger" data-dismiss="modal" v-if="editStatus" v-on:click="deleteData(book.id)">Delete</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script type="text/javascript">
        var actionUrl = '{{ url('books') }}';
        var apiUrl = '{{ url('api/books') }}';

        var app = new Vue({
            el: '#controller',
            data: {
                books: [],
                search: '',
                book: {},
                actionUrl,
                apiUrl,
                editStatus: false
            },
            mounted: function() {
                this.get_books();
            },
            methods: {
                get_books() {
                    const _this = this;
                    $.ajax({
                        url: apiUrl,
                        method: 'GET',
                        success: function(data) {
                            _this.books = JSON.parse(data);
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                },
                addData(){
                    this.book = {};
                    this.editStatus = false;
                    $('#modal-default').modal();
                },
                editData(book){
                    this.book = book;
                    this.editStatus = true;
                    $('#modal-default').modal();
                },
                deleteData(id){
                    this.actionUrl = '{{ url('books') }}'+'/'+id;
                    if (confirm("Are you sure ?")) {
                        axios.post(this.actionUrl, {_method: 'DELETE'}).then(response => {
                            location.reload();
                        });
                    }
                },
                numberWithSpaces(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },
                submitForm(event, id) {
                    event.preventDefault();
                    const _this = this;
                    var actionUrl = ! this.editStatus ? this.actionUrl : this.actionUrl + '/' + id;
                    axios.post(actionUrl, new FormData($(event.target)[0])).then(response => {
                        $('#modal-default').modal('hide');
                        location.reload();
                    });
                }
            },
            computed: {
                filteredList() {
                    return this.books.filter(book => {
                        return book.title.toLowerCase().includes(this.search.toLowerCase()) || book.qty.toString().toLowerCase().includes(this.search.toLowerCase()) || book.isbn.toString().toLowerCase().includes(this.search.toLowerCase()) || book.price.toString().toLowerCase().includes(this.search.toLowerCase()) || book.year.toString().toLowerCase().includes(this.search.toLowerCase())
                    })
                }
            }
        })

    </script>
@endsection
