<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-7">
  
  <!-- Welcome Greeting Banner -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2">
        <span class="text-xl">👋</span>
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Welcome back, Abdullah!</h2>
      </div>
      <p class="text-xs text-slate-500 mt-1">Here's an overview of your guest post brokerage operations today.</p>
    </div>

    <!-- Interactive Date Filter Calendar -->
    <div class="flex items-center gap-2">
      <div class="relative w-64">
        <i class="fa-regular fa-calendar absolute left-3.5 top-3 text-xs text-slate-400 z-10 pointer-events-none"></i>
        <input 
          type="text" 
          id="dashboardDateRange" 
          data-calendar="true" 
          data-mode="range" 
          data-shortcuts="true"
          placeholder="Select period..." 
          value="<?php echo e(date('M 01, Y')); ?> to <?php echo e(date('M d, Y')); ?>"
          class="custom-datepicker w-full bg-white border border-slate-200/90 shadow-sm rounded-xl pl-9 pr-8 py-2 text-xs font-bold text-slate-700 hover:border-slate-300 focus:outline-none focus:border-indigo-500 transition cursor-pointer"
        >
        <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none"></i>
      </div>
    </div>
  </div>

  <!-- High-Level Business Metric Cards (Matching Original GuestPost Pro Design) -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    
    <!-- Total Clients -->
    <a href="<?php echo e(route('clients')); ?>" class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
      <div class="flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl bg-blue-500 text-white shadow-md shadow-blue-500/20 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-users text-lg"></i>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-medium text-slate-500">Total Clients (Buyers)</p>
          <div class="flex items-baseline gap-2 mt-1">
            <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono"><?php echo e(count($clients ?? [])); ?></h3>
            <span class="inline-flex items-center text-xs font-bold text-emerald-600">
              <i class="fa-solid fa-arrow-up text-[10px] mr-0.5"></i> Active
            </span>
          </div>
        </div>
      </div>
      <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between text-xs text-indigo-600 font-bold group-hover:text-indigo-800 transition">
        <span>Go to Clients CRM</span>
        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
      </div>
    </a>

    <!-- Total Sellers / Vendors -->
    <a href="<?php echo e(route('sellers')); ?>" class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
      <div class="flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl bg-purple-500 text-white shadow-md shadow-purple-500/20 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-handshake text-lg"></i>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-medium text-slate-500">Total Sellers (Vendors)</p>
          <div class="flex items-baseline gap-2 mt-1">
            <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono"><?php echo e(count($sellers ?? [])); ?></h3>
            <span class="inline-flex items-center text-xs font-bold text-purple-600">
              <i class="fa-solid fa-arrow-up text-[10px] mr-0.5"></i> Active
            </span>
          </div>
        </div>
      </div>
      <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between text-xs text-purple-600 font-bold group-hover:text-purple-800 transition">
        <span>Go to Sellers CRM</span>
        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
      </div>
    </a>

    <!-- Total Websites (Inventory) -->
    <a href="<?php echo e(route('websites')); ?>" class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
      <div class="flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl bg-emerald-500 text-white shadow-md shadow-emerald-500/20 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-globe text-lg"></i>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-medium text-slate-500">Websites (Inventory)</p>
          <div class="flex items-baseline gap-2 mt-1">
            <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono"><?php echo e(count($websites ?? [])); ?></h3>
            <span class="inline-flex items-center text-xs font-bold text-emerald-600">
              <i class="fa-solid fa-arrow-up text-[10px] mr-0.5"></i> Active
            </span>
          </div>
        </div>
      </div>
      <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between text-xs text-emerald-600 font-bold group-hover:text-emerald-800 transition">
        <span>Go to Websites CRM</span>
        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
      </div>
    </a>

    <!-- Total Net Profit -->
    <a href="<?php echo e(route('orders')); ?>" class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
      <div class="flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl bg-amber-500 text-white shadow-md shadow-amber-500/20 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-wallet text-lg"></i>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-medium text-slate-500">Total Net Profit</p>
          <div class="flex items-baseline gap-2 mt-1">
            <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono"><?php echo e($stats['total_profit'] ?? '$0.00'); ?></h3>
            <span class="inline-flex items-center text-xs font-bold text-amber-600">
              Auto-Calculated
            </span>
          </div>
        </div>
      </div>
      <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between text-xs text-amber-600 font-bold group-hover:text-amber-800 transition">
        <span>Go to Orders CRM</span>
        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
      </div>
    </a>

  </div>

  <!-- Analytics and Quick Overview Section (Matching Reference Layout) -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Revenue Overview Financial Card -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] p-6 flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between pb-4 border-b border-slate-50">
          <div>
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
              <i class="fa-solid fa-chart-line text-indigo-600"></i>
              <span>Financial Overview & Profit Margins</span>
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">Automated calculation: Client Price - Vendor Cost = Net Profit</p>
          </div>
          <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
            Real DB Data
          </span>
        </div>

        <!-- 3 Financial Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
          <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
            <p class="text-xs text-slate-500 font-medium">Total Billed to Clients</p>
            <h4 class="text-xl font-black text-slate-800 mt-1 font-mono"><?php echo e($stats['total_revenue'] ?? '$0.00'); ?></h4>
            <span class="text-[10px] text-slate-400">Gross Buyer Payments</span>
          </div>

          <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
            <p class="text-xs text-slate-500 font-medium">Total Paid to Vendors</p>
            <h4 class="text-xl font-black text-slate-700 mt-1 font-mono"><?php echo e($stats['total_cost'] ?? '$0.00'); ?></h4>
            <span class="text-[10px] text-slate-400">Direct Cost of Goods</span>
          </div>

          <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-100">
            <p class="text-xs text-emerald-800 font-bold">Total Brokerage Profit</p>
            <h4 class="text-xl font-black text-emerald-600 mt-1 font-mono"><?php echo e($stats['total_profit'] ?? '$0.00'); ?></h4>
            <span class="text-[10px] text-emerald-700 font-semibold">Your Net Business Margin</span>
          </div>
        </div>

        <!-- Order Workflow Status Bar -->
        <div class="mt-6 p-4 rounded-xl border border-slate-100 bg-slate-50/40">
          <div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-2">
            <span>Order Pipeline</span>
            <span class="text-indigo-600 font-mono"><?php echo e(count($orders ?? [])); ?> Total Orders</span>
          </div>
          <div class="grid grid-cols-2 gap-3 text-xs">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full bg-amber-500"></span>
              <span class="text-slate-600">Pending / In Progress: <strong class="text-slate-800"><?php echo e($stats['pending_orders'] ?? 0); ?></strong></span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
              <span class="text-slate-600">Published / Live: <strong class="text-slate-800"><?php echo e($stats['published_posts'] ?? 0); ?></strong></span>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-6 pt-4 border-t border-slate-50 flex flex-wrap items-center gap-3">
        <a href="<?php echo e(route('clients')); ?>" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
          <i class="fa-solid fa-users text-[10px]"></i>
          <span>Clients (<?php echo e(count($clients ?? [])); ?>)</span>
        </a>
        <a href="<?php echo e(route('sellers')); ?>" class="px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
          <i class="fa-solid fa-handshake text-[10px]"></i>
          <span>Sellers (<?php echo e(count($sellers ?? [])); ?>)</span>
        </a>
        <a href="<?php echo e(route('websites')); ?>" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
          <i class="fa-solid fa-globe text-[10px]"></i>
          <span>Websites (<?php echo e(count($websites ?? [])); ?>)</span>
        </a>
        <a href="<?php echo e(route('orders')); ?>" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5">
          <i class="fa-solid fa-cart-shopping text-[10px]"></i>
          <span>Manage Orders (<?php echo e(count($orders ?? [])); ?>)</span>
        </a>
      </div>
    </div>

    <!-- Quick Actions & Roadmap Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] p-6 flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between pb-4 border-b border-slate-50">
          <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-bolt text-amber-500"></i>
            <span>Sequential Roadmap</span>
          </h3>
          <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">
            Step 1 to 4 Live
          </span>
        </div>

        <div class="space-y-3 mt-4">
          <!-- Step 1: Clients -->
          <a href="<?php echo e(route('clients')); ?>" class="p-3 rounded-xl border border-indigo-100 bg-indigo-50/50 hover:bg-indigo-50 flex items-center justify-between transition">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                1
              </div>
              <div>
                <h4 class="text-xs font-bold text-indigo-900">Clients (Buyers)</h4>
                <p class="text-[11px] text-indigo-600/80"><?php echo e(count($clients ?? [])); ?> Buyers Registered</p>
              </div>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-indigo-400"></i>
          </a>

          <!-- Step 2: Vendors -->
          <a href="<?php echo e(route('sellers')); ?>" class="p-3 rounded-xl border border-purple-100 bg-purple-50/50 hover:bg-purple-50 flex items-center justify-between transition">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-purple-600 text-white flex items-center justify-center text-xs font-bold">
                2
              </div>
              <div>
                <h4 class="text-xs font-bold text-purple-900">Sellers (Vendors)</h4>
                <p class="text-[11px] text-purple-600/80"><?php echo e(count($sellers ?? [])); ?> Webmasters Registered</p>
              </div>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-purple-400"></i>
          </a>

          <!-- Step 3: Websites -->
          <a href="<?php echo e(route('websites')); ?>" class="p-3 rounded-xl border border-emerald-100 bg-emerald-50/50 hover:bg-emerald-50 flex items-center justify-between transition">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">
                3
              </div>
              <div>
                <h4 class="text-xs font-bold text-emerald-900">Websites (Inventory)</h4>
                <p class="text-[11px] text-emerald-600/80"><?php echo e(count($websites ?? [])); ?> Sites Live</p>
              </div>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-emerald-400"></i>
          </a>

          <!-- Step 4: Orders -->
          <a href="<?php echo e(route('orders')); ?>" class="p-3 rounded-xl border border-amber-100 bg-amber-50/50 hover:bg-amber-50 flex items-center justify-between transition">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center text-xs font-bold">
                4
              </div>
              <div>
                <h4 class="text-xs font-bold text-amber-900">Orders & Net Profit</h4>
                <p class="text-[11px] text-amber-700"><?php echo e(count($orders ?? [])); ?> Orders • <?php echo e($stats['total_profit'] ?? '$0.00'); ?> Profit</p>
              </div>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-amber-500"></i>
          </a>
        </div>
      </div>

      <div class="pt-4 mt-4 border-t border-slate-50">
        <a href="<?php echo e(route('orders')); ?>" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 transition">
          <span>Go to Orders CRM</span>
          <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
      </div>
    </div>
        </div>
      </div>

      <div class="pt-4 mt-4 border-t border-slate-50">
        <a href="<?php echo e(route('clients')); ?>" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 transition">
          <span>Go to Clients Management</span>
          <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
      </div>
    </div>

  </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repositories\Guest Post\Modules\AdminManagement\Resources\views/dashboard/index.blade.php ENDPATH**/ ?>