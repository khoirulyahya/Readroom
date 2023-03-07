<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; //import use DB
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Member;
use App\Models\TransactionDetail;
use DateTime;

class TransactionController extends Controller
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
            return view('admin.transaction', compact('transactions_notif'));
    }


    public function api(Request $request)
    {
        if ($request->status) {
            $transactions = Transaction::select('transactions.id','transactions.member_id','transactions.date_start','transactions.date_end','members.name', DB::raw("DATEDIFF(transactions.date_end,transactions.date_start) as lama_pinjam"), 'transactions.created_at','transactions.status')
                            ->join('members', 'members.id', '=', 'transactions.member_id')
                            ->orderBy('transactions.id')
                            ->where('status', $request->status)
                            ->get();
        } else if ($request->date_start) {
            $transactions = Transaction::select('transactions.id','transactions.member_id','transactions.date_start','transactions.date_end','members.name', DB::raw("DATEDIFF(transactions.date_end,transactions.date_start) as lama_pinjam"), 'transactions.created_at','transactions.status')
                            ->join('members', 'members.id', '=', 'transactions.member_id')
                            ->orderBy('transactions.id')
                            ->whereMonth('date_start', [$request->date_start])
                            ->get();
        } else {
            $transactions = Transaction::select('transactions.id','transactions.member_id','transactions.date_start','transactions.date_end','members.name', DB::raw("DATEDIFF(transactions.date_end,transactions.date_start) as lama_pinjam"), 'transactions.created_at','transactions.status')
                            ->join('members', 'members.id', '=', 'transactions.member_id')
                            ->orderBy('transactions.id')
                            ->get();
        }

        // if ($request->date_start) {
        //     $transactions = Transaction::select('transactions.id','transactions.member_id','transactions.date_start','transactions.date_end','members.name', DB::raw("DATEDIFF(transactions.date_end,transactions.date_start) as lama_pinjam"), 'transactions.created_at','transactions.status')
        //                     ->join('members', 'members.id', '=', 'transactions.member_id')
        //                     ->orderBy('transactions.id')
        //                     ->where('date_start', $request->date_start)
        //                     ->get();
        // } else {
        //     $transactions = Transaction::select('transactions.id','transactions.member_id','transactions.date_start','transactions.date_end','members.name', DB::raw("DATEDIFF(transactions.date_end,transactions.date_start) as lama_pinjam"), 'transactions.created_at','transactions.status')
        //                     ->join('members', 'members.id', '=', 'transactions.member_id')
        //                     ->orderBy('transactions.id')
        //                     ->get();
        // }


        foreach ($transactions as $key => $transaction) {
            $transaction->details = DB::table('transaction_details')
                                    ->select('title', 'price', 'transaction_details.qty', DB::raw("books.price*transaction_details.qty as total_bayar"), 'transaction_details.created_at')
                                    ->where('transaction_id', $transaction->id)
                                    ->join('books', 'books.id', '=', 'transaction_details.book_id')
                                    ->get();
            $transaction->duration = $transaction->lama_pinjam.' Days';
            // $fdate = date('d-m-y');
            // $tdate = $transaction->date_end;
            // $datetime1 = new DateTime();
            // $datetime2 = new DateTime($tdate); //convert data ke dalam objek
            // $interval = $datetime1->diff($datetime2);
            $transaction->duration_late = convert_late($transaction->date_end);

            foreach ($transaction->details as $detaile) {
                $transaction->total_buku += $detaile->qty;
                $transaction->jumlah_bayar += $detaile->total_bayar;
            }
            $transaction->jumlah_buku = $transaction->total_buku.' pcs';
            $transaction->harga = number_format($transaction->jumlah_bayar,0,",",".").' IDR';
        }

        $datatables = datatables()->of($transactions)
                                    ->addColumn('detail_status', function($transactions) {
                                        if ($transactions->status == 2) {
                                            return 'Returned';
                                        } return 'Borrowed';
                                    })
                                    ->addColumn('detail_duration', function($transactions) {
                                        if ($transactions->duration_late <= 0 AND $transactions->status == 2) {
                                            return 'Ontime';
                                        } else if ($transactions->duration_late == 0 AND $transactions->status == 1) {
                                            return 'Deadline';
                                        } return 'Late';
                                    })
                                    ->addIndexColumn();

        return $datatables->make(true);

        // return view('admin.transaction.index', compact('transactions')); //proses melempar data
    }


    public function print($id)
    {
        $transaction = Transaction::select('transactions.id','date_start','date_end','name', DB::raw("DATEDIFF(date_end,date_start) as lama_pinjam"))
                        ->join('members', 'members.id', '=', 'transactions.member_id')
                        ->where('transactions.id', $id)
                        ->first();

        $transaction->details = DB::table('transaction_details')
                                    ->select('title', 'price', 'transaction_details.qty', DB::raw("price*transaction_details.qty as total_bayar"))
                                    ->where('transaction_id', $transaction->id)
                                    ->join('books', 'books.id', '=', 'transaction_details.book_id')
                                    ->get();

        $transaction->total_buku = $transaction->details->count();

        // return $transaction;

        return view('admin.transaction.print', compact('transaction'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $members = Member::all();
        $books = Book::where('qty','>',0)->get();
        $transactionDetails = TransactionDetail::with('book','transaction')->get();
        // return $transactionDetails;
        return view('admin.transaction.create', compact('members','books','transactionDetails','transactions_notif'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation of required

        // return $request;
        //cara pertama
        $transaction = new Transaction;
        $transaction->member_id = $request->member_id;
        $transaction->date_start = $request->date_start;
        $transaction->date_end = $request->date_end;
        $transaction->status = $request->status;
        $transaction->save();

        foreach ($request->book as $books) {
            // if(!empty($books)) {

                $transactionDetail = new TransactionDetail;
                $transactionDetail->transaction_id = $transaction->id;
                $transactionDetail->book_id = $books;
                $transactionDetail->qty = $request->qty;
                $transactionDetail->save();
            // }
                $bookOutStock = Book::find($transactionDetail->book_id);
                $bookOutStock->decrement('qty');
        }

        return redirect('transactions');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
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
        $members = Member::all();
        $books = Book::where('qty','>',0)->get();
        $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();

        return view('admin.transaction.detail', compact('members','books','transaction','transactionDetails','transactions_notif'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
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
        $members = Member::all();
        $books = Book::where('qty','>',0)->get();
        // $transaction->transaction_id = $transaction->id;
        // return $transaction;

        $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
        // foreach ($transactionDetails as $transactionDetail) {
        //    $transactionDetail->book = Book::where('id',$transactionDetail->book_id)->get();
        // }
        // return $transactionDetails;
        return view('admin.transaction.edit', compact('members','books','transaction','transactionDetails','transactions_notif'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //cara pertama
        $transaction = Transaction::find($transaction->id);
        $transaction->member_id = $request->member_id;
        $transaction->date_start = $request->date_start;
        $transaction->date_end = $request->date_end;
        $transaction->status = $request->status;
        $transaction->save();

        $detil_query = TransactionDetail::select('id','transaction_id','book_id','qty')->where('transaction_id',$transaction->id)->get();

        //START TRY aturane nambah buku //Kelemahan jika dari perubahan status Return ke Borrow query stok tidak jalan(karena barang yg sudah kembali tidak bisa dipinjam lagi, harus transaksi baru)
        // error_reporting(0);
        for ($i=0; $i < count($request->book); $i++) {
            if ($request->book[$i] != $detil_query[$i]->book_id) {
                // return "BOOK ID beda!!Tambah Query Array Ke = ".$i." ID = ".$request->book[$i]."<br>";
                $transactionDetail = new TransactionDetail;
                $transactionDetail->transaction_id = $transaction->id;
                $transactionDetail->book_id =$request->book[$i];
                $transactionDetail->qty = $request->qty;
                $transactionDetail->save();
                if (1 < $request->status) {
                    $bookOutStock = Book::find($transactionDetail->book_id);
                    $bookOutStock->increment('qty');
                } else if (2 < $request->status) {
                    $bookOutStock = Book::find($transactionDetail->book_id);
                    $bookOutStock->decrement('qty');
                } else if (2 > $request->status){
                    $bookOutStock = Book::find($transactionDetail->book_id);
                    $bookOutStock->decrement('qty');
                }
            } else if (1 < $request->status OR 2 < $request->status) {
                if (1 < $request->status) {
                    $bookOutStock = Book::find($detil_query[$i]->book_id);
                    $bookOutStock->increment('qty');
                } else if (2 < $request->status) {
                    $bookOutStock = Book::find($detil_query[$i]->book_id);
                    $bookOutStock->decrement('qty');
                } else if (2 > $request->status){
                    $bookOutStock = Book::find($detil_query[$i]->book_id);
                    $bookOutStock->decrement('qty');
                }
            }
        }

        for ($e = 0; $e < count($detil_query) ; ++$e) {
            if (is_null($request->book[$e]) AND $detil_query[$e]->book_id) {
                // return "HAPUS  Query Array Ke = ".$e." ID = ".$detil_query[$e]->book_id."<br>";
                TransactionDetail::where('book_id',$detil_query[$e]->book_id)->delete();
                $bookOutStock = Book::find($detil_query[$e]->book_id);
                $bookOutStock->increment('qty');
            }
        }

        return redirect('transactions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        TransactionDetail::where('transaction_id',$transaction->id)->delete();
        Transaction::where('id',$transaction->id)->delete();
        //Try how to check when transaction status BORROW cannot to delete
        return redirect('transactions');
    }
}
