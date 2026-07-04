<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Borrowings Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #222;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }
        p.subtitle {
            margin-top: 0;
            color: #666;
            margin-bottom: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            color: #fff;
        }
        .badge-pending  { background-color: #f0ad4e; }
        .badge-borrowed { background-color: #337ab7; }
        .badge-rejected { background-color: #d9534f; }
        .badge-returned { background-color: #5cb85c; }
        .footer {
            margin-top: 24px;
            font-size: 10px;
            color: #999;
            text-align: right;
        }
    </style>
</head>
<body>

    <h1>Borrowings Report</h1>
    <p class="subtitle">Generated on {{ now()->format('d M Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Borrower</th>
                <th>Product(s)</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $borrowing)
                <tr>
                    <td>{{ $borrowing->id }}</td>
                    <td>{{ $borrowing->user->name ?? 'Unknown User' }}</td>
                    <td>
                        @foreach($borrowing->details as $detail)
                            {{ $detail->product->name ?? 'Unknown' }} (x{{ $detail->qty }})@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>{{ $borrowing->borrow_date ? \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') : '-' }}</td>
                    <td>{{ $borrowing->due_date ? \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') : '-' }}</td>
                    <td>{{ $borrowing->return_date ? \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') : '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $borrowing->status }}">
                            {{ ucfirst($borrowing->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">No borrowing records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total records: {{ $borrowings->count() }}
    </div>

</body>
</html>