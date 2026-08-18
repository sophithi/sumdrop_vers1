@extends('layouts.admin')

@section('title', __('stock.page_title'))
@section('page-title', __('stock.page_title_full'))
@section('page-subtitle', __('stock.page_subtitle'))
@section('page-actions')
    <a href="{{ route('products.index') }}" class="btn btn-secondary">{{ __('stock.manage_products') }}</a>
@endsection

@push('styles')
<style>
    .stock-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .stat-tile {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.1rem 1.25rem;
        box-shadow: var(--shadow);
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .stat-tile.total .stat-icon { background: var(--accent-soft); color: var(--accent); }
    .stat-tile.low .stat-icon { background: #fef3c7; color: #b45309; }
    .stat-tile.out .stat-icon { background: #fee2e2; color: #b91c1c; }

    .stat-label { font-size: 0.82rem; color: var(--muted); margin-bottom: 0.15rem; }
    .stat-value { font-size: 1.6rem; font-weight: 700; color: var(--text); line-height: 1.1; }

    .live-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--muted);
        font-size: 0.85rem;
        background: #f8fafc;
        border: 1px solid var(--border);
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        white-space: nowrap;
    }

    .live-dot { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; animation: pulse 1.5s ease-in-out infinite; flex-shrink: 0; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

    .stock-toolbar-row {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 0.9rem;
    }

    .search-box { position: relative; flex: 1; min-width: 200px; }

    .search-box input {
        width: 100%;
        padding: 0.65rem 0.9rem 0.65rem 2.4rem;
        border: 1px solid var(--border);
        border-radius: 12px;
        font-size: 0.95rem;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .search-icon {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.95rem;
        opacity: 0.55;
        pointer-events: none;
    }

    .category-pills {
        display: flex;
        gap: 0.5rem;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 0.6rem;
        margin-bottom: 0.6rem;
        scrollbar-width: thin;
    }

    .category-pills::-webkit-scrollbar { height: 5px; }
    .category-pills::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

    .pill {
        flex: 0 0 auto;
        border: 1px solid var(--border);
        background: #ffffff;
        color: #475569;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: border-color 0.15s ease, color 0.15s ease, background 0.15s ease;
    }

    .pill:hover { border-color: var(--accent); color: var(--accent); }
    .pill.active { background: var(--accent); border-color: var(--accent); color: #ffffff; }

    .product-thumb {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--accent-soft);
        object-fit: cover;
        display: inline-block;
        flex-shrink: 0;
    }

    .product-name-cell { display: flex; align-items: center; gap: 0.8rem; }

    #stock-table thead th {
        position: sticky;
        top: 0;
        background: var(--surface);
        box-shadow: 0 1px 0 var(--border);
        z-index: 2;
    }

    #stock-table tbody tr { transition: background 0.12s ease; }
    #stock-table tbody tr:hover { background: #f8fafc; }

    .stock-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .stock-pill .dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

    .stock-pill.ok { background: #dcfce7; color: #166534; }
    .stock-pill.ok .dot { background: #16a34a; }
    .stock-pill.low { background: #fef3c7; color: #92400e; }
    .stock-pill.low .dot { background: #d97706; }
    .stock-pill.out { background: #fee2e2; color: #991b1b; }
    .stock-pill.out .dot { background: #dc2626; }

    .stock-flash { animation: flash 0.6s ease; }
    @keyframes flash { 0% { background: #fef08a; } 100% { background: transparent; } }

    .adjust-controls { display: flex; gap: 0.6rem; flex-wrap: wrap; align-items: center; }

    .qty-stepper { display: inline-flex; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
    .qty-stepper button {
        border: none;
        border-right: 1px solid var(--border);
        background: #ffffff;
        padding: 0.4rem 0.7rem;
        font-weight: 700;
        color: var(--accent);
        cursor: pointer;
    }
    .qty-stepper button:last-child { border-right: none; }
    .qty-stepper button:hover { background: var(--accent-soft); }

    .adjust-set { display: inline-flex; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
    .adjust-set input {
        border: none;
        width: 80px;
        padding: 0.4rem 0.6rem;
        font-size: 0.9rem;
    }
    .adjust-set input:focus { outline: none; }
    .adjust-set button {
        border: none;
        border-left: 1px solid var(--border);
        background: #f8fafc;
        padding: 0.4rem 0.8rem;
        font-weight: 600;
        color: var(--text);
        cursor: pointer;
    }
    .adjust-set button:hover { background: var(--accent-soft); color: var(--accent); }

    .no-results { text-align: center; color: var(--muted); padding: 2rem 0; }

    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--muted); }
    .empty-state-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }

    /* Below this width, the Stock/Adjust columns are the whole point of the page —
       don't let them hide behind a horizontal scroll. Stack each row as a card. */
    @media (max-width: 768px) {
        .table-responsive { overflow-x: visible; }
        #stock-table { min-width: 0; }
        #stock-table thead { display: none; }
        #stock-table, #stock-table tbody, #stock-table tr, #stock-table td { display: block; width: 100%; }

        #stock-table tr {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 0.9rem 1rem;
            margin-bottom: 0.75rem;
        }
        #stock-table tr:last-child { margin-bottom: 0; }

        #stock-table td { border-bottom: none; padding: 0.5rem 0; }
        #stock-table td:first-child { padding-top: 0; }
        #stock-table td:last-child { padding-bottom: 0; }

        #stock-table td[data-label]::before {
            content: attr(data-label);
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--muted);
            margin-bottom: 0.3rem;
        }

        .adjust-controls { justify-content: flex-start; }
    }
</style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="stock-stats">
        <div class="stat-tile total">
            <div class="stat-icon">📦</div>
            <div>
                <div class="stat-label">{{ __('stock.total_products') }}</div>
                <div class="stat-value" id="stat-total">{{ $products->count() }}</div>
            </div>
        </div>
        <div class="stat-tile low">
            <div class="stat-icon">⚠️</div>
            <div>
                <div class="stat-label">{{ __('stock.low_stock') }}</div>
                <div class="stat-value" id="stat-low">{{ $lowStockCount }}</div>
            </div>
        </div>
        <div class="stat-tile out">
            <div class="stat-icon">⛔</div>
            <div>
                <div class="stat-label">{{ __('stock.out_of_stock') }}</div>
                <div class="stat-value" id="stat-out">{{ $outOfStockCount }}</div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3>{{ __('stock.inventory') }}</h3>
            <span class="live-chip"><span class="live-dot"></span> {{ __('stock.live_prefix') }} <span id="last-updated">{{ __('stock.just_now') }}</span></span>
        </div>

        @if($products->count())
            <div class="stock-toolbar-row">
                <div class="search-box">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="stock-filter" placeholder="{{ __('stock.search_placeholder') }}">
                </div>
            </div>

            <div class="category-pills" id="category-pills">
                <button type="button" class="pill active" data-cat="all" onclick="filterCategory('all')">{{ __('common.all') }}</button>
                @foreach($categories as $cat)
                    <button type="button" class="pill" data-cat="{{ $cat->id }}" onclick="filterCategory('{{ $cat->id }}')">{{ $cat->name }}</button>
                @endforeach
                @if($products->contains(fn($p) => !$p->category))
                    <button type="button" class="pill" data-cat="none" onclick="filterCategory('none')">{{ __('stock.other') }}</button>
                @endif
            </div>

            <div class="table-responsive">
            <table class="table-list" style="width:100%;" id="stock-table">
                <thead>
                    <tr>
                        <th>{{ __('common.product') }}</th>
                        <th>{{ __('common.category') }}</th>
                        <th>{{ __('common.stock') }}</th>
                        <th>{{ __('stock.table_adjust') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr data-product-id="{{ $product->id }}" data-product-name="{{ strtolower($product->name) }}" data-category="{{ $product->category->id ?? 'none' }}" data-unit="{{ $product->unit }}" data-pack-quantity="{{ $product->pack_quantity }}">
                            <td>
                                <div class="product-name-cell">
                                    @if($product->hasImage())
                                        <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" class="product-thumb">
                                    @else
                                        <span class="product-thumb"></span>
                                    @endif
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        @if($product->size)
                                            <span class="badge badge-size">{{ $product->size }}</span>
                                        @endif
                                        @if($product->isCase())
                                            <span class="badge badge-unit">{{ $product->getUnitLabel() }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td data-label="{{ __('common.category') }}">
                                @if($product->category)
                                    <span class="badge">{{ $product->category->name }}</span>
                                @else
                                    <span style="color:var(--muted);">—</span>
                                @endif
                            </td>
                            <td data-label="{{ __('common.stock') }}">
                                @php
                                    $stockState = $product->isOutOfStock() ? 'out' : ($product->isLowStock() ? 'low' : 'ok');
                                    $stockLabel = $product->isOutOfStock() ? __('stock.out_of_stock') : ($product->isLowStock() ? __('stock.low_stock') : __('stock.in_stock'));
                                @endphp
                                <span class="stock-pill {{ $stockState }}" id="stock-badge-{{ $product->id }}" title="{{ $stockLabel }}">
                                    <span class="dot"></span>
                                    <span id="stock-value-{{ $product->id }}">{{ $product->stockDisplay() }}</span>
                                </span>
                            </td>
                            <td data-label="{{ __('stock.table_adjust') }}">
                                <div class="adjust-controls">
                                    <div class="qty-stepper">
                                        <button type="button" onclick="adjustStock({{ $product->id }}, 'decrement', 1)">−1</button>
                                        <button type="button" onclick="adjustStock({{ $product->id }}, 'increment', 1)">+1</button>
                                        <button type="button" onclick="adjustStock({{ $product->id }}, 'increment', 10)">+10</button>
                                        @if($product->isCase() && $product->pack_quantity)
                                            <button type="button" title="{{ __('stock.add_one_case_title') }}" onclick="adjustStock({{ $product->id }}, 'increment', {{ $product->pack_quantity }})">{{ __('stock.add_case_btn') }}</button>
                                        @endif
                                    </div>
                                    <div class="adjust-set">
                                        <input type="number" min="0" id="set-input-{{ $product->id }}" placeholder="{{ __('stock.set_to_placeholder') }}">
                                        <button type="button" onclick="setStock({{ $product->id }})">{{ __('common.save') }}</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <p class="no-results" id="no-results" style="display:none;">{{ __('stock.no_products_match_filters') }}</p>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <p>{{ __('stock.no_products_yet') }}</p>
            </div>
        @endif
    </div>
@endsection

@php
    $stockText = [
        'case' => __('common.case'),
        'can' => __('common.can'),
        'pack' => __('common.pack'),
        'box' => __('common.box'),
        'glass' => __('common.glass'),
        'piece' => __('common.piece'),
        'pieces' => __('common.pieces'),
        'outOfStock' => __('stock.out_of_stock'),
        'lowStock' => __('stock.low_stock'),
        'inStock' => __('stock.in_stock'),
        'couldNotUpdateStock' => __('stock.could_not_update_stock'),
        'enterValidStock' => __('stock.enter_valid_stock'),
    ];
@endphp
@push('scripts')
<script>
    const stockText = @json($stockText);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let currentCategory = 'all';

    function flashRow(id) {
        const badge = document.getElementById('stock-badge-' + id);
        if (!badge) return;
        badge.classList.add('stock-flash');
        setTimeout(() => badge.classList.remove('stock-flash'), 600);
    }

    function recomputeStats() {
        let low = 0, out = 0;
        document.querySelectorAll('.stock-pill').forEach(pill => {
            if (pill.classList.contains('out')) out++;
            else if (pill.classList.contains('low')) low++;
        });
        const lowEl = document.getElementById('stat-low');
        const outEl = document.getElementById('stat-out');
        if (lowEl) lowEl.textContent = low;
        if (outEl) outEl.textContent = out;
    }

    const CASE_LIKE_UNITS = ['case', 'can', 'pack', 'box', 'glass'];

    function formatStockDisplay(stock, unit, packQuantity) {
        if (!CASE_LIKE_UNITS.includes(unit) || !packQuantity) {
            return String(stock);
        }

        const cases = Math.floor(stock / packQuantity);
        const remainder = stock % packQuantity;

        if (cases === 0) {
            return remainder + ' ' + (remainder === 1 ? stockText.piece : stockText.pieces);
        }

        let label = cases + ' ' + (stockText[unit] || stockText.case);
        if (remainder > 0) {
            label += ' + ' + remainder + ' ' + (remainder === 1 ? stockText.piece : stockText.pieces);
        }
        return label;
    }

    function applyStockUpdate(id, stock, isLow) {
        const valueEl = document.getElementById('stock-value-' + id);
        const badgeEl = document.getElementById('stock-badge-' + id);
        if (!valueEl || !badgeEl) return;

        const row = document.querySelector('tr[data-product-id="' + id + '"]');
        const unit = row ? row.dataset.unit : 'piece';
        const packQuantity = row ? (parseInt(row.dataset.packQuantity, 10) || 0) : 0;
        const display = formatStockDisplay(stock, unit, packQuantity);

        if (valueEl.textContent !== display) {
            valueEl.textContent = display;
            flashRow(id);
        }

        const isOut = stock <= 0;
        const state = isOut ? 'out' : (isLow ? 'low' : 'ok');
        const label = isOut ? stockText.outOfStock : (isLow ? stockText.lowStock : stockText.inStock);

        badgeEl.classList.remove('ok', 'low', 'out');
        badgeEl.classList.add(state);
        badgeEl.title = label;

        recomputeStats();
    }

    async function adjustStock(id, action, amount) {
        const res = await fetch(`/stock/${id}/adjust`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ action, amount }),
        });

        if (!res.ok) {
            alert(stockText.couldNotUpdateStock);
            return;
        }

        const data = await res.json();
        applyStockUpdate(data.id, data.stock, data.is_low);
    }

    function setStock(id) {
        const input = document.getElementById('set-input-' + id);
        const amount = parseInt(input.value, 10);

        if (isNaN(amount) || amount < 0) {
            alert(stockText.enterValidStock);
            return;
        }

        adjustStock(id, 'set', amount).then(() => { input.value = ''; });
    }

    async function pollStock() {
        try {
            const res = await fetch('/stock/data', { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const data = await res.json();

            data.products.forEach(p => applyStockUpdate(p.id, p.stock, p.is_low));

            document.getElementById('last-updated').textContent = new Date().toLocaleTimeString();
        } catch (e) {
            // silently skip a failed poll; the next interval will retry
        }
    }

    setInterval(pollStock, 5000);

    function filterCategory(catId) {
        currentCategory = String(catId);
        document.querySelectorAll('.pill').forEach(pill => {
            pill.classList.toggle('active', pill.dataset.cat === currentCategory);
        });
        applyFilters();
    }

    function applyFilters() {
        const filterInput = document.getElementById('stock-filter');
        const term = filterInput ? filterInput.value.trim().toLowerCase() : '';
        const noResults = document.getElementById('no-results');
        let visibleCount = 0;

        document.querySelectorAll('#stock-table tbody tr').forEach(row => {
            const matchesCategory = currentCategory === 'all' || row.dataset.category === currentCategory;
            const matchesSearch = !term || row.dataset.productName.includes(term);
            const visible = matchesCategory && matchesSearch;
            row.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });

        if (noResults) noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    const stockFilterInput = document.getElementById('stock-filter');
    if (stockFilterInput) stockFilterInput.addEventListener('input', applyFilters);
</script>
@endpush
