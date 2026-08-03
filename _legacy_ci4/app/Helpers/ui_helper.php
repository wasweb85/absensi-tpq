<?php

if (!function_exists('get_kehadiran_label')) {
    function get_kehadiran_label($id_kehadiran)
    {
        switch ($id_kehadiran) {
            case 1: return 'Hadir';
            case 2: return 'Sakit';
            case 3: return 'Izin';
            case 4: return 'Tanpa Keterangan';
            default: return 'Belum Absen';
        }
    }
}

if (!function_exists('get_kehadiran_color')) {
    function get_kehadiran_color($id_kehadiran)
    {
        switch ($id_kehadiran) {
            case 1: return 'success';
            case 2: return 'warning';
            case 3: return 'info';
            case 4: return 'danger';
            default: return 'secondary';
        }
    }
}
