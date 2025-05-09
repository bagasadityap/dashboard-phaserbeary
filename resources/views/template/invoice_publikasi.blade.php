<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
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
            @php
                $path = public_path('image/logo_ub.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
            <img src="{{ $logo }}" alt="Logo">
            <div class="header-content">
                <h1>DIREKTORAT PENGEMBANGAN KARIR DAN ALUMNI</h1>
                <h2>UNIVERSITAS BRAWIJAYA</h2>
                <p>Office: Jl. Veteran Malang Telp. 0341 583787 Fax. 0341 575453<br>Telp. 0341 551611 Ext. 130<br>http://upkk.ub.ac.id - jpc@ub.ac.id</p>
            </div>
        </div>

        <div class="title">
            <h3>INVOICE</h3>
        </div>

        <div class="details">
            <table class="table table-borderless">
                <tr>
                    <td><strong>Nomor</strong></td>
                    <td>: {{ $model->id < 10 ? '0' . $model->id : $model->id }}/BCE-I/DPKA/II/{{ $model->created_at->format('Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Telah diterima dari</strong></td>
                    <td>: {{ $model->user->instansi }}</td>
                </tr>
                <tr>
                    <td><strong>Buat Pembayaran</strong></td>
                    <td>: Biaya Publikasi Acara {{ $model->judul }}<br>Tanggal {{ \Carbon\Carbon::parse($model->tanggal)->translatedFormat('j F Y') }}</td>
                </tr>
            </table>

            <table class="table table-striped rincian-table">
                <thead>
                    <tr>
                        <th class="text-start">Deskripsi</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-start">Biaya Publikasi</td>
                        <td class="text-end">{{ $model->biayaPublikasi }}</td>
                    </tr>
                    @foreach ($tambahanOpsional as $opsi)
                        <tr>
                            <td class="text-start">{{ $opsi->nama }}</td>
                            <td class="text-end">{{ $opsi->biaya }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-start"><strong>Total Harga</strong></td>
                        @php
                            $harga = $model->biayaPublikasi;
                            foreach ($tambahanOpsional as $opsi) {
                                $harga += $opsi->biaya;
                            }
                        @endphp
                        <td class="text-end"><strong>Rp. {{ $harga }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-start">PPN</td>
                        <td class="text-end">Rp. {{ $model->PPN }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-start"><strong>Grand Total</strong></td>
                        <td><strong>Rp. {{ $model->totalBiaya }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="amount">
            @include('template.fungsi_terbilang')
            <p>Terbilang: {{ terbilang($model->totalBiaya) }} Rupiah</p>
        </div>

        <div class="footer">
            <p>Transfer ke Virtual Account Universitas Brawijaya:<br>Mandiri : 1440001120234<br>a/n Karuniiawan Puji Wicaksono . Brawijaya Career Expo {{ \Carbon\Carbon::today()->format('Y') }}</p>
            <div class="signature">
                <p>Malang, {{ \Carbon\Carbon::parse($model->updated_at)->translatedFormat('j F Y') }}</p>
                <p><strong>Andy Sulistyowatik</strong></p>
            </div>
        </div>
    </div>
</body>
</html>
