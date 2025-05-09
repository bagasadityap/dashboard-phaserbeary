<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" as="style">
    <style>
        body {
            background-color: #ffffff;
            font-family: 'Times New Roman', Times, serif;
        }

        .invoice-container {
            width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid black;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }

        .header img {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }

        .header-content {
            text-align: center;
            flex-grow: 1;
        }

        .header-content h1 {
            font-size: 18px;
            text-transform: uppercase;
            margin: 0;
        }

        .header-content h2 {
            font-size: 14px;
            margin: 0;
        }

        .header-content p {
            font-size: 12px;
            margin: 5px 0;
        }

        .title {
            text-align: right;
            margin-top: 10px;
        }

        .title h3 {
            font-size: 24px;
            font-weight: bold;
        }

        .details {
            margin: 20px 0;
        }

        .details table {
            width: 100%;
            font-size: 14px;
        }

        .rincian-table {
            margin-top: 20px;
        }

        .rincian-table th, .rincian-table td {
            text-align: right;
        }

        .amount {
            font-size: 16px;
            margin-top: 20px;
        }

        .amount .grand-total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            border-top: 2px solid black;
            padding-top: 10px;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
        }

        .footer .signature {
            text-align: right;
            margin-top: 50px;
        }

        .footer .signature p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <img src="https://wiki.ub.ac.id/lib/exe/fetch.php?cache=&media=ub-logo-small.png" alt="Logo">
            <div class="header-content">
                <b><h1>DIREKTORAT PENGEMBANGAN KARIR DAN ALUMNI</h1>
                <h1>UNIVERSITAS BRAWIJAYA</h1></b>
                <p>Office: Jl. Veteran Malang Telp. 0341 583787 Fax. 0341 575453<br>Telp. 0341 551611 Ext. 130<br><span class="text-primary">https://dpka.ub.ac.id/</span> - jpc@ub.ac.id</p>
            </div>
        </div>

        <div class="title">
            <h3 class="text-center">INVOICE</h3>
        </div>

        <div class="details">
            <table class="mb-4">
                <tr>
                    <td><strong>Nomor</strong></td>
                    <td>: {{$transaction['transaction_number']}}/BCE-I/DPKA/II/{{$transaction['created_at']->year}}</td>
                </tr>
                <tr>
                    <td><strong>Telah diterima dari</strong></td>
                    <td>: {{$transaction['company_name']}}</td>
                </tr>
                <tr>
                    <td><strong>Buat Pembayaran</strong></td>
                    <td>: Biaya Sewa Booth Kegiatan {{$transaction['agenda_name']}}<br><span class="text-light opacity-0">:</span> Tanggal {{$transaction['start_date']}} - {{$transaction['end_date']}}.</td>
                </tr>
                <tr>
                    <td><strong>Metode Pembayaran</strong></td>
                    <td>: {{$transaction['payment_type'] == 'transfer' ? 'Transfer' : 'Langsung ke Kantor DPKA'}}</td>
                </tr>
            </table>

            <table class="table">
                <thead>
                    <tr>
                        <th class="text-start">Deskripsi</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookedBooths as $booth)
                    <tr>
                        <td class="text-start">{{$booth->name}} - Booth {{$booth->type.$booth->label}} </td>
                        <td class="text-end">Rp{{number_format($booth->fixed_price, 0, ',', '.')}}</td>
                    </tr>
                    @endforeach
                    @if ($items != null)
                        @for ($i = 0; $i < count($items["name"]); $i++)
                        <tr>
                            <td class="text-start">{{ucwords($items["name"][$i])}}</td>
                            <td class="text-end">Rp{{number_format((int)$items["price"][$i],0, ',', '.')}}</td>
                        </tr>
                        @endfor
                    @endif
                    <tr>
                        <td class="text-start fs-6"><strong>Total Sebelum Pajak</strong></td>
                        <td class="text-end fs-6"><strong>Rp{{$transaction['total_price']}}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-start">PPN ({{$setting->tax_percentage}}%)</td>
                        <td class="text-end">Rp{{$transaction['tax_amount']}}</td>
                    </tr>
                    <tr>
                        <td class="text-start fs-6"><strong>Total Setelah Pajak</strong></td>
                        <td class="text-end fs-6"><strong>Rp{{$transaction['grand_total']}}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="amount mb-4">
            <p>Terbilang: {{ucwords($transaction['grand_total_terbilang'])}} Rupiah</p>
        </div>

        <div class="footer">
            <p style="font-size: 14px">* Mohon diperhatikan bahwa total sebelum pajak dan PPN harus dibayar secara terpisah. Pastikan untuk memeriksa jumlah ini dengan seksama.</p>
            @if($transaction['payment_type'] == 'transfer')
            <p class="m-0" style="font-size: 14px">Transfer pembayaran booth ke Virtual Account Universitas Brawijaya {{$setting->booth_bank_account_name}} : {{$setting->booth_bank_account_number}} a/n {{$setting->booth_bank_account_owner}}.</p>
            <p class="m-0 mt-1" style="font-size: 14px">Transfer pembayaran pajak ke Virtual Account Universitas Brawijaya {{$setting->booth_bank_account_name}} : {{$setting->booth_bank_account_number}} a/n {{$setting->booth_bank_account_owner}}.</p>
            @else
            <p class="m-0" style="font-size: 14px">Pembayaran bisa langsung dilakukan di kantor DPKA Universitas Brawijaya, Jl. Veteran, Kota Malang.</p>
            @endif
            <div class="signature">
                <p class="mb-5">Malang, {{$transaction['invoice_generated']}}</p>
                <p><strong>Andy Sulistyowatik</strong></p>
            </div>
        </div>
    </div>
</body>
</html>
