<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Login Admin</title>

    <link href="{{ asset('assets/admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

<div class="container">

    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">

                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image">
                            <img src="{{ asset('assets/img/masjid.jpg') }}" alt="Masjid" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <div class="col-lg-6">
                            <div class="p-5">

                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Login Admin</h1>
                                </div>

                                <form class="user" method="POST" action="{{ route('admin.login.submit') }}">
                                    @csrf

                                    <!-- Username -->
                                    <div class="form-group">
                                        <input type="text"
                                            name="username"
                                            class="form-control form-control-user @error('username') is-invalid @enderror"
                                            placeholder="Username"
                                            value="{{ old('username') }}"
                                            required
                                            autofocus
                                            autocomplete="username">

                                        @error('username')
                                            <div class="invalid-feedback text-center">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group">
                                        <input type="password"
                                            name="password"
                                            class="form-control form-control-user @error('password') is-invalid @enderror"
                                            placeholder="Password"
                                            required
                                            autocomplete="current-password">

                                        @error('password')
                                            <div class="invalid-feedback text-center">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<script src="{{ asset('assets/admin/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/sb-admin-2.min.js') }}"></script>

</body>
</html>
