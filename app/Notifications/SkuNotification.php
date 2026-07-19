<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SkuNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $details; // Variabel penampung data

    public function __construct($details)
    {
        $this->details = $details; // Simpan data dari Controller ke variabel class
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Aktifkan Database & Email
    }

    // Format untuk Notifikasi Lonceng (Database)
    public function toArray($notifiable)
    {
        return [
            'title'   => $this->details['title'] ?? 'SKU Update',
            'message' => $this->details['message'],
            'link'    => isset($this->details['sku_id']) ? route('sku.show', $this->details['sku_id']) : url('/'),
            'icon'    => $this->details['icon'] ?? 'fa-bell',
            'color'   => $this->details['color'] ?? 'text-primary',
        ];
    }

    // Format untuk Email (Menggunakan Custom HTML Formal)
    public function toMail($notifiable)
    {
        // 1. Siapkan URL
        $url = isset($this->details['sku_id']) 
                ? route('sku.show', $this->details['sku_id']) 
                : url('/');

        // 2. Ambil Title & Message
        $title = $this->details['title'] ?? 'Notification';
        $message = $this->details['message'] ?? '-';
        
        // 3. LOGIC PINTAR: Tentukan Nada Bicara & Teks Tombol berdasarkan Title/Context
        $introLine = 'Berikut adalah pembaruan status terkini terkait pengajuan SKU.';
        $buttonText = 'Lihat Detail Pengajuan';
        $level = 'primary'; // Default

        // Cek Keywords untuk menyesuaikan pesan
        $lowerTitle = strtolower($title);

        if (str_contains($lowerTitle, 'waiting') || str_contains($lowerTitle, 'approve') || str_contains($lowerTitle, 'process')) {
            // KASUS: Butuh Approval (Reminder Keras)
            $introLine = 'Terdapat pengajuan SKU yang membutuhkan persetujuan/tinjauan Anda. Mohon segera diperiksa.';
            $buttonText = 'Tinjau & Approve Sekarang';
        } 
        elseif (str_contains($lowerTitle, 'reject')) {
            // KASUS: Ditolak (Perlu Revisi)
            $introLine = 'Mohon maaf, pengajuan SKU Anda telah dikembalikan atau ditolak. Silakan cek alasan penolakan dan lakukan revisi.';
            $buttonText = 'Lihat Alasan & Revisi';
            $level = 'error'; 
        } 
        elseif (str_contains($lowerTitle, 'completed') || str_contains($lowerTitle, 'finish') || str_contains($lowerTitle, 'assigned')) {
            // KASUS: Selesai / Siap
            $introLine = 'Pemberitahuan bahwa pengajuan SKU telah diproses atau diselesaikan oleh pihak terkait.';
            $buttonText = 'Lihat Progress Pengajuan';
            if (str_contains($lowerTitle, 'completed') || str_contains($lowerTitle, 'finish')) {
                $introLine = 'Selamat! Proses pengajuan SKU Anda telah selesai sepenuhnya dan sudah masuk ke Master Data.';
                $buttonText = 'Lihat Master Product';
            }
        }

        // 4. Ambil data submission lengkap untuk ditampilkan dalam email
        $skuId = $this->details['sku_id'] ?? null;
        $skuSubmission = null;
        if ($skuId) {
            $skuSubmission = \App\Models\SKUSubmission::with(['details', 'department', 'detail_department'])->find($skuId);
        }

        // 5. Susun Email menggunakan View HTML Premium Custom
        return (new MailMessage)
                    ->subject('[SKU System] ' . $title)
                    ->view('emails.sku_notification', [
                        'notifiable'     => $notifiable,
                        'title'          => $title,
                        'contentMessage' => $message,
                        'url'            => $url,
                        'introLine'      => $introLine,
                        'buttonText'     => $buttonText,
                        'level'          => $level,
                        'skuSubmission'  => $skuSubmission,
                        'details'        => $this->details
                    ]);
    }
}