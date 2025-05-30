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
            font-family: Arial, Helvetica, sans-serif;
            max-width: 770px;
        }

        .invoice-container {
            width: 770px;
            padding: 20px;
        }

        .content-container{
            width: 770px;
            border: 1px solid black;
        }

        .alamat-font{
            font-size: 12px;
        }

        .header-font{
            font-size: 18.62px;
        }

        .top{
            vertical-align: top;
            text-align: left;
        }

        .table-content-font{
            font-size: 13.33px;
        }

        .paralellogram{
            width: fit-content;
            padding: 10px 30px;
            transform: skew(-30deg);
            border: 1px solid black;
            box-shadow: 2px 2px 5px rgb(46, 46, 46);
        }

        .paralellogram-text{
            transform: skew(30deg);
            font-size: 15.96px;
        }

        .total-price-font{
            font-size: 15.96px;
        }


    </style>
</head>
<body>
    <div class="invoice-container">
        <table class="header">
            <tr>
                <td rowspan="3"><img src="https://wiki.ub.ac.id/lib/exe/fetch.php?cache=&media=ub-logo-small.png" alt="Logo" width="100" height="100"></td>
                <td><b><p class="header-font m-0">DIREKTORAT PENGEMBANGAN</p></b></td>
            </tr>
            <tr>
                <td><b><p class="header-font m-0">KARIR DAN ALUMNI</p></b></td>
            </tr>
            <tr>
                <td><p class="header-font m-0" style="color: white; background-color: black; -webkit-print-color-adjust: exact; padding-left: 2px; width: 120%;"><strong>UNIVERSITAS BRAWIJAYA</strong></p></td>
            </tr>
        </table>
        <table style="width: 100%;">
            @php
                $path = public_path('image/invoice.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
            <tr>
                <td><p class="alamat-font" style="margin-bottom: 0;">Office:</p></td>
                <td rowspan="4" style="padding: 0;">
                    <div style="display: flex; flex-direction: column; justify-content: flex-end; height: 100%;">
                      <div style="display: flex; justify-content: flex-end;">
                        <img src="{{$base64}}" alt="" width="150">
                      </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><p class="alamat-font" style="margin-bottom: 0;">Jl. Veteran Malang Telp. 0341 583787 Fax. 0341 575453</p></td>
            </tr>
            <tr>
                <td><p class="alamat-font" style="margin-bottom: 0;">Telp. 0341 551611 Ext. 130</p></td>
            </tr>
            <tr>
                <td class="d-flex">
                    <a class="alamat-font" href="http://upkk.ub.ac.id" style="margin-right: 5px;">http://upkk.ub.ac.id</a>
                    <p class="alamat-font"> - </p>
                    <a class="alamat-font" href="mailto:jpc@ub.ac.id">jpc@ub.ac.id</a>
                </td>
            </tr>
        </table>
        <div class="content-container">
            <table border="0" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%;" class="table-content-font">
                <tr>
                  <td class="top">Nomor</td>
                  <td class="top">:</td>
                  <td class="top">{{ $model->id < 10 ? '0' . $model->id : $model->id }}/BCE-I/DPKA/II/{{ $model->created_at->format('Y') }}</td>
                </tr>
                <tr>
                  <td class="top">Telah terima dari</td>
                  <td class="top">:</td>
                  <td class="top"> <strong>{{ $model->user->instansi }}</strong></td>
                </tr>
                <tr>
                  <td class="top">Buat Pembayaran</td>
                  <td class="top"><p>:</p></td>
                  <td class="w-75 top">
                    Biaya Publikasi Kegiatan {{ $model->judul }}<br>
                    Tanggal {{ \Carbon\Carbon::parse($model->tanggal)->translatedFormat('j F Y') }}<br>
                    Dengan Rincian Sebagai Berikut:<br>
                    <table>
                        <tr>
                            <td>Biaya Publikasi</td>
                            <td>:</td>
                            <td class="text-end">Rp. {{number_format($model->hargaPublikasi, 0, ',', thousands_separator: '.')}}</td>
                        </tr>
                        @foreach ($tambahanOpsional as $opsi)
                            <tr>
                                <td>{{ $opsi->nama }}</td>
                                <td>:</td>
                                <td class="text-end">Rp. {{number_format((int)$opsi->harga,0, ',', '.')}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-start">PPN</td>
                            <td class="text-end">Rp. {{number_format($model->PPN, 0, ',', thousands_separator: '.')}}</td>
                        </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                    @include('template.fungsi_terbilang')
                    <td class="top">Terbilang</td>
                    <td class="top">:</td>
                    <td class="top">{{ terbilang($model->totalHarga) }} Rupiah</td>
                </tr>
                <tr>
                    <td class="top">
                        <p class="m-0">Jumlah</p>
                        <i><small>*include tax</small></i>
                    </td>
                    <td class="top">:</td>
                    <td class="top">
                        <div class="paralellogram">
                            <b><p class="my-auto paralellogram-text">Rp. {{number_format((int)$model->totalHarga,0, ',', '.')}},-*  </p></b>
                        </div>
                    </td>
                  </tr>
              </table>
              <table style="border: 0; margin-top: 10px; width: 100%; margin-bottom: 15px;" class="table-content-font">
                <tr>
                    <td class="w-75 top">
                        @if($model['payment_type'] == 'transfer')
                            <b><i><u>
                                <p class="m-0 ms-2">Transfer ke Virtual Account Universitas Brawijaya:</p>
                                <p class="m-0 ms-2">Mandiri: 1440001120234 a/n Karuniawan Puji Wicaksono</p>
                            </u></i></b>
                        @else
                            <b><i><u>
                                <p class="m-0 ms-2">Pembayaran bisa langsung dilakukan di : </p>
                                <p class="m-0 ms-2">Kantor DPKA Universitas Brawijaya,</p>
                                <p class="m-0 ms-2">Jl. Veteran, Kota Malang.</p>
                            </u></i></b>
                        @endif
                    </td>
                    <td class="w-525">
                        <p class="text-end pe-3" >Malang, {{ \Carbon\Carbon::parse($model->created_at)->translatedFormat('j F Y') }}</p>
                        <b><p class="text-end pe-3" style="margin-bottom: 0; margin-top: 40px;">{{ $confirmedBy }}</p></b>
                    </td>
                </tr>
              </table>
        </div>
    </div>
</body>
</html>
