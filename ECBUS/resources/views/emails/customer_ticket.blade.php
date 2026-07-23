<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $isConfirmation ? 'Booking Confirmed' : 'Booking Receipt' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #800000; /* Primary Maroon */
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .content {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .details-box {
            background-color: #f9f9f9;
            border-left: 4px solid #800000;
            padding: 15px;
            margin: 20px 0;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            background-color: #800000;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        .footer {
            background-color: #eeeeee;
            color: #777777;
            text-align: center;
            padding: 15px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>{{ $isConfirmation ? 'Booking Finalized & Confirmed!' : 'Your Ticket Booking Receipt' }}</h2>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $booking->customer_name }}</strong>,</p>
            
            @if($isConfirmation)
                <p>Great news! Your booking status is now **CONFIRMED** and your seats are successfully reserved.</p>
            @else
                <p>Thank you for choosing ECBUS. Your booking has been received and is currently **{{ strtoupper($booking->booking_status) }}**.</p>
            @endif
            
            <div class="details-box">
                <strong>Ticket Number:</strong> {{ $booking->ticket_number }}<br>
                <strong>Route:</strong> {{ $booking->schedule->route->fromLocation->name ?? 'N/A' }} to {{ $booking->schedule->route->toLocation->name ?? 'N/A' }}<br>
                <strong>Departure:</strong> {{ $booking->schedule->date }} at {{ $booking->schedule->departure_time }}<br>
                <strong>Seat Numbers:</strong> {{ is_array($booking->seat_numbers) ? implode(', ', $booking->seat_numbers) : $booking->seat_numbers }}<br>
                <strong>Total Amount:</strong> LKR {{ number_format($booking->total_amount, 2) }}<br>
                <strong>Status:</strong> {{ strtoupper($booking->booking_status) }}
            </div>
            
            <div class="btn-container">
                <a href="{{ route('booking.ticket', $booking->id) }}" class="btn" style="color: #ffffff;">Download & Print Ticket</a>
            </div>
            
            <p>If you have any questions or need to make changes, please contact support.</p>
            <p>Best regards,<br>The ECBUS Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ECBUS. All rights reserved.
        </div>
    </div>
</body>
</html>
