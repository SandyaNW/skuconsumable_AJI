<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SKUSubmission extends Model
{
    use HasFactory;

    protected $table = 'sku_submissions';

    protected $fillable = [
        'nama',
        'npk',
        'departement',
        'section',
        'dept_id',
        'detail_dept_id',
        'remarks',
        'issue_date',
        'status',
        'reject_reason',
    ];

    // 1. DEFINISI STATUS (Todo List 1: Backend Flow)
    // Pake konstanta biar pas ngoding di Controller lo panggil nama, bukan angka
    const STATUS_PENDING_SPV = 1; //Menunggu SPV approve
    const STATUS_PENDING_DEPT = 2; // Menunggu depthead
    const STATUS_WAITING_FA    = 3; // Menunggu Finance Input SKU
    const STATUS_REJECTED      = 4; // Ditolak
    const STATUS_WAITING_PPIC  = 5; // Menunggu Final Verif PPIC Admin
    const STATUS_COMPLETED     = 6; // Selesai
    const STATUS_FINAL_REJECTED = 7; // Permanent Reject by PPIC (No Revision)

    // Relasi: Satu pengajuan punya banyak item detail
    public function details()
    {
        return $this->hasMany(SKUDetail::class, 'sku_submission_id','id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'id');
    }
    public function section()
    {
        return $this->belongsTo(DetailDepartement::class, 'detail_dept_id', 'id');
    }

    public function detail_department() // Saya sarankan pakai nama ini agar sinkron dengan kolom detail_dept_id
    {
        return $this->belongsTo(DetailDepartement::class, 'detail_dept_id', 'id');
    }

    // 2. HELPER STATUS LABEL (Update sesuai Jalur Baru)
    public function getStatusLabelAttribute()
    {
        $status = [
            self::STATUS_PENDING_SPV => '<span class="label label-warning"><i class="fa fa-clock-o"></i> Waiting Supervisor</span>',
            self::STATUS_PENDING_DEPT => '<span class="label label-warning"><i class="fa fa-clock-o"></i> Waiting Dept Head</span>',
            self::STATUS_WAITING_FA   => '<span class="label label-info"><i class="fa fa-calculator"></i> Processing by FA</span>',
            self::STATUS_REJECTED     => '<span class="label label-danger"><i class="fa fa-times"></i> Rejected</span>',
            self::STATUS_WAITING_PPIC => '<span class="label label-primary" style="background-color: #f8ac59; border-color: #f8ac59;"><i class="fa fa-check-square-o"></i> Waiting Final ACC</span>',
            self::STATUS_COMPLETED    => '<span class="label label-primary" style="background-color: #1ab394;"><i class="fa fa-check-circle"></i> Completed</span>',
            self::STATUS_FINAL_REJECTED => '<span class="label label-danger" style="background-color: #000; border-color: #000;"><i class="fa fa-ban"></i> Final Rejected</span>',
        ];

        return $status[$this->status] ?? '<span class="label label-default">Unknown</span>';
    }
}
