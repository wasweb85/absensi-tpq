<?= $this->extend('templates/layout_siswa_modern'); ?>
<?= $this->section('content'); ?>



<section class="section-content" style="padding-top: 20px;">
    
    <!-- Profile Main Card -->
    <div class="profile-main-card">
        <div class="avatar-wrapper">
            <div class="profile-avatar-big">
                <i class="material-icons">account_circle</i>
            </div>
            <div class="verified-badge">
                <i class="material-icons" style="font-size: 16px;">verified</i>
            </div>
        </div>
        <span class="profile-role-label">Siswa</span>
        <h1 class="profile-name-big"><?= $user ?></h1>
        <p class="profile-class-sub">Jilid: <?= $kelasInfo->kelas ?? 'N/A' ?></p>
        
        <div class="badge-row">
            <div class="id-pill">ID:2026-<?= $nis ?></div>
            <div class="status-pill">Aktif</div>
        </div>
    </div>

    <!-- Attendance Rate Large Card -->
    <div class="attendance-rate-large-card">
        <span class="rate-title-small">ATTENDANCE RATE</span>
        <div class="rate-percent-huge"><?= $attendanceRate ?><span>%</span></div>
        <div class="rate-progress-wrap">
            <div class="rate-progress-fill" style="width: <?= $attendanceRate ?>%;"></div>
        </div>
        <div class="rate-summary-text">
            <?= $summary['hadir'] ?> Hadir • <?= $summary['izin'] ?> Izin • <?= $summary['alpha'] ?> Alpha • <?= $summary['sakit'] ?> Sakit
        </div>
    </div>

    <!-- Account Settings Group -->
    <div class="settings-section">
        <div class="settings-header">
            <i class="material-icons">settings</i>
            <h3>Account Settings</h3>
        </div>
        <div class="settings-list">
            <a href="#" class="setting-item">
                <div class="setting-icon-box">
                    <i class="material-icons">person_add_alt</i>
                </div>
                <div class="setting-info">
                    <span class="setting-title">Personal Information</span>
                    <span class="setting-desc">Update your name, photo, and bio</span>
                </div>
                <i class="material-icons" style="color: #cbd5e1; font-size: 20px;">chevron_right</i>
            </a>
            <a href="#" class="setting-item">
                <div class="setting-icon-box">
                    <i class="material-icons">notifications_active</i>
                </div>
                <div class="setting-info">
                    <span class="setting-title">Notification Preferences</span>
                    <span class="setting-desc">Manage alerts and reminders</span>
                </div>
                <i class="material-icons" style="color: #cbd5e1; font-size: 20px;">chevron_right</i>
            </a>
            <a href="#" class="setting-item">
                <div class="setting-icon-box">
                    <i class="material-icons">lock</i>
                </div>
                <div class="setting-info">
                    <span class="setting-title">Privacy & Security</span>
                    <span class="setting-desc">Password and biometric access</span>
                </div>
                <i class="material-icons" style="color: #cbd5e1; font-size: 20px;">chevron_right</i>
            </a>
        </div>
    </div>

    <!-- Support & Legal Group -->
    <div class="settings-section">
        <div class="settings-header">
            <i class="material-icons">help_outline</i>
            <h3>Support & Legal</h3>
        </div>
        <div class="settings-list">
            <a href="#" class="setting-item">
                <div class="setting-icon-box">
                    <i class="material-icons">headset_mic</i>
                </div>
                <div class="setting-info">
                    <span class="setting-title">Contact Sanctuary Support</span>
                    <span class="setting-desc">We're here to help 24/7</span>
                </div>
                <i class="material-icons" style="color: #cbd5e1; font-size: 20px;">chevron_right</i>
            </a>
            <a href="#" class="setting-item">
                <div class="setting-icon-box">
                    <i class="material-icons">description</i>
                </div>
                <div class="setting-info">
                    <span class="setting-title">Knowledge Base</span>
                    <span class="setting-desc">Student guidelines and FAQ</span>
                </div>
                <i class="material-icons" style="color: #cbd5e1; font-size: 20px;">chevron_right</i>
            </a>
            <a href="#" class="setting-item">
                <div class="setting-icon-box">
                    <i class="material-icons">verified_user</i>
                </div>
                <div class="setting-info">
                    <span class="setting-title">Terms of Service</span>
                    <span class="setting-desc">Privacy policy and legal data</span>
                </div>
                <i class="material-icons" style="color: #cbd5e1; font-size: 20px;">chevron_right</i>
            </a>
        </div>
    </div>

    <!-- Sign Out -->
    <a href="<?= base_url('siswa/logout') ?>" class="sign-out-btn">
        <i class="material-icons">logout</i>
        <span>Sign out of Account</span>
    </a>

    <p class="app-version-text">APP VERSION 2.4.0 (BUILD 902)</p>

</section>

<?= $this->endSection(); ?>
