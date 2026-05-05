<!DOCTYPE html>
<html>
<head>
    <title>Licence Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Pemberitahuan Kadaluarsa Licence</h2>
    <p>Halo,</p>
    <p>Ini adalah pengingat otomatis bahwa licence Anda untuk <strong>{{ $licence->name }}</strong> memasuki masa peringatan: <strong>{{ $period }}</strong>.</p>
    
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 600px;">
        <tr>
            <th style="background-color: #f2f2f2; text-align: left; width: 30%;">ID Licence</th>
            <td>{{ $licence->id }}</td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2; text-align: left;">Nama Licence</th>
            <td>{{ $licence->name }}</td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2; text-align: left;">Company Name (Vendor)</th>
            <td>{{ $licence->vendor_name }}</td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2; text-align: left;">Tanggal Expired</th>
            <td style="{{ $period == 'Sudah Expired' ? 'color: red; font-weight: bold;' : '' }}">
                {{ \Carbon\Carbon::parse($licence->period_end)->format('d F Y') }}
            </td>
        </tr>
    </table>

    <p>Mohon segera lakukan pengecekan dan perpanjangan jika diperlukan.</p>
    
    <p style="margin-top: 20px;">
        <a href="{{ route('licences.show', $licence->id) }}" style="background-color: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            Lihat Detail Lisensi
        </a>
    </p>

    <p style="margin-top: 30px;">Terima kasih.</p>
</body>
</html>
