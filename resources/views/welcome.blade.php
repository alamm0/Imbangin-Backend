<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; background: #fdf7f4; color: #222; }
            .container { max-width: 720px; margin: 0 auto; padding: 48px 24px; }
            .card { background: white; border-radius: 16px; padding: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
            .btn { display: inline-block; margin-top: 16px; padding: 10px 16px; background: #6b1829; color: white; text-decoration: none; border-radius: 10px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <h1>IMBANGIN Backend</h1>
                <p>Laravel backend sedang berjalan dengan baik. Fitur chat AI siap dipakai dari halaman yang sudah terhubung.</p>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn">Ke Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn">Login</a>
                    @endauth
                @endif
            </div>
        </div>
    </body>
</html>
