@if ($totalPages > 1)
    <nav aria-label="Pagination">
        <ul class="pagination justify-content-center">
            <!-- Trang trước -->
            <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                <a class="page-link" href="?{{ $url_request . "=" . ($currentPage - 1) }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Các trang -->
            @for ($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {{ $i === $currentPage ? 'active' : '' }}">
                    <a class="page-link" href="?{{ $url_request . "=" .  $i }}">{{ $i }}</a>
                </li>
            @endfor

            <!-- Trang sau -->
            <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                <a class="page-link" href="?{{ $url_request . "=" . $currentPage + 1 }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
@endif