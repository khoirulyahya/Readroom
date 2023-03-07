<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script type="text/javascript">
        window.print();
    </script>
</head>
<body>

        Tanggal Pinjam : {{ $transaction->date_start }} <br>
        Tanggal Kembali : {{ $transaction->date_end }} <br>
        Lama Pinjam : {{ $transaction->lama_pinjam }} Hari <br>
        Nama Peminjam : {{ $transaction->name }} <br> <br>

        <table border="1" width="100%" cellpadding="10" cellspacing="0">
            <tr>
                <td><b>Judul Buku</b></td>
                <td><b>Jumlah Buku</b></td>
                <td><b>Harga Satuan</b></td>
                <td><b>Total</b></td>
            </tr>
            @foreach ($transaction->details as $detail)
            <tr>
                <td>{{ $detail->title }}</td>
                <td>{{ $detail->qty }}</td>
                <td>Rp. {{ number_format($detail->price) }}</td>
                <td>Rp. {{ number_format($detail->total_bayar) }}</td>
            </tr>
            @endforeach

        </table>
</body>
</html>
