<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('jumlah_approval_belum')) {
    # code...
}
function jumlah_approval_belum(){
    $role = Auth::user()->roles->pluck('name')[0];

    $kode_role ="0";

    // if role
    if ($role == "Leader Quality") {
        $kode_role = '1';
    } 
    if ($role == "Foreman Quality") {
        $kode_role = '2';
    } 
    if ($role == "Supervisor Quality") {
        $kode_role = '3';
    } 
    if ($role == "Dept Head Quality") {
        $kode_role = '4';
    } 
    if ($role == "Director Quality") {
        $kode_role = '5';
    } 
    

    // cari jumlah approval yang belum di approve oleh leader up sesuai role
    $jumlah = DB::table('quality_cs_ipqcs')->select('*')->where('approval_status', $kode_role)->count();

    return $jumlah;

}