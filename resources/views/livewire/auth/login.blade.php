@section('title', 'Login')

@section('css')
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-social/bootstrap-social.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">

    <!-- Custom Modern CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a0a2e 50%, #16213e 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.15), transparent);
            border-radius: 50%;
            top: -250px;
            right: -250px;
            animation: pulse 8s infinite;
        }

        body::after {
            content: '';
            position: fixed;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(240, 147, 251, 0.1), transparent);
            border-radius: 50%;
            bottom: -200px;
            left: -200px;
            animation: pulse 6s infinite;
        }

        @keyframes pulse {
            0%,100% { transform: scale(1); opacity: .5; }
            50% { transform: scale(1.1); opacity: .8; }
        }

        #app { position: relative; z-index: 1; }

        .section { padding: 3rem 0; }

        /* Logo */
        .login-brand {
            margin-bottom: 2rem;
            text-align: center;
            animation: fadeInDown 0.8s ease;
        }

        .login-brand .logo-container {
            display: inline-flex; justify-content: center; align-items: center;
            width: 120px; height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
            position: relative; overflow: hidden;
        }

        .login-brand .logo-container::before {
            content: ''; position: absolute;
            width: 200%; height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,.1), transparent);
            top: -50%; left: -50%;
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logo-text { font-size: 4rem; }

        .login-brand h2 {
            margin-top: 1rem; font-size: 1.8rem; font-weight: 900;
            background: linear-gradient(135deg, #667eea, #764ba2, #f093fb);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        /* Card */
        .card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeInUp .8s ease;
        }

        .card-header { background: transparent; border-bottom: 1px solid rgba(255,255,255,0.1); padding: 2rem; }
        .card-header h4 { color: white; font-size: 1.8rem; font-weight: 800; }

        .card-body { padding: 2.5rem; }

        /* Form */
        .form-group label { color: rgba(255,255,255,.9); font-weight: 600; margin-bottom: 0.5rem; }
        .form-control {
            background: rgba(255,255,255,0.08);
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.15);
            padding: .85rem 1.2rem;
            color: white;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.1);
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,.2);
        }
        .invalid-feedback { color: #ff6b9d; }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(102,126,234,.4);
        }

        /* Footer */
        .text-muted { color: rgba(255,255,255,.6) !important; }

        @keyframes fadeInUp { from {opacity:0;transform:translateY(30px);} to {opacity:1;transform:translateY(0);} }
        @keyframes fadeInDown { from {opacity:0;transform:translateY(-30px);} to {opacity:1;transform:translateY(0);} }

        @media(max-width:576px){
            .card-body { padding: 1.5rem; }
            .logo-container { width:100px;height:100px; }
            .logo-text { font-size:3rem; }
        }
    </style>
@endsection

@section('js')
    <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
@endsection

<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-5 offset-xl-3">

                    <!-- Logo -->
                    <div class="login-brand">
                        <div class="logo-container">
                            <span class="logo-text">🧁</span>
                        </div>
                        <h2>HANA'S CAKE</h2>
                    </div>

                    <!-- Login Card -->
                    <div class="card card-primary">
                        <div class="card-header"><h4>Welcome Back</h4></div>

                        <div class="card-body">
                            <form wire:submit.prevent="login">

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input id="email" type="email" wire:model="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Enter your email" required autocomplete="email">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group">
                                    <label for="password" class="control-label">Password</label>
                                    <input id="password" type="password" wire:model="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Enter your password" required autocomplete="current-password">
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- Remember Me -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" wire:model="remember" class="custom-control-input" id="remember-me">
                                        <label class="text-black custom-control-label" for="remember-me">Remember Me</label>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" wire:loading.attr="disabled">
                                        <span wire:loading.remove>Login</span>
                                        <span wire:loading><i class="fas fa-spinner fa-spin"></i> Processing...</span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-4 text-center text-muted">
                        Don't have an account? <a href="{{ route('register') }}">Create One</a>
                    </div>
                    <div class="simple-footer">
                        &copy; {{ date('Y') }} Hana's Cake. All Rights Reserved.
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
