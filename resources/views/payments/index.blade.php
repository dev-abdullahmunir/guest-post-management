@extends('layouts.app')

@section('title', 'Payments & Financial Transactions')

@section('content')
<div class="space-y-6">

  <!-- Header Section with Actions -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
          <i class="fa-solid fa-credit-card"></i>
        </div>
        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Payments & Financial Transactions</h2>
      </div>
      <p class="text-xs text-slate-500 mt-1">Track buyer receipts (inflow), webmaster payouts (outflow), and cash balance.</p>
    </div>

    <div class="flex items-center gap-3">
      <!-- Search Input in Payments Section -->
      <div class="relative w-64">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-xs text-slate-400"></i>
        <input 
          type="text" 
          id="paymentSearchInput" 
          onkeyup="filterPaymentsTable()" 
          placeholder="Filter by party, trans ID, method..." 
          class="w-full bg-white border border-slate-200/90 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500 shadow-sm transition"
        />
      </div>

      <!-- Add New Payment Button -->
      <button 
        type="button" 
        onclick="openCreatePaymentModal()" 
        class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/25 rounded-xl px-4 py-2 text-xs font-bold flex items-center gap-2 transition"
      >
        <i class="fa-solid fa-plus text-[10px]"></i>
        <span>Record Payment</span>
      </button>
    </div>
  </div>

  @php
    $totalReceived = 0;
    $totalPaid = 0;
    $pendingTotal = 0;
    $pendingCount = 0;

    foreach ($payments ?? [] as $p) {
      $amt = (float)($p['amount'] ?? 0);
      if (($p['status'] ?? '') === 'completed') {
        if ($p['payment_type'] === 'received') {
          $totalReceived += $amt;
        } else if ($p['payment_type'] === 'paid') {
          $totalPaid += $amt;
        }
      } else if (($p['status'] ?? '') === 'pending') {
        $pendingTotal += $amt;
        $pendingCount++;
      }
    }
    $netCashBalance = $totalReceived - $totalPaid;
  @endphp

  <!-- Financial KPI Summary Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    
    <!-- Total Received (Inflow) -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-emerald-500 text-white shadow-md shadow-emerald-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-arrow-down-left"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Client Inflow (Received)</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">
            ${{ number_format($totalReceived, 2) }}
          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">
        Inflow
      </span>
    </div>

    <!-- Total Paid (Outflow) -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-rose-500 text-white shadow-md shadow-rose-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-arrow-up-right"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Vendor Outflow (Paid)</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">
            ${{ number_format($totalPaid, 2) }}
          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-100">
        Disbursed
      </span>
    </div>

    <!-- Net Cash Balance -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-indigo-500 text-white shadow-md shadow-indigo-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-scale-balanced"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Net Cash Flow Margin</p>
          <h3 class="text-xl font-black {{ $netCashBalance >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-0.5 font-mono">
            {{ $netCashBalance >= 0 ? '+' : '-' }}${{ number_format(abs($netCashBalance), 2) }}
          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
        Cash Margin
      </span>
    </div>

    <!-- Pending Payments -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-amber-500 text-white shadow-md shadow-amber-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Pending Clearance</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">
            ${{ number_format($pendingTotal, 2) }}
          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100">
        {{ $pendingCount }} Pending
      </span>
    </div>

  </div>

  <!-- Dedicated Payments Full Table -->
  <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-50 gap-4">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-receipt text-amber-500 text-xs"></i>
        <h3 class="text-sm font-bold text-slate-800">Payment Transactions</h3>
      </div>

      <!-- Type Filter Tabs -->
      <div class="flex items-center gap-1.5 bg-slate-100/70 p-1 rounded-xl text-xs font-bold text-slate-600 overflow-x-auto">
        <button onclick="filterByType('')" class="type-tab px-3 py-1 rounded-lg bg-white shadow-sm text-slate-800 transition" data-type="">All</button>
        <button onclick="filterByType('received')" class="type-tab px-3 py-1 rounded-lg hover:text-slate-800 transition" data-type="received">Received (Client)</button>
        <button onclick="filterByType('paid')" class="type-tab px-3 py-1 rounded-lg hover:text-slate-800 transition" data-type="paid">Paid (Vendor)</button>
        <button onclick="filterByPaymentStatus('pending')" class="type-tab px-3 py-1 rounded-lg hover:text-slate-800 transition" data-status="pending">Pending</button>
      </div>
    </div>

    <div class="overflow-x-auto mt-2">
      <table class="min-w-full text-left text-xs divide-y divide-slate-100" id="paymentsTable">
        <thead class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
          <tr>
            <th class="py-3.5 pr-4">Date</th>
            <th class="py-3.5 px-4">Type</th>
            <th class="py-3.5 px-4">Party (Client / Vendor)</th>
            <th class="py-3.5 px-4">Order / Post Link</th>
            <th class="py-3.5 px-4">Amount</th>
            <th class="py-3.5 px-4">Method & Trans ID</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-4">Notes</th>
            <th class="py-3.5 pl-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50 text-slate-600" id="paymentsTableBody">
          @forelse($payments ?? [] as $payment)
            @php
              $isReceived = ($payment['payment_type'] ?? '') === 'received';
            @endphp
            <tr class="hover:bg-slate-50/70 transition payment-row" 
                data-payment-type="{{ $payment['payment_type'] ?? '' }}"
                data-payment-status="{{ $payment['status'] ?? 'completed' }}"
                id="payment-row-{{ $payment['id'] }}">
              
              <!-- Payment Date -->
              <td class="py-3.5 pr-4 font-mono text-slate-600 whitespace-nowrap">
                {{ !empty($payment['payment_date']) ? date('M d, Y', strtotime($payment['payment_date'])) : '-' }}
              </td>

              <!-- Payment Type Badge -->
              <td class="py-3.5 px-4 whitespace-nowrap">
                @if($isReceived)
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="fa-solid fa-arrow-down-left text-[9px]"></i> Received
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    <i class="fa-solid fa-arrow-up-right text-[9px]"></i> Paid
                  </span>
                @endif
              </td>

              <!-- Party (Client or Vendor) -->
              <td class="py-3.5 px-4">
                @if($isReceived)
                  <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-[10px]">
                      {{ substr($payment['client']['name'] ?? 'CL', 0, 2) }}
                    </div>
                    <div>
                      <h5 class="font-bold text-slate-800 party-name">{{ $payment['client']['name'] ?? 'Client #' . $payment['client_id'] }}</h5>
                      <span class="text-[9px] text-indigo-500 font-medium">Client (Buyer)</span>
                    </div>
                  </div>
                @else
                  <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-purple-100 text-purple-700 font-bold flex items-center justify-center text-[10px]">
                      {{ substr($payment['vendor']['name'] ?? 'VN', 0, 2) }}
                    </div>
                    <div>
                      <h5 class="font-bold text-slate-800 party-name">{{ $payment['vendor']['name'] ?? 'Vendor #' . $payment['vendor_id'] }}</h5>
                      <span class="text-[9px] text-purple-500 font-medium">Vendor (Webmaster)</span>
                    </div>
                  </div>
                @endif
              </td>

              <!-- Linked Order / Guest Post -->
              <td class="py-3.5 px-4">
                @if(!empty($payment['order_item']))
                  <div class="max-w-[200px]">
                    <span class="font-mono text-[10px] font-bold text-indigo-600 block">
                      Item #{{ $payment['order_item']['id'] }}
                    </span>
                    <span class="font-bold text-slate-700 text-[11px] truncate block" title="{{ $payment['order_item']['website']['website_name'] ?? '' }}">
                      {{ $payment['order_item']['website']['website_name'] ?? 'Website #' . $payment['order_item']['website_id'] }}
                    </span>
                    <span class="text-[10px] text-slate-400 truncate block">
                      "{{ $payment['order_item']['anchor_text'] ?? '' }}"
                    </span>
                  </div>
                @else
                  <span class="text-[11px] text-slate-400 italic">General Retainer / Order</span>
                @endif
              </td>

              <!-- Amount -->
              <td class="py-3.5 px-4 font-mono whitespace-nowrap">
                @if($isReceived)
                  <span class="font-bold text-emerald-600 text-xs">
                    +${{ number_format($payment['amount'] ?? 0, 2) }}
                  </span>
                @else
                  <span class="font-bold text-rose-600 text-xs">
                    -${{ number_format($payment['amount'] ?? 0, 2) }}
                  </span>
                @endif
                <span class="text-[10px] text-slate-400 ml-0.5">{{ $payment['currency'] ?? 'USD' }}</span>
              </td>

              <!-- Payment Method & Trans ID -->
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-1.5 font-medium text-slate-800">
                  <i class="fa-regular fa-credit-card text-[11px] text-slate-400"></i>
                  <span class="method-name">{{ $payment['payment_method'] ?? 'N/A' }}</span>
                </div>
                @if(!empty($payment['transaction_id']))
                  <span class="font-mono text-[10px] text-slate-400 block trans-id mt-0.5">
                    Ref: {{ $payment['transaction_id'] }}
                  </span>
                @endif
              </td>

              <!-- Status Badge -->
              <td class="py-3.5 px-4 whitespace-nowrap">
                @php
                  $st = $payment['status'] ?? 'completed';
                  $badgeClasses = match($st) {
                    'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                    'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                    'failed' => 'bg-rose-50 text-rose-600 border-rose-100',
                    'refunded' => 'bg-slate-100 text-slate-600 border-slate-200',
                    default => 'bg-slate-50 text-slate-500 border-slate-100',
                  };
                @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClasses }}">
                  {{ ucfirst($st) }}
                </span>
              </td>

              <!-- Notes -->
              <td class="py-3.5 px-4 text-slate-400 truncate max-w-[160px]" title="{{ $payment['notes'] ?? '' }}">
                {{ $payment['notes'] ?? '-' }}
              </td>

              <!-- Actions -->
              <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                <button 
                  type="button" 
                  onclick='openEditPaymentModal(@json($payment))' 
                  class="text-indigo-600 hover:text-indigo-800 font-bold mr-3 inline-flex items-center gap-1 transition"
                >
                  <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                </button>
                <button 
                  type="button" 
                  onclick="deletePayment({{ $payment['id'] }})" 
                  class="text-rose-500 hover:text-rose-700 font-bold inline-flex items-center gap-1 transition"
                >
                  <i class="fa-regular fa-trash-can text-[11px]"></i> Delete
                </button>
              </td>
            </tr>
          @empty
            <tr id="emptyPaymentRow">
              <td colspan="9" class="py-12 text-center text-slate-400">
                <div class="flex flex-col items-center justify-center">
                  <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl mb-3">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                  </div>
                  <h4 class="font-bold text-slate-700 text-sm">No payment records found</h4>
                  <p class="text-xs text-slate-400 mt-1">Record client receipts or vendor disbursements to track financial balance.</p>
                  <button onclick="openCreatePaymentModal()" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    + Record First Payment
                  </button>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- Payment Add / Edit Modal -->
<div id="paymentModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-200">
  <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl w-full max-w-xl overflow-hidden transform transition-all scale-95 duration-200" id="paymentModalCard">
    
    <!-- Modal Header -->
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shadow-md shadow-amber-500/30">
          <i class="fa-solid fa-credit-card text-base" id="paymentModalIcon"></i>
        </div>
        <div>
          <h3 class="text-base font-black text-slate-800" id="paymentModalTitle">Record Payment</h3>
          <p class="text-xs text-slate-400">Track client receipts (inflow) or vendor disbursements (outflow).</p>
        </div>
      </div>
      <button type="button" onclick="closePaymentModal()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200/70 flex items-center justify-center transition">
        <i class="fa-solid fa-xmark text-sm"></i>
      </button>
    </div>

    <!-- Modal Body Form -->
    <form id="paymentForm" onsubmit="handlePaymentSubmit(event)" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto custom-scroll">
      <input type="hidden" id="paymentId" name="id" value="">

      <!-- Payment Type Toggle (Received vs Paid) -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-2">
          Payment Type <span class="text-rose-500">*</span>
        </label>
        <div class="grid grid-cols-2 gap-3">
          <label class="cursor-pointer">
            <input type="radio" name="payment_type" id="typeReceived" value="received" class="sr-only peer" checked onchange="togglePartyFields()">
            <div class="p-3 rounded-2xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/50 flex items-center gap-3 transition">
              <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-arrow-down-left"></i>
              </div>
              <div>
                <h5 class="text-xs font-bold text-slate-800">Received (Inflow)</h5>
                <p class="text-[10px] text-slate-400">Client paid money to us</p>
              </div>
            </div>
          </label>

          <label class="cursor-pointer">
            <input type="radio" name="payment_type" id="typePaid" value="paid" class="sr-only peer" onchange="togglePartyFields()">
            <div class="p-3 rounded-2xl border-2 border-slate-200 peer-checked:border-rose-500 peer-checked:bg-rose-50/50 flex items-center gap-3 transition">
              <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-arrow-up-right"></i>
              </div>
              <div>
                <h5 class="text-xs font-bold text-slate-800">Paid (Outflow)</h5>
                <p class="text-[10px] text-slate-400">Paid out to vendor</p>
              </div>
            </div>
          </label>
        </div>
      </div>

      <!-- Client Selection (Shown when Received) -->
      <div id="clientSelectWrapper">
        <label class="block text-xs font-bold text-slate-700 mb-1.5">
          Client (Buyer) <span class="text-rose-500">*</span>
        </label>
        <select 
          id="paymentClientId" 
          name="client_id" 
          class="custom-select"
          data-placeholder="Select Client (Buyer)..."
          data-icon="fa-solid fa-users"
        >
          <option value="">Select Client...</option>
          @foreach($clients ?? [] as $c)
            <option value="{{ $c['id'] }}" data-subtext="{{ $c['company_name'] ?? 'Direct Buyer' }}">{{ $c['name'] }}</option>
          @endforeach
        </select>
      </div>

      <!-- Vendor Selection (Shown when Paid) -->
      <div id="vendorSelectWrapper" class="hidden">
        <label class="block text-xs font-bold text-slate-700 mb-1.5">
          Vendor (Seller) <span class="text-rose-500">*</span>
        </label>
        <select 
          id="paymentVendorId" 
          name="vendor_id" 
          class="custom-select"
          data-placeholder="Select Vendor (Seller)..."
          data-icon="fa-solid fa-handshake"
        >
          <option value="">Select Vendor...</option>
          @foreach($sellers ?? [] as $s)
            <option value="{{ $s['id'] }}" data-subtext="{{ $s['company_name'] ?? 'Webmaster / Seller' }}">{{ $s['name'] }}</option>
          @endforeach
        </select>
      </div>

      <!-- Optional Link to Order Item -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">
          Linked Order / Guest Post Item <span class="text-slate-400 font-normal">(Optional)</span>
        </label>
        <select 
          id="paymentOrderItemId" 
          name="order_item_id" 
          class="custom-select"
          data-placeholder="None (General invoice / deposit / retainer)"
          data-icon="fa-solid fa-link"
        >
          <option value="">None (General invoice / deposit / retainer)</option>
          @foreach($orderItems ?? [] as $oi)
            <option 
              value="{{ $oi['id'] }}" 
              data-badge="Item #{{ $oi['id'] }}"
              data-subtext="{{ $oi['website']['website_name'] ?? 'Website #' . $oi['website_id'] }} • Anchor: '{{ $oi['anchor_text'] }}'"
            >
              Order #{{ $oi['order']['order_number'] ?? $oi['order_id'] }} - {{ $oi['website']['website_name'] ?? 'Website' }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Amount & Currency Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Amount <span class="text-rose-500">*</span>
          </label>
          <div class="relative">
            <i class="fa-solid fa-dollar-sign absolute left-3.5 top-3 text-xs text-slate-400"></i>
            <input 
              type="number" 
              step="0.01" 
              id="paymentAmount" 
              name="amount" 
              required 
              placeholder="e.g. 500.00"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition font-mono font-bold"
            >
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Currency</label>
          <select 
            id="paymentCurrency" 
            name="currency" 
            class="custom-select"
            data-icon="fa-solid fa-coins"
          >
            <option value="USD" selected>USD ($)</option>
            <option value="EUR">EUR (€)</option>
            <option value="GBP">GBP (£)</option>
            <option value="USDT">USDT (Crypto)</option>
            <option value="AED">AED (Dirhams)</option>
            <option value="PKR">PKR (Rupees)</option>
          </select>
        </div>
      </div>

      <!-- Payment Method & Transaction ID Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Payment Method <span class="text-rose-500">*</span>
          </label>
          <div class="relative">
            <i class="fa-regular fa-credit-card absolute left-3.5 top-3 text-xs text-slate-400"></i>
            <input 
              type="text" 
              id="paymentMethod" 
              name="payment_method" 
              required 
              placeholder="e.g. PayPal, Bank Wire, Wise"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
            >
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Transaction ID / Ref #</label>
          <div class="relative">
            <i class="fa-solid fa-hashtag absolute left-3.5 top-3 text-xs text-slate-400"></i>
            <input 
              type="text" 
              id="paymentTransactionId" 
              name="transaction_id" 
              placeholder="e.g. PP-9901452"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition font-mono"
            >
          </div>
        </div>
      </div>

      <!-- Date & Status Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Payment Date <span class="text-rose-500">*</span>
          </label>
          <div class="relative">
            <i class="fa-regular fa-calendar absolute left-3.5 top-3 text-xs text-slate-400 z-10 pointer-events-none"></i>
            <input 
              type="text" 
              id="paymentDate" 
              name="payment_date" 
              data-calendar="true"
              data-shortcuts="true"
              required 
              value="{{ date('Y-m-d') }}"
              placeholder="Select Payment Date..."
              class="custom-datepicker w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 font-medium focus:outline-none focus:border-indigo-500 focus:bg-white transition cursor-pointer"
            >
            <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none"></i>
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Payment Status</label>
          <select 
            id="paymentStatus" 
            name="status" 
            class="custom-select"
            data-icon="fa-solid fa-circle-check"
          >
            <option value="completed">Completed (Funds Cleared)</option>
            <option value="pending">Pending (Awaiting Confirmation)</option>
            <option value="failed">Failed</option>
            <option value="refunded">Refunded</option>
          </select>
        </div>
      </div>

      <!-- Notes -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">Payment Notes / Description</label>
        <textarea 
          id="paymentNotes" 
          name="notes" 
          rows="2" 
          placeholder="e.g. 50% advance received for guest post package."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition resize-none"
        ></textarea>
      </div>

      <!-- Modal Footer Actions -->
      <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
        <button 
          type="button" 
          onclick="closePaymentModal()" 
          class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
        >
          Cancel
        </button>
        <button 
          type="submit" 
          id="paymentSubmitBtn" 
          class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md shadow-amber-600/30 flex items-center gap-2 transition"
        >
          <span id="paymentSubmitText">Save Payment</span>
          <i class="fa-solid fa-check text-xs"></i>
        </button>
      </div>
    </form>

  </div>
</div>

<script>
  let activeTypeFilter = '';
  let activeStatusFilter = '';

  function togglePartyFields() {
    const isReceived = document.getElementById('typeReceived').checked;
    const clientWrap = document.getElementById('clientSelectWrapper');
    const vendorWrap = document.getElementById('vendorSelectWrapper');
    const clientSelect = document.getElementById('paymentClientId');
    const vendorSelect = document.getElementById('paymentVendorId');

    if (isReceived) {
      clientWrap.classList.remove('hidden');
      vendorWrap.classList.add('hidden');
      vendorSelect.value = '';
    } else {
      clientWrap.classList.add('hidden');
      vendorWrap.classList.remove('hidden');
      clientSelect.value = '';
    }
    window.refreshCustomSelect('paymentClientId');
    window.refreshCustomSelect('paymentVendorId');
  }

  function filterByType(type) {
    activeTypeFilter = type;
    activeStatusFilter = '';
    updateTabButtons();
    filterPaymentsTable();
  }

  function filterByPaymentStatus(status) {
    activeStatusFilter = status;
    activeTypeFilter = '';
    updateTabButtons();
    filterPaymentsTable();
  }

  function updateTabButtons() {
    document.querySelectorAll('.type-tab').forEach(btn => {
      const matchType = btn.dataset.type !== undefined && btn.dataset.type === activeTypeFilter && !activeStatusFilter;
      const matchStatus = btn.dataset.status !== undefined && btn.dataset.status === activeStatusFilter && !activeTypeFilter;
      if (matchType || matchStatus) {
        btn.className = 'type-tab px-3 py-1 rounded-lg bg-white shadow-sm text-slate-800 transition';
      } else {
        btn.className = 'type-tab px-3 py-1 rounded-lg hover:text-slate-800 transition';
      }
    });
  }

  function filterPaymentsTable() {
    const query = document.getElementById('paymentSearchInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.payment-row');

    rows.forEach(row => {
      const party = row.querySelector('.party-name')?.textContent.toLowerCase() || '';
      const method = row.querySelector('.method-name')?.textContent.toLowerCase() || '';
      const transId = row.querySelector('.trans-id')?.textContent.toLowerCase() || '';
      const pType = row.dataset.paymentType || '';
      const pStatus = row.dataset.paymentStatus || '';

      const matchesQuery = party.includes(query) || method.includes(query) || transId.includes(query);
      const matchesType = !activeTypeFilter || pType === activeTypeFilter;
      const matchesStatus = !activeStatusFilter || pStatus === activeStatusFilter;

      if (matchesQuery && matchesType && matchesStatus) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  function openCreatePaymentModal() {
    document.getElementById('paymentForm').reset();
    document.getElementById('paymentId').value = '';
    document.getElementById('typeReceived').checked = true;
    document.getElementById('paymentDate').value = new Date().toISOString().substring(0, 10);
    togglePartyFields();

    document.getElementById('paymentModalTitle').textContent = 'Record Payment';
    document.getElementById('paymentSubmitText').textContent = 'Save Payment';
    window.refreshCustomSelect('paymentClientId');
    window.refreshCustomSelect('paymentVendorId');
    window.refreshCustomSelect('paymentOrderItemId');
    window.refreshCustomSelect('paymentCurrency');
    window.refreshCustomSelect('paymentStatus');

    const modal = document.getElementById('paymentModal');
    const card = document.getElementById('paymentModalCard');
    modal.classList.remove('hidden');
    setTimeout(() => card.classList.remove('scale-95'), 10);
  }

  function openEditPaymentModal(payment) {
    document.getElementById('paymentForm').reset();
    document.getElementById('paymentId').value = payment.id;

    if (payment.payment_type === 'paid') {
      document.getElementById('typePaid').checked = true;
    } else {
      document.getElementById('typeReceived').checked = true;
    }
    togglePartyFields();

    if (payment.client_id) {
      document.getElementById('paymentClientId').value = payment.client_id;
    }
    if (payment.vendor_id) {
      document.getElementById('paymentVendorId').value = payment.vendor_id;
    }
    if (payment.order_item_id) {
      document.getElementById('paymentOrderItemId').value = payment.order_item_id;
    }

    document.getElementById('paymentAmount').value = payment.amount;
    document.getElementById('paymentCurrency').value = payment.currency || 'USD';
    document.getElementById('paymentMethod').value = payment.payment_method || '';
    document.getElementById('paymentTransactionId').value = payment.transaction_id || '';
    document.getElementById('paymentDate').value = payment.payment_date ? payment.payment_date.substring(0, 10) : '';
    document.getElementById('paymentStatus').value = payment.status || 'completed';
    document.getElementById('paymentNotes').value = payment.notes || '';
    window.refreshCustomSelect('paymentClientId');
    window.refreshCustomSelect('paymentVendorId');
    window.refreshCustomSelect('paymentOrderItemId');
    window.refreshCustomSelect('paymentCurrency');
    window.refreshCustomSelect('paymentStatus');

    document.getElementById('paymentModalTitle').textContent = 'Edit Payment Details';
    document.getElementById('paymentSubmitText').textContent = 'Update Payment';

    const modal = document.getElementById('paymentModal');
    const card = document.getElementById('paymentModalCard');
    modal.classList.remove('hidden');
    setTimeout(() => card.classList.remove('scale-95'), 10);
  }

  function closePaymentModal() {
    const modal = document.getElementById('paymentModal');
    const card = document.getElementById('paymentModalCard');
    card.classList.add('scale-95');
    setTimeout(() => modal.classList.add('hidden'), 150);
  }

  async function handlePaymentSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('paymentSubmitBtn');
    const submitText = document.getElementById('paymentSubmitText');
    const originalText = submitText.textContent;
    btn.disabled = true;
    submitText.textContent = 'Saving...';

    const id = document.getElementById('paymentId').value;
    const isReceived = document.getElementById('typeReceived').checked;

    const payload = {
      payment_type: isReceived ? 'received' : 'paid',
      client_id: isReceived ? (document.getElementById('paymentClientId').value || null) : null,
      vendor_id: !isReceived ? (document.getElementById('paymentVendorId').value || null) : null,
      order_item_id: document.getElementById('paymentOrderItemId').value || null,
      amount: parseFloat(document.getElementById('paymentAmount').value || 0),
      currency: document.getElementById('paymentCurrency').value,
      payment_method: document.getElementById('paymentMethod').value.trim(),
      transaction_id: document.getElementById('paymentTransactionId').value.trim() || null,
      payment_date: document.getElementById('paymentDate').value,
      status: document.getElementById('paymentStatus').value,
      notes: document.getElementById('paymentNotes').value.trim() || null,
    };

    const url = id ? `/api/payments/${id}/update` : '/api/payments';

    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });

      const data = await res.json();
      if (res.ok && data.status === 'success') {
        showToast(data.message || 'Payment saved successfully!');
        closePaymentModal();
        setTimeout(() => window.location.reload(), 600);
      } else {
        showToast(data.message || 'Error occurred while saving payment.', true);
      }
    } catch (err) {
      showToast('Network error, please check connection.', true);
    } finally {
      btn.disabled = false;
      submitText.textContent = originalText;
    }
  }

  async function deletePayment(id) {
    if (!confirm('Are you sure you want to delete this payment record?')) {
      return;
    }

    try {
      const res = await fetch(`/api/payments/${id}/delete`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
      });
      const data = await res.json();
      if (res.ok && data.status === 'success') {
        showToast('Payment record deleted successfully!');
        setTimeout(() => window.location.reload(), 600);
      } else {
        showToast(data.message || 'Failed to delete payment.', true);
      }
    } catch (err) {
      showToast('Network connection failed.', true);
    }
  }
</script>
@endsection
