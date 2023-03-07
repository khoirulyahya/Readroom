<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; //import to use DB
use App\Models\Member;
use App\Models\Transaction;
use App\Models\Catalog;
use App\Models\Publisher;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_members = Member::count();
        $total_books = Book::count();
        $total_transactions = Transaction::count();
        $total_publishers = Publisher::count();

        $data_donut = Book::select(DB::raw("COUNT(publisher_id) as total"))->groupBy('publisher_id')->orderBy('publisher_id','asc')->pluck('total');
        $label_donut = Publisher::orderBy('publishers.id','asc')->groupBy('publishers.id')->join('books','books.publisher_id', '=', 'publishers.id')->pluck('name');
        $count_donut = count($label_donut);

        $label_bar = ['Loan','Return'];
        $data_bar = [];

        foreach ($label_bar as $key => $value) {
            $data_bar[$key]['label'] = $label_bar[$key];
            $data_bar[$key]['backgroundColor'] =  $key == 0 ? 'rgb(60,141,188,0.9)' : 'rgb(210,214,222,1)';
            $data_month = [];

            foreach (range(1,12) as $month) {
                if ($key == 0) {
                    $data_month[] = Transaction::select(DB::raw("COUNT(*) as total"))->whereMonth('date_start', $month)->first()->total;
                } else {
                    $data_month[] = Transaction::select(DB::raw("COUNT(*) as total"))->whereMonth('date_end', $month)->first()->total;
                }
            }
            $data_bar[$key]['data'] = $data_month;
        }
        // return $data_month;
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

        return view('home', compact('total_members','total_books','total_transactions','total_publishers','data_donut','label_donut','data_bar','count_donut','transactions_notif'));
    }

    public function test_spatie()
    {
    //    $role = Role::create(['name' => 'Officer']);
    //    $permission = Permission::create(['name' => 'index transactions']);

    //    $role->givePermissionTo($permission);
    //    $permission->assignRole($role);

    //Cek login as what
    // $user = auth()->user();
    // return $user;

    // $user = User::with('roles')->get();
    // return $user;

    // $user = User::where('id', 2)->first();
    // $user->assignRole('Officer');
    // return $user;

    //Give permission to user, login as user other or give permission to id user other
    // $user = auth()->user();
    // $user->assignRole('Officer');
    // return $user;

    //delete role
    // $user = User::where('id', 2)->first();
    // $user->removeRole('Officer');

    }
}
