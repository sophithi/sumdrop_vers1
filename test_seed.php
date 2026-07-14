
$users = App\Models\User::all();
$products = App\Models\Product::all();

$dates = ["2026-07-10", "2026-07-11", "2026-07-12", "2026-07-13", "2026-07-14"];
$paymentMethods = ["cash", "bank", "mobile"];
$statuses = ["completed", "completed", "completed", "pending", "cancelled"];

$createdCount = 0;
$currencyCounts = ["khr" => 0, "usd" => 0];

for ($i = 0; $i < 25; $i++) {
    $currency = (rand(1, 100) <= 70) ? "khr" : "usd";
    $currencyCounts[$currency]++;
    
    $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
    $status = $statuses[array_rand($statuses)];
    $user = $users->random();
    
    $randomDate = $dates[array_rand($dates)] . " " . sprintf("%02d:%02d:%02d", rand(8, 20), rand(0, 59), rand(0, 59));
    
    $numItems = rand(1, 3);
    $selectedProducts = $products->random(min($numItems, $products->count()));
    
    $subtotal = 0;
    $itemsData = [];
    
    foreach ($selectedProducts as $product) {
        $qty = rand(1, 5);
        $price = $product->getPriceByCurrency($currency);
        $lineTotal = $price * $qty;
        
        $subtotal += $lineTotal;
        $itemsData[] = [
            "product_id" => $product->id,
            "quantity" => $qty,
            "price" => $price
        ];
    }
    
    $tax = round($subtotal * 0.08, 2);
    $total = $subtotal + $tax;
    
    $order = App\Models\Order::create([
        "user_id" => $user->id,
        "subtotal" => $subtotal,
        "tax" => $tax,
        "total" => $total,
        "status" => $status,
        "payment_method" => $paymentMethod,
        "currency" => $currency,
        "created_at" => $randomDate,
        "updated_at" => $randomDate,
    ]);
    
    foreach ($itemsData as $item) {
        $order->items()->create($item);
    }
    
    $createdCount++;
}

echo "RESULT_START\n";
echo "CREATED: " . $createdCount . "\n";
echo "KHR_COUNT: " . $currencyCounts["khr"] . "\n";
echo "USD_COUNT: " . $currencyCounts["usd"] . "\n";
echo "RESULT_END\n";

