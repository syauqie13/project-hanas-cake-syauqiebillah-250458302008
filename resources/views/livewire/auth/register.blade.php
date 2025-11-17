@section('title', 'Register')

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

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        #app {
            position: relative;
            z-index: 1;
        }

        .section {
            padding: 3rem 0;
        }

        /* Logo Brand */
        .login-brand {
            margin-bottom: 2rem;
            text-align: center;
            animation: fadeInDown 0.8s ease;
        }

        .login-brand .logo-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .login-brand .logo-container::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            top: -50%;
            left: -50%;
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .login-brand .logo-text {
            font-size: 4rem;
            position: relative;
            z-index: 1;
        }

        .login-brand h2 {
            margin-top: 1rem;
            font-size: 1.8rem;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -1px;
        }

        /* Card Styles */
        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.8s ease;
            overflow: hidden;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem;
        }

        .card-header h4 {
            color: #fff;
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .card-body {
            padding: 2.5rem;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 0.85rem 1.2rem;
            color: #fff;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            color: #fff;
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-control.is-invalid {
            border-color: #ff6b9d;
            box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.2);
        }

        .invalid-feedback {
            color: #ff6b9d;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 700;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Footer Text */
        .text-muted {
            color: rgba(255, 255, 255, 0.6) !important;
            font-size: 0.95rem;
        }

        .text-muted a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .text-muted a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .simple-footer {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.85rem;
            text-align: center;
            margin-top: 1.5rem;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading Animation */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        [wire\:loading] {
            display: inline-block;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem;
            }

            .login-brand .logo-container {
                width: 100px;
                height: 100px;
            }

            .login-brand .logo-text {
                font-size: 3rem;
            }

            .login-brand h2 {
                font-size: 1.5rem;
            }

            .card-header h4 {
                font-size: 1.5rem;
            }
        }

        /* Auto-fill styling */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #fff;
            -webkit-box-shadow: 0 0 0px 1000px rgba(102, 126, 234, 0.2) inset;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
@endsection

@section('js')
    <!-- General JS Scripts -->
    <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
@endsection

<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div
                    class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-5 offset-xl-3">

                    <!-- Logo -->
                    <div class="login-brand">
                        <div class="logo-container">
                            <span class="logo-text">🧁</span>
                        </div>
                        <h2>HANA'S CAKE</h2>
                    </div>

                    <!-- Register Card -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Create Account</h4>
                        </div>

                        <div class="card-body">
                            <form wire:submit.prevent="register" novalidate>

                                <!-- Name -->
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input id="name" type="text" wire:model="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Enter your full name" tabindex="1" required autofocus
                                        autocomplete="name">
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input id="email" type="email" wire:model="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Enter your email" tabindex="2" required autocomplete="username">
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group">
                                    <label for="password" class="control-label">Password</label>
                                    <input id="password" type="password" wire:model="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Create a strong password" tabindex="3" required
                                        autocomplete="new-password">
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-group">
                                    <label for="password_confirmation" class="control-label">Confirm Password</label>
                                    <input id="password_confirmation" type="password" wire:model="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        placeholder="Re-enter your password" tabindex="4" required
                                        autocomplete="new-password">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="5"
                                        wire:loading.attr="disabled">
                                        <span wire:loading.remove>Register Now</span>
                                        <span wire:loading>
                                            <i class="fas fa-spinner fa-spin"></i> Processing...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-4 text-center text-muted">
                        Already have an account? <a href="{{ route('login') }}">Sign In</a>
                    </div>
                    <div class="simple-footer">
                        Copyright &copy; {{ date('Y') }} Hana's Cake. All Rights Reserved.
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
