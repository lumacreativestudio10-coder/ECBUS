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
            <div><strong>Route:</strong> {{ $schedule->route?->fromLocation?->name ?? '?' }} &rarr; {{ $schedule->route?->toLocation?->name ?? '?' }}</div>
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
                <th style="width: 40px; text-align: center;">#</th>
                <th>Passenger Name</th>
                <th>Phone Number</th>
                <th>Seats</th>
                <th style="text-align: center;">Pax</th>
                <th>Booking Ref</th>
                <th>Boarding</th>
                <th>Dropping</th>
                <th style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalSeats = 0; @endphp
            @forelse($bookings as $index => $booking)
                @php 
                    $seatCount = is_array($booking->seat_numbers) ? count($booking->seat_numbers) : $booking->passenger_count;
                    $totalSeats += $seatCount;
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                    <td style="font-weight: bold;">{{ $booking->customer_name }}</td>
                    <td>{{ $booking->phone }}</td>
                    <td style="font-weight: bold;">{{ is_array($booking->seat_numbers) ? implode(', ', $booking->seat_numbers) : ($booking->seat_numbers ?? 'N/A') }}</td>
                    <td style="text-align: center;">{{ $seatCount }}</td>
                    <td>{{ $booking->booking_reference }}</td>
                    <td>{{ $booking->boarding_point ?? '-' }}</td>
                    <td>{{ $booking->dropping_point ?? '-' }}</td>
                    <td style="text-align: center;">
                        <span style="display: inline-block; width: 15px; height: 15px; border: 1px solid #000; margin-right: 5px;"></span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">No passengers booked for this schedule yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <p><strong>Total Bookings:</strong> {{ $bookings->count() }}</p>
        <p><strong>Total Passengers/Seats Booked:</strong> {{ $totalSeats }}</p>
        <p><strong>Total Available Seats:</strong> {{ $schedule->bus->total_seats - $totalSeats }}</p>
    </div>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} | ECBUS System
    </div>

</body>
</html>
