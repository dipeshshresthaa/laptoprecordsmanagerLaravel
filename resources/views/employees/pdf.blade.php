<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Report</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            color: #1e293b;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #64748b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .inactive {
            color: #ef4444;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Employee directory report</h2>
        <p>Generated on {{ date('F j, Y, g:i a') }}</p>
        @if(!$showLeftEmployees)
            <p><em>Showing active employees only</em></p>
        @else
            <p><em>Showing all employees (Active & inactive)</em></p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Full name</th>
                <th>Role / desig.</th>
                <th>Phone</th>
                <th>PAN number</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $emp)
                <tr class="{{ !$emp->is_active ? 'inactive' : '' }}">
                    <td>{{ $emp->emp_code }}</td>
                    <td>{{ $emp->full_name }}</td>
                    <td>{{ $emp->role_display }} <br> <span style="font-size:10px; color:#666;">{{ $emp->designation }}</span></td>
                    <td>{{ $emp->phone_number ?? 'N/A' }}</td>
                    <td>{{ $emp->pan_number ?? 'N/A' }}</td>
                    <td>
                        @if($emp->is_active)
                            Active
                        @else
                            Left<br>
                            <span style="font-size:10px;">{{ \Carbon\Carbon::parse($emp->exit_date)->format('Y-m-d') }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>