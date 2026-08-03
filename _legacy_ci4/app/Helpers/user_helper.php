<?php

use App\Libraries\enums\UserRole;

// Fungsi ini tetap sama, aman.
function getUserRole(int|string $role): string
{
    return UserRole::from(intval($role))->label();
}

// --------------------------------------------------------------------------
// FUNGSI UTAMA (Dengan Pengaman Anti-Crash)
// --------------------------------------------------------------------------

// HAPUS ': UserRole' di sebelah kanan nama fungsi
function user_role() 
{
    // 1. CEK SAFETY: Apakah fungsi user() tersedia? (Halaman Siswa biasanya tidak punya)
    if (!function_exists('user')) {
        return null;
    }

    // 2. Ambil data user
    $u = user();

    // 3. Cek apakah user benar-benar login?
    if (!$u) {
        return null;
    }

    // 4. Jika aman, baru kembalikan Role
    return UserRole::from(intval($u->is_superadmin));
}

function is_wali_kelas(): bool
{
    // 1. CEK: Apakah fungsi user() ada? (Siswa tidak punya fungsi ini)
    if (!function_exists('user')) {
        return false;
    }
    
    // 2. CEK: Apakah user sedang login?
    $u = user();
    if (!$u) {
        return false;
    }

    // 3. JALANKAN KODE ASLI
    return !empty($u->id_guru);
}

function is_superadmin(): bool
{
    // 1. CEK KEAMANAN
    if (!function_exists('user') || !user()) {
        return false;
    }

    // 2. JALANKAN KODE ASLI (Memanggil user_role() jadi aman)
    return user_role()->isSuperAdmin();
}

function is_kepsek(): bool
{
    // 1. CEK KEAMANAN
    if (!function_exists('user') || !user()) {
        return false;
    }

    // 2. JALANKAN KODE ASLI
    return user_role() === UserRole::Kepsek;
}

function can_edit_attendance(): bool
{
    // 1. CEK KEAMANAN
    if (!function_exists('user') || !user()) {
        return false;
    }

    // 2. JALANKAN KODE ASLI
    return in_array(user_role(), [UserRole::SuperAdmin, UserRole::StafPetugas]);
}

function can_generate_qr(): bool
{
    // 1. CEK KEAMANAN
    if (!function_exists('user') || !user()) {
        return false;
    }

    // 2. JALANKAN KODE ASLI
    return in_array(user_role(), [UserRole::SuperAdmin, UserRole::StafPetugas]);
}

function can_view_report(): bool
{
    // 1. CEK KEAMANAN
    if (!function_exists('user') || !user()) {
        return false;
    }

    // 2. JALANKAN KODE ASLI
    return in_array(user_role(), [UserRole::SuperAdmin, UserRole::StafPetugas, UserRole::Kepsek]);
}