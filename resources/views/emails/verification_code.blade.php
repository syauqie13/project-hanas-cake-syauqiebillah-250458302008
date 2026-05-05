<!DOCTYPE html>
<html>
<head>
    <title>Kode Verifikasi Email</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; text-align: center; }
        .code { font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #6777ef; margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px; }
        p { color: #555; line-height: 1.5; }
        .footer { margin-top: 30px; font-size: 12px; color: #aaa; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Halo, {{ $user->name }}!</h2>
        <p>Terima kasih telah mendaftar di Hana's Cake. Untuk menyelesaikan pendaftaran Anda, silakan masukkan kode verifikasi berikut:</p>
        
        <div class="code">{{ $code }}</div>
        
        <p>Kode ini hanya berlaku sementara. Jika Anda tidak mendaftar di situs kami, abaikan saja email ini.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} Hana's Cake. Semua hak cipta dilindungi.
        </div>
    </div>
</body>
</html>
