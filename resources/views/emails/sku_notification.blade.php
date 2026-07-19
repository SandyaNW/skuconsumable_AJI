<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 1.6;
            color: #333333;
        }
        a:hover {
            text-decoration: none !important;
            opacity: 0.9;
        }
        /* Media Queries for Mobile Responsiveness */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                padding: 10px !important;
            }
            .mobile-padding {
                padding: 20px !important;
            }
            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f4f6f9" style="table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 30px 0 40px 0;">
                <!-- Main Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="650" class="email-container" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); border-collapse: separate; overflow: hidden;">
                    
                    <!-- Decorative Top Border -->
                    @php
                        // Tentukan warna tema berdasarkan level / title
                        $themeColor = '#1F7F4C'; // Primary Astra Green
                        $badgeBg = '#FFF3CD';
                        $badgeText = '#856404';
                        $badgeBorder = '#FFEBA8';
                        $statusText = 'Menunggu Tindakan';
                        
                        $lowerTitle = strtolower($title);
                        if (str_contains($lowerTitle, 'reject') || str_contains($lowerTitle, 'permanently')) {
                            $themeColor = '#D9534F'; // Red
                            $badgeBg = '#F8D7DA';
                            $badgeText = '#721C24';
                            $badgeBorder = '#F5C6CB';
                            $statusText = 'Ditolak / Perlu Revisi';
                        } elseif (str_contains($lowerTitle, 'completed') || str_contains($lowerTitle, 'finish') || str_contains($lowerTitle, 'success')) {
                            $themeColor = '#1ab394'; // Green
                            $badgeBg = '#D4EDDA';
                            $badgeText = '#155724';
                            $badgeBorder = '#C3E6CB';
                            $statusText = 'Selesai';
                        } elseif (str_contains($lowerTitle, 'waiting dept head') || str_contains($lowerTitle, 'need approval')) {
                            $themeColor = '#f8ac59'; // Orange
                            $badgeBg = '#FFF3CD';
                            $badgeText = '#856404';
                            $badgeBorder = '#FFEBA8';
                            $statusText = 'Menunggu Approval';
                        } elseif (str_contains($lowerTitle, 'finance') || str_contains($lowerTitle, 'assigned')) {
                            $themeColor = '#0F4C81'; // Corporate Blue
                            $badgeBg = '#D1ECF1';
                            $badgeText = '#0C5460';
                            $badgeBorder = '#BEE5EB';
                            $statusText = 'Diproses Keuangan';
                        }
                    @endphp

                    <tr>
                        <td height="5" style="background-color: {{ $themeColor }}; font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <!-- Header -->
                    <tr>
                        <td align="left" style="padding: 25px 30px 20px 30px; border-bottom: 1px solid #eeeeee;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td valign="middle" width="90">
                                        <img src="https://astra-juoku-indonesia.com/wp-content/uploads/2021/08/cropped-output-onlinepngtools-21.png" alt="Astra Juoku" width="80" style="display: block; border: 0; height: auto;">
                                    </td>
                                    <td valign="middle" align="right">
                                        <span style="font-size: 16px; font-weight: bold; color: #333333; letter-spacing: 0.5px; display: block; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">AJI INTERNAL PORTAL</span>
                                        <span style="font-size: 11px; font-weight: 600; color: #888888; text-transform: uppercase; letter-spacing: 1px; display: block; margin-top: 2px;">SKU Consumable System</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class="mobile-padding" style="padding: 35px 30px 30px 30px;">
                            <!-- Salutation -->
                            <p style="margin-top: 0; margin-bottom: 15px; font-size: 15px; color: #333333; font-weight: 600;">
                                Yth. Bapak/Ibu {{ $notifiable->name }},
                            </p>
                            
                            <!-- Intro message -->
                            <p style="margin-top: 0; margin-bottom: 25px; font-size: 14px; color: #555555; line-height: 1.6;">
                                {{ $introLine }}
                            </p>

                            <!-- Document Metadata Card -->
                            @if($skuSubmission)
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 25px; border-collapse: separate; overflow: hidden;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="40%" valign="top" style="padding-bottom: 8px; font-size: 13px; color: #718096; font-weight: 500;">No. Pengajuan</td>
                                                <td width="60%" valign="top" style="padding-bottom: 8px; font-size: 13px; color: #1a202c; font-weight: bold;">{{ $skuSubmission->id_pengajuan }}</td>
                                            </tr>
                                            <tr>
                                                <td valign="top" style="padding-bottom: 8px; font-size: 13px; color: #718096; font-weight: 500;">Pemohon</td>
                                                <td valign="top" style="padding-bottom: 8px; font-size: 13px; color: #1a202c; font-weight: 600;">{{ $skuSubmission->nama }} ({{ $skuSubmission->npk }})</td>
                                            </tr>
                                            <tr>
                                                <td valign="top" style="padding-bottom: 8px; font-size: 13px; color: #718096; font-weight: 500;">Dept / Section</td>
                                                <td valign="top" style="padding-bottom: 8px; font-size: 13px; color: #1a202c;">{{ $skuSubmission->departement }} / {{ $skuSubmission->detail_department->name ?? $skuSubmission->section }}</td>
                                            </tr>
                                            <tr>
                                                <td valign="top" style="padding-bottom: 8px; font-size: 13px; color: #718096; font-weight: 500;">Tanggal Pengajuan</td>
                                                <td valign="top" style="padding-bottom: 8px; font-size: 13px; color: #1a202c;">{{ \Carbon\Carbon::parse($skuSubmission->issue_date)->translatedFormat('d F Y') }}</td>
                                            </tr>
                                            @if($skuSubmission->remarks)
                                            <tr>
                                                <td valign="top" style="padding-bottom: 8px; font-size: 13px; color: #718096; font-weight: 500;">Keterangan</td>
                                                <td valign="top" style="padding-bottom: 8px; font-size: 13px; color: #4a5568; font-style: italic;">"{{ $skuSubmission->remarks }}"</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td valign="top" style="font-size: 13px; color: #718096; font-weight: 500; padding-top: 4px;">Status Terkini</td>
                                                <td valign="top" style="padding-top: 4px;">
                                                    <span style="display: inline-block; padding: 3px 10px; font-size: 11px; font-weight: 700; border-radius: 4px; background-color: {{ $badgeBg }}; color: {{ $badgeText }}; border: 1px solid {{ $badgeBorder }}; text-transform: uppercase;">
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Rejection Alert Card -->
                            @if($skuSubmission && $skuSubmission->status == 4 && $skuSubmission->reject_reason)
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fffaf0; border-left: 4px solid #f6ad55; border-radius: 4px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <strong style="color: #dd6b20; font-size: 13px; display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Alasan Pengembalian / Penolakan:</strong>
                                        <p style="margin: 0; font-size: 13px; color: #7b341e; line-height: 1.5; font-style: italic;">
                                            "{{ $skuSubmission->reject_reason }}"
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @elseif($skuSubmission && $skuSubmission->status == 7 && $skuSubmission->reject_reason)
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fff5f5; border-left: 4px solid #fc8181; border-radius: 4px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <strong style="color: #e53e3e; font-size: 13px; display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Alasan Penolakan Permanen:</strong>
                                        <p style="margin: 0; font-size: 13px; color: #742a2a; line-height: 1.5; font-style: italic;">
                                            "{{ $skuSubmission->reject_reason }}"
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Detail Message -->
                            @if($contentMessage && !str_contains($contentMessage, 'New request') && !str_contains($contentMessage, 'approved by SPV') && !str_contains($contentMessage, 'is ready for SKU') && !str_contains($contentMessage, 'fully validated by PPIC') && !str_contains($contentMessage, 'Finance has assigned') && !str_contains($contentMessage, 'fixed the request'))
                            <div style="background-color: #f7fafc; padding: 15px; border-radius: 5px; border: 1px solid #edf2f7; font-size: 13px; color: #4a5568; margin-bottom: 25px;">
                                <strong>Catatan Update:</strong><br>
                                {{ $contentMessage }}
                            </div>
                            @endif

                            <!-- Items Summary Table -->
                            @if($skuSubmission && $skuSubmission->details->count() > 0)
                            <p style="margin-top: 0; margin-bottom: 10px; font-size: 13px; font-weight: bold; color: #4a5568; text-transform: uppercase; letter-spacing: 0.5px;">Daftar Item Pengajuan ({{ $skuSubmission->details->count() }} item):</p>
                            <div class="table-responsive" style="margin-bottom: 30px;">
                                <table border="0" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse; font-size: 12px; border: 1px solid #e2e8f0; min-width: 500px;">
                                    <thead>
                                        <tr bgcolor="#f8fafc" style="border-bottom: 2px solid #e2e8f0;">
                                            <th align="center" width="5%" style="border-right: 1px solid #e2e8f0; font-weight: bold; color: #4a5568;">No</th>
                                            <th align="left" width="30%" style="border-right: 1px solid #e2e8f0; font-weight: bold; color: #4a5568;">Nama Barang</th>
                                            <th align="left" width="25%" style="border-right: 1px solid #e2e8f0; font-weight: bold; color: #4a5568;">Spesifikasi</th>
                                            <th align="center" width="12%" style="border-right: 1px solid #e2e8f0; font-weight: bold; color: #4a5568;">Qty</th>
                                            <th align="left" width="15%" style="border-right: 1px solid #e2e8f0; font-weight: bold; color: #4a5568;">Kategori</th>
                                            <th align="left" width="13%" style="font-weight: bold; color: #4a5568;">Kode / SKU</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($skuSubmission->details as $idx => $detail)
                                        <tr style="border-bottom: 1px solid #edf2f7;">
                                            <td align="center" style="border-right: 1px solid #edf2f7; color: #718096;">{{ $idx + 1 }}</td>
                                            <td align="left" style="border-right: 1px solid #edf2f7; font-weight: 500; color: #2d3748;">{{ $detail->item_name }}</td>
                                            <td align="left" style="border-right: 1px solid #edf2f7; color: #4a5568;">{{ $detail->specification ?? '-' }}</td>
                                            <td align="center" style="border-right: 1px solid #edf2f7; font-weight: 600; color: #2d3748;">{{ $detail->qty }} {{ $detail->uom }}</td>
                                            <td align="left" style="border-right: 1px solid #edf2f7; color: #718096; font-size: 11px;">{{ $detail->category ?? '-' }}</td>
                                            <td align="left" style="color: #4a5568;">
                                                @if($detail->product_code || $detail->sku)
                                                    <span style="font-size: 10px; display: block; color: #718096;">Part: {{ $detail->product_code ?? '-' }}</span>
                                                    <span style="font-size: 10px; display: block; font-weight: 600; color: #1F7F4C; margin-top: 1px;">SKU: {{ $detail->sku ?? '-' }}</span>
                                                @else
                                                    <span style="color: #a0aec0; font-style: italic;">Belum diinput</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif

                            <!-- Action Button Call to Action -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 10px; margin-bottom: 15px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}" target="_blank" style="background-color: {{ $themeColor }}; font-size: 14px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; text-decoration: none; padding: 13px 28px; color: #ffffff; border-radius: 5px; display: inline-block; box-shadow: 0 3px 6px rgba(0,0,0,0.1); letter-spacing: 0.5px;">
                                            {{ $buttonText }} &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin-top: 25px; margin-bottom: 0; font-size: 13px; color: #718096; text-align: center; font-style: italic;">
                                Mohon abaikan pesan ini jika Anda sudah menindaklanjuti proses di atas.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 25px 30px; background-color: #fafbfc; border-top: 1px solid #eeeeee;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="font-size: 12px; color: #888888; line-height: 1.5;">
                                        <strong>MIS Department - PT Astra Juoku Indonesia</strong><br>
                                        Kawasan Industri Mitrakarawang (KIM) Jl. Mitra Raya II Blok H-6, Karawang, Jawa Barat<br>
                                        <span style="display: block; margin-top: 10px; font-size: 11px; color: #aaaaaa;">Email ini dikirim secara otomatis oleh sistem portal internal. Mohon tidak membalas email ini secara langsung.</span>
                                        <span style="display: block; margin-top: 5px; font-size: 11px; color: #aaaaaa;">&copy; {{ date('Y') }} AJI MIS. All Rights Reserved.</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
