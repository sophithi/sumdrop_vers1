@extends('layouts.admin')

@section('title', 'Coffee POS')
@section('page-title', 'Coffee POS')
@section('page-subtitle', 'Tap items and complete checkout quickly.')

@push('styles')
<style>
    .pos-grid {
        display: grid;
        grid-template-columns: 1.7fr 1fr;
        gap: 1rem;
    }

    .product-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1rem;
        background: #ffffff;
        cursor: pointer;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        display: grid;
        grid-template-columns: 64px 1fr;
        gap: 0.9rem;
        align-items: center;
    }

    .product-card:hover {
        border-color: #2563eb;
        box-shadow: 0 12px 24px rgba(37, 99, 235, 0.08);
    }

    .product-thumb {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: #eef2ff;
        object-fit: cover;
        display: block;
    }

    .product-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .product-icon.beverages {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .product-icon.food {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .product-icon.snacks {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .product-card strong { display: block; margin-bottom: 0.5rem; }
    .product-card small { color: #64748b; }
    .product-price { color: #2563eb; font-weight: 700; margin-top: 0.5rem; display: block; }

    .product-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 0.9rem;
    }

    .cart-panel {
        display: flex;
        flex-direction: column;
        gap: 1rem;
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
    }

    /* Phone */
    @media (max-width: 768px) {
        .product-list {
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
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

        .product-card {
            grid-template-columns: 56px 1fr;
        }

        .product-thumb,
        .product-icon {
            width: 56px;
            height: 56px;
        }

        .exchange-modal-content {
            padding: 1.25rem;
        }
    }

</style>
@endpush

@section('content')
    <div class="pos-grid">
        <section class="panel">
            <div class="panel-head">
                <h3>Menu</h3>
                <div class="pos-toolbar">
                    <div class="currency-toggle">
                        <button type="button" id="currency-usd" class="currency-btn" onclick="setCurrency('usd')">USD</button>
                        <button type="button" id="currency-khr" class="currency-btn active" onclick="setCurrency('khr')">KHR</button>
                    </div>
                    <button type="button" id="settings-btn" onclick="toggleExchangeRateModal()" style="padding: 0.5rem 0.75rem; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; font-size: 1.2rem;" title="Exchange Rate Settings">⚙️</button>
                </div>
            </div>
            <div class="product-list">
                @php $khrRate = 4100; @endphp
                @foreach($products as $product)
                    @php
                        $priceKhr = $product->price_khr ?? $product->price;
                        $priceUsd = $product->price_usd ?? round($priceKhr / $khrRate, 2);
                    @endphp
                    <div class="product-card" role="button" tabindex="0" onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $priceUsd }}, {{ $priceKhr }})">
                        @if($product->hasImage())
                            <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" class="product-thumb">
                        @else
                            @php
                                $icon = '☕';
                                $categoryName = $product->category->name ?? '';
                                $productName = strtolower($product->name);
                                
                                if (str_contains($productName, 'tea') || str_contains($categoryName, 'tea')) {
                                    $icon = '🫖';
                                } elseif (str_contains($productName, 'latte') || str_contains($productName, 'cappuccino')) {
                                    $icon = '🥛';
                                } elseif (str_contains($productName, 'iced') || str_contains($productName, 'cold')) {
                                    $icon = '🧊';
                                } elseif (str_contains($productName, 'smoothie')) {
                                    $icon = '🧋';
                                }
                            @endphp
                            <div class="product-icon beverages">{{ $icon }}</div>
                        @endif
                        <div>
                            <strong>{{ $product->name }}</strong>
                            <small>{{ $product->category->name ?? 'Coffee' }}</small>
                            <div class="product-price" id="price-{{ $product->id }}" style="display: flex; gap: 0.5rem; font-size: 0.9rem;">
                                <span>៛{{ number_format($priceKhr) }}</span>
                                <span>/</span>
                                <span>${{ number_format($priceUsd, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="panel cart-panel">
            <div class="panel-head">
                <h3>Current order <span class="item-count" id="item-count" style="display:none;">0</span></h3>
            </div>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <div class="cart-actions" id="cart-actions" style="display:none;">
                <div id="total-items-display" style="font-weight:600; color:#2563eb;"></div>
            </div>

            <div id="cart-items"></div>
            <div class="summary" id="cart-summary">
                <span>Subtotal</span>
                <span>៛0</span>
            </div>

            <form id="checkout-form" method="POST" action="{{ route('orders.store') }}">
                @csrf
                <input type="hidden" name="items" id="items-input" value="[]">
                <input type="hidden" name="currency" id="currency-input" value="khr">

                <label for="payment_method">Payment method</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="cash">Cash</option>
                    <option value="mobile">Mobile (ABA, Wing)</option>
                </select>

                <div id="payment-calculator" style="margin-top: 1rem; display: none; background: #f3f4f6; padding: 1rem; border-radius: 8px;">
                    <label for="paid-amount" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Amount Paid</label>
                    <input type="number" id="paid-amount" placeholder="0" style="width: 100%; padding: 0.6rem; font-size: 1.1rem; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 0.75rem;" min="0" step="1" inputmode="decimal">
                    
                    <div id="numpad-container" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-bottom: 0.75rem;">
                        <button type="button" class="numpad-btn" data-value="1000" data-value-usd="0.50" style="padding: 0.5rem; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">1000</button>
                        <button type="button" class="numpad-btn" data-value="5000" data-value-usd="1" style="padding: 0.5rem; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">5000</button>
                        <button type="button" class="numpad-btn" data-value="10000" data-value-usd="2" style="padding: 0.5rem; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">10000</button>
                        <button type="button" class="numpad-btn" data-value="20000" data-value-usd="5" style="padding: 0.5rem; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">20000</button>
                        <button type="button" class="numpad-btn" data-value="50000" data-value-usd="10" style="padding: 0.5rem; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">50000</button>
                        <button type="button" id="clear-paid" style="padding: 0.5rem; background: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; color: white;">Clear</button>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div style="background: white; padding: 0.75rem; border-radius: 6px; text-align: center; border: 2px solid #e2e8f0;">
                            <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.3rem;">Total</div>
                            <div id="display-total" style="font-size: 1.2rem; font-weight: bold; color: #1f2937;">0</div>
                        </div>
                        <div style="background: white; padding: 0.75rem; border-radius: 6px; text-align: center; border: 2px solid #e2e8f0;">
                            <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.3rem;">Amount Paid</div>
                            <div id="display-paid" style="font-size: 1.2rem; font-weight: bold; color: #1f2937;">0</div>
                        </div>
                    </div>

                    <div id="transfer-back-container" style="display: none; background: #dcfce7; padding: 1rem; border-radius: 8px; border: 2px solid #86efac; margin-top: 0.75rem;">
                        <div style="font-size: 0.8rem; color: #15803d; margin-bottom: 0.5rem; font-weight: 600;">AMOUNT TO TRANSFER BACK</div>
                        <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                            <div id="transfer-back-amount" style="font-size: 2.2rem; font-weight: bold; color: #16a34a;">0</div>
                            <div id="transfer-back-currency" style="font-size: 1rem; font-weight: 600; color: #16a34a;">៛</div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success" style="width:100%; margin-top:1rem;">Checkout</button>
            </form>
        </section>
    </div>

    <!-- Exchange Rate Modal -->
    <div id="exchangeRateModal" class="exchange-modal">
        <div class="exchange-modal-content">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.3rem;">Exchange Rate Settings</h3>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">KHR to USD Rate</label>
                <p style="margin: 0.5rem 0; font-size: 0.9rem; color: #666;">How many KHR equals 1 USD?</p>
                <input type="number" id="exchangeRateInput" min="1" step="1" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 1rem;">
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="saveExchangeRate()" style="flex: 1; padding: 0.75rem; background: #10b981; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Save</button>
                <button type="button" onclick="toggleExchangeRateModal()" style="flex: 1; padding: 0.75rem; background: #e5e7eb; color: #333; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Cancel</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currency = 'khr';
    let khrRate = parseFloat(localStorage.getItem('khrRate')) || 4100;
    const cart = [];
    const cartItems = document.getElementById('cart-items');
    const summary = document.getElementById('cart-summary');
    const itemsInput = document.getElementById('items-input');
    const currencyInput = document.getElementById('currency-input');
    const cartActions = document.getElementById('cart-actions');
    const itemCountBadge = document.getElementById('item-count');
    const exchangeRateModal = document.getElementById('exchangeRateModal');
    const exchangeRateInput = document.getElementById('exchangeRateInput');

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
            const id = el.id.replace('price-', '');
            const productId = Number(id);
            const item = cart.find(i => i.product_id === productId);
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
        // Update item count badge
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        if (cart.length === 0) {
            itemCountBadge.style.display = 'none';
            cartActions.style.display = 'none';
            cartItems.innerHTML = '<p class="empty-cart">No items yet. Tap a product to add it.</p>';
            summary.innerHTML = '<span>Subtotal</span><span>' + formatCurrency(0) + '</span>';
            itemsInput.value = '[]';
            return;
        }

        itemCountBadge.textContent = cart.length;
        itemCountBadge.style.display = 'inline-block';
        cartActions.style.display = 'flex';
        document.getElementById('total-items-display').textContent = totalItems + ' items';

        let subtotal = 0;
        cartItems.innerHTML = '';
        cart.forEach((item, index) => {
            const price = currency === 'khr' ? item.price_khr : item.price_usd;
            subtotal += price * item.quantity;
            cartItems.innerHTML += `
                <div class="cart-item">
                    <div>
                        <strong>${item.name}</strong>
                        <small>${formatCurrency(price)}</small>
                    </div>
                    <div style="display:flex; gap:0.75rem; align-items:center;">
                        <span class="qty-controls">
                            <button type="button" class="qty-btn" onclick="changeQty(${index}, -1)">&minus;</button>
                            <span class="qty-value">${item.quantity}</span>
                            <button type="button" class="qty-btn" onclick="changeQty(${index}, 1)">+</button>
                        </span>
                        <span class="product-price" style="margin-left:0.8rem; min-width:70px; text-align:right;">${formatCurrency(price * item.quantity)}</span>
                        <button type="button" class="remove-btn" onclick="removeItem(${index})" title="Remove item">×</button>
                    </div>
                </div>`;
        });
        summary.innerHTML = '<span>Subtotal</span><span>' + formatCurrency(subtotal) + '</span>';
        itemsInput.value = JSON.stringify(cart.map(({product_id, quantity}) => ({product_id, quantity})));
    }

    function addToCart(productId, name, priceUsd, priceKhr) {
        const existing = cart.find(item => item.product_id === productId);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({ product_id: productId, name, price_usd: priceUsd, price_khr: priceKhr, quantity: 1 });
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
            displayTotal.textContent = '\u17db' + new Intl.NumberFormat('en-US').format(Math.round(total));
            displayPaid.textContent = paid > 0 ? '\u17db' + new Intl.NumberFormat('en-US').format(Math.round(paid)) : '\u17db0';
            transferBackCurrency.textContent = '\u17db';
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
        document.querySelectorAll('.product-card').forEach(card => {
            const priceDiv = card.querySelector('.product-price');
            if (priceDiv) {
                const khrMatch = priceDiv.textContent.match(/៛([\d,]+)/);
                if (khrMatch) {
                    const khr = parseInt(khrMatch[1].replace(/,/g, ''));
                    const usd = (khr / khrRate).toFixed(2);
                    priceDiv.innerHTML = `<span>៛${new Intl.NumberFormat('en-US').format(khr)}</span><span>/</span><span>$${usd}</span>`;
                }
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
            alert('Exchange rate updated to: 1 USD = ' + newRate + ' KHR');
        } else {
            alert('Please enter a valid exchange rate.');
        }
    }

    renderCart();
</script>
@endpush
