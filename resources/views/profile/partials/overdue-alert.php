@if($overdueBorrowings->count() > 0)
    <div class="alert alert-danger d-flex gap-3 align-items-start mb-4">

        <i class="bi bi-exclamation-triangle-fill fs-4"></i>

        <div class="flex-grow-1">

            <p class="fw-semibold mb-1">
                {{ $overdueBorrowings->count() }} borrowing(s) are overdue!
            </p>

            <ul class="mb-0 ps-3 small">
                @foreach($overdueBorrowings->take(5) as $borrowing)
                    <li>
                        <a href="{{ route('borrowings.show', $borrowing->id) }}" class="alert-link">
                            {{ $borrowing->user->name ?? 'Unknown User' }}
                        </a>
                        — due {{ \Carbon\Carbon::parse($borrowing->due_date)->diffForHumans() }}
                        ({{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }})
                    </li>
                @endforeach
            </ul>

            @if($overdueBorrowings->count() > 5)
                <p class="small mb-0 mt-1">
                    and {{ $overdueBorrowings->count() - 5 }} more...
                </p>
            @endif

        </div>

        <a href="{{ route('borrowings.index') }}" class="btn btn-danger btn-sm">
            View All
        </a>

    </div>
@endif