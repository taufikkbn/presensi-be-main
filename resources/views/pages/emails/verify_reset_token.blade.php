<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Token</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <div class="w-screen h-screen flex justify-center items-center flex-col">
        <h1 class="text-2xl">Reset Password {{ $status ? 'Berhasil' : 'Gagal' }}</h1>
        <p class="text-slate-500">
            {{ $status ? 'Password baru akan dikirimkan ke email anda, silahkan cek email anda' : $msg }}
        </p>
    </div>
</body>

</html>
