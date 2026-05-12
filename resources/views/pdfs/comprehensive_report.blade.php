<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Comprehensive Firm Report</title>
    <style>
        @page {
            margin: 40px 40px;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .doc-title {
            font-size: 14px;
            margin: 5px 0 0 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .group-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .data-table th {
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            font-weight: bold;
        }

        .data-table td {
            padding: 8px 10px;
            border-bottom: 1px dotted #ccc;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    @php
        $printHeader = function ($subtitle) use ($search) {
            $searchHtml = $search
                ? "<p style='margin: 5px 0 0 0; font-size: 10px;'>Filtered by Search: \"{$search}\"</p>"
                : '';
            $date = now()->format('M d, Y');
            return "
            <table class='header-table'>
                <tr>
                    <td>
                        <h1 class='company-name'>Firm employees report</h1>
                        <h2 class='doc-title'>{$subtitle}</h2>
                        {$searchHtml}
                    </td>
                    <td style='text-align: right; vertical-align: bottom;'>
                        <strong>Generated:</strong> {$date}
                    </td>
                </tr>
            </table>";
        };
    @endphp

    {!! $printHeader('Part 1: Active employees directory') !!}
    <div class="section-title">Active employees ({{ $activeStaff->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="30%">Employee name</th>
                <th width="20%">Role</th>
                <th width="25%">Department</th>
                <th width="25%">Assigned principal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activeStaff as $staff)
                <tr>
                    <td><strong>{{ $staff->full_name }}</strong></td>
                    <td>{{ $staff->role_display }}</td>
                    <td>{{ $staff->department ?? '-' }}</td>
                    <td>{{ $staff->principal->full_name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>
    {!! $printHeader('Part 2: Left / Inactive employees') !!}
    <div class="section-title">Inactive employees ({{ $leftStaff->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="30%">Employee name</th>
                <th width="20%">Role</th>
                <th width="25%">Department</th>
                <th width="25%">Former principal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leftStaff as $staff)
                <tr>
                    <td><strong>{{ $staff->full_name }}</strong></td>
                    <td>{{ $staff->role_display }}</td>
                    <td>{{ $staff->department ?? '-' }}</td>
                    <td>{{ $staff->principal->full_name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>
    {!! $printHeader('Part 3: Principal-wise trainee allocation') !!}
    <div class="section-title">Active trainees grouped by principal</div>

    @forelse($principalGroups as $principalId => $trainees)
        @php $principal = $allPrincipals->get($principalId); @endphp

        <div class="group-title">
            Principal: {{ $principal ? $principal->full_name : 'Unassigned' }}
            ({{ $trainees->count() }} trainees)
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="40%">Trainee Name</th>
                    <th width="30%">Joining Date</th> {{-- Added for clarity --}}
                    <th width="30%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trainees as $trainee)
                    <tr>
                        <td>{{ $trainee->full_name }}</td>
                        <td>{{ $trainee->joining_date?->format('d-M-Y') }}</td>
                        <td>Active</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p>No active trainees currently assigned to principals.</p>
    @endforelse

    <div class="page-break"></div>
    {!! $printHeader('Part 4: Master roster (Overall employees)') !!}
    <div class="section-title">Total workforce ({{ $totalStaff->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="25%">Employee name</th>
                <th width="15%">Role</th>
                <th width="20%">Department</th>
                <th width="25%">Principal</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($totalStaff as $staff)
                <tr>
                    <td><strong>{{ $staff->full_name }}</strong></td>
                    <td>{{ $staff->role_display }}</td>
                    <td>{{ $staff->department ?? '-' }}</td>
                    <td>{{ $staff->principal->full_name ?? 'NA' }}</td>
                    <td>{{ $staff->is_active ? 'Active' : 'Left' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
