<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Medalla Milagrosa</title>
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .login-background {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            background: url('{{ asset('imagenes/medalla-fondo.jpg') }}') no-repeat center center fixed;
            background-size: cover;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .form-check-label {
            margin-left: 8px; 
        }

        .error-message {
            color: #dc3545; 
            font-weight: bold; 
        }
    </style>
</head>

<body class="hold-transition login-page">
    <section class="login-background">
        <div class="overlay"></div>
        <div class="card auth-card shadow-none d-flex justify-content-center mb-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ asset('imagenes/medalla-logo.png') }}" alt="Logo de la Escuela" width="100" class="me-3">
                    <h3 class="mb-0">Registrarse</h3>
                </div>

                @if ($errors->any())
                <div class="mb-4">
                    <ul class="list-unstyled error-message">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-outline mb-4">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end">
                                <i class="bi bi-person-fill"></i>
                            </span>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control form-control-lg" placeholder="Nombre completo" required autofocus />
                        </div>
                    </div>

                    <div class="form-outline mb-4">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end">
                                <i class="fas fa-envelope"></i>
                            </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" placeholder="Email" required />
                        </div>
                    </div>

                    <div class="form-outline mb-4">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end">
                                <i class="bi bi-unlock-fill"></i>
                            </span>
                        <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Contraseña" required autocomplete="new-password" />
                        </div>
                    </div>

                    <div class="form-outline mb-4">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg" placeholder="Confirmar contraseña" required autocomplete="new-password" />
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mb-4">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Registrarse</button>
                    </div>

                    <p class="mt-3 text-center">
                        ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="text-underline">Iniciar sesión</a>
                    </p>

                </form>

            </div>
        </div>
    </section>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
</body>

</html>
