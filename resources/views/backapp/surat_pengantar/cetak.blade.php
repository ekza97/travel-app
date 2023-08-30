<html>

<head>
    <style>
        h2 {
            padding: 0px;
            margin: 0px;
            font-size: 14pt;
        }

        h4 {
            font-size: 12pt;
        }

        text {
            padding: 0px;
        }

        table {
            border-collapse: collapse;
            font-size: 11pt;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        th,
        td {
            padding: 2px;
        }

        .tab th {
            border: 0.5px solid #000;
        }

        .tab td {
            border: 0.5px solid #000;
            padding: 3px;
        }

        table.tab {
            table-layout: auto;
            border: 0.5px solid #000;
            width: 100%;
            page-break-inside: auto;
        }

        table.no-border {
            table-layout: auto;
            border: 0px solid #000;
            width: 100%;
        }

        .rp {
            float: left;
            text-align: left;
        }

        .left {
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .b {
            font-weight: bold;
        }

        .i {
            font-style: italic;
        }

        .underline {
            text-decoration: underline;
        }

        .justify {
            text-align: justify;
        }
    </style>
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
    <div style="page-break-after:always;margin-top:0%;">
        <div class="center" style="margin-top:0%;float:left;margin-left:15px;margin-right:25px;">
            <img src="{{ asset('logo_kop.jpg') }}" width="80%" height="40">
        </div>
        <p style="font-size: 9pt;text-align:center;color:rgb(20, 152, 152);line-height:18px;">
            No.Ijin 132/Tahun 2020; Kantor Cabang : Jl.Kebun Cengkeh - Komplek Irman Jaya , Manokwari - Papua Barat;
            <br>
            ☎ HP. 0813-4447-3555; email: primaunggulmkw@gmail.com
        </p>
        <br>
        <hr style="border:rgb(20, 152, 152) 1px solid;margin-top:-10px;margin-bottom:-10px;">
        <br>
        <div style="line-height: 0px;padding:0px;">
            <h5 class="underline" style="font-size: 16pt;text-align:center;margin-bottom:25px;">SURAT KETERANGAN</h5>
            <p class="center">Izin Umroh No. 132 Tahun 2020</p>
        </div>
        <br>
        <table class="no-border">
            <tr>
                <td width="120">No</td>
                <td width="3">:</td>
                <td>{{ $row->fullnumber }}</td>
            </tr>
            <tr>
                <td width="120">Lampiran</td>
                <td width="3">:</td>
                <td>-</td>
            </tr>
            <tr>
                <td width="120">Perihal</td>
                <td width="3">:</td>
                <td>Permohonan Rekomendasi Passport</td>
            </tr>
        </table>
        <p>Kepada Yth : <br> Kepala Kantor KEMENAG <br> Di</p>
        <p style="margin-left: 70px;">TEMPAT</p>
        <br>
        <p>Dengan Hormat, bersama dengan ini kami PT. PRIMA UNGGUL menerangkan bahwa :</p>
        <table class="no-border">
            <tr>
                <td width="120">Nama</td>
                <td width="3">:</td>
                <td>{{ $row->jamaahs->fullname }}</td>
            </tr>
            <tr>
                <td width="120">Tempat/Tgl Lahir</td>
                <td width="3">:</td>
                <td>{{ $row->jamaahs->pob . ', ' . Helper::dateIndo($row->jamaahs->dob, 'd-m-Y') }}</td>
            </tr>
            <tr>
                <td width="120">Alamat</td>
                <td width="3">:</td>
                <td>{{ $row->jamaahs->address }}</td>
            </tr>
            <tr>
                <td width="120">NIK</td>
                <td width="3">:</td>
                <td>{{ $row->jamaahs->nik }}</td>
            </tr>
        </table>
        <p class="justify">Adalah benar calon jamaah Umroh dari PT. PRIMA UNGGUL yang diberangkatkan pada Tanggal
            <strong>{{ Helper::dateIndo($row->jamaahs->schedules->start_date) }}</strong> sampai dengan
            <strong>{{ Helper::dateIndo($row->jamaahs->schedules->end_date) }}</strong>.
        </p>
        <p class="justify">Adapun surat ini kami buat sebagai persyaratan permohonan rekomendasi Passport di Kantor
            Imigrasi.</p>
        <p class="justify">Permohonan yang kami ajukan ini adalah benar Warga Negara Indonesia yang akan melaksanakan
            perjalanan Ibadah Umroh ke Tanah Suci Arab Saudi.</p>
        <ol>
            <li class="justify">Adapun hal-hal yang menyangkut jamaah adalah sepenuhnya tanggung jawab PT. PRIMA UNGGUL
            </li>
            <li class="justify">Apabila ada hal yang bertentangan dengan peraturan atau mengingkari pernyataan ini maka
                kami dengan ini siap menerima sanksi sesuai dengan Undang-undang yang berlaku.</li>
        </ol>
        <p>Demikian surat pernyataan ini kami buat sebagaimana mestinya, atas kerjasamanya kami ucapkan Terima Kasih.
        </p>

        <table class="no-border">
            <tr>
                <td class="center" width="40%">
                    <p>Manokwari, {{ Helper::dateIndo(date('Y-m-d')) }} <br> <strong>PT. Prima Unggul Global</strong>
                    </p>
                    <br>
                    <br>
                    <br>
                    <h4 class="urderline">RINI SAFITRI</h4>
                </td>
                <td width="60%"></td>
            </tr>
        </table>
        <hr style="border:rgb(20, 152, 152) 1px solid;margin-top:5px;margin-bottom:5px;">
        <table class="no-border" style="color:rgb(20, 152, 152);">
            <tr>
                <td width="30%">
                    <p><strong>PT. Prima Unggul Global</strong> <br> Kantor Cabang Manokwari: <br> Jl. Kebun Cengkeh
                        <br> Irman Jaya - Manokwari <br> Tlp. 0813 4447 3555
                    </p>
                </td>
                <td width="30%">
                    <p>Head Office: <br> Jl. Sultan Alauddin <br> Komp. Ruko Alauddin Plaza <br> Blok BA No. 16 Makassar
                    </p>
                </td>
                <td width="40%">
                    <p>Tlp. 0411 898 7561 <br> Email: upgtourandtravel@yahoo.com</p>
                </td>
            </tr>
        </table>
    </div>

</body>


</html>

<script>
    window.print();
    window.onfocus = function() {
        window.close();
    }
</script>
