<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - {{ $booking->booking_reference }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .ticket-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px dashed #800000;
            border-radius: 10px;
            padding: 20px;
            position: relative;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #800000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #800000;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 28px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            color: #555;
            font-weight: bold;
        }
        .ticket-details {
            display: flex;
            justify-content: space-between;
        }
        .left-col {
            width: 65%;
        }
        .right-col {
            width: 30%;
            text-align: right;
            border-left: 2px dashed #ddd;
            padding-left: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .info-row {
            display: flex;
            margin-bottom: 15px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
        }
        .info-value {
            font-size: 16px;
            font-weight: bold;
        }
        .qr-placeholder {
            width: 100px;
            height: 100px;
            border: 2px solid #333;
            margin-left: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #999;
        }
        .price-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-top: 20px;
            border: 1px solid #ddd;
        }
        .price-box h2 {
            margin: 0;
            color: #800000;
            font-size: 24px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            color: white;
        }
        .status-confirmed { background: #10B981; }
        .status-pending { background: #F59E0B; }
        .status-cancelled { background: #EF4444; }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
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

    <div class="no-print" style="text-align: center;">
        <button onclick="window.print()" class="btn-print">Print / Save as PDF</button>
    </div>

    <div class="ticket-container">
        <div class="header">
            <h1>{{ $booking->booking_status === 'pending' ? 'PROFORMA INVOICE' : 'E-TICKET' }}</h1>
            <p>{{ $booking->schedule->bus->busCompany->company_name ?? 'ECBUS' }}</p>
        </div>

        <div class="ticket-details">
            <div class="left-col">
                <div class="info-row">
                    <div class="info-label">Passenger Name</div>
                    <div class="info-value">{{ $booking->customer_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $booking->phone }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Route</div>
                    <div class="info-value">
                        {{ $booking->schedule->route->fromLocation->name ?? '?' }} &rarr; {{ $booking->schedule->route->toLocation->name ?? '?' }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date & Time</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($booking->schedule->date)->format('d M Y') }} at 
                        {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Bus Info</div>
                    <div class="info-value">
                        {{ $booking->schedule->bus->bus_number }} ({{ $booking->schedule->bus->busType->name ?? 'A/C' }})
                    </div>
                </div>
                @if($booking->boarding_point)
                <div class="info-row">
                    <div class="info-label">Boarding Point</div>
                    <div class="info-value">{{ $booking->boarding_point }}</div>
                </div>
                @endif
                @if($booking->dropping_point)
                <div class="info-row">
                    <div class="info-label">Dropping Point</div>
                    <div class="info-value">{{ $booking->dropping_point }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Seats</div>
                    <div class="info-value">
                        @if($booking->seat_numbers && is_array($booking->seat_numbers) && count($booking->seat_numbers) > 0)
                            {{ implode(', ', $booking->seat_numbers) }}
                        @else
                            <span style="color: #999;">Not Assigned</span>
                            ({{ $booking->passenger_count }} Passenger(s))
                        @endif
                    </div>
                </div>
            </div>

            <div class="right-col">
                <div>
                    <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: bold; text-transform: uppercase;">Booking Reference</div>
                    <div style="font-size: 20px; font-weight: bold; color: #333; margin-bottom: 15px;">{{ $booking->booking_reference }}</div>
                    
                    <div class="qr-placeholder">
                        QR CODE
                    </div>
                </div>

                <div class="price-box">
                    <div style="font-size: 12px; font-weight: bold; color: #666; text-transform: uppercase; margin-bottom: 5px;">Total Amount</div>
                    <h2>LKR {{ number_format($booking->total_amount, 2) }}</h2>
                    <div style="margin-top: 10px;">
                        <span class="status-badge status-{{ strtolower($booking->booking_status) }}">
                            {{ $booking->booking_status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            Generated on {{ now()->format('d M Y, h:i A') }} | ECBUS {{ $booking->booking_status === 'pending' ? 'Invoice' : 'E-Ticket' }}<br>
            @if($booking->booking_status === 'pending')
                <strong style="color: #EF4444; font-size: 14px;">PAYMENT PENDING - NOT VALID FOR TRAVEL YET</strong><br>
                Please contact admin to pay and confirm your booking.
            @else
                Please show this ticket to the conductor while boarding.
            @endif
        </div>
    </div>

</body>
</html>
