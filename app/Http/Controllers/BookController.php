<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; //import to use DB
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Catalog;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Class constructor, optional security.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //START NOTIF
        $transactions_notif = Transaction::select('transactions.id','transactions.member_id','transactions.date_start','transactions.date_end','members.name', DB::raw("DATEDIFF(transactions.date_end,transactions.date_start) as lama_pinjam"), 'transactions.created_at','transactions.status')
                            ->join('members', 'members.id', '=', 'transactions.member_id')
                            ->where('date_end','<',date('Y-m-d'))
                            ->where('status','=',1)
                            ->get();

        foreach ($transactions_notif as $key => $transaction_notif) {
            $transaction_notif->details = DB::table('transaction_details')
                                    ->select('title', 'price', 'transaction_details.qty', DB::raw("books.price*transaction_details.qty as total_bayar"), 'transaction_details.created_at')
                                    ->where('transaction_id', $transaction_notif->id)
                                    ->join('books', 'books.id', '=', 'transaction_details.book_id')
                                    ->get();
            $transaction_notif->duration = $transaction_notif->lama_pinjam.' Days';
            // $fdate = date('d-m-y');
            // $tdate = $transaction->date_end;
            // $datetime1 = new DateTime();
            // $datetime2 = new DateTime($tdate); //convert data ke dalam objek
            // $interval = $datetime1->diff($datetime2);
            $transaction_notif->duration_late = convert_late($transaction_notif->date_end);

            foreach ($transaction_notif->details as $detaile) {
                $transaction_notif->total_buku += $detaile->qty;
                $transaction_notif->jumlah_bayar += $detaile->total_bayar;
            }
            $transaction_notif->jumlah_buku = $transaction_notif->total_buku.' pcs';
            $transaction_notif->harga = number_format($transaction_notif->jumlah_bayar,0,",",".").' IDR';
            if ($transaction_notif->duration_late <= 0 AND $transaction_notif->status == 2) {
                $transaction_notif->date_notif = 'Ontime';
            } else if ($transaction_notif->duration_late > 0 AND $transaction_notif->status == 1) {
                $transaction_notif->date_notif = 'Late';
            }
        }
        //END NOTIF
        $books = Book::all();
        // $books = Book::with('publisher','author','catalog')->get();
        // foreach ($books as $key => $book) {
        //     $book->Publisher::all();
        //     $book->Author::all();
        //     $book->Catalog::all();
        // }
        // return $books;

        // return $books;
        $publishers = Publisher::all();
        $authors = Author::all();
        $catalogs = Catalog::all();
        return view('admin.book', compact('publishers','authors','catalogs','transactions_notif'));
    }

    public function api()
    {
        $books = Book::all();
         //cara pertama manggil function helpers di controller
        foreach ($books as $key => $book) {
            $book->dateBy_helpers = convert_date($book->created_at);
        }

        return json_encode($books);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'isbn'  => ['required'],
            'title'  => ['required'],
            'year'  => ['required','numeric'],
            'publisher_id'  => ['required'],
            'author_id'  => ['required'],
            'catalog_id'  => ['required'],
            'qty'  => ['required','numeric'],
            'price'  => ['required','numeric']
        ]);

        //cara pertama
        $book = new Book;
        $book->isbn = $request->isbn;
        $book->title = $request->title;
        $book->year = $request->year;
        $book->publisher_id = $request->publisher_id;
        $book->author_id = $request->author_id;
        $book->catalog_id = $request->catalog_id;
        $book->qty = $request->qty;
        $book->price = $request->price;
        $book->save();

        return redirect('books');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $this->validate($request, [
            'isbn'  => ['required'],
            'title'  => ['required'],
            'year'  => ['required','numeric'],
            'publisher_id'  => ['required'],
            'author_id'  => ['required'],
            'catalog_id'  => ['required'],
            'qty'  => ['required','numeric'],
            'price'  => ['required','numeric']
        ]);

        //cara pertama
        $book = Book::find($book->id);
        $book->isbn = $request->isbn;
        $book->title = $request->title;
        $book->year = $request->year;
        $book->publisher_id = $request->publisher_id;
        $book->author_id = $request->author_id;
        $book->catalog_id = $request->catalog_id;
        $book->qty = $request->qty;
        $book->price = $request->price;
        $book->save();

        return redirect('books');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect('books');
    }
}
