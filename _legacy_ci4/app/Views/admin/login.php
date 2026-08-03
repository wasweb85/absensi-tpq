<?= $this->extend('templates/starting_page_layout'); ?>

<?= $this->section('content'); ?>
<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: url('<?= base_url("assets/img/bg-login.jpg") ?>') no-repeat center center fixed;
        background-size: cover;
    }
    .navbar {
        display: none !important;
    }
    .bg {
        filter: blur(8px);
        transform: scale(1.1);
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background: url('<?= base_url("assets/img/bg-login.jpg") ?>') no-repeat center center;
        background-size: cover;
    }
    .wrapper-login {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
        z-index: 1;
    }
    .login-container {
        display: flex;
        width: 100%;
        max-width: 1000px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 40px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }
    .login-left {
        flex: 1.2;
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: rgba(255, 255, 255, 0.03);
    }
    .login-right {
        flex: 1;
        padding: 60px;
        background: rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .login-right h2 {
        color: #fff;
        font-weight: 700;
        margin-bottom: 30px;
        font-size: 2.2rem;
        text-align: center;
    }
    .school-logo {
        width: 80px;
        height: auto;
        margin-bottom: 30px;
        border-bottom: 4px solid #4A6CF7;
        padding-bottom: 12px;
        border-radius: 4px;
    }
    .brand-title {
        font-size: 4rem;
        font-weight: 800;
        margin: 0;
        line-height: 1;
        color: #4A6CF7;
        letter-spacing: -2px;
        text-transform: uppercase;
    }
    .brand-subtitle {
        font-size: 2.5rem;
        font-weight: 700;
        color: #fff;
        margin-top: 5px;
        text-transform: uppercase;
    }
    .brand-desc {
        color: rgba(255, 255, 255, 0.6);
        margin-top: 50px;
        font-size: 1.1rem;
        max-width: 320px;
        font-style: italic;
    }
    .brand-desc span {
        color: #4A6CF7;
        font-weight: 600;
        font-style: normal;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    .form-group label {
        display: none !important;
    }
    .form-control {
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        border-radius: 15px !important;
        padding: 16px 20px !important;
        color: #fff !important;
        height: auto !important;
        font-size: 1rem !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }
    .form-control:focus {
        background: rgba(255, 255, 255, 0.12) !important;
        border-color: rgba(255, 255, 255, 0.4) !important;
        transform: scale(1.02);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    .btn-login {
        background: #4A6CF7 !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 16px !important;
        font-weight: 700 !important;
        width: 100%;
        color: #fff !important;
        margin-top: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: capitalize;
        font-size: 1.1rem;
        box-shadow: 0 8px 20px rgba(74, 108, 247, 0.3);
    }
    .btn-login:hover {
        background: #3a5bd9 !important;
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(74, 108, 247, 0.5);
    }
    
    @media (max-width: 992px) {
        .brand-title { font-size: 3.2rem; }
        .brand-subtitle { font-size: 2rem; }
    }
    @media (max-width: 768px) {
        .login-container {
            flex-direction: column;
            border-radius: 30px;
            max-width: 480px;
        }
        .login-left {
            padding: 40px 30px;
            text-align: center;
            align-items: center;
        }
        .school-logo {
            width: 50px;
            margin-bottom: 20px;
            padding-bottom: 8px;
        }
        .brand-title {
            font-size: 1.8rem;
        }
        .brand-subtitle {
            font-size: 1.2rem;
        }
        .brand-desc { display: none; }
        .login-right {
            padding: 40px 30px;
        }
    }
</style>

<div class="wrapper-login">
    <div class="login-container">
        <!-- Kolom Kiri: Branding -->
        <div class="login-left">
            <img src="<?= base_url('uploads/logo/logo-tpq.png') ?>" alt="Logo" class="school-logo">
            <h1 class="brand-title">E-ABSENSI</h1>
            <h2 class="brand-subtitle">TPQ</h2>
            <p class="brand-desc">Sistem <span>Absensi</span> Mudah <span>Cepat</span></p>
        </div>

        <!-- Kolom Kanan: Form Login -->
        <div class="login-right">
            <h2>Login</h2>
            <?= view('\App\Views\admin\_message_block') ?>

            <form action="<?= url_to('login') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <input type="text" name="login" class="form-control <?php if (session('errors.login')): ?>is-invalid<?php endif ?>" placeholder="Username / NIS / Email" autofocus>
                    <div class="invalid-feedback">
                        <?= session('errors.login') ?>
                    </div>
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-control <?php if (session('errors.password')): ?>is-invalid<?php endif ?>" placeholder="Password / NIS (Siswa)">
                    <div class="invalid-feedback">
                        <?= session('errors.password') ?>
                    </div>
                </div>

                <?php if ($config->allowRemembering): ?>
                    <div class="form-check mb-4 p-0 ml-4">
                        <label class="form-check-label text-white">
                            <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')): ?> checked <?php endif ?>>
                            <?= lang('Auth.rememberMe') ?>
                        </label>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn-login">Sign in</button>

                <?php if ($config->activeResetter): ?>
                    <div class="text-center mt-3">
                        <a href="<?= url_to('forgot') ?>" class="text-white small"><?= lang('Auth.forgotYourPassword') ?>?</a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
