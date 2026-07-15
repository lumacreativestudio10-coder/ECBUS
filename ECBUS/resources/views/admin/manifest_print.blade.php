<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Manifest - {{ $schedule->bus->busCompany->company_name ?? 'ECBUS' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .info-box {
            width: 48%;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
        }
        .info-box strong {
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px 12px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 30px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
        .btn-print {
            display: inline-block;
            background: #800000;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 20px;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="btn-print">Print Manifest</button>
    </div>

    <div class="header">
        <h1>PASSENGER MANIFEST</h1>
        <p>{{ $schedule->bus->busCompany->company_name ?? 'ECBUS' }}</p>
    </div>

    <div class="info-section">
        <div class="info-box">
            <div><strong>Route:</strong> {{ $schedule->route->fromLocation->name ?? '?' }} &rarr; {{ $schedule->route->toLocation->name ?? '?' }}</div>
            <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</div>
            <div><strong>Departure Time:</strong> {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</div>
        </div>
        <div class="info-box">
            <div><strong>Bus Number:</strong> {{ $schedule->bus->bus_number }}</div>
            <div><strong>Bus Type:</strong> {{ $schedule->bus->busType->name ?? 'Unknown' }}</div>
            <div><strong>Driver/Conductor:</strong> ___________________</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">Seat</th>
                <th>Passenger Name</th>
                <th>Phone Number</th>
                <th>Booking Ref</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($passengers as $p)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $p['seat'] }}</td>
                    <td>{{ $p['name'] }}</td>
                    <td>{{ $p['phone'] }}</td>
                    <td>{{ $p['ref'] }}</td>
                    <td>
                        <span style="display: inline-block; width: 15px; h-height: 15px; border: 1px solid #000; margin-right: 5px;"></span> Boarded
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No passengers booked for this schedule yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <p><strong>Total Passengers Booked:</strong> {{ count($passengers) }}</p>
        <p><strong>Total Available Seats:</strong> {{ $schedule->bus->total_seats - count($passengers) }}</p>
    </div>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} | ECBUS System
    </div>

</body>
</html>
