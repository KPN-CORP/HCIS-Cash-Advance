<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Request Notification</title>
</head>

<body>
    <h2>Ticket Request Notification</h2>
    <p>Dear Sir/Madam: <b>{{ $managerName }}</b></p><br>

    <p><strong>No SPPD:</strong> {{ $noSppd }}</p>
    <p><strong>Approval Status:</strong> {{ $approvalStatus }}</p>

    <h3>Ticket Details</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">#</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Ticket Number</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Passenger Name</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Departure</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Ticket Type</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Departure Date & Time</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Return Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($noTktList as $index => $noTkt)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $noTkt }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $namaPenumpang[$index] }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $dariTkt[$index] }} - {{ $keTkt[$index] }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $tipeTkt[$index] }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $tglBrktTkt[$index] }} at
                        {{ $jamBrktTkt[$index] }} WIB</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">
                        @if ($tipeTkt[$index] == 'Round Trip')
                            {{ $tglPlgTkt[$index] }} at {{ $jamPlgTkt[$index] }} WIB
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <hr>
    <p>For approval or rejection of the Business Trip, you can choose the following links: <a href="#">Approve</a>
        / <a href="#">Reject</a></p>

    <p>Thank you,</p>
    <p>HC System</p>
</body>

</html>