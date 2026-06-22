<!DOCTYPE html>
<html>
<head>
    <title>KRS Pending</title>
</head>
<body>

<h2>KRS Pending</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Mahasiswa</th>
        <th>NIM</th>
        <th>Mata Kuliah</th>
        <th>SKS</th>
        <th>Aksi</th>
    </tr>

    @foreach($krs as $item)
    <tr>
        <td>{{ $item['mahasiswa'] }}</td>
        <td>{{ $item['nim'] }}</td>
        <td>{{ $item['mata_kuliah'] }}</td>
        <td>{{ $item['sks'] }}</td>
        <td>
            <button onclick="approve({{ $item['id'] }})">Approve</button>
            <button onclick="reject({{ $item['id'] }})">Reject</button>
        </td>
    </tr>
    @endforeach

</table>

<script>
function approve(id) {
    fetch('/api/admin/krs/approve/' + id, { method: 'POST' })
        .then(() => location.reload());
}

function reject(id) {
    fetch('/api/admin/krs/reject/' + id, { method: 'POST' })
        .then(() => location.reload());
}
</script>

</body>
</html>