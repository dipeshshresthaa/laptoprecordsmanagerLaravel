<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hardware history - {{ $laptop->serial_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .header {
            border-bottom: 2px solid #1e293b;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #0f172a;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #64748b;
            font-size: 12px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 10px;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }

        /* Specs Table */
        .specs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            /* background-color: #f8fafc; */
        }

        .specs-table td {
            padding: 10px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .specs-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 3px;
            display: block;
        }

        .specs-value {
            font-weight: bold;
            color: #0f172a;
            font-size: 13px;
        }

        /* Timeline Table */
        .timeline-table {
            width: 100%;
            border-collapse: collapse;
        }

        .timeline-table th {
            /* background-color: #f1f5f9; */
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            color: #475569;
            border-bottom: 2px solid #cbd5e1;
        }

        .timeline-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .date-col {
            width: 15%;
            font-weight: bold;
            color: #334155;
        }

        .type-col {
            width: 20%;
            font-weight: bold;
        }

        .details-col {
            width: 65%;
        }

        .event-title {
            font-weight: bold;
            margin-bottom: 4px;
            color: #0f172a;
            font-size: 14px;
        }

        .event-desc {
            color: #475569;
            font-size: 12px;
        }

        /* Colors based on type */
        .type-Purchase {
            color: #059669;
        }

        .type-Assignment {
            color: #2563eb;
        }

        .type-Return {
            color: #d97706;
        }

        .type-Repair {
            color: #e11d48;
        }

        .type-Hardware {
            color: #7c3aed;
        }

        .type-Disposal {
            color: #475569;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Hardware history</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>

    <div class="section-title">Device specifications</div>
    <table class="specs-table">
        <tr>
            <td width="33%">
                <span class="specs-label">Make and model</span>
                <span class="specs-value">{{ $laptop->brand->value ?? 'N/A' }} {{ $laptop->model->value ?? '' }}</span>
            </td>
            <td width="33%">
                <span class="specs-label">Serial Number</span>
                <span class="specs-value" style="font-family: monospace;">{{ $laptop->serial_number }}</span>
            </td>
            <td width="33%">
                <span class="specs-label">Current Status</span>
                <span class="specs-value" style="color: #2563eb; text-transform: uppercase;">
                    {{ $laptop->status }}
                    @if ($laptop->status === 'Assigned' && $laptop->activeAssignment)
                        <br>
                        <span style="color: #64748b; text-transform: none; font-size: 10px; font-weight: normal;">
                            Assigned to: {{ $laptop->activeAssignment->employee?->full_name ?? 'Unknown' }}
                        </span>
                    @endif
                </span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="specs-label">Processor</span>
                <span class="specs-value">{{ $laptop->processor->value ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="specs-label">Memory (RAM)</span>
                <span class="specs-value">{{ $laptop->ramSize->value ?? 'N/A' }} ({{ $laptop->ram_type }})</span>
            </td>
            <td>
                <span class="specs-label">Storage</span>
                <span class="specs-value">{{ $laptop->storageSize->value ?? 'N/A' }}
                    ({{ $laptop->storage_type }})</span>
            </td>
        </tr>
    </table>

    <div class="section-title">Lifecycle timeline</div>
    <table class="timeline-table">
        <thead>
            <tr>
                <th class="date-col">Date</th>
                <th class="type-col">Event type</th>
                <th class="details-col">Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($timeline as $event)
                @php
                    // Map the event type to a safe CSS class name
                    $colorClass = 'type-' . explode(' ', $event->type)[0];
                @endphp
                <tr>
                    <td class="date-col">{{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}</td>
                    <td class="type-col {{ $colorClass }}">{{ mb_strtoupper($event->type) }}</td>
                    <td class="details-col">
                        <div class="event-title">{{ $event->title }}</div>
                        <div class="event-desc">{{ $event->details }}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
