<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
        }
        
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #eefaf6;
            position: relative;
            overflow: hidden;
            padding: 20px;
        }

        /* Mesh Gradient Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.6;
            z-index: 1;
        }
        .orb-1 {
            top: -10%; left: -10%;
            width: 50vw; height: 50vw;
            background: #b2ebf2;
        }
        .orb-2 {
            bottom: -10%; right: -10%;
            width: 50vw; height: 50vw;
            background: #c8e6c9;
        }
        .orb-3 {
            top: 20%; right: 15%;
            width: 30vw; height: 30vw;
            background: #e0f7fa;
            opacity: 0.5;
        }

        .login-content {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo-box {
            width: 64px;
            height: 64px;
            background: #10b981;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(16,185,129,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            color: white;
        }

        .app-title {
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }

        .app-subtitle {
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            margin: 0 0 32px 0;
        }

        .glass-card {
            width: 100%;
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 32px;
            padding: 40px 32px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.04);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            text-align: center;
            margin: 0 0 24px 0;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            display: flex;
            pointer-events: none;
        }

        .custom-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            padding: 14px 16px 14px 48px;
            font-size: 15px;
            color: #1f2937;
            transition: all 0.2s;
            box-sizing: border-box;
            outline: none;
        }

        .custom-input:focus {
            background: #ffffff;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16,185,129,0.1);
        }
        
        .custom-input::placeholder {
            color: #9ca3af;
        }

        .eye-btn {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            display: flex;
            padding: 4px;
        }
        .eye-btn:hover {
            color: #4b5563;
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .custom-checkbox {
            appearance: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .custom-checkbox:checked {
            background: #10b981;
            border-color: #10b981;
        }

        .custom-checkbox::after {
            content: '';
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            display: none;
        }

        .custom-checkbox:checked::after {
            display: block;
        }

        .forgot-link {
            font-size: 13px;
            font-weight: 700;
            color: #10b981;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #059669;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(to right, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 20px rgba(16,185,129,0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16,185,129,0.4);
        }

        .footer-text {
            text-align: center;
            font-size: 12px;
            font-weight: 500;
            color: #9ca3af;
            margin-top: 32px;
        }
    </style>

    <div class="login-wrapper">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>

        <div class="login-content">
            <div class="logo-box">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path>
                </svg>
            </div>
            <h1 class="app-title">SMART-TPQ</h1>
            <p class="app-subtitle">Sistem Manajemen Absensi TPQ</p>

            <div class="glass-card">
                <h2 class="card-title">Masuk ke Akun</h2>

                <x-validation-errors class="mb-4" />

                @session('status')
                    <div style="margin-bottom: 16px; font-size: 14px; font-weight: 500; color: #16a34a; text-align: center;">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="input-group">
                        <div class="input-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="username" placeholder="Username / Email" class="custom-input" />
                    </div>

                    <div class="input-group" x-data="{ show: false }">
                        <div class="input-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="Password" class="custom-input" />
                        <button type="button" @click="show = !show" class="eye-btn">
                            <svg x-show="!show" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg x-show="show" style="display:none;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>

                    <div class="options-row">
                        <label class="checkbox-label" for="remember_me">
                            <input type="checkbox" id="remember_me" name="remember" class="custom-checkbox" />
                            {{ __('Ingat Saya') }}
                        </label>

                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                {{ __('Lupa Password?') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="login-btn">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                        Masuk
                    </button>
                </form>
            </div>

            <p class="footer-text">SMART-TPQ &copy; {{ date('Y') }}. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</x-guest-layout>
