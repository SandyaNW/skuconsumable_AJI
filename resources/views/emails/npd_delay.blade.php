
<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Delay schedule NPD</title>
    <meta name="description" content="Reset Password Email Template.">
    <style type="text/css">
        a:hover {text-decoration: underline !important;}
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
    <!--100% body table-->
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
        style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
        <tr>
            <td>
                <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                    align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                    {{-- <tr>
                        <td style="text-align:center;">
                          <a href="https://rakeshmandal.com" title="logo" target="_blank">
                            <img width="60" src="https://i.ibb.co/hL4XZp2/android-chrome-192x192.png" title="logo" alt="logo">
                          </a>
                        </td>
                    </tr> --}}
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                <tr>
                                    <td align="left"  valign="middle"> 
                                        <img style="padding-left:20px;padding-top:20px" src="https://astra-juoku-indonesia.com/wp-content/uploads/2021/08/cropped-output-onlinepngtools-21.png" alt="logo" width="80">
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">
                                            @if ($delay_type === 'delay_start')
                                                Event ini telah terlambat dari plan mulai, mohon segera dimulai! 
                                            @else
                                                Event ini telah terlambat dari plan selesai, mohon segera diselesaikan! 
                                            @endif
                                        </h1>
                                        <span
                                            style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;" align="left">
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            project: {{$project}}<br>
                                            event: {{$event}}<br>
                                            {{$plan}}<br>
                                            delay: {{$delay_day}}<br>
                                            description: {{$description}}<br>
                                            eskalasi spv: {{$eskalasi_spv}}<br>
                                            eskalasi depthead: {{$eskalasi_depthead}}<br>
                                            eskalasi director: {{$eskalasi_director}}<br>
                                        </p>
                                        <br>
                                        
                                        @if ($jenis_pic === 'spv')
                                            <a href="{{route('NewProductPortalScheduleAjiController.show_pica_email',['data'=> $id, 'type' => $delay_type, 'project' => $project, 'event' => $event, 'description' => $description])}}" data-id="{{$id}}" style="background-color: #1F7F4C; font-size: 18px; font-family: Helvetica, Arial, sans-serif; font-weight: bold; text-decoration: none; padding: 14px 20px; color: #ffffff; border-radius: 5px; display: inline-block; mso-padding-alt: 0;">
                                                <span style="mso-text-raise: 15pt; font-family: Helvetica, Arial, sans-serif; font-weight: bold;">
                                                    <!--[if mso]>
                                                    <i style="letter-spacing: 25px; mso-font-width: -100%; mso-text-raise: 30pt;">&nbsp;</i>
                                                    <![endif]-->
                                                    @if ($delay_type === 'delay_start')
                                                        Isi PICA Delay Start &rarr;
                                                    @else
                                                        Isi PICA Delay End &rarr;
                                                    @endif
                                                    <!--[if mso]>
                                                    <i style="letter-spacing: 25px; mso-font-width: -100%;">&nbsp;</i>
                                                    <![endif]-->
                                                </span>
                                            </a>
                                        @elseif ($jenis_pic === 'depthead')
                                        @elseif ($jenis_pic === 'director')
                                        @else
                                            <a href="{{route('NewProductPortalScheduleAjiController.show_pica_email',['data'=> $id, 'type' => $delay_type, 'project' => $project, 'event' => $event, 'description' => $description])}}" data-id="{{$id}}" style="background-color: #1F7F4C; font-size: 18px; font-family: Helvetica, Arial, sans-serif; font-weight: bold; text-decoration: none; padding: 14px 20px; color: #ffffff; border-radius: 5px; display: inline-block; mso-padding-alt: 0;">
                                                <span style="mso-text-raise: 15pt; font-family: Helvetica, Arial, sans-serif; font-weight: bold;">
                                                    <!--[if mso]>
                                                    <i style="letter-spacing: 25px; mso-font-width: -100%; mso-text-raise: 30pt;">&nbsp;</i>
                                                    <![endif]-->
                                                    @if ($delay_type === 'delay_start')
                                                        Isi PICA Delay Start &rarr;
                                                    @else
                                                        Isi PICA Delay End &rarr;
                                                    @endif
                                                    <!--[if mso]>
                                                    <i style="letter-spacing: 25px; mso-font-width: -100%;">&nbsp;</i>
                                                    <![endif]-->
                                                </span>
                                            </a>
                                        @endif
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong>AJI INTERNAL PORTAL</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!--/100% body table-->
</body>

</html>