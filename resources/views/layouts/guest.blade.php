<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beezam Auth')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .guest-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            width: 100%;
            max-width: 420px;
        }

        .guest-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .guest-logo .logo-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }

        .guest-logo .logo-icon i {
            font-size: 32px;
            color: #fff;
        }

        .guest-logo h4 {
            font-weight: 700;
            color: #1a73e8;
            margin: 0;
        }

        .guest-logo p {
            color: #999;
            font-size: 13px;
            margin: 0;
        }

        .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.15);
        }

        .btn-login {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            border: none;
            color: #fff;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            font-size: 15px;
        }

        .btn-login:hover {
            opacity: 0.9;
            color: #fff;
        }
    </style>

    @yield('styles')
</head>
<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>