<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h2 {
            font-size: 16px;
            margin: 0 0 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-grid {
            display: block;
            width: 100%;
        }
        .info-item {
            display: inline-block;
            width: 48%;
            vertical-align: top;
            margin-bottom: 15px;
        }
        .info-item h3 {
            font-size: 14px;
            margin: 0 0 5px;
            color: #666;
        }
        .info-item p {
            margin: 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Receipt #: {{ str_pad($payment->id, 8, '0', STR_PAD_LEFT) }}</p>
            <p>Date: {{ $payment->created_at->format('F d, Y') }}</p>
        </div>
        
        <div class="info-section">
            <h2>Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <h3>Customer</h3>
                    <p>{{ $serviceRecord->user->name }}</p>
                    <p>{{ $serviceRecord->user->email }}</p>
                    @if($serviceRecord->user->phone)
                        <p>{{ $serviceRecord->user->phone }}</p>
                    @endif
                </div>
                
                <div class="info-item">
                    <h3>Payment Details</h3>
                    <p>Method: {{ ucfirst($payment->payment_method) }}</p>
                    <p>Status: {{ ucfirst($payment->status) }}</p>
                    <p>Transaction ID: {{ $payment->transaction_id }}</p>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <h2>Service Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $serviceRecord->service->name }}</td>
                        <td>{{ $serviceRecord->service->description ?? 'No description' }}</td>
                        <td class="text-right">${{ number_format($serviceRecord->amount, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right">Total:</td>
                        <td class="text-right">${{ number_format($payment->amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>