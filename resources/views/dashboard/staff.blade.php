@extends('layouts.admin')

@section('title', __('pos.page_title'))
@section('page-title', __('pos.page_title'))
@section('page-subtitle', __('pos.page_subtitle'))

@push('styles')
<style>
    .pos-grid {
        display: grid;
        grid-template-columns: 1.7fr 1fr;
        gap: 1rem;
        align-items: start;
    }

    .pos-toolbar-row {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 0.9rem;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 180px;
    }

    .search-box input {
        width: 100%;
        padding: 0.65rem 0.9rem 0.65rem 2.4rem;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        background: #fff;
    }

    .search-box input:focus {
        outline: none;
        border-color: #2563eb;
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
        margin-bottom: 0.9rem;
        scrollbar-width: thin;
    }

    .category-pills::-webkit-scrollbar {
        height: 5px;
    }

    .category-pills::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .pill {
        flex: 0 0 auto;
        border: 1px solid #e2e8f0;
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

    .pill:hover {
        border-color: #2563eb;
        color: #2563eb;
    }

    .pill.active {
        background: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
    }

    .product-card {
        position: relative;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 0;
        background: #ffffff;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        border-color: #2563eb;
        box-shadow: 0 12px 24px rgba(37, 99, 235, 0.08);
    }

    .product-card.in-cart {
        border-color: #2563eb;
        background: #f5f8ff;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.12);
    }

    /* Dual-mode cards (case + piece) aren't a single click target — only their
       variant buttons are — so don't imply the whole card is tappable. */
    .product-card:not([role="button"]) {
        cursor: default;
    }

    .product-card:not([role="button"]):hover {
        border-color: #e2e8f0;
        box-shadow: none;
    }

    .product-card:not([role="button"]).in-cart:hover {
        border-color: #2563eb;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.12);
    }

    .card-banner {
        position: relative;
        height: 108px;
        border-radius: 15px 15px 0 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .card-banner-img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .card-banner-icon {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: #ffffff;
        text-transform: uppercase;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-banner.palette-0 { background: linear-gradient(135deg, #fbc7d4 0%, #f8a8c1 100%); }
    .card-banner.palette-1 { background: linear-gradient(135deg, #aee1fb 0%, #8ec9f5 100%); }
    .card-banner.palette-2 { background: linear-gradient(135deg, #fddca8 0%, #fbc389 100%); }
    .card-banner.palette-3 { background: linear-gradient(135deg, #c9c3f5 0%, #a9a0ea 100%); }
    .card-banner.palette-4 { background: linear-gradient(135deg, #a8ecc7 0%, #86dfae 100%); }
    .card-banner.palette-5 { background: linear-gradient(135deg, #f7c9ec 0%, #dba9e8 100%); }

    .card-add-btn,
    .card-qty-badge {
        position: absolute;
        top: 0.55rem;
        right: 0.55rem;
        width: 26px;
        height: 26px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.15);
    }

    .card-add-btn {
        background: rgba(255, 255, 255, 0.92);
        color: #334155;
        font-size: 1.05rem;
        line-height: 1;
    }

    .product-card.in-cart .card-add-btn { display: none; }

    .card-qty-badge {
        display: none;
        background: #2563eb;
        color: #ffffff;
        font-size: 0.72rem;
    }

    .card-body {
        padding: 0.8rem 1rem 1rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 0;
    }

    .card-body strong { display: block; overflow-wrap: break-word; line-height: 1.3; margin-bottom: 0.35rem; }

    .size-chip {
        align-self: flex-start;
        background: #ede9fe;
        color: #5b21b6;
        font-size: 0.7rem;
        font-weight: 700;
        line-height: 1;
        padding: 0.28rem 0.55rem;
        border-radius: 999px;
        margin-bottom: 0.35rem;
    }

    .card-meta {
        font-size: 0.78rem;
        color: #94a3b8;
        line-height: 1.5;
        overflow-wrap: break-word;
    }

    .card-meta .meta-stock { display: block; }
    .card-meta .meta-stock.status-low { color: #d97706; font-weight: 600; }
    .card-meta .meta-stock.status-out { color: #dc2626; font-weight: 700; }

    .card-divider { border: none; border-top: 1px solid #eef2f7; margin: 0.7rem 0 0.6rem; }

    .product-price { margin-top: auto; display: flex; align-items: baseline; gap: 0.35rem; flex-wrap: wrap; }

    .price-primary { color: #dc2626; font-weight: 700; font-size: 0.95rem; overflow-wrap: break-word; }
    .price-sep { color: #cbd5e1; font-size: 0.78rem; }
    .price-secondary { color: #94a3b8; font-weight: 600; font-size: 0.78rem; overflow-wrap: break-word; }

    .product-card .badge {
        max-width: 100%;
        white-space: normal;
        overflow-wrap: break-word;
        text-align: left;
    }

    .variant-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-top: auto;
        padding-top: 0.65rem;
    }

    .variant-box {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.3rem;
        min-width: 0;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.5rem 0.55rem;
        background: #ffffff;
        cursor: pointer;
        text-align: left;
        transition: border-color 0.15s ease, background 0.15s ease;
    }

    .variant-box:hover:not(:disabled) {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .variant-box:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .variant-box-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        overflow-wrap: break-word;
    }

    .variant-btn-price { display: flex; flex-direction: column; gap: 0.1rem; }
    .variant-btn-price .price-primary { font-size: 0.82rem; overflow-wrap: anywhere; }
    .variant-btn-price .price-secondary { font-size: 0.68rem; }

    .variant-btn-badge {
        display: none;
        position: absolute;
        top: -7px;
        right: -7px;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        border-radius: 999px;
        background: #2563eb;
        color: #ffffff;
        font-size: 0.65rem;
        font-weight: 700;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.4);
    }

    .product-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.9rem;
    }

    .cart-panel {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        position: sticky;
        top: 1rem;
    }

    .cart-drawer-handle {
        display: none;
    }

    .cart-drawer-close {
        display: none;
    }

    #cart-items {
        max-height: 40vh;
        overflow-y: auto;
        padding-right: 2px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.9rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .cart-item:last-child { border-bottom: none; }
    .cart-item strong { display: block; margin-bottom: 0.2rem; }
    .cart-item small { color: #64748b; }

    .qty-controls {
        display: inline-flex;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .qty-btn {
        width: 28px;
        height: 28px;
        border: none;
        background: #ffffff;
        color: #2563eb;
        cursor: pointer;
    }

    .qty-btn:hover { background: #eff6ff; }

    .qty-value {
        width: 32px;
        text-align: center;
        line-height: 28px;
        font-weight: 600;
    }

    .summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
        font-weight: 700;
    }

    .currency-toggle {
        display: inline-flex;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .currency-btn {
        border: none;
        background: #ffffff;
        padding: 0.5rem 0.9rem;
        cursor: pointer;
        color: #64748b;
    }

    .currency-btn.active {
        background: #2563eb;
        color: #ffffff;
    }

    .empty-cart { color: #64748b; }
    .btn-success { background: #16a34a; }

    .btn-danger {
        background: #dc2626;
        color: #ffffff;
        border: none;
        padding: 0.5rem 0.9rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.2s ease;
    }

    .btn-danger:hover {
        background: #b91c1c;
    }

    .btn-secondary {
        background: #6b7280;
        color: #ffffff;
        border: none;
        padding: 0.5rem 0.9rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.2s ease;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .cart-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: space-between;
        align-items: center;
        margin: 1rem 0;
        padding: 0.75rem;
        background: #f3f4f6;
        border-radius: 12px;
    }

    .cart-actions-left {
        display: flex;
        gap: 0.5rem;
    }

    .remove-btn {
        width: 24px;
        height: 24px;
        border: none;
        background: #ef4444;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s ease;
    }

    .remove-btn:hover {
        background: #dc2626;
    }

    .item-count {
        display: inline-block;
        background: #2563eb;
        color: white;
        border-radius: 12px;
        padding: 0.25rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 700;
        margin-right: 0.5rem;
    }

    .pos-toolbar {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .exchange-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 3000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .exchange-modal-content {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
    }

    /* Mobile cart drawer + floating summary bar (hidden on desktop) */
    .cart-overlay {
        display: none;
    }

    .mobile-cart-bar {
        display: none;
    }

    /* Laptop */
    @media (max-width: 1280px) {
        .pos-grid {
            grid-template-columns: 1.45fr 1fr;
        }
    }

    /* Tablet */
    @media (max-width: 1024px) {
        .pos-grid {
            grid-template-columns: 1fr;
        }

        .cart-panel {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            top: auto;
            margin: 0;
            max-height: 88vh;
            overflow-y: auto;
            border-radius: 20px 20px 0 0;
            transform: translateY(calc(100% + 2rem));
            transition: transform 0.3s ease;
            z-index: 2500;
            box-shadow: 0 -20px 40px rgba(15, 23, 42, 0.25);
        }

        body.cart-drawer-open .cart-panel {
            transform: translateY(0);
        }

        #cart-items {
            max-height: none;
        }

        .cart-drawer-handle {
            display: block;
            width: 44px;
            height: 5px;
            border-radius: 3px;
            background: #e2e8f0;
            margin: 0 auto 0.75rem;
        }

        .cart-drawer-close {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 8px;
            background: #f3f4f6;
            color: #475569;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .cart-overlay {
            display: block;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            z-index: 2400;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        body.cart-drawer-open .cart-overlay {
            opacity: 1;
            visibility: visible;
        }

        .mobile-cart-bar {
            position: fixed;
            left: 1rem;
            right: 1rem;
            bottom: 1rem;
            z-index: 2200;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: none;
            border-radius: 16px;
            padding: 0.9rem 1.2rem;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            cursor: pointer;
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.35);
            font-weight: 700;
        }

        .mobile-cart-bar.show {
            display: flex;
        }

        body.cart-drawer-open .mobile-cart-bar {
            display: none;
        }

        .mobile-cart-cta {
            font-weight: 700;
        }
    }

    /* Phone */
    @media (max-width: 768px) {
        .product-list {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        }

        .cart-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .cart-item > div:last-child {
            width: 100%;
            justify-content: space-between;
        }

        .panel-head {
            align-items: flex-start;
        }
    }

    /* Small phone */
    @media (max-width: 480px) {
        .product-list {
            grid-template-columns: 1fr;
        }

        .card-banner {
            height: 92px;
        }

        .exchange-modal-content {
            padding: 1.25rem;
        }

        .mobile-cart-bar {
            left: 0.75rem;
            right: 0.75rem;
            bottom: 0.75rem;
            padding: 0.8rem 1rem;
        }
    }

</style>
@endpush

@section('content')
    <div class="pos-grid">
        <section class="panel">
            <div class="panel-head">
                <h3>{{ __('menu.menu') }} <span style="color:#94a3b8; font-weight:500; font-size:0.85rem;">({{ __('pos.items_count', ['count' => $products->count()]) }})</span></h3>
                <div class="pos-toolbar">
                    <div class="currency-toggle">
                        <button type="button" id="currency-usd" class="currency-btn" onclick="setCurrency('usd')">USD</button>
                        <button type="button" id="currency-khr" class="currency-btn active" onclick="setCurrency('khr')">KHR</button>
                    </div>
                    <button type="button" id="settings-btn" onclick="toggleExchangeRateModal()" style="padding: 0.5rem 0.75rem; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; font-size: 1.2rem;" title="{{ __('pos.exchange_rate_settings') }}">⚙️</button>
                </div>
            </div>

            <div class="pos-toolbar-row">
                <div class="search-box">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="product-search" placeholder="{{ __('pos.search_placeholder') }}" oninput="applyFilters()">
                </div>
            </div>

            <div class="category-pills" id="category-pills">
                <button type="button" class="pill active" data-cat="all" onclick="filterCategory('all')">{{ __('common.all') }}</button>
                @foreach($categories as $cat)
                    <button type="button" class="pill" data-cat="{{ $cat->id }}" onclick="filterCategory('{{ $cat->id }}')">{{ $cat->name }}</button>
                @endforeach
                @if($products->contains(fn($p) => !$p->category))
                    <button type="button" class="pill" data-cat="none" onclick="filterCategory('none')">{{ __('pos.other') }}</button>
                @endif
            </div>

            <div class="product-list" id="product-list">
                @php $khrRate = 4100; @endphp
                @foreach($products as $product)
                    @php
                        $priceKhr = $product->price_khr ?? $product->price;
                        $priceUsd = $product->price_usd ?? round($priceKhr / $khrRate, 2);
                        $dual = $product->sellsByPiece();
                        $cardUnit = $product->isCase() ? 'case' : 'piece';
                        $pieceKhr = $product->price_khr_piece;
                        $pieceUsd = $product->price_usd_piece ?? ($pieceKhr !== null ? round($pieceKhr / $khrRate, 2) : null);

                        $paletteIndex = crc32((string) ($product->category_id ?? $product->id)) % 6;
                        $avatarLetter = mb_strtoupper(mb_substr(trim($product->name), 0, 1)) ?: '?';

                        $displayName = $product->name;
                        if ($product->size && preg_match('/\s*\(' . preg_quote($product->size, '/') . '\)\s*$/iu', $displayName)) {
                            $displayName = trim(preg_replace('/\s*\(' . preg_quote($product->size, '/') . '\)\s*$/iu', '', $displayName));
                        }

                        $stockStatus = $product->isOutOfStock() ? 'status-out' : ($product->isLowStock() ? 'status-low' : 'status-ok');
                    @endphp
                    <div class="product-card" data-cat="{{ $product->category->id ?? 'none' }}" data-name="{{ strtolower($product->name) }}"
                        @unless($dual)
                            role="button" tabindex="0"
                            style="{{ $product->isOutOfStock() ? 'opacity:0.5;cursor:not-allowed;' : '' }}"
                            onclick="{{ $product->isOutOfStock() ? '' : "addToCart({$product->id}, '" . addslashes($product->name) . "', {$priceUsd}, {$priceKhr}, '{$cardUnit}')" }}"
                        @else
                            style="{{ $product->isOutOfStock() ? 'opacity:0.6;' : '' }}"
                        @endunless
                    >
                        <div class="card-banner palette-{{ $paletteIndex }}">
                            @if($product->hasImage())
                                <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" class="card-banner-img">
                            @else
                                <span class="card-banner-icon">{{ $avatarLetter }}</span>
                            @endif
                            @unless($dual)
                                <span class="card-add-btn" aria-hidden="true">+</span>
                                <span class="card-qty-badge" id="badge-{{ $product->id }}-{{ $cardUnit }}">0</span>
                            @endunless
                        </div>
                        <div class="card-body">
                            <strong>{{ $displayName }}</strong>
                            @if($product->size)
                                <span class="size-chip">{{ $product->size }}</span>
                            @endif
                            @if($product->unit !== 'piece' && !$dual)
                                <span class="badge badge-unit">{{ $product->getUnitLabel() }}</span>
                            @endif
                            <div class="card-meta">
                                <span class="meta-category">{{ $product->category->name ?? __('pos.category_fallback') }}</span>
                                <span class="meta-stock {{ $stockStatus }}">
                                    {{ $product->isOutOfStock() ? __('pos.out_of_stock') : __('pos.stock_label', ['value' => $product->stockDisplay()]) }}
                                </span>
                            </div>
                            @if($dual)
                                <div class="variant-row">
                                    <button type="button" class="variant-box" {{ ($product->pack_quantity && $product->stock < $product->pack_quantity) ? 'disabled' : '' }}
                                        onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $priceUsd }}, {{ $priceKhr }}, 'case', '{{ addslashes($product->getUnitLabel()) }}')">
                                        <span class="variant-btn-badge" id="badge-{{ $product->id }}-case">0</span>
                                        <span class="variant-box-label">{{ __('common.case') }} ({{ $product->pack_quantity }})</span>
                                        <span class="variant-btn-price" id="price-{{ $product->id }}-case"><span class="price-primary">៛{{ number_format($priceKhr) }}</span><span class="price-secondary">${{ number_format($priceUsd, 2) }}</span></span>
                                    </button>
                                    <button type="button" class="variant-box" {{ $product->isOutOfStock() ? 'disabled' : '' }}
                                        onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $pieceUsd }}, {{ $pieceKhr }}, 'piece', '{{ addslashes(__('common.single')) }}')">
                                        <span class="variant-btn-badge" id="badge-{{ $product->id }}-piece">0</span>
                                        <span class="variant-box-label">{{ __('common.single') }}</span>
                                        <span class="variant-btn-price" id="price-{{ $product->id }}-piece"><span class="price-primary">៛{{ number_format($pieceKhr) }}</span><span class="price-secondary">${{ number_format($pieceUsd, 2) }}</span></span>
                                    </button>
                                </div>
                            @else
                                <hr class="card-divider">
                                <div class="product-price" id="price-{{ $product->id }}-{{ $cardUnit }}">
                                    <span class="price-primary">៛{{ number_format($priceKhr) }}</span>
                                    <span class="price-sep">/</span>
                                    <span class="price-secondary">${{ number_format($priceUsd, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="empty-cart" id="no-results" style="display:none;">{{ __('pos.no_search_results') }}</p>
        </section>

        <section class="panel cart-panel" id="cart-panel">
            <div class="cart-drawer-handle"></div>
            <div class="panel-head">
                <h3>{{ __('pos.current_order') }} <span class="item-count" id="item-count" style="display:none;">0</span></h3>
                <button type="button" class="cart-drawer-close" onclick="closeCartDrawer()" aria-label="{{ __('pos.close_cart') }}">×</button>
            </div>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <div class="cart-actions" id="cart-actions" style="display:none;">
                <div id="total-items-display" style="font-weight:600; color:#2563eb;"></div>
            </div>

            <div id="cart-items"></div>
            <div class="summary" id="cart-summary">
                <span>{{ __('common.subtotal') }}</span>
                <span>៛0</span>
            </div>

            <form id="checkout-form" method="POST" action="{{ route('orders.store') }}">
                @csrf
                <input type="hidden" name="items" id="items-input" value="[]">
                <input type="hidden" name="currency" id="currency-input" value="khr">

                <label for="payment_method">{{ __('pos.payment_method') }}</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="cash">{{ __('pos.cash') }}</option>
                    <option value="mobile">{{ __('pos.mobile_payment') }}</option>
                </select>

                <div id="payment-calculator" style="margin-top: 1rem; display: none; background: #f3f4f6; padding: 1rem; border-radius: 8px;">
                    <label for="paid-amount" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">{{ __('pos.amount_paid') }}</label>
                    <input type="number" id="paid-amount" placeholder="0" style="width: 100%; padding: 0.6rem; font-size: 1.1rem; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 0.75rem;" min="0" step="1" inputmode="decimal">

                    <div id="numpad-container" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-bottom: 0.75rem;">
                        <button type="button" class="numpad-btn" data-value="1000" data-value-usd="0.50" style="padding: 0.5rem; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">1000</button>
                        <button type="button" class="numpad-btn" data-value="5000" data-value-usd="1" style="padding: 0.5rem; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">5000</button>
                        <button type="button" class="numpad-btn" data-value="10000" data-value-usd="2" style="padding: 0.5rem; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">10000</button>
                        <button type="button" class="numpad-btn" data-value="20000" data-value-usd="5" style="padding: 0.5rem; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">20000</button>
                        <button type="button" class="numpad-btn" data-value="50000" data-value-usd="10" style="padding: 0.5rem; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">50000</button>
                        <button type="button" id="clear-paid" style="padding: 0.5rem; background: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; color: white;">{{ __('common.clear') }}</button>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div style="background: white; padding: 0.75rem; border-radius: 6px; text-align: center; border: 2px solid #e2e8f0;">
                            <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.3rem;">{{ __('pos.total') }}</div>
                            <div id="display-total" style="font-size: 1.2rem; font-weight: bold; color: #1f2937;">0</div>
                        </div>
                        <div style="background: white; padding: 0.75rem; border-radius: 6px; text-align: center; border: 2px solid #e2e8f0;">
                            <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.3rem;">{{ __('pos.amount_paid') }}</div>
                            <div id="display-paid" style="font-size: 1.2rem; font-weight: bold; color: #1f2937;">0</div>
                        </div>
                    </div>

                    <div id="transfer-back-container" style="display: none; background: #dcfce7; padding: 1rem; border-radius: 8px; border: 2px solid #86efac; margin-top: 0.75rem;">
                        <div style="font-size: 0.8rem; color: #15803d; margin-bottom: 0.5rem; font-weight: 600; text-transform: uppercase;">{{ __('pos.transfer_back') }}</div>
                        <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                            <div id="transfer-back-amount" style="font-size: 2.2rem; font-weight: bold; color: #16a34a;">0</div>
                            <div id="transfer-back-currency" style="font-size: 1rem; font-weight: 600; color: #16a34a;">៛</div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success" style="width:100%; margin-top:1rem;">{{ __('pos.checkout') }}</button>
            </form>
        </section>
    </div>

    <div class="cart-overlay" id="cart-overlay" onclick="closeCartDrawer()"></div>
    <div class="mobile-cart-bar" id="mobile-cart-bar" onclick="openCartDrawer()">
        <span id="mobile-cart-summary">{{ __('pos.items_count', ['count' => 0]) }}</span>
        <span class="mobile-cart-cta">{{ __('pos.view_cart') }}</span>
    </div>

    <!-- Exchange Rate Modal -->
    <div id="exchangeRateModal" class="exchange-modal">
        <div class="exchange-modal-content">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.3rem;">{{ __('pos.exchange_rate_settings') }}</h3>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">{{ __('pos.exchange_rate_label') }}</label>
                <p style="margin: 0.5rem 0; font-size: 0.9rem; color: #666;">{{ __('pos.exchange_rate_help') }}</p>
                <input type="number" id="exchangeRateInput" min="1" step="1" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 1rem;">
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="saveExchangeRate()" style="flex: 1; padding: 0.75rem; background: #10b981; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">{{ __('common.save') }}</button>
                <button type="button" onclick="toggleExchangeRateModal()" style="flex: 1; padding: 0.75rem; background: #e5e7eb; color: #333; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">{{ __('common.cancel') }}</button>
            </div>
        </div>
    </div>
@endsection

@php
    $posText = [
        'subtotal' => __('common.subtotal'),
        'noItemsYet' => __('pos.no_items_yet'),
        'itemSingular' => __('pos.item_word_singular'),
        'itemPlural' => __('pos.item_word_plural'),
        'removeItem' => __('pos.remove_item'),
        'exchangeRateUpdated' => __('pos.exchange_rate_updated'),
        'exchangeRateInvalid' => __('pos.exchange_rate_invalid'),
    ];
@endphp
@push('scripts')
<script>
    const posText = @json($posText);

    let currency = 'khr';
    let khrRate = parseFloat(localStorage.getItem('khrRate')) || 4100;
    let currentCategory = 'all';
    const cart = [];
    const cartItems = document.getElementById('cart-items');
    const summary = document.getElementById('cart-summary');
    const itemsInput = document.getElementById('items-input');
    const currencyInput = document.getElementById('currency-input');
    const cartActions = document.getElementById('cart-actions');
    const itemCountBadge = document.getElementById('item-count');
    const exchangeRateModal = document.getElementById('exchangeRateModal');
    const exchangeRateInput = document.getElementById('exchangeRateInput');
    const productSearch = document.getElementById('product-search');
    const mobileCartBar = document.getElementById('mobile-cart-bar');
    const mobileCartSummary = document.getElementById('mobile-cart-summary');
    const cartOverlay = document.getElementById('cart-overlay');
    const noResults = document.getElementById('no-results');

    exchangeRateInput.value = khrRate;

    function formatCurrency(value) {
        if (currency === 'khr') {
            return '៛' + new Intl.NumberFormat('en-US').format(Math.round(value));
        }
        return '$' + value.toFixed(2);
    }

    function setCurrency(value) {
        currency = value;
        currencyInput.value = value;
        document.getElementById('currency-usd').classList.toggle('active', value === 'usd');
        document.getElementById('currency-khr').classList.toggle('active', value === 'khr');

        // Clear payment calculator when currency changes
        paidAmountInput.value = '';
        document.getElementById('display-total').textContent = '0';
        document.getElementById('display-paid').textContent = '0';
        document.getElementById('transfer-back-container').style.display = 'none';

        // Update numpad button labels
        updateNumpadButtons();
        document.querySelectorAll('[id^="price-"]').forEach(el => {
            const parts = el.id.split('-'); // ['price', productId, unit]
            const productId = Number(parts[1]);
            const unit = parts[2];
            const item = cart.find(i => i.product_id === productId && i.unit === unit);
            if (item) {
                el.textContent = formatCurrency(currency === 'khr' ? item.price_khr : item.price_usd);
            }
        });
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        // Reset product card "in cart" badges before re-applying
        document.querySelectorAll('.product-card').forEach(card => card.classList.remove('in-cart'));
        document.querySelectorAll('.card-qty-badge, .variant-btn-badge').forEach(badge => {
            badge.style.display = 'none';
            badge.textContent = '0';
        });

        // Update item count badge
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        if (cart.length === 0) {
            itemCountBadge.style.display = 'none';
            cartActions.style.display = 'none';
            cartItems.innerHTML = '<p class="empty-cart">' + posText.noItemsYet + '</p>';
            summary.innerHTML = '<span>' + posText.subtotal + '</span><span>' + formatCurrency(0) + '</span>';
            itemsInput.value = '[]';
            mobileCartBar.classList.remove('show');
            closeCartDrawer();
            return;
        }

        itemCountBadge.textContent = cart.length;
        itemCountBadge.style.display = 'inline-block';
        cartActions.style.display = 'flex';
        document.getElementById('total-items-display').textContent = totalItems + ' ' + (totalItems === 1 ? posText.itemSingular : posText.itemPlural);

        let subtotal = 0;
        cartItems.innerHTML = '';
        cart.forEach((item, index) => {
            const price = currency === 'khr' ? item.price_khr : item.price_usd;
            subtotal += price * item.quantity;

            const badge = document.getElementById('badge-' + item.product_id + '-' + item.unit);
            if (badge) {
                badge.textContent = item.quantity;
                badge.style.display = 'flex';
                badge.closest('.product-card')?.classList.add('in-cart');
            }

            cartItems.innerHTML += `
                <div class="cart-item">
                    <div>
                        <strong>${item.name}</strong>
                        <small>${formatCurrency(price)}${item.variantLabel ? ' · ' + item.variantLabel : ''}</small>
                    </div>
                    <div style="display:flex; gap:0.75rem; align-items:center;">
                        <span class="qty-controls">
                            <button type="button" class="qty-btn" onclick="changeQty(${index}, -1)">&minus;</button>
                            <span class="qty-value">${item.quantity}</span>
                            <button type="button" class="qty-btn" onclick="changeQty(${index}, 1)">+</button>
                        </span>
                        <span class="cart-item-price" style="display:inline-block; margin-left:0.8rem; min-width:70px; text-align:right; color:#2563eb; font-weight:700;">${formatCurrency(price * item.quantity)}</span>
                        <button type="button" class="remove-btn" onclick="removeItem(${index})" title="${posText.removeItem}">×</button>
                    </div>
                </div>`;
        });
        summary.innerHTML = '<span>' + posText.subtotal + '</span><span>' + formatCurrency(subtotal) + '</span>';
        itemsInput.value = JSON.stringify(cart.map(({product_id, quantity, unit}) => ({product_id, quantity, unit})));

        mobileCartBar.classList.add('show');
        mobileCartSummary.textContent = totalItems + ' ' + (totalItems === 1 ? posText.itemSingular : posText.itemPlural) + ' · ' + formatCurrency(subtotal);
    }

    function addToCart(productId, name, priceUsd, priceKhr, unit, variantLabel) {
        unit = unit || 'piece';
        const existing = cart.find(item => item.product_id === productId && item.unit === unit);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({ product_id: productId, name, price_usd: priceUsd, price_khr: priceKhr, quantity: 1, unit, variantLabel: variantLabel || null });
        }
        renderCart();
    }

    function changeQty(index, delta) {
        if (cart[index].quantity + delta <= 0) {
            cart.splice(index, 1);
        } else {
            cart[index].quantity += delta;
        }
        renderCart();
    }

    // Category + search filtering
    function filterCategory(catId) {
        currentCategory = String(catId);
        document.querySelectorAll('.pill').forEach(pill => {
            pill.classList.toggle('active', pill.dataset.cat === currentCategory);
        });
        applyFilters();
    }

    function applyFilters() {
        const term = productSearch.value.trim().toLowerCase();
        let anyVisible = false;
        document.querySelectorAll('.product-card').forEach(card => {
            const matchesCategory = currentCategory === 'all' || card.dataset.cat === currentCategory;
            const matchesSearch = !term || card.dataset.name.includes(term);
            const visible = matchesCategory && matchesSearch;
            card.style.display = visible ? '' : 'none';
            if (visible) anyVisible = true;
        });
        noResults.style.display = anyVisible ? 'none' : 'block';
    }

    // Mobile cart drawer
    function openCartDrawer() {
        document.body.classList.add('cart-drawer-open');
    }

    function closeCartDrawer() {
        document.body.classList.remove('cart-drawer-open');
    }

    cartOverlay.addEventListener('click', closeCartDrawer);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeCartDrawer();
    });

    // Payment Calculator
    const paymentMethod = document.getElementById('payment_method');
    const paymentCalculator = document.getElementById('payment-calculator');
    const paidAmountInput = document.getElementById('paid-amount');
    const changeDisplay = document.getElementById('change-display');
    const changeAmount = document.getElementById('change-amount');
    const numpadBtns = document.querySelectorAll('.numpad-btn');
    const clearPaidBtn = document.getElementById('clear-paid');

    function getCurrentTotal() {
        const totalText = document.getElementById('cart-summary').textContent;
        const match = totalText.match(/៛([\d,]+)|[$]([\d,.]+)/);
        if (!match) return 0;
        const value = match[1] || match[2];
        if (currency === 'usd') {
            return parseFloat(value.replace(/,/g, '')) || 0;
        } else {
            return parseInt(value.replace(/,/g, '')) || 0;
        }
    }

    function updateNumpadButtons() {
        const buttons = document.querySelectorAll('.numpad-btn');
        buttons.forEach(btn => {
            if (currency === 'usd') {
                btn.textContent = '$' + btn.getAttribute('data-value-usd');
            } else {
                btn.textContent = btn.getAttribute('data-value');
            }
        });
    }

    function updateChange() {
        const total = getCurrentTotal();
        const paid = parseFloat(paidAmountInput.value) || 0;
        const change = paid - total;

        // Update display fields
        const displayTotal = document.getElementById('display-total');
        const displayPaid = document.getElementById('display-paid');
        const transferBackContainer = document.getElementById('transfer-back-container');
        const transferBackAmount = document.getElementById('transfer-back-amount');
        const transferBackCurrency = document.getElementById('transfer-back-currency');

        if (currency === 'khr') {
            displayTotal.textContent = '៛' + new Intl.NumberFormat('en-US').format(Math.round(total));
            displayPaid.textContent = paid > 0 ? '៛' + new Intl.NumberFormat('en-US').format(Math.round(paid)) : '៛0';
            transferBackCurrency.textContent = '៛';
            if (change > 0) {
                transferBackAmount.textContent = new Intl.NumberFormat('en-US').format(Math.round(change));
                transferBackContainer.style.display = 'block';
            } else {
                transferBackContainer.style.display = 'none';
            }
        } else {
            displayTotal.textContent = '$' + total.toFixed(2);
            displayPaid.textContent = paid > 0 ? '$' + paid.toFixed(2) : '$0.00';
            transferBackCurrency.textContent = '$';
            if (change > 0) {
                transferBackAmount.textContent = change.toFixed(2);
                transferBackContainer.style.display = 'block';
            } else {
                transferBackContainer.style.display = 'none';
            }
        }
    }

    paymentMethod.addEventListener('change', (e) => {
        if (e.target.value === 'cash') {
            paymentCalculator.style.display = 'block';
        } else {
            paymentCalculator.style.display = 'none';
        }
    });

    paidAmountInput.addEventListener('input', updateChange);

    numpadBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            let value, current;
            if (currency === 'usd') {
                value = parseFloat(btn.getAttribute('data-value-usd'));
                current = parseFloat(paidAmountInput.value) || 0;
                paidAmountInput.value = (current + value).toFixed(2);
            } else {
                value = parseInt(btn.getAttribute('data-value'));
                current = parseInt(paidAmountInput.value) || 0;
                paidAmountInput.value = current + value;
            }
            updateChange();
        });
    });

    clearPaidBtn.addEventListener('click', (e) => {
        e.preventDefault();
        paidAmountInput.value = '';
        document.getElementById('display-total').textContent = '0';
        document.getElementById('display-paid').textContent = '0';
        document.getElementById('transfer-back-container').style.display = 'none';
    });

    function toggleExchangeRateModal() {
        exchangeRateModal.style.display = exchangeRateModal.style.display === 'flex' ? 'none' : 'flex';
    }

    function updateProductPrices() {
        document.querySelectorAll('.product-price, .variant-btn-price').forEach(priceEl => {
            const khrMatch = priceEl.textContent.match(/៛([\d,]+)/);
            if (khrMatch) {
                const khr = parseInt(khrMatch[1].replace(/,/g, ''));
                const usd = (khr / khrRate).toFixed(2);
                const sep = priceEl.classList.contains('product-price') ? '<span class="price-sep">/</span>' : '';
                priceEl.innerHTML = `<span class="price-primary">៛${new Intl.NumberFormat('en-US').format(khr)}</span>${sep}<span class="price-secondary">$${usd}</span>`;
            }
        });
    }

    function saveExchangeRate() {
        const newRate = parseFloat(exchangeRateInput.value);
        if (newRate && newRate > 0) {
            khrRate = newRate;
            localStorage.setItem('khrRate', newRate);
            toggleExchangeRateModal();
            updateProductPrices();
            alert(posText.exchangeRateUpdated.replace(':rate', newRate));
        } else {
            alert(posText.exchangeRateInvalid);
        }
    }

    renderCart();
</script>
@endpush
