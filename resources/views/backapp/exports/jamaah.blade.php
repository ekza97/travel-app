<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kategori</th>
            <th>Agent</th>
            <th>Jadwal</th>
            <th>NIK</th>
            <th>Nama Lengkap</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>JK</th>
            <th>No. Telp</th>
            <th>Status Nikah</th>
            <th>Pekerjaan</th>
            <th>Alamat</th>
            <th>Nama Ahli Waris</th>
            <th>Hubungan Ahli Waris</th>
            <th>No. Telp Ahli Waris</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jamaah as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->categories->name }}</td>
                <td>{{ $item->agents->fullname }}</td>
                <td>{{ $item->schedules->name }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->fullname }}</td>
                <td>{{ $item->pob }}</td>
                <td>{{ $item->dob }}</td>
                <td>{{ $item->gender }}</td>
                <td>{{ $item->phone }}</td>
                <td>{{ $item->martial_status }}</td>
                <td>{{ $item->profession }}</td>
                <td>{{ $item->address }}</td>
                <td>{{ $item->heir_name }}</td>
                <td>{{ $item->heir_relation }}</td>
                <td>{{ $item->heir_phone }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
