@extends('layouts.app-master')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">

    {{-- Baris Widget Atas --}}
    <div class="row">
        
        <div class="col-lg-3">
            <div class="ibox shadow-sm border-left-right" style="border-left: 4px solid #2f4050;">
                <div class="ibox-title">
                    <span class="label label-default pull-right">Summary</span>
                    <h5>Total Submissions</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins font-bold" style="color: #2f4050;">{{ $stats['total_all'] }}</h1>
                    <small>All created requests</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="ibox shadow-sm border-left-right" style="border-left: 4px solid #f8ac59;">
                <div class="ibox-title">
                    <span class="label label-warning pull-right">Urgent</span>
                    <h5>Wait SPV</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins font-bold text-warning">{{ $stats['pending_spv'] }}</h1>
                    <small>Need Approval SPV</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox shadow-sm border-left-right" style="border-left: 4px solid #1ab394;">
                <div class="ibox-title">
                    <span class="label label-warning pull-right">Urgent</span>
                    <h5>Wait Dept Head</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins font-bold text-warning">{{ $stats['pending_head'] }}</h1>
                    <small>Need Approval Dept Head</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox shadow-sm border-left-right" style="border-left: 4px solid #1c84c6;">
                <div class="ibox-title">
                    <span class="label label-info pull-right">FA</span>
                    <h5>Waiting SKU</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins font-bold text-info">{{ $stats['waiting_fa'] }}</h1>
                    <small>Finance Input Stage</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="ibox shadow-sm border-left-right" style="border-left: 4px solid #ed5565;">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">Fix it</span>
                    <h5>Rejected</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins font-bold text-danger">{{ $stats['rejected'] }}</h1>
                    <small>Need Revision</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="ibox shadow-sm border-left-right" style="border-left: 4px solid #23c6c8;">
                <div class="ibox-title">
                    <span class="label label-primary pull-right" style="background-color: #23c6c8;">PPIC</span>
                    <h5>Final Validation</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins font-bold" style="color: #23c6c8;">{{ $stats['waiting_maya'] }}</h1>
                    <small>PPIC Verification</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="ibox shadow-sm border-left-right" style="border-left: 4px solid #000000;">
                <div class="ibox-title">
                    <span class="label label-danger pull-right" style="background-color: #000;">Closed</span>
                    <h5>Final Rejected</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins font-bold" style="color: #000;">{{ $stats['final_rejected'] }}</h1>
                    <small>Permanently Denied</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="ibox shadow-sm border-left-right" style="border-left: 4px solid #1ab394;">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">Done</span>
                    <h5>Completed</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins font-bold text-navy">{{ $stats['completed'] }}</h1>
                    <small>Successfully Created</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row m-t-md">
        {{-- Tombol Action --}}
        <div class="col-lg-4">
            <div class="ibox shadow-sm">
                <div class="ibox-content text-center p-xl">
                    <div class="m-b-md">
                        <i class="fa fa-plus-circle fa-5x text-navy"></i>
                    </div>
                    <h2 class="font-bold">New Submission</h2>
                    <p>Click the button below to request a new Part Number or SKU for production materials.</p>
                    <a href="{{ route('sku.create') }}" class="btn btn-primary btn-block btn-lg font-bold m-t-lg">
                        <i class="fa fa-paper-plane"></i> CREATE REQUEST
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Activity Table --}}
        <div class="col-lg-8">
            <div class="ibox shadow-sm">
                <div class="ibox-title">
                    <h5>Recent Activity (Your Department)</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('sku.index') }}" class="btn btn-xs btn-white">View All</a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-hover no-margins">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Submitted</th>
                                    <th>PIC</th>
                                    <th class="text-center">Current Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSubmissions as $recent)
                                <tr>
                                    <td>
                                        <a href="{{ route('sku.show', $recent->id) }}" class="text-navy font-bold">
                                            {{ Str::limit($recent->details->first()->item_name ?? 'Unnamed Item', 25) }}
                                        </a>
                                    </td>
                                    <td><small><i class="fa fa-clock-o"></i> {{ $recent->created_at->diffForHumans() }}</small></td>
                                    <td>{{ $recent->nama }}</td>
                                    <td class="text-center">
                                        @if($recent->status == 1)
                                            <span class="label label-warning">Wait Supervisor</span>
                                        @elseif($recent->status == 2)
                                            <span class="label label-warning">Wait Dept Head</span>
                                        @elseif($recent->status == 3)
                                            <span class="label label-info">Processing FA</span>
                                        @elseif($recent->status == 4)
                                            <span class="label label-danger">Rejected</span>
                                        @elseif($recent->status == 5)
                                            <span class="label label-primary" style="background-color: #23c6c8;">Wait PPIC</span>
                                        @elseif($recent->status == 6)
                                            <span class="label label-primary">Completed</span>
                                        @elseif($recent->status == 7)
                                            <span class="label label-danger" style="background-color: #000;">Final Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent activity found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection