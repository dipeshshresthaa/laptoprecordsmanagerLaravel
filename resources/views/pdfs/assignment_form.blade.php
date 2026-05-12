<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laptop assignment form</title>
    <style>
        @page {
            margin: 40px 50px;
        }

        body {
            font-family: Arial;
            font-size: 12px;
            color: #1e293b;
            line-height: 1.5;
        }

        /* Header / Letterhead */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .header-table td {
            vertical-align: bottom;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .doc-title {
            font-size: 16px;
            color: #2563eb;
            font-weight: bold;
            text-transform: uppercase;
            margin: 5px 0 0 0;
        }

        .doc-meta {
            text-align: right;
            color: #64748b;
            font-size: 11px;
            line-height: 1.6;
        }

        /* Typography */
        h3 {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            background-color: #f1f5f9;
            padding: 8px 12px;
            border-left: 4px solid #2563eb;
            margin: 25px 0 15px 0;
        }

        /* Tables */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .info-table .label {
            width: 25%;
            font-weight: bold;
            color: #475569;
            /* background-color: #f8fafc; */
            font-size: 11px;
            text-transform: uppercase;
        }

        .info-table .value {
            width: 75%;
            color: #0f172a;
            font-weight: 500;
        }

        /* Terms Box */
        .terms-box {
            /* background-color: #f8fafc; */
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 4px;
            text-align: justify;
            color: #475569;
            font-size: 11px;
            line-height: 1.6;
            margin-bottom: 40px;
            page-break-inside: avoid;
        }

        /* Signatures (Using tables for DomPDF compatibility) */
        .signature-container {
            width: 100%;
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 45%;
        }

        .sig-line {
            border-top: 1px solid #0f172a;
            margin-right: 180px;
            margin-bottom: 8px;
            padding-top: 4px;
        }

        .sig-title {
            font-weight: bold;
            color: #0f172a;
            font-size: 13px;
        }

        .sig-name {
            color: #64748b;
            font-size: 11px;
            margin-top: 2px;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            color: #94a3b8;
            font-size: 10px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td>
                <h1 class="company-name">N. Amatya & Co.</h1>
                <h2 class="doc-title">IT Asset Assignment Agreement</h2>
            </td>
            <td class="doc-meta">
                <strong>Date:</strong> {{ $assignment->assigned_date->format('F d, Y') }}<br>
                <strong>Ref ID:</strong> ASN-{{ str_pad($assignment->id, 5, '0', STR_PAD_LEFT) }}<br>
                <strong>Status:</strong> <span style="color: #059669; font-weight: bold;">Active</span>
            </td>
        </tr>
    </table>

    <h3>1. EMPLOYEE DETAILS</h3>
    <table class="info-table">
        <tr>
            <td class="label">Full name</td>
            <td class="value">{{ $assignment->employee->first_name }} {{ $assignment->employee->last_name }}</td>
        </tr>
        <tr>
            <td class="label">Department</td>
            <td class="value">{{ $assignment->employee->department ?? 'N/A' }}</td>
        </tr>
    </table>

    <h3>2. HARDWARE SPECIFICATIONS</h3>
    <table class="info-table">
        <tr>
            <td class="label">Asset ID / FA code</td>
            <td class="value">{{ $assignment->laptop->laptop_fa_code ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Make and model</td>
            <td class="value">{{ $assignment->laptop->brand->value ?? '' }}
                {{ $assignment->laptop->model->value ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Serial number</td>
            <td class="value" style="font-family: monospace;">{{ $assignment->laptop->serial_number }}</td>
        </tr>
        <tr>
            <td class="label">Service tag</td>
            <td class="value" style="font-family: monospace;">{{ $assignment->laptop->service_tag ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Processing power</td>
            <td class="value">{{ $assignment->laptop->processor->value ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Memory (RAM)</td>
            <td class="value">{{ $assignment->laptop->ramSize->value ?? 'N/A' }}
                ({{ $assignment->laptop->ram_type }})</td>
        </tr>
        <tr>
            <td class="label">Storage capacity</td>
            <td class="value">{{ $assignment->laptop->storageSize->value ?? 'N/A' }}
                ({{ $assignment->laptop->storage_type }})</td>
        </tr>
    </table>

    <h3>3. TERMS OF ACCEPTANCE</h3>
    <div class="terms-box">
        <strong>Declaration:</strong> I acknowledge the receipt of the IT equipment listed above in good working
        condition.
        I agree to use this equipment strictly for official purposes. I assume responsibility for the physical safety
        and proper maintenance of this hardware while it is in my possession. I agree to return this equipment
        immediately upon request by the IT Department or upon the termination of my service. I understand that this
        equipment remains the exclusive property of the entity at all times.
    </div>

    <table class="signature-container">
        <tr>
            <td class="sig-box">
                <div class="sig-line">
                    <div class="sig-title">Employee signature</div>
                    <div class="sig-name">{{ $assignment->employee->first_name }}
                        {{ $assignment->employee->last_name }}</div>
                </div>
            </td>

            <td style="width: 10%;"></td>
            <td class="sig-box">
                <div class="sig-line">
                    <div class="sig-title">Authorized issuer</div>
                    <div class="sig-name">{{ Auth::user()->first_name ?? Auth::user()->name }}
                        {{ Auth::user()->last_name ?? '' }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Generated by IT Asset Management System &bull; Printed on {{ now()->format('M d, Y') }}
    </div>

</body>

</html>
