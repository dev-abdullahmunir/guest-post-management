@extends('layouts.app')

@section('title', 'Orders Management')

@section('content')
<div class="space-y-6">

  <!-- Header Section with Actions -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Orders Management</h2>
      </div>
      <p class="text-xs text-slate-500 mt-1">Track guest post orders, pricing, vendor costs, delivery deadlines, and net profit margins.</p>
    </div>

    <div class="flex items-center gap-2.5 flex-wrap">
      <!-- Search Input in Orders Section -->
      <div class="relative w-56">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-xs text-slate-400 pointer-events-none"></i>
        <input 
          type="text" 
          id="orderSearchInput" 
          onkeyup="filterOrdersTable()" 
          placeholder="Filter by order #, client..." 
          class="w-full bg-white border border-slate-200/90 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500 shadow-sm transition"
        />
      </div>

      <!-- Date Range Calendar Filter with Quick Reset -->
      <div class="relative w-60">
        <i class="fa-regular fa-calendar absolute left-3.5 top-3 text-xs text-slate-400 z-10 pointer-events-none"></i>
        <input 
          type="text" 
          id="orderDateRangeFilter" 
          data-calendar="true" 
          data-mode="range" 
          data-shortcuts="true"
          onchange="filterOrdersTable()" 
          placeholder="Filter by date range..." 
          class="custom-datepicker w-full bg-white border border-slate-200/90 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-700 font-medium placeholder-slate-400 focus:outline-none focus:border-indigo-500 shadow-sm transition cursor-pointer"
        />
        <button 
          type="button" 
          id="clearOrderDateRangeBtn" 
          onclick="clearOrderDateFilter()" 
          title="Reset date filter" 
          class="hidden absolute right-2.5 top-2.5 w-5 h-5 rounded-md hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center text-[10px] transition z-10"
        >
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Add New Order Button -->
      <button 
        type="button" 
        onclick="openCreateOrderModal()" 
        class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/25 rounded-xl px-4 py-2 text-xs font-bold flex items-center gap-2 transition shrink-0"
      >
        <i class="fa-solid fa-plus text-[10px]"></i>
        <span>Create Order</span>
      </button>
    </div>
  </div>

  <!-- Executive Financial KPI Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    
    <!-- Total Orders -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-blue-500 text-white shadow-md shadow-blue-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Total Orders</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">{{ count($orders ?? []) }}</h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">
        All Time
      </span>
    </div>

    <!-- Total Client Revenue -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-indigo-500 text-white shadow-md shadow-indigo-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-wallet"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Total Buyer Revenue</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">
            ${{ number_format(array_sum(array_column($orders ?? [], 'total_client_price')), 2) }}
          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
        Billed
      </span>
    </div>

    <!-- Total Vendor Cost -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-rose-500 text-white shadow-md shadow-rose-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-money-bill-wave"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Total Vendor Cost</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">
            ${{ number_format(array_sum(array_column($orders ?? [], 'total_vendor_cost')), 2) }}
          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-100">
        Expenses
      </span>
    </div>

    <!-- Total Net Profit -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-emerald-500 text-white shadow-md shadow-emerald-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-arrow-trend-up"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Total Net Profit</p>
          <h3 class="text-xl font-black text-emerald-600 mt-0.5 font-mono">
            ${{ number_format(array_sum(array_column($orders ?? [], 'total_profit')), 2) }}
          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">
        Net Margin
      </span>
    </div>

  </div>

  <!-- Dedicated Orders Full Table -->
  <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-50 gap-4">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-list-check text-indigo-600 text-xs"></i>
        <h3 class="text-sm font-bold text-slate-800">Orders List</h3>
      </div>

      <!-- Status Filter Tabs -->
      <div class="flex items-center gap-1.5 bg-slate-100/70 p-1 rounded-xl text-xs font-bold text-slate-600 overflow-x-auto">
        <button onclick="filterByStatus('')" class="status-tab px-3 py-1 rounded-lg bg-white shadow-sm text-slate-800 transition" data-status="">All</button>
        <button onclick="filterByStatus('pending')" class="status-tab px-3 py-1 rounded-lg hover:text-slate-800 transition" data-status="pending">Pending</button>
        <button onclick="filterByStatus('in_progress')" class="status-tab px-3 py-1 rounded-lg hover:text-slate-800 transition" data-status="in_progress">In Progress</button>
        <button onclick="filterByStatus('published')" class="status-tab px-3 py-1 rounded-lg hover:text-slate-800 transition" data-status="published">Published</button>
        <button onclick="filterByStatus('completed')" class="status-tab px-3 py-1 rounded-lg hover:text-slate-800 transition" data-status="completed">Completed</button>
      </div>
    </div>

    <div class="overflow-x-auto mt-2">
      <table class="min-w-full text-left text-xs divide-y divide-slate-100" id="ordersTable">
        <thead class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
          <tr>
            <th class="py-3.5 pr-4">Order #</th>
            <th class="py-3.5 px-4">Client (Buyer)</th>
            <th class="py-3.5 px-4">Guest Posts</th>
            <th class="py-3.5 px-4">Order Date</th>
            <th class="py-3.5 px-4">Deadline</th>
            <th class="py-3.5 px-4">Client Price</th>
            <th class="py-3.5 px-4">Vendor Cost</th>
            <th class="py-3.5 px-4">Net Profit</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 pl-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50 text-slate-600" id="ordersTableBody">
          @forelse($orders ?? [] as $order)
            <tr class="hover:bg-slate-50/70 transition order-row" data-order-status="{{ $order['status'] ?? 'pending' }}" data-order-date="{{ !empty($order['order_date']) ? date('Y-m-d', strtotime($order['order_date'])) : '' }}" id="order-row-{{ $order['id'] }}">
              
              <!-- Order Number -->
              <td class="py-3.5 pr-4 font-mono font-bold text-indigo-600 order-number">
                {{ $order['order_number'] ?? 'ORD-' . $order['id'] }}
              </td>

              <!-- Client -->
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                    {{ substr($order['client']['name'] ?? 'CL', 0, 2) }}
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-800 order-client">{{ $order['client']['name'] ?? 'Client #' . $order['client_id'] }}</h4>
                    <p class="text-[10px] text-slate-400">{{ $order['client']['company_name'] ?? 'Direct Buyer' }}</p>
                  </div>
                </div>
              </td>

              <!-- Order Items (Guest Posts) Count & Manage Button -->
              <td class="py-3.5 px-4">
                <button 
                  type="button" 
                  onclick="openOrderItemsModal({{ $order['id'] }})"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200/70 text-xs font-bold transition shadow-sm"
                  title="Click to view and manage guest post links"
                >
                  <i class="fa-solid fa-list-check text-[11px]"></i>
                  <span id="order-items-count-{{ $order['id'] }}">{{ count($order['items'] ?? []) }} Posts</span>
                </button>
              </td>

              <!-- Order Date -->
              <td class="py-3.5 px-4 font-mono text-slate-600">
                {{ !empty($order['order_date']) ? date('M d, Y', strtotime($order['order_date'])) : '-' }}
              </td>

              <!-- Deadline -->
              <td class="py-3.5 px-4 font-mono">
                @if(!empty($order['deadline']))
                  <span class="inline-flex items-center gap-1 font-semibold text-slate-700">
                    <i class="fa-regular fa-clock text-[10px] text-slate-400"></i>
                    {{ date('M d, Y', strtotime($order['deadline'])) }}
                  </span>
                @else
                  <span class="text-slate-400">-</span>
                @endif
              </td>

              <!-- Client Price -->
              <td class="py-3.5 px-4 font-mono font-bold text-slate-800" id="order-client-price-{{ $order['id'] }}">
                ${{ number_format($order['total_client_price'] ?? 0, 2) }}
              </td>

              <!-- Vendor Cost -->
              <td class="py-3.5 px-4 font-mono text-slate-500" id="order-vendor-cost-{{ $order['id'] }}">
                ${{ number_format($order['total_vendor_cost'] ?? 0, 2) }}
              </td>

              <!-- Net Profit -->
              <td class="py-3.5 px-4 font-mono font-black text-emerald-600" id="order-profit-{{ $order['id'] }}">
                <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                  +${{ number_format($order['total_profit'] ?? 0, 2) }}
                </span>
              </td>

              <!-- Status -->
              <td class="py-3.5 px-4">
                @php
                  $status = $order['status'] ?? 'pending';
                  $badgeClasses = match($status) {
                    'published', 'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                    'in_progress' => 'bg-blue-50 text-blue-600 border-blue-100',
                    'cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
                    default => 'bg-amber-50 text-amber-600 border-amber-100',
                  };
                  $label = match($status) {
                    'in_progress' => 'In Progress',
                    'published' => 'Published',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                    default => 'Pending',
                  };
                @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClasses }}">
                  {{ $label }}
                </span>
              </td>

              <!-- Actions -->
              <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                <button 
                  type="button" 
                  onclick='openEditOrderModal(@json($order))' 
                  class="text-indigo-600 hover:text-indigo-800 font-bold mr-3 inline-flex items-center gap-1 transition"
                >
                  <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                </button>
                <button 
                  type="button" 
                  onclick="deleteOrder({{ $order['id'] }}, '{{ addslashes($order['order_number']) }}')" 
                  class="text-rose-500 hover:text-rose-700 font-bold inline-flex items-center gap-1 transition"
                >
                  <i class="fa-regular fa-trash-can text-[11px]"></i> Delete
                </button>
              </td>
            </tr>
          @empty
            <tr id="emptyOrderRow">
              <td colspan="10" class="py-12 text-center text-slate-400">
                <div class="flex flex-col items-center justify-center">
                  <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mb-3">
                    <i class="fa-solid fa-cart-shopping"></i>
                  </div>
                  <h4 class="font-bold text-slate-700 text-sm">No orders placed yet</h4>
                  <p class="text-xs text-slate-400 mt-1">Create your first guest post order to begin tracking client revenue and profit.</p>
                  <button onclick="openCreateOrderModal()" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    + Create First Order
                  </button>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Order Items (Guest Posts) Management Modal (Step 4b: order_items) -->
  <div id="orderItemsModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-200">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl w-full max-w-5xl overflow-hidden transform transition-all scale-95 duration-200" id="orderItemsModalCard">
      
      <!-- Modal Header -->
      <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-600/30">
            <i class="fa-solid fa-file-lines text-base"></i>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h3 class="text-base font-black text-slate-800" id="itemsModalOrderNumber">Order Guest Posts</h3>
              <span id="itemsModalOrderStatus" class="px-2 py-0.5 rounded-full text-[10px] font-bold"></span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">
              Client: <span id="itemsModalClientName" class="font-bold text-slate-700"></span> • Manage individual links, anchors, vendor costs & margins.
            </p>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <button 
            type="button" 
            onclick="openAddOrderItemModal()" 
            class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/25 rounded-xl px-4 py-2 text-xs font-bold flex items-center gap-2 transition"
          >
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Add Guest Post</span>
          </button>

          <button type="button" onclick="closeOrderItemsModal()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200/70 flex items-center justify-center transition">
            <i class="fa-solid fa-xmark text-sm"></i>
          </button>
        </div>
      </div>

      <!-- Financial Rollup Summary for Selected Order -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 px-6 py-4 bg-slate-50/80 border-b border-slate-100">
        <div class="bg-white p-3 rounded-xl border border-slate-200/60">
          <p class="text-[10px] font-bold uppercase text-slate-400">Total Posts</p>
          <p class="text-base font-black text-slate-800 font-mono mt-0.5" id="itemsModalPostCount">0</p>
        </div>
        <div class="bg-white p-3 rounded-xl border border-slate-200/60">
          <p class="text-[10px] font-bold uppercase text-slate-400">Client Revenue</p>
          <p class="text-base font-black text-slate-800 font-mono mt-0.5" id="itemsModalClientTotal">$0.00</p>
        </div>
        <div class="bg-white p-3 rounded-xl border border-slate-200/60">
          <p class="text-[10px] font-bold uppercase text-slate-400">Vendor Cost</p>
          <p class="text-base font-black text-rose-600 font-mono mt-0.5" id="itemsModalVendorTotal">$0.00</p>
        </div>
        <div class="bg-white p-3 rounded-xl border border-slate-200/60">
          <p class="text-[10px] font-bold uppercase text-slate-400">Net Profit Margin</p>
          <p class="text-base font-black text-emerald-600 font-mono mt-0.5" id="itemsModalProfitTotal">+$0.00</p>
        </div>
      </div>

      <!-- Items Table Area -->
      <div class="p-6 max-h-[55vh] overflow-y-auto custom-scroll">
        <div class="overflow-x-auto">
          <table class="min-w-full text-left text-xs divide-y divide-slate-100">
            <thead class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
              <tr>
                <th class="py-2.5 pr-3">Website</th>
                <th class="py-2.5 px-3">Vendor / Seller</th>
                <th class="py-2.5 px-3">Anchor & Target</th>
                <th class="py-2.5 px-3">Client Price</th>
                <th class="py-2.5 px-3">Vendor Cost</th>
                <th class="py-2.5 px-3">Profit</th>
                <th class="py-2.5 px-3">Deadline</th>
                <th class="py-2.5 px-3">Status</th>
                <th class="py-2.5 pl-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-slate-600" id="orderItemsTableBody">
              <!-- Dynamically populated via JS -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="px-6 py-3.5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
        <span>Order totals automatically recalculate as items are added, updated, or removed.</span>
        <button 
          type="button" 
          onclick="closeOrderItemsModal()" 
          class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition"
        >
          Close
        </button>
      </div>

    </div>
  </div>

  <!-- Add / Edit Single Order Item (Guest Post) Submodal -->
  <div id="orderItemFormModal" class="fixed inset-0 z-60 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-200">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl w-full max-w-xl overflow-hidden transform transition-all scale-95 duration-200" id="orderItemFormCard">
      
      <!-- Submodal Header -->
      <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-600/30">
            <i class="fa-solid fa-link text-base" id="itemFormIcon"></i>
          </div>
          <div>
            <h3 class="text-base font-black text-slate-800" id="itemFormTitle">Add Guest Post to Order</h3>
            <p class="text-xs text-slate-400">Select website, fill anchor details, and set buyer/seller pricing.</p>
          </div>
        </div>
        <button type="button" onclick="closeOrderItemFormModal()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200/70 flex items-center justify-center transition">
          <i class="fa-solid fa-xmark text-sm"></i>
        </button>
      </div>

      <!-- Submodal Body Form -->
      <form id="orderItemForm" onsubmit="handleOrderItemSubmit(event)" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scroll">
        <input type="hidden" id="itemFormId" name="id" value="">
        <input type="hidden" id="itemFormOrderId" name="order_id" value="">
        <input type="hidden" id="itemFormVendorId" name="vendor_id" value="">

        <!-- Website Selection -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Website / Publication <span class="text-rose-500">*</span>
          </label>
          <select 
            id="itemFormWebsiteId" 
            name="website_id" 
            required
            onchange="onItemWebsiteChanged()"
            class="custom-select"
            data-placeholder="Select Inventory Website..."
            data-icon="fa-solid fa-globe"
          >
            <option value="">Select Inventory Website...</option>
            @foreach($websites ?? [] as $w)
              <option 
                value="{{ $w['id'] }}" 
                data-vendor-id="{{ $w['vendor_id'] }}"
                data-vendor-name="{{ $w['vendor']['name'] ?? 'Vendor #' . $w['vendor_id'] }}"
                data-vendor-price="{{ $w['vendor_price'] }}"
                data-badge="DA: {{ $w['domain_authority'] ?? 'N/A' }}"
                data-subtext="Cost: ${{ number_format($w['vendor_price'], 2) }} • {{ $w['vendor']['name'] ?? 'Vendor #' . $w['vendor_id'] }}"
              >
                {{ $w['website_name'] }}
              </option>
            @endforeach
          </select>
          <!-- Vendor Info Pill -->
          <div id="itemVendorInfo" class="mt-1.5 text-[11px] text-slate-500 flex items-center gap-1.5 hidden">
            <span class="inline-flex items-center gap-1 font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
              <i class="fa-solid fa-handshake text-[10px]"></i> Vendor: <span id="itemVendorName"></span>
            </span>
          </div>
        </div>

        <!-- Anchor Text & Target URL Grid -->
        <div class="space-y-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Anchor Text <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
              <i class="fa-solid fa-anchor absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="itemFormAnchorText" 
                name="anchor_text" 
                required 
                placeholder="e.g. best project management tool"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Target Landing URL <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
              <i class="fa-solid fa-arrow-up-right-from-square absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="url" 
                id="itemFormTargetUrl" 
                name="target_url" 
                required 
                placeholder="https://clientdomain.com/landing-page"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition font-mono"
              >
            </div>
          </div>
        </div>

        <!-- Pricing & Profit Calculation Card -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-[11px] font-bold text-slate-700 mb-1">
                Client Price ($) <span class="text-rose-500">*</span>
              </label>
              <div class="relative">
                <i class="fa-solid fa-dollar-sign absolute left-3 top-2.5 text-xs text-slate-400"></i>
                <input 
                  type="number" 
                  step="0.01" 
                  id="itemFormClientPrice" 
                  name="client_price" 
                  required 
                  onkeyup="calcItemProfit()" 
                  onchange="calcItemProfit()" 
                  placeholder="250.00"
                  class="w-full bg-white border border-slate-200 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-800 font-mono font-bold focus:outline-none focus:border-indigo-500 transition"
                >
              </div>
              <p class="text-[10px] text-slate-400 mt-0.5">Charged to buyer</p>
            </div>

            <div>
              <label class="block text-[11px] font-bold text-slate-700 mb-1">
                Vendor Price ($) <span class="text-rose-500">*</span>
              </label>
              <div class="relative">
                <i class="fa-solid fa-dollar-sign absolute left-3 top-2.5 text-xs text-slate-400"></i>
                <input 
                  type="number" 
                  step="0.01" 
                  id="itemFormVendorPrice" 
                  name="vendor_price" 
                  required 
                  onkeyup="calcItemProfit()" 
                  onchange="calcItemProfit()" 
                  placeholder="150.00"
                  class="w-full bg-white border border-slate-200 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-800 font-mono text-slate-600 focus:outline-none focus:border-indigo-500 transition"
                >
              </div>
              <p class="text-[10px] text-slate-400 mt-0.5">Cost to webmaster</p>
            </div>
          </div>

          <!-- Profit Margin Badge -->
          <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-600">Item Profit Margin:</span>
            <span id="itemFormProfitBadge" class="text-xs font-black text-emerald-600 bg-emerald-100/80 px-2.5 py-1 rounded-lg border border-emerald-200 font-mono">
              +$0.00
            </span>
          </div>
        </div>

        <!-- Deadline & Status Grid (Enhanced with Flatpickr Calendar) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Anchor / Post Deadline</label>
            <div class="relative">
              <i class="fa-regular fa-calendar-check absolute left-3.5 top-3 text-xs text-slate-400 z-10 pointer-events-none"></i>
              <input 
                type="text" 
                id="itemFormAnchorDeadline" 
                name="anchor_deadline" 
                data-calendar="true"
                data-shortcuts="true"
                placeholder="Select Deadline..."
                class="custom-datepicker w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 font-medium focus:outline-none focus:border-indigo-500 focus:bg-white transition cursor-pointer"
              >
              <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none"></i>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Status</label>
            <select 
              id="itemFormStatus" 
              name="status" 
              class="custom-select"
              data-placeholder="Select Status..."
            >
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="published">Published</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>

        <!-- Modal Footer Actions -->
        <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
          <button 
            type="button" 
            onclick="closeOrderItemFormModal()" 
            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            id="itemFormSubmitBtn" 
            class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/30 flex items-center gap-2 transition"
          >
            <span id="itemFormSubmitText">Save Post</span>
            <i class="fa-solid fa-check text-xs"></i>
          </button>
        </div>
      </form>

    </div>
  </div>

</div>

<script>
  let activeStatusFilter = '';
  let currentOrderId = null;
  let currentOrderData = null;

  function filterByStatus(status) {
    activeStatusFilter = status;
    document.querySelectorAll('.status-tab').forEach(btn => {
      if (btn.dataset.status === status) {
        btn.className = 'status-tab px-3 py-1 rounded-lg bg-white shadow-sm text-slate-800 transition';
      } else {
        btn.className = 'status-tab px-3 py-1 rounded-lg hover:text-slate-800 transition';
      }
    });
    filterOrdersTable();
  }

  function clearOrderDateFilter() {
    const el = document.getElementById('orderDateRangeFilter');
    if (el && el._flatpickr) {
      el._flatpickr.clear();
    }
    const clearBtn = document.getElementById('clearOrderDateRangeBtn');
    if (clearBtn) clearBtn.classList.add('hidden');
    filterOrdersTable();
  }

  function filterOrdersTable() {
    const query = document.getElementById('orderSearchInput').value.toLowerCase().trim();
    const fp = document.getElementById('orderDateRangeFilter')?._flatpickr;
    const clearBtn = document.getElementById('clearOrderDateRangeBtn');

    let startDate = null;
    let endDate = null;

    if (fp && fp.selectedDates && fp.selectedDates.length > 0) {
      if (clearBtn) clearBtn.classList.remove('hidden');

      if (fp.selectedDates.length === 1) {
        // User is in the middle of picking the 2nd date: do not blank out rows yet
        startDate = null;
        endDate = null;
      } else if (fp.selectedDates.length >= 2) {
        startDate = new Date(fp.selectedDates[0]);
        startDate.setHours(0, 0, 0, 0);
        endDate = new Date(fp.selectedDates[1]);
        endDate.setHours(23, 59, 59, 999);
      }
    } else {
      if (clearBtn) clearBtn.classList.add('hidden');
    }

    const rows = document.querySelectorAll('.order-row');

    rows.forEach(row => {
      const orderNum = row.querySelector('.order-number')?.textContent.toLowerCase() || '';
      const client = row.querySelector('.order-client')?.textContent.toLowerCase() || '';
      const status = row.dataset.orderStatus || '';
      const rowDateStr = (row.dataset.orderDate || '').trim();

      const matchesQuery = orderNum.includes(query) || client.includes(query);
      const matchesStatus = !activeStatusFilter || status === activeStatusFilter;

      let matchesDate = true;
      if (startDate && endDate && rowDateStr) {
        const parts = rowDateStr.split('-');
        if (parts.length === 3) {
          const rowDate = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]), 12, 0, 0);
          matchesDate = rowDate >= startDate && rowDate <= endDate;
        }
      } else if (startDate && endDate && !rowDateStr) {
        matchesDate = false;
      }

      if (matchesQuery && matchesStatus && matchesDate) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  // --- Order Items (Guest Posts) Management Logic ---
  async function openOrderItemsModal(orderId) {
    currentOrderId = orderId;
    const modal = document.getElementById('orderItemsModal');
    const card = document.getElementById('orderItemsModalCard');
    
    // Clear and show loading state
    document.getElementById('orderItemsTableBody').innerHTML = `
      <tr>
        <td colspan="9" class="py-8 text-center text-slate-400">
          <i class="fa-solid fa-circle-notch fa-spin text-lg text-indigo-500 mb-2"></i>
          <p>Loading guest post items...</p>
        </td>
      </tr>
    `;

    modal.classList.remove('hidden');
    setTimeout(() => card.classList.remove('scale-95'), 10);

    try {
      const res = await fetch(`/api/orders/${orderId}/items`);
      const data = await res.json();
      if (data.status === 'success') {
        currentOrderData = data.order;
        renderOrderItems(data.data, data.order);
      } else {
        showToast(data.message || 'Failed to fetch items', true);
      }
    } catch (e) {
      showToast('Error connecting to server', true);
    }
  }

  function renderOrderItems(items, order) {
    document.getElementById('itemsModalOrderNumber').textContent = order.order_number || `Order #${order.id}`;
    document.getElementById('itemsModalClientName').textContent = (order.client && order.client.name) ? order.client.name : `Client #${order.client_id}`;
    
    // Status Badge
    const statusElem = document.getElementById('itemsModalOrderStatus');
    statusElem.textContent = (order.status || 'pending').toUpperCase();
    statusElem.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold ' + 
      (order.status === 'completed' || order.status === 'published' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100');

    // Rollup stats
    const totalPosts = items.length;
    let clientSum = 0;
    let vendorSum = 0;
    items.forEach(it => {
      clientSum += parseFloat(it.client_price || 0);
      vendorSum += parseFloat(it.vendor_price || 0);
    });
    const profitSum = clientSum - vendorSum;

    document.getElementById('itemsModalPostCount').textContent = totalPosts;
    document.getElementById('itemsModalClientTotal').textContent = '$' + clientSum.toFixed(2);
    document.getElementById('itemsModalVendorTotal').textContent = '$' + vendorSum.toFixed(2);
    document.getElementById('itemsModalProfitTotal').textContent = (profitSum >= 0 ? '+$' : '-$') + Math.abs(profitSum).toFixed(2);

    // Update parent order row badges on the main table
    const countBadge = document.getElementById(`order-items-count-${order.id}`);
    if (countBadge) countBadge.textContent = `${totalPosts} Posts`;
    const clientPriceCell = document.getElementById(`order-client-price-${order.id}`);
    if (clientPriceCell) clientPriceCell.textContent = '$' + clientSum.toFixed(2);
    const vendorCostCell = document.getElementById(`order-vendor-cost-${order.id}`);
    if (vendorCostCell) vendorCostCell.textContent = '$' + vendorSum.toFixed(2);
    const profitCell = document.getElementById(`order-profit-${order.id}`);
    if (profitCell) {
      profitCell.innerHTML = `<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">+` + (profitSum >= 0 ? '$' : '-$') + Math.abs(profitSum).toFixed(2) + `</span>`;
    }

    const tbody = document.getElementById('orderItemsTableBody');
    if (!items || items.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="9" class="py-10 text-center text-slate-400">
            <div class="flex flex-col items-center justify-center">
              <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-lg mb-2">
                <i class="fa-solid fa-link-slash"></i>
              </div>
              <h5 class="font-bold text-slate-700 text-xs">No guest posts added yet</h5>
              <p class="text-[11px] text-slate-400 mt-0.5">Click "Add Guest Post" to associate publications, anchors, and pricing.</p>
            </div>
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = items.map(item => {
      const profit = (parseFloat(item.client_price || 0) - parseFloat(item.vendor_price || 0)).toFixed(2);
      const statusClasses = {
        'published': 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'completed': 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'in_progress': 'bg-blue-50 text-blue-600 border-blue-100',
        'cancelled': 'bg-rose-50 text-rose-600 border-rose-100',
      }[item.status] || 'bg-amber-50 text-amber-600 border-amber-100';

      const websiteName = item.website ? item.website.website_name : 'Website #' + item.website_id;
      const websiteUrl = item.website ? item.website.website_url : '#';
      const vendorName = item.vendor ? item.vendor.name : 'Vendor #' + item.vendor_id;

      return `
        <tr class="hover:bg-slate-50/70 transition">
          <td class="py-2.5 pr-3">
            <div class="font-bold text-slate-800">${escapeHtml(websiteName)}</div>
            <a href="${escapeHtml(websiteUrl)}" target="_blank" class="text-[10px] text-indigo-500 hover:underline flex items-center gap-1">
              ${escapeHtml(websiteUrl)} <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
            </a>
          </td>
          <td class="py-2.5 px-3">
            <span class="inline-flex items-center gap-1 font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md text-[10px]">
              <i class="fa-solid fa-handshake text-[9px] text-slate-400"></i> ${escapeHtml(vendorName)}
            </span>
          </td>
          <td class="py-2.5 px-3 max-w-[200px]">
            <div class="font-bold text-slate-800 text-[11px] truncate" title="${escapeHtml(item.anchor_text)}">
              "${escapeHtml(item.anchor_text)}"
            </div>
            <a href="${escapeHtml(item.target_url)}" target="_blank" class="text-[10px] text-slate-400 hover:text-indigo-600 truncate block mt-0.5" title="${escapeHtml(item.target_url)}">
              ${escapeHtml(item.target_url)}
            </a>
          </td>
          <td class="py-2.5 px-3 font-mono font-bold text-slate-800">
            $${parseFloat(item.client_price).toFixed(2)}
          </td>
          <td class="py-2.5 px-3 font-mono text-slate-500">
            $${parseFloat(item.vendor_price).toFixed(2)}
          </td>
          <td class="py-2.5 px-3 font-mono font-bold text-emerald-600">
            +$${profit}
          </td>
          <td class="py-2.5 px-3 font-mono text-slate-500 whitespace-nowrap">
            ${item.anchor_deadline ? item.anchor_deadline.substring(0, 10) : '-'}
          </td>
          <td class="py-2.5 px-3 whitespace-nowrap">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold border ${statusClasses}">
              ${(item.status || 'pending').replace('_', ' ').toUpperCase()}
            </span>
          </td>
          <td class="py-2.5 pl-3 text-right whitespace-nowrap">
            <button 
              type="button" 
              onclick='openEditOrderItemModal(${JSON.stringify(item)})' 
              class="text-indigo-600 hover:text-indigo-800 font-bold mr-2 text-[11px] transition"
            >
              <i class="fa-regular fa-pen-to-square"></i>
            </button>
            <button 
              type="button" 
              onclick="deleteOrderItem(${item.id}, '${escapeHtml(item.anchor_text)}')" 
              class="text-rose-500 hover:text-rose-700 font-bold text-[11px] transition"
            >
              <i class="fa-regular fa-trash-can"></i>
            </button>
          </td>
        </tr>
      `;
    }).join('');
  }

  function closeOrderItemsModal() {
    const modal = document.getElementById('orderItemsModal');
    const card = document.getElementById('orderItemsModalCard');
    card.classList.add('scale-95');
    setTimeout(() => modal.classList.add('hidden'), 150);
  }

  // --- Submodal: Add / Edit Order Item Form Logic ---
  function openAddOrderItemModal() {
    document.getElementById('orderItemForm').reset();
    document.getElementById('itemFormId').value = '';
    document.getElementById('itemFormOrderId').value = currentOrderId;
    document.getElementById('itemFormVendorId').value = '';
    document.getElementById('itemVendorInfo').classList.add('hidden');
    document.getElementById('itemFormProfitBadge').textContent = '+$0.00';
    window.setDatePickerValue('itemFormAnchorDeadline', '');

    document.getElementById('itemFormTitle').textContent = 'Add Guest Post to Order';
    document.getElementById('itemFormSubmitText').textContent = 'Save Post';
    window.refreshCustomSelect('itemFormWebsiteId');
    window.refreshCustomSelect('itemFormStatus');

    const modal = document.getElementById('orderItemFormModal');
    const card = document.getElementById('orderItemFormCard');
    modal.classList.remove('hidden');
    setTimeout(() => card.classList.remove('scale-95'), 10);
  }

  function openEditOrderItemModal(item) {
    document.getElementById('orderItemForm').reset();
    document.getElementById('itemFormId').value = item.id;
    document.getElementById('itemFormOrderId').value = item.order_id;
    document.getElementById('itemFormWebsiteId').value = item.website_id;
    document.getElementById('itemFormVendorId').value = item.vendor_id;
    document.getElementById('itemFormAnchorText').value = item.anchor_text || '';
    document.getElementById('itemFormTargetUrl').value = item.target_url || '';
    document.getElementById('itemFormClientPrice').value = item.client_price || '';
    document.getElementById('itemFormVendorPrice').value = item.vendor_price || '';
    window.setDatePickerValue('itemFormAnchorDeadline', item.anchor_deadline ? item.anchor_deadline.substring(0, 10) : '');
    document.getElementById('itemFormStatus').value = item.status || 'pending';

    onItemWebsiteChanged();
    calcItemProfit();
    window.refreshCustomSelect('itemFormWebsiteId');
    window.refreshCustomSelect('itemFormStatus');

    document.getElementById('itemFormTitle').textContent = 'Edit Guest Post Details';
    document.getElementById('itemFormSubmitText').textContent = 'Update Post';

    const modal = document.getElementById('orderItemFormModal');
    const card = document.getElementById('orderItemFormCard');
    modal.classList.remove('hidden');
    setTimeout(() => card.classList.remove('scale-95'), 10);
  }

  function closeOrderItemFormModal() {
    const modal = document.getElementById('orderItemFormModal');
    const card = document.getElementById('orderItemFormCard');
    card.classList.add('scale-95');
    setTimeout(() => modal.classList.add('hidden'), 150);
  }

  function onItemWebsiteChanged() {
    const select = document.getElementById('itemFormWebsiteId');
    const selectedOpt = select.options[select.selectedIndex];
    if (!selectedOpt || !selectedOpt.value) {
      document.getElementById('itemVendorInfo').classList.add('hidden');
      return;
    }

    const vendorId = selectedOpt.getAttribute('data-vendor-id');
    const vendorName = selectedOpt.getAttribute('data-vendor-name');
    const vendorPrice = selectedOpt.getAttribute('data-vendor-price');

    document.getElementById('itemFormVendorId').value = vendorId;
    document.getElementById('itemVendorName').textContent = vendorName;
    document.getElementById('itemVendorInfo').classList.remove('hidden');

    const vendorPriceInput = document.getElementById('itemFormVendorPrice');
    if (!vendorPriceInput.value || document.getElementById('itemFormId').value === '') {
      vendorPriceInput.value = parseFloat(vendorPrice || 0).toFixed(2);
    }

    calcItemProfit();
  }

  function calcItemProfit() {
    const clientPrice = parseFloat(document.getElementById('itemFormClientPrice').value || 0);
    const vendorPrice = parseFloat(document.getElementById('itemFormVendorPrice').value || 0);
    const profit = clientPrice - vendorPrice;
    const badge = document.getElementById('itemFormProfitBadge');

    badge.textContent = (profit >= 0 ? '+$' : '-$') + Math.abs(profit).toFixed(2);
    if (profit >= 0) {
      badge.className = 'text-xs font-black text-emerald-600 bg-emerald-100/80 px-2.5 py-1 rounded-lg border border-emerald-200 font-mono';
    } else {
      badge.className = 'text-xs font-black text-rose-600 bg-rose-100/80 px-2.5 py-1 rounded-lg border border-rose-200 font-mono';
    }
  }

  async function handleOrderItemSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('itemFormSubmitBtn');
    const submitText = document.getElementById('itemFormSubmitText');
    const originalText = submitText.textContent;
    btn.disabled = true;
    submitText.textContent = 'Saving...';

    const id = document.getElementById('itemFormId').value;
    const orderId = document.getElementById('itemFormOrderId').value;

    const payload = {
      order_id: parseInt(orderId),
      website_id: parseInt(document.getElementById('itemFormWebsiteId').value),
      vendor_id: parseInt(document.getElementById('itemFormVendorId').value),
      anchor_text: document.getElementById('itemFormAnchorText').value.trim(),
      target_url: document.getElementById('itemFormTargetUrl').value.trim(),
      client_price: parseFloat(document.getElementById('itemFormClientPrice').value || 0),
      vendor_price: parseFloat(document.getElementById('itemFormVendorPrice').value || 0),
      anchor_deadline: document.getElementById('itemFormAnchorDeadline').value || null,
      status: document.getElementById('itemFormStatus').value,
    };

    const url = id ? `/api/order-items/${id}/update` : `/api/orders/${orderId}/items`;

    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });

      const data = await res.json();
      if (res.ok && data.status === 'success') {
        showToast(data.message || 'Saved successfully!');
        closeOrderItemFormModal();
        // Refresh items table for this order
        openOrderItemsModal(orderId);
      } else {
        showToast(data.message || 'Error occurred while saving item.', true);
      }
    } catch (err) {
      showToast('Network error, please check connection.', true);
    } finally {
      btn.disabled = false;
      submitText.textContent = originalText;
    }
  }

  async function deleteOrderItem(itemId, anchorText) {
    if (!confirm(`Are you sure you want to remove the guest post "${anchorText}" from this order?`)) {
      return;
    }

    try {
      const res = await fetch(`/api/order-items/${itemId}/delete`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
      });
      const data = await res.json();
      if (res.ok && data.status === 'success') {
        showToast('Guest post removed from order.');
        openOrderItemsModal(currentOrderId);
      } else {
        showToast(data.message || 'Failed to delete guest post.', true);
      }
    } catch (err) {
      showToast('Network connection failed.', true);
    }
  }

  function escapeHtml(text) {
    if (!text) return '';
    return text
      .toString()
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }
</script>
@endsection
