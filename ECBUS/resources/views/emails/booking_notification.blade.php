<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Ticket Booking Received</title>
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
            <h2>New Booking Alert!</h2>
        </div>
        <div class="content">
            <p>Hello team,</p>
            <p>A new ticket booking has been successfully placed. Below are the booking details:</p>
            
            <div class="details-box">
                <strong>Ticket Number:</strong> {{ $booking->ticket_number }}<br>
                <strong>Customer Name:</strong> {{ $booking->customer_name }}<br>
                <strong>Phone:</strong> {{ $booking->phone }}<br>
                <strong>Seats booked:</strong> {{ is_array($booking->seat_numbers) ? implode(', ', $booking->seat_numbers) : $booking->seat_numbers }}<br>
                <strong>Passenger Count:</strong> {{ $booking->passenger_count }}<br>
                <strong>Total Amount:</strong> LKR {{ number_format($booking->total_amount, 2) }}<br>
                <strong>Route:</strong> {{ $booking->schedule->route->fromLocation->name ?? 'N/A' }} to {{ $booking->schedule->route->toLocation->name ?? 'N/A' }}<br>
                <strong>Date & Time:</strong> {{ $booking->schedule->date }} at {{ $booking->schedule->departure_time }}
            </div>
            
            <p>Please ensure all arrangements are in place for the trip.</p>
            <p>Best regards,<br>The ECBUS System</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ECBUS. All rights reserved.
        </div>
    </div>
</body>
</html>
