<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - GuestPost Pro</title>
  
  <!-- Plus Jakarta Sans Font & FontAwesome 6 -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="<?php echo e(asset('js/custom-select.js')); ?>"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            mono: ['"JetBrains Mono"', 'monospace'],
          },
          colors: {
            brand: {
              primary: '#4f46e5',
              dark: '#111827',
              sidebar: '#111827',
            }
          }
        }
      }
    }

    // Immediate anti-flicker check for sidebar state
    (function() {
      try {
        if (localStorage.getItem('guestpost_sidebar_collapsed') === 'true' && window.innerWidth >= 1024) {
          document.documentElement.classList.add('sidebar-is-collapsed');
        }
      } catch (e) {}
    })();
  </script>

  <style>
    body {
      background-color: #f4f6fa;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #1e293b;
    }
    .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    /* Separated Card Sidebar Styling & Smooth Animations */
    #mainSidebar {
      transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sidebar-text {
      transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      white-space: nowrap;
    }
    html.sidebar-is-collapsed #mainSidebar {
      width: 4.75rem; /* ~76px */
    }
    html.sidebar-is-collapsed .sidebar-text {
      display: none !important;
    }
    html.sidebar-is-collapsed .sidebar-section-title {
      display: none !important;
    }
    html.sidebar-is-collapsed .sidebar-link {
      justify-content: center !important;
      padding-left: 0.5rem !important;
      padding-right: 0.5rem !important;
      border-left-width: 0 !important;
    }
    html.sidebar-is-collapsed #collapseIcon {
      transform: rotate(180deg);
    }
    html.sidebar-is-collapsed .sidebar-footer-full {
      display: none !important;
    }
    html.sidebar-is-collapsed .sidebar-footer-mini {
      display: flex !important;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col antialiased bg-[#f4f6fa] text-slate-800">

  <!-- Master Floating Layout Container (Top Gap & Side Margins matching Reference Image) -->
  <div class="w-full max-w-[1720px] mx-auto px-4 sm:px-6 pt-3.5 sm:pt-4 pb-6 flex-1 flex flex-col space-y-4">

    <!-- Floating Top Header Card (with top gap and rounded-2xl corners like FINAPT) -->
    <header class="w-full bg-white rounded-2xl border border-slate-200/90 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] px-6 py-3.5 flex items-center justify-between sticky top-3.5 z-30">
      
      <!-- Left: Brand Logo & Global Search -->
      <div class="flex items-center gap-6">
        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 group" title="GuestPost Pro">
          <div class="w-9 h-9 rounded-xl bg-indigo-600 group-hover:bg-indigo-700 flex items-center justify-center text-white shadow-md shadow-indigo-600/30 transition">
            <i class="fa-solid fa-chart-line text-base"></i>
          </div>
          <div>
            <h1 class="font-black text-base tracking-tight text-slate-800 flex items-center gap-1">
              GuestPost <span class="text-indigo-600">Pro</span>
            </h1>
            <p class="text-[10px] text-slate-400 font-semibold tracking-wide">Publish • Grow • Rank</p>
          </div>
        </a>

        <!-- Global Search Input -->
        <div class="relative w-64 sm:w-80 lg:w-96 hidden md:block">
          <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-xs text-slate-400"></i>
          <input 
            type="text" 
            placeholder="Search anything... (e.g. order, client, website)" 
            class="w-full bg-slate-50 border border-slate-200/90 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
          />
        </div>
      </div>

      <!-- Right: Quick Actions, Notifications & Profile -->
      <div class="flex items-center gap-3">
        
        <!-- Quick Activity Icon -->
        <button class="w-9 h-9 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 flex items-center justify-center transition" title="Recent Activity">
          <i class="fa-regular fa-clock text-sm"></i>
        </button>

        <!-- Quick Help / Docs Icon -->
        <button class="w-9 h-9 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 flex items-center justify-center transition" title="Help & Documentation">
          <i class="fa-regular fa-circle-question text-sm"></i>
        </button>

        <!-- Notification Bell with Red Badge -->
        <button class="relative w-9 h-9 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 flex items-center justify-center transition" title="Notifications">
          <i class="fa-regular fa-bell text-sm"></i>
          <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-rose-500 text-white text-[9px] font-bold flex items-center justify-center">
            3
          </span>
        </button>

        <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>

        <!-- User Profile -->
        <div class="flex items-center gap-3 pl-1 cursor-pointer group">
          <div class="w-9 h-9 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-sm overflow-hidden ring-2 ring-slate-100">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="w-full h-full object-cover">
          </div>
          <div class="hidden sm:block text-left">
            <h4 class="text-xs font-black text-slate-800 leading-tight group-hover:text-indigo-600 transition">Abdullah Munir</h4>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Administrator</p>
          </div>
          <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition hidden sm:block"></i>
        </div>

      </div>
    </header>

    <!-- Main Body Layout: Floating Separated Sidebar + Content Area -->
    <div class="flex-1 flex gap-5 min-w-0">
      
      <!-- Floating Separated Sidebar Card (Matching Reference Image) -->
      <aside id="mainSidebar" class="w-64 bg-white rounded-2xl border border-slate-200/90 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] p-3.5 flex flex-col justify-between shrink-0 sticky top-[82px] h-[calc(100vh-6.25rem)] overflow-y-auto custom-scroll transition-all duration-300 select-none">
      <div class="space-y-5">
        
        <!-- Sidebar Card Header with Collapse Button -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 px-1.5">
          <div class="flex items-center gap-2 sidebar-text">
            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
            <span class="text-xs font-black text-slate-700 uppercase tracking-wider">Navigation</span>
          </div>
          <button 
            type="button" 
            onclick="toggleSidebar()" 
            class="w-7 h-7 rounded-lg bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 text-slate-400 border border-slate-200/60 flex items-center justify-center transition shadow-xs" 
            title="Collapse / Expand sidebar (Ctrl+B)"
          >
            <i id="collapseIcon" class="fa-solid fa-angles-left text-xs transition-transform duration-300"></i>
          </button>
        </div>

        <!-- Navigation Links -->
        <nav class="space-y-4 text-xs">
          
          <!-- Section: MAIN -->
          <div class="space-y-1">
            <div class="sidebar-section-title px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
              Main
            </div>
            
            <!-- Dashboard -->
            <a href="<?php echo e(route('dashboard')); ?>" title="Dashboard" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900 border-l-[3px] border-indigo-600 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-[3px] border-transparent font-semibold'); ?>">
              <i class="fa-solid fa-house w-4 text-center text-sm shrink-0 <?php echo e(request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-slate-400'); ?>"></i>
              <span class="sidebar-text">Dashboard</span>
            </a>
          </div>

          <!-- Section: CRM & DIRECTORY -->
          <div class="space-y-1">
            <div class="sidebar-section-title px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
              CRM & Directory
            </div>

            <!-- Clients -->
            <a href="<?php echo e(route('clients')); ?>" title="Clients (Buyers)" class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('clients') ? 'bg-slate-100 text-slate-900 border-l-[3px] border-indigo-600 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-[3px] border-transparent font-semibold'); ?>">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-users w-4 text-center text-sm shrink-0 <?php echo e(request()->routeIs('clients') ? 'text-indigo-600' : 'text-slate-400'); ?>"></i>
                <span class="sidebar-text">Clients (Buyers)</span>
              </div>
              <span class="sidebar-text inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                Active
              </span>
            </a>

            <!-- Sellers -->
            <a href="<?php echo e(route('sellers')); ?>" title="Sellers (Vendors)" class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('sellers') ? 'bg-slate-100 text-slate-900 border-l-[3px] border-indigo-600 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-[3px] border-transparent font-semibold'); ?>">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-handshake w-4 text-center text-sm shrink-0 <?php echo e(request()->routeIs('sellers') ? 'text-indigo-600' : 'text-slate-400'); ?>"></i>
                <span class="sidebar-text">Sellers (Vendors)</span>
              </div>
              <span class="sidebar-text inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-purple-50 text-purple-600 border border-purple-100">
                Active
              </span>
            </a>

            <!-- Websites -->
            <a href="<?php echo e(route('websites')); ?>" title="Websites (Inventory)" class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('websites') ? 'bg-slate-100 text-slate-900 border-l-[3px] border-indigo-600 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-[3px] border-transparent font-semibold'); ?>">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-globe w-4 text-center text-sm shrink-0 <?php echo e(request()->routeIs('websites') ? 'text-indigo-600' : 'text-slate-400'); ?>"></i>
                <span class="sidebar-text">Websites (Inventory)</span>
              </div>
              <span class="sidebar-text inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                Active
              </span>
            </a>
          </div>

          <!-- Section: OPERATIONS & FINANCE -->
          <div class="space-y-1">
            <div class="sidebar-section-title px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
              Operations & Finance
            </div>

            <!-- Orders -->
            <a href="<?php echo e(route('orders')); ?>" title="Orders" class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('orders') ? 'bg-slate-100 text-slate-900 border-l-[3px] border-indigo-600 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-[3px] border-transparent font-semibold'); ?>">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-cart-shopping w-4 text-center text-sm shrink-0 <?php echo e(request()->routeIs('orders') ? 'text-indigo-600' : 'text-slate-400'); ?>"></i>
                <span class="sidebar-text">Orders</span>
              </div>
              <span class="sidebar-text inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-600 border border-rose-100">
                Active
              </span>
            </a>

            <!-- Payments -->
            <a href="<?php echo e(route('payments')); ?>" title="Payments Ledger" class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('payments') ? 'bg-slate-100 text-slate-900 border-l-[3px] border-indigo-600 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-[3px] border-transparent font-semibold'); ?>">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-credit-card w-4 text-center text-sm shrink-0 <?php echo e(request()->routeIs('payments') ? 'text-indigo-600' : 'text-slate-400'); ?>"></i>
                <span class="sidebar-text">Payments</span>
              </div>
              <span class="sidebar-text inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                Active
              </span>
            </a>

            <!-- Expenses -->
            <a href="<?php echo e(route('expenses')); ?>" title="Agency Expenses" class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('expenses') ? 'bg-slate-100 text-slate-900 border-l-[3px] border-indigo-600 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-[3px] border-transparent font-semibold'); ?>">
              <div class="flex items-center gap-3">
                <i class="fa-solid fa-receipt w-4 text-center text-sm shrink-0 <?php echo e(request()->routeIs('expenses') ? 'text-indigo-600' : 'text-slate-400'); ?>"></i>
                <span class="sidebar-text">Expenses</span>
              </div>
              <span class="sidebar-text inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-600 border border-rose-100">
                Active
              </span>
            </a>
          </div>

        </nav>
      </div>

      <!-- System Status Footer inside Sidebar Card -->
      <div class="pt-3 border-t border-slate-100 mt-auto">
        <div class="sidebar-footer-full bg-slate-50/80 rounded-xl p-2.5 flex items-center gap-2.5 border border-slate-100">
          <span class="relative flex h-2 w-2 shrink-0">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
          </span>
          <div class="sidebar-text min-w-0">
            <p class="text-[11px] font-bold text-slate-700 leading-none">System Active</p>
            <p class="text-[9px] text-slate-400 mt-0.5 truncate">All core modules synced</p>
          </div>
        </div>
        <div class="sidebar-footer-mini hidden items-center justify-center py-2" title="System Active">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 min-w-0 space-y-6">
      <?php echo $__env->yieldContent('content'); ?>
    </main>

  </div>

</div>

<!-- Client Add/Edit Modal -->
  <div id="clientModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-200">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl w-full max-w-lg overflow-hidden transform transition-all scale-95 duration-200" id="clientModalCard">
      
      <!-- Modal Header -->
      <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-600/30">
            <i class="fa-solid fa-user-plus text-base" id="clientModalIcon"></i>
          </div>
          <div>
            <h3 class="text-base font-black text-slate-800" id="clientModalTitle">Add New Client</h3>
            <p class="text-xs text-slate-400">Save client & buyer information for guest posts.</p>
          </div>
        </div>
        <button type="button" onclick="closeClientModal()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200/70 flex items-center justify-center transition">
          <i class="fa-solid fa-xmark text-sm"></i>
        </button>
      </div>

      <!-- Modal Body Form -->
      <form id="clientForm" onsubmit="handleClientSubmit(event)" class="p-6 space-y-4">
        <input type="hidden" id="clientId" name="id" value="">

        <!-- Name & Company Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Client Name <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
              <i class="fa-solid fa-user absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="clientName" 
                name="name" 
                required 
                placeholder="e.g. Sarah Khan"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Company / Agency</label>
            <div class="relative">
              <i class="fa-solid fa-building absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="clientCompany" 
                name="company_name" 
                placeholder="e.g. SaaS Growth Media"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>
        </div>

        <!-- Email & Phone Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address</label>
            <div class="relative">
              <i class="fa-solid fa-envelope absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="email" 
                id="clientEmail" 
                name="email" 
                placeholder="e.g. sarah@growth.co"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Phone / WhatsApp</label>
            <div class="relative">
              <i class="fa-solid fa-phone absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="clientPhone" 
                name="phone" 
                placeholder="e.g. +1 415 555 2671"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>
        </div>

        <!-- Country -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Country</label>
          <div class="relative">
            <i class="fa-solid fa-earth-americas absolute left-3.5 top-3 text-xs text-slate-400"></i>
            <input 
              type="text" 
              id="clientCountry" 
              name="country" 
              placeholder="e.g. United States"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
            >
          </div>
        </div>

        <!-- Notes -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Client Notes & Requirements</label>
          <textarea 
            id="clientNotes" 
            name="notes" 
            rows="3" 
            placeholder="e.g. VIP client, requires tech & business niches with DR 50+"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition resize-none"
          ></textarea>
        </div>

        <!-- Modal Footer Actions -->
        <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
          <button 
            type="button" 
            onclick="closeClientModal()" 
            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            id="clientSubmitBtn" 
            class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/30 flex items-center gap-2 transition"
          >
            <span id="clientSubmitText">Save Client</span>
            <i class="fa-solid fa-check text-xs"></i>
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- Seller Add/Edit Modal (Step 2: Vendors / Sellers Management) -->
  <div id="sellerModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-200">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl w-full max-w-lg overflow-hidden transform transition-all scale-95 duration-200" id="sellerModalCard">
      
      <!-- Modal Header -->
      <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-purple-600 text-white flex items-center justify-center shadow-md shadow-purple-600/30">
            <i class="fa-solid fa-handshake text-base" id="sellerModalIcon"></i>
          </div>
          <div>
            <h3 class="text-base font-black text-slate-800" id="sellerModalTitle">Add New Seller</h3>
            <p class="text-xs text-slate-400">Save vendor, publisher & webmaster details.</p>
          </div>
        </div>
        <button type="button" onclick="closeSellerModal()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200/70 flex items-center justify-center transition">
          <i class="fa-solid fa-xmark text-sm"></i>
        </button>
      </div>

      <!-- Modal Body Form -->
      <form id="sellerForm" onsubmit="handleSellerSubmit(event)" class="p-6 space-y-4">
        <input type="hidden" id="sellerId" name="id" value="">

        <!-- Name & Company Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Seller Name <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
              <i class="fa-solid fa-user absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="sellerName" 
                name="name" 
                required 
                placeholder="e.g. Ahmed Hassan"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Publication / Company</label>
            <div class="relative">
              <i class="fa-solid fa-building absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="sellerCompany" 
                name="company_name" 
                placeholder="e.g. Tech Outreach Media"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>
        </div>

        <!-- Email & Phone Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address</label>
            <div class="relative">
              <i class="fa-solid fa-envelope absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="email" 
                id="sellerEmail" 
                name="email" 
                placeholder="e.g. ahmed@techoutreach.com"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Phone / WhatsApp</label>
            <div class="relative">
              <i class="fa-solid fa-phone absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="sellerPhone" 
                name="phone" 
                placeholder="e.g. +971 50 123 4567"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>
        </div>

        <!-- Country -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Country / Region</label>
          <div class="relative">
            <i class="fa-solid fa-earth-americas absolute left-3.5 top-3 text-xs text-slate-400"></i>
            <input 
              type="text" 
              id="sellerCountry" 
              name="country" 
              placeholder="e.g. United Arab Emirates"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
            >
          </div>
        </div>

        <!-- Notes -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Websites & Niches Notes</label>
          <textarea 
            id="sellerNotes" 
            name="notes" 
            rows="3" 
            placeholder="e.g. Contributor on Forbes & TechCrunch. Fast publishing in 2-3 days."
            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition resize-none"
          ></textarea>
        </div>

        <!-- Modal Footer Actions -->
        <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
          <button 
            type="button" 
            onclick="closeSellerModal()" 
            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            id="sellerSubmitBtn" 
            class="px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-md shadow-purple-600/30 flex items-center gap-2 transition"
          >
            <span id="sellerSubmitText">Save Seller</span>
            <i class="fa-solid fa-check text-xs"></i>
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- Website Add/Edit Modal (Step 3: Websites Inventory Management) -->
  <div id="websiteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-200">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl w-full max-w-xl overflow-hidden transform transition-all scale-95 duration-200" id="websiteModalCard">
      
      <!-- Modal Header -->
      <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md shadow-emerald-600/30">
            <i class="fa-solid fa-globe text-base" id="websiteModalIcon"></i>
          </div>
          <div>
            <h3 class="text-base font-black text-slate-800" id="websiteModalTitle">Add New Website</h3>
            <p class="text-xs text-slate-400">Add vendor guest post website, metrics, and pricing.</p>
          </div>
        </div>
        <button type="button" onclick="closeWebsiteModal()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200/70 flex items-center justify-center transition">
          <i class="fa-solid fa-xmark text-sm"></i>
        </button>
      </div>

      <!-- Modal Body Form -->
      <form id="websiteForm" onsubmit="handleWebsiteSubmit(event)" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto custom-scroll">
        <input type="hidden" id="websiteId" name="id" value="">

        <!-- Vendor / Seller Dropdown -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Vendor / Seller <span class="text-rose-500">*</span>
          </label>
          <select 
            id="websiteVendorId" 
            name="vendor_id" 
            required
            class="custom-select"
            data-placeholder="Select Vendor / Seller..."
            data-icon="fa-solid fa-handshake"
          >
            <option value="">Select Vendor / Seller...</option>
            <?php $__currentLoopData = \Modules\Shared\Models\Seller::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($vendor->id); ?>" data-subtext="<?php echo e($vendor->company_name ?? 'Individual Vendor'); ?>"><?php echo e($vendor->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <!-- Website Name & URL Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Website / Publication Name <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
              <i class="fa-solid fa-heading absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="websiteName" 
                name="website_name" 
                required 
                placeholder="e.g. TechRadar Outreach"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Website URL <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
              <i class="fa-solid fa-link absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="url" 
                id="websiteUrl" 
                name="website_url" 
                required 
                placeholder="https://example.com"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>
        </div>

        <!-- Niche & Status Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Niche / Category <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
              <i class="fa-solid fa-tag absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="websiteNiche" 
                name="niche" 
                required 
                placeholder="e.g. Technology, Finance, Crypto"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Inventory Status</label>
            <select 
              id="websiteStatus" 
              name="status" 
              class="custom-select"
              data-placeholder="Select Status..."
            >
              <option value="active">Active (Available for Orders)</option>
              <option value="paused">Paused (Temporarily Unavailable)</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>

        <!-- SEO Metrics: DA, DR, Traffic Grid -->
        <div class="grid grid-cols-3 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Domain Auth (DA)</label>
            <input 
              type="number" 
              id="websiteDA" 
              name="domain_authority" 
              min="0" 
              max="100" 
              placeholder="e.g. 75"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
            >
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Domain Rating (DR)</label>
            <input 
              type="number" 
              id="websiteDR" 
              name="domain_rating" 
              min="0" 
              max="100" 
              placeholder="e.g. 78"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
            >
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Monthly Traffic</label>
            <input 
              type="number" 
              id="websiteTraffic" 
              name="traffic" 
              min="0" 
              placeholder="e.g. 150000"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
            >
          </div>
        </div>

        <!-- Vendor Price & Turnaround Days Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Vendor Cost Price ($) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
              <i class="fa-solid fa-dollar-sign absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="number" 
                step="0.01" 
                id="websiteVendorPrice" 
                name="vendor_price" 
                required 
                placeholder="e.g. 75.00"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition font-mono"
              >
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Cost you pay to the seller</p>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Turnaround Time (Days)</label>
            <div class="relative">
              <i class="fa-solid fa-clock absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="number" 
                id="websiteTurnaroundDays" 
                name="turnaround_days" 
                min="1" 
                placeholder="e.g. 3"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Estimated publishing days</p>
          </div>
        </div>

        <!-- Notes -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Editorial Guidelines & Notes</label>
          <textarea 
            id="websiteNotes" 
            name="notes" 
            rows="2" 
            placeholder="e.g. Max 2 dofollow links, minimum 800 words, permanent post, fast index."
            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition resize-none"
          ></textarea>
        </div>

        <!-- Modal Footer Actions -->
        <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
          <button 
            type="button" 
            onclick="closeWebsiteModal()" 
            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            id="websiteSubmitBtn" 
            class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/30 flex items-center gap-2 transition"
          >
            <span id="websiteSubmitText">Save Website</span>
            <i class="fa-solid fa-check text-xs"></i>
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- Order Add/Edit Modal (Step 4: Orders Management) -->
  <div id="orderModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-200">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl w-full max-w-xl overflow-hidden transform transition-all scale-95 duration-200" id="orderModalCard">
      
      <!-- Modal Header -->
      <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-600/30">
            <i class="fa-solid fa-cart-shopping text-base" id="orderModalIcon"></i>
          </div>
          <div>
            <h3 class="text-base font-black text-slate-800" id="orderModalTitle">Create Guest Post Order</h3>
            <p class="text-xs text-slate-400">Track client price, vendor cost & auto profit calculation.</p>
          </div>
        </div>
        <button type="button" onclick="closeOrderModal()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200/70 flex items-center justify-center transition">
          <i class="fa-solid fa-xmark text-sm"></i>
        </button>
      </div>

      <!-- Modal Body Form -->
      <form id="orderForm" onsubmit="handleOrderSubmit(event)" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto custom-scroll">
        <input type="hidden" id="orderId" name="id" value="">

        <!-- Client (Buyer) Selection -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Client / Buyer <span class="text-rose-500">*</span>
          </label>
          <select 
            id="orderClientId" 
            name="client_id" 
            required
            class="custom-select"
            data-placeholder="Select Client (Buyer)..."
            data-icon="fa-solid fa-users"
          >
            <option value="">Select Client (Buyer)...</option>
            <?php $__currentLoopData = \Modules\Shared\Models\Client::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($c->id); ?>" data-subtext="<?php echo e($c->company_name ?? 'Direct Buyer'); ?>"><?php echo e($c->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <!-- Dates Grid: Order Date & Deadline -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Order Date <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
              <i class="fa-regular fa-calendar absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="date" 
                id="orderDate" 
                name="order_date" 
                required 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Delivery Deadline</label>
            <div class="relative">
              <i class="fa-solid fa-flag-checkered absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="date" 
                id="orderDeadline" 
                name="deadline" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>
        </div>

        <!-- Financial Calculation Grid -->
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
                  id="orderClientPrice" 
                  name="total_client_price" 
                  required 
                  onkeyup="calcModalProfit()" 
                  onchange="calcModalProfit()" 
                  placeholder="150.00"
                  class="w-full bg-white border border-slate-200 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-800 font-mono font-bold focus:outline-none focus:border-indigo-500 transition"
                >
              </div>
              <p class="text-[10px] text-slate-400 mt-0.5">Amount client pays you</p>
            </div>

            <div>
              <label class="block text-[11px] font-bold text-slate-700 mb-1">
                Vendor Cost ($) <span class="text-rose-500">*</span>
              </label>
              <div class="relative">
                <i class="fa-solid fa-dollar-sign absolute left-3 top-2.5 text-xs text-slate-400"></i>
                <input 
                  type="number" 
                  step="0.01" 
                  id="orderVendorCost" 
                  name="total_vendor_cost" 
                  required 
                  onkeyup="calcModalProfit()" 
                  onchange="calcModalProfit()" 
                  placeholder="75.00"
                  class="w-full bg-white border border-slate-200 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-800 font-mono text-slate-600 focus:outline-none focus:border-indigo-500 transition"
                >
              </div>
              <p class="text-[10px] text-slate-400 mt-0.5">Amount you pay to seller</p>
            </div>
          </div>

          <!-- Real-Time Auto Profit Display -->
          <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-600">Auto Calculated Profit:</span>
            <div class="flex items-center gap-2 font-mono">
              <span id="orderModalProfitBadge" class="text-xs font-black text-emerald-600 bg-emerald-100/80 px-2.5 py-1 rounded-lg border border-emerald-200">
                +$0.00
              </span>
            </div>
          </div>
        </div>

        <!-- Status -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Order Status</label>
          <select 
            id="orderStatus" 
            name="status" 
            class="custom-select"
            data-placeholder="Select Order Status..."
          >
            <option value="pending">Pending (Awaiting Vendor / Content)</option>
            <option value="in_progress">In Progress (Writing / Editorial Review)</option>
            <option value="published">Published (Live Link Received)</option>
            <option value="completed">Completed (Paid & Finalized)</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>

        <!-- Notes -->
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Anchor Text, Target URL & Notes</label>
          <textarea 
            id="orderNotes" 
            name="notes" 
            rows="3" 
            placeholder="e.g. Target URL: https://client.com/page, Anchor Text: 'Best SEO Agency', Target Website: Forbes or TechCrunch"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition resize-none"
          ></textarea>
        </div>

        <!-- Modal Footer Actions -->
        <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
          <button 
            type="button" 
            onclick="closeOrderModal()" 
            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            id="orderSubmitBtn" 
            class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/30 flex items-center gap-2 transition"
          >
            <span id="orderSubmitText">Save Order</span>
            <i class="fa-solid fa-check text-xs"></i>
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- Global Toast Notification -->
  <div id="toastNotification" class="fixed bottom-6 right-6 z-50 hidden transform transition-all duration-300 translate-y-4 opacity-0">
    <div class="bg-slate-900 text-white rounded-2xl px-5 py-3.5 shadow-2xl border border-slate-800 flex items-center gap-3">
      <div id="toastIcon" class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">
        <i class="fa-solid fa-check"></i>
      </div>
      <div>
        <p id="toastMessage" class="text-xs font-bold">Operation completed successfully!</p>
      </div>
    </div>
  </div>

  <!-- Client Management JavaScript Logic -->
  <script>
    function showToast(message, isError = false) {
      const toast = document.getElementById('toastNotification');
      const toastMsg = document.getElementById('toastMessage');
      const toastIcon = document.getElementById('toastIcon');

      toastMsg.textContent = message;
      if (isError) {
        toastIcon.className = "w-7 h-7 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center text-xs";
        toastIcon.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
      } else {
        toastIcon.className = "w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs";
        toastIcon.innerHTML = '<i class="fa-solid fa-check"></i>';
      }

      toast.classList.remove('hidden');
      setTimeout(() => {
        toast.classList.remove('translate-y-4', 'opacity-0');
      }, 10);

      setTimeout(() => {
        toast.classList.add('translate-y-4', 'opacity-0');
        setTimeout(() => toast.classList.add('hidden'), 300);
      }, 3500);
    }

    function openCreateClientModal() {
      document.getElementById('clientForm').reset();
      document.getElementById('clientId').value = '';
      document.getElementById('clientModalTitle').textContent = 'Add New Client';
      document.getElementById('clientSubmitText').textContent = 'Save Client';
      
      const modal = document.getElementById('clientModal');
      const card = document.getElementById('clientModalCard');
      modal.classList.remove('hidden');
      setTimeout(() => card.classList.remove('scale-95'), 10);
      document.getElementById('clientName').focus();
    }

    function openEditClientModal(client) {
      document.getElementById('clientForm').reset();
      document.getElementById('clientId').value = client.id;
      document.getElementById('clientName').value = client.name || '';
      document.getElementById('clientCompany').value = client.company_name || '';
      document.getElementById('clientEmail').value = client.email || '';
      document.getElementById('clientPhone').value = client.phone || '';
      document.getElementById('clientCountry').value = client.country || '';
      document.getElementById('clientNotes').value = client.notes || '';

      document.getElementById('clientModalTitle').textContent = 'Edit Client Details';
      document.getElementById('clientSubmitText').textContent = 'Update Client';

      const modal = document.getElementById('clientModal');
      const card = document.getElementById('clientModalCard');
      modal.classList.remove('hidden');
      setTimeout(() => card.classList.remove('scale-95'), 10);
      document.getElementById('clientName').focus();
    }

    function closeClientModal() {
      const modal = document.getElementById('clientModal');
      const card = document.getElementById('clientModalCard');
      card.classList.add('scale-95');
      setTimeout(() => modal.classList.add('hidden'), 150);
    }

    async function handleClientSubmit(e) {
      e.preventDefault();
      const submitBtn = document.getElementById('clientSubmitBtn');
      const submitText = document.getElementById('clientSubmitText');
      const originalText = submitText.textContent;

      submitBtn.disabled = true;
      submitText.textContent = 'Saving...';

      const id = document.getElementById('clientId').value;
      const payload = {
        name: document.getElementById('clientName').value,
        company_name: document.getElementById('clientCompany').value,
        email: document.getElementById('clientEmail').value,
        phone: document.getElementById('clientPhone').value,
        country: document.getElementById('clientCountry').value,
        notes: document.getElementById('clientNotes').value,
      };

      const url = id ? `/api/clients/${id}/update` : '/api/clients';

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
          closeClientModal();
          showToast(result.message);
          setTimeout(() => window.location.reload(), 700);
        } else {
          showToast(result.message || 'Error processing request.', true);
        }
      } catch (err) {
        showToast('Network error, please try again.', true);
      } finally {
        submitBtn.disabled = false;
        submitText.textContent = originalText;
      }
    }

    async function deleteClient(id, name) {
      if (!confirm(`Are you sure you want to delete client "${name}"?`)) {
        return;
      }

      try {
        const response = await fetch(`/api/clients/${id}/delete`, {
          method: 'POST',
          headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
          showToast(result.message);
          setTimeout(() => window.location.reload(), 700);
        } else {
          showToast(result.message || 'Failed to delete client.', true);
        }
      } catch (err) {
        showToast('Network error deleting client.', true);
      }
    }

    /* Seller Management JS Logic (Step 2) */
    function openCreateSellerModal() {
      document.getElementById('sellerForm').reset();
      document.getElementById('sellerId').value = '';
      document.getElementById('sellerModalTitle').textContent = 'Add New Seller';
      document.getElementById('sellerSubmitText').textContent = 'Save Seller';
      
      const modal = document.getElementById('sellerModal');
      const card = document.getElementById('sellerModalCard');
      modal.classList.remove('hidden');
      setTimeout(() => card.classList.remove('scale-95'), 10);
      document.getElementById('sellerName').focus();
    }

    function openEditSellerModal(seller) {
      document.getElementById('sellerForm').reset();
      document.getElementById('sellerId').value = seller.id;
      document.getElementById('sellerName').value = seller.name || '';
      document.getElementById('sellerCompany').value = seller.company_name || '';
      document.getElementById('sellerEmail').value = seller.email || '';
      document.getElementById('sellerPhone').value = seller.phone || '';
      document.getElementById('sellerCountry').value = seller.country || '';
      document.getElementById('sellerNotes').value = seller.notes || '';

      document.getElementById('sellerModalTitle').textContent = 'Edit Seller Details';
      document.getElementById('sellerSubmitText').textContent = 'Update Seller';

      const modal = document.getElementById('sellerModal');
      const card = document.getElementById('sellerModalCard');
      modal.classList.remove('hidden');
      setTimeout(() => card.classList.remove('scale-95'), 10);
      document.getElementById('sellerName').focus();
    }

    function closeSellerModal() {
      const modal = document.getElementById('sellerModal');
      const card = document.getElementById('sellerModalCard');
      card.classList.add('scale-95');
      setTimeout(() => modal.classList.add('hidden'), 150);
    }

    async function handleSellerSubmit(e) {
      e.preventDefault();
      const submitBtn = document.getElementById('sellerSubmitBtn');
      const submitText = document.getElementById('sellerSubmitText');
      const originalText = submitText.textContent;

      submitBtn.disabled = true;
      submitText.textContent = 'Saving...';

      const id = document.getElementById('sellerId').value;
      const payload = {
        name: document.getElementById('sellerName').value,
        company_name: document.getElementById('sellerCompany').value,
        email: document.getElementById('sellerEmail').value,
        phone: document.getElementById('sellerPhone').value,
        country: document.getElementById('sellerCountry').value,
        notes: document.getElementById('sellerNotes').value,
      };

      const url = id ? `/api/sellers/${id}/update` : '/api/sellers';

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
          closeSellerModal();
          showToast(result.message);
          setTimeout(() => window.location.reload(), 700);
        } else {
          showToast(result.message || 'Error processing request.', true);
        }
      } catch (err) {
        showToast('Network error, please try again.', true);
      } finally {
        submitBtn.disabled = false;
        submitText.textContent = originalText;
      }
    }

    async function deleteSeller(id, name) {
      if (!confirm(`Are you sure you want to delete seller "${name}"?`)) {
        return;
      }

      try {
        const response = await fetch(`/api/sellers/${id}/delete`, {
          method: 'POST',
          headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
          showToast(result.message);
          setTimeout(() => window.location.reload(), 700);
        } else {
          showToast(result.message || 'Failed to delete seller.', true);
        }
      } catch (err) {
        showToast('Network error deleting seller.', true);
      }
    }

    /* Website Inventory JS Logic (Step 3) */
    function openCreateWebsiteModal() {
      document.getElementById('websiteForm').reset();
      document.getElementById('websiteId').value = '';
      document.getElementById('websiteModalTitle').textContent = 'Add New Website';
      document.getElementById('websiteSubmitText').textContent = 'Save Website';
      window.refreshCustomSelect('websiteVendorId');
      window.refreshCustomSelect('websiteStatus');
      
      const modal = document.getElementById('websiteModal');
      const card = document.getElementById('websiteModalCard');
      modal.classList.remove('hidden');
      setTimeout(() => card.classList.remove('scale-95'), 10);
      document.getElementById('websiteName').focus();
    }

    function openEditWebsiteModal(website) {
      document.getElementById('websiteForm').reset();
      document.getElementById('websiteId').value = website.id;
      document.getElementById('websiteVendorId').value = website.vendor_id || '';
      document.getElementById('websiteName').value = website.website_name || '';
      document.getElementById('websiteUrl').value = website.website_url || '';
      document.getElementById('websiteNiche').value = website.niche || '';
      document.getElementById('websiteDA').value = website.domain_authority !== null ? website.domain_authority : '';
      document.getElementById('websiteDR').value = website.domain_rating !== null ? website.domain_rating : '';
      document.getElementById('websiteTraffic').value = website.traffic !== null ? website.traffic : '';
      document.getElementById('websiteVendorPrice').value = website.vendor_price !== null ? website.vendor_price : '';
      document.getElementById('websiteTurnaroundDays').value = website.turnaround_days !== null ? website.turnaround_days : '';
      document.getElementById('websiteStatus').value = website.status || 'active';
      document.getElementById('websiteNotes').value = website.notes || '';
      window.refreshCustomSelect('websiteVendorId');
      window.refreshCustomSelect('websiteStatus');

      document.getElementById('websiteModalTitle').textContent = 'Edit Website Details';
      document.getElementById('websiteSubmitText').textContent = 'Update Website';

      const modal = document.getElementById('websiteModal');
      const card = document.getElementById('websiteModalCard');
      modal.classList.remove('hidden');
      setTimeout(() => card.classList.remove('scale-95'), 10);
      document.getElementById('websiteName').focus();
    }

    function closeWebsiteModal() {
      const modal = document.getElementById('websiteModal');
      const card = document.getElementById('websiteModalCard');
      card.classList.add('scale-95');
      setTimeout(() => modal.classList.add('hidden'), 150);
    }

    async function handleWebsiteSubmit(e) {
      e.preventDefault();
      const submitBtn = document.getElementById('websiteSubmitBtn');
      const submitText = document.getElementById('websiteSubmitText');
      const originalText = submitText.textContent;

      submitBtn.disabled = true;
      submitText.textContent = 'Saving...';

      const id = document.getElementById('websiteId').value;
      const payload = {
        vendor_id: document.getElementById('websiteVendorId').value,
        website_name: document.getElementById('websiteName').value,
        website_url: document.getElementById('websiteUrl').value,
        niche: document.getElementById('websiteNiche').value,
        domain_authority: document.getElementById('websiteDA').value || null,
        domain_rating: document.getElementById('websiteDR').value || null,
        traffic: document.getElementById('websiteTraffic').value || null,
        vendor_price: document.getElementById('websiteVendorPrice').value,
        turnaround_days: document.getElementById('websiteTurnaroundDays').value || null,
        status: document.getElementById('websiteStatus').value,
        notes: document.getElementById('websiteNotes').value,
      };

      const url = id ? `/api/websites/${id}/update` : '/api/websites';

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
          closeWebsiteModal();
          showToast(result.message);
          setTimeout(() => window.location.reload(), 700);
        } else {
          showToast(result.message || 'Error processing request.', true);
        }
      } catch (err) {
        showToast('Network error, please try again.', true);
      } finally {
        submitBtn.disabled = false;
        submitText.textContent = originalText;
      }
    }

    async function deleteWebsite(id, name) {
      if (!confirm(`Are you sure you want to delete website "${name}"?`)) {
        return;
      }

      try {
        const response = await fetch(`/api/websites/${id}/delete`, {
          method: 'POST',
          headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
          showToast(result.message);
          setTimeout(() => window.location.reload(), 700);
        } else {
          showToast(result.message || 'Failed to delete website.', true);
        }
      } catch (err) {
        showToast('Network error deleting website.', true);
      }
    }

    /* Orders Management JS Logic (Step 4) */
    function calcModalProfit() {
      const clientPrice = parseFloat(document.getElementById('orderClientPrice').value) || 0;
      const vendorCost = parseFloat(document.getElementById('orderVendorCost').value) || 0;
      const profit = clientPrice - vendorCost;
      const badge = document.getElementById('orderModalProfitBadge');

      if (profit >= 0) {
        badge.className = 'text-xs font-black text-emerald-600 bg-emerald-100/80 px-2.5 py-1 rounded-lg border border-emerald-200';
        badge.textContent = `+$${profit.toFixed(2)}`;
      } else {
        badge.className = 'text-xs font-black text-rose-600 bg-rose-100/80 px-2.5 py-1 rounded-lg border border-rose-200';
        badge.textContent = `-$${Math.abs(profit).toFixed(2)}`;
      }
    }

    function openCreateOrderModal() {
      document.getElementById('orderForm').reset();
      document.getElementById('orderId').value = '';
      document.getElementById('orderDate').value = new Date().toISOString().split('T')[0];
      document.getElementById('orderModalTitle').textContent = 'Create Guest Post Order';
      document.getElementById('orderSubmitText').textContent = 'Save Order';
      window.refreshCustomSelect('orderClientId');
      window.refreshCustomSelect('orderStatus');
      calcModalProfit();

      const modal = document.getElementById('orderModal');
      const card = document.getElementById('orderModalCard');
      modal.classList.remove('hidden');
      setTimeout(() => card.classList.remove('scale-95'), 10);
      document.getElementById('orderClientId').focus();
    }

    function openEditOrderModal(order) {
      document.getElementById('orderForm').reset();
      document.getElementById('orderId').value = order.id;
      document.getElementById('orderClientId').value = order.client_id || '';
      document.getElementById('orderDate').value = order.order_date || '';
      document.getElementById('orderDeadline').value = order.deadline || '';
      document.getElementById('orderClientPrice').value = order.total_client_price !== null ? order.total_client_price : '';
      document.getElementById('orderVendorCost').value = order.total_vendor_cost !== null ? order.total_vendor_cost : '';
      document.getElementById('orderStatus').value = order.status || 'pending';
      document.getElementById('orderNotes').value = order.notes || '';
      window.refreshCustomSelect('orderClientId');
      window.refreshCustomSelect('orderStatus');

      document.getElementById('orderModalTitle').textContent = `Edit Order #${order.order_number || order.id}`;
      document.getElementById('orderSubmitText').textContent = 'Update Order';
      calcModalProfit();

      const modal = document.getElementById('orderModal');
      const card = document.getElementById('orderModalCard');
      modal.classList.remove('hidden');
      setTimeout(() => card.classList.remove('scale-95'), 10);
      document.getElementById('orderClientId').focus();
    }

    function closeOrderModal() {
      const modal = document.getElementById('orderModal');
      const card = document.getElementById('orderModalCard');
      card.classList.add('scale-95');
      setTimeout(() => modal.classList.add('hidden'), 150);
    }

    async function handleOrderSubmit(e) {
      e.preventDefault();
      const submitBtn = document.getElementById('orderSubmitBtn');
      const submitText = document.getElementById('orderSubmitText');
      const originalText = submitText.textContent;

      submitBtn.disabled = true;
      submitText.textContent = 'Saving...';

      const id = document.getElementById('orderId').value;
      const payload = {
        client_id: document.getElementById('orderClientId').value,
        order_date: document.getElementById('orderDate').value,
        deadline: document.getElementById('orderDeadline').value || null,
        total_client_price: document.getElementById('orderClientPrice').value,
        total_vendor_cost: document.getElementById('orderVendorCost').value,
        status: document.getElementById('orderStatus').value,
        notes: document.getElementById('orderNotes').value,
      };

      const url = id ? `/api/orders/${id}/update` : '/api/orders';

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
          closeOrderModal();
          showToast(result.message);
          setTimeout(() => window.location.reload(), 700);
        } else {
          showToast(result.message || 'Error processing request.', true);
        }
      } catch (err) {
        showToast('Network error, please try again.', true);
      } finally {
        submitBtn.disabled = false;
        submitText.textContent = originalText;
      }
    }

    async function deleteOrder(id, orderNumber) {
      if (!confirm(`Are you sure you want to delete order "${orderNumber}"?`)) {
        return;
      }

      try {
        const response = await fetch(`/api/orders/${id}/delete`, {
          method: 'POST',
          headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
          showToast(result.message);
          setTimeout(() => window.location.reload(), 700);
        } else {
          showToast(result.message || 'Failed to delete order.', true);
        }
      } catch (err) {
        showToast('Network error deleting order.', true);
      }
    }

    // Sidebar Closeable & Collapsible Animated Engine
    function toggleSidebar() {
      const html = document.documentElement;
      const isCollapsed = html.classList.toggle('sidebar-is-collapsed');
      try {
        localStorage.setItem('guestpost_sidebar_collapsed', isCollapsed ? 'true' : 'false');
      } catch (e) {}
    }

    // Keyboard shortcut for toggling sidebar: Ctrl + B / Cmd + B
    window.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b') {
        const activeTag = document.activeElement ? document.activeElement.tagName.toLowerCase() : '';
        if (activeTag !== 'input' && activeTag !== 'textarea') {
          e.preventDefault();
          toggleSidebar();
        }
      }
    });
  </script>

</body>
</html>
<?php /**PATH D:\Repositories\Guest Post\resources\views/layouts/app.blade.php ENDPATH**/ ?>