<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Guest Post OS') - Laravel Management System</title>
  
  <!-- Google Fonts: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Laravel Asset Helper stylesheet -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

  <!-- Left Sidebar Navigation -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <div class="logo-icon">GP</div>
      <div class="logo-text">GuestPost OS</div>
    </div>

    <nav class="sidebar-nav">
      <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="nav-icon">📊</span>
        <span class="nav-text">Dashboard</span>
      </a>
      <a href="{{ route('inventory') }}" class="nav-item {{ request()->routeIs('inventory') ? 'active' : '' }}">
        <span class="nav-icon">🌐</span>
        <span class="nav-text">Site Inventory</span>
      </a>
      <a href="{{ route('orders') }}" class="nav-item {{ request()->routeIs('orders') ? 'active' : '' }}">
        <span class="nav-icon">🚀</span>
        <span class="nav-text">Orders & Live Links</span>
      </a>
      <a href="{{ route('contacts') }}" class="nav-item {{ request()->routeIs('contacts') ? 'active' : '' }}">
        <span class="nav-icon">📇</span>
        <span class="nav-text">Vendor & Client CRM</span>
      </a>
      <a href="{{ route('login') }}" class="nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
        <span class="nav-icon">🔐</span>
        <span class="nav-text">Auth & Roles</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <button class="theme-toggle-btn" id="themeToggleBtn">
        <span>☀️ Light Mode</span>
      </button>
    </div>
  </aside>

  <!-- Main Content Wrapper -->
  <div class="main-wrapper">
    <!-- Topbar Header -->
    <header class="topbar">
      <h1 class="page-title" id="pageTitleText">@yield('title', 'Dashboard & Profit Analytics')</h1>
      
      <div class="topbar-actions">
        <a href="{{ route('login') }}" class="user-profile" style="text-decoration: none; color: inherit;">
          <div class="avatar">AO</div>
          <div class="user-info">
            <span class="user-name">Agency Owner</span>
            <span class="user-role">Admin Access</span>
          </div>
        </a>
      </div>
    </header>

    <!-- Laravel Dynamic Blade Content -->
    <main class="content-area">
      @yield('content')
    </main>
  </div>

  <!-- ================= GLOBAL LARAVEL MODALS ================= -->

  <!-- Add New Site Modal -->
  <div class="modal-backdrop" id="modalAddSite">
    <div class="modal-card">
      <div class="modal-header">
        <h3 class="modal-title">➕ Add New Guest Post Site</h3>
        <button class="close-btn" onclick="closeModal('modalAddSite')">&times;</button>
      </div>
      <form onsubmit="submitNewSiteForm(event)">
        @csrf
        <div class="form-group">
          <label class="form-label">Website Domain</label>
          <input type="text" id="newSiteDomain" class="form-control" placeholder="e.g. Forbes.com" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Niche Category</label>
            <select id="newSiteNiche" class="form-control" required>
              <option value="Tech & Software">Tech & Software</option>
              <option value="Finance & Crypto">Finance & Crypto</option>
              <option value="Health & Fitness">Health & Fitness</option>
              <option value="Real Estate">Real Estate</option>
              <option value="Fashion & Lifestyle">Fashion & Lifestyle</option>
              <option value="Marketing & SEO">Marketing & SEO</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Turnaround Time (TAT)</label>
            <input type="text" id="newSiteTAT" class="form-control" placeholder="e.g. 2-4 Days" value="2-4 Days">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Domain Authority (DA)</label>
            <input type="number" id="newSiteDA" class="form-control" value="65" min="1" max="100">
          </div>
          <div class="form-group">
            <label class="form-label">Domain Rating (DR)</label>
            <input type="number" id="newSiteDR" class="form-control" value="62" min="1" max="100">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Monthly Traffic</label>
            <input type="text" id="newSiteTraffic" class="form-control" value="150K" placeholder="e.g. 150K">
          </div>
          <div class="form-group">
            <label class="form-label">Vendor / Provider Name</label>
            <input type="text" id="newSiteVendorName" class="form-control" placeholder="Vendor contact or agency name">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Vendor Price ($)</label>
            <input type="number" id="newSiteVendorCost" class="form-control" value="100" min="0" required>
          </div>
          <div class="form-group">
            <label class="form-label">Client Selling Price ($)</label>
            <input type="number" id="newSiteSellingPrice" class="form-control" value="220" min="0" required>
          </div>
        </div>

        <div class="profit-calculator-preview">
          <span class="calc-label">Calculated Net Profit per Post:</span>
          <span class="calc-val" id="newSiteProfitDisplay">+$120 (54.5% Margin)</span>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
          <button type="button" class="btn btn-secondary" onclick="closeModal('modalAddSite')">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Site to Inventory</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Create Order Modal -->
  <div class="modal-backdrop" id="modalAddOrder">
    <div class="modal-card">
      <div class="modal-header">
        <h3 class="modal-title">🛒 Create New Guest Post Order</h3>
        <button class="close-btn" onclick="closeModal('modalAddOrder')">&times;</button>
      </div>
      <form onsubmit="submitNewOrderForm(event)">
        @csrf
        <div class="form-group">
          <label class="form-label">Client Name / Company</label>
          <input type="text" id="orderClientName" class="form-control" placeholder="e.g. Acme Marketing Inc." required>
        </div>

        <div class="form-group">
          <label class="form-label">Target Website Domain</label>
          <input type="text" id="orderSiteDomain" class="form-control" readonly style="background-color: var(--bg-card);">
        </div>

        <div class="form-group">
          <label class="form-label">Target URL (Client Landing Page)</label>
          <input type="url" id="orderTargetUrl" class="form-control" placeholder="https://clientwebsite.com/blog-page" required>
        </div>

        <div class="form-group">
          <label class="form-label">Anchor Text</label>
          <input type="text" id="orderAnchorText" class="form-control" placeholder="e.g. best digital marketing tools" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Vendor Cost ($)</label>
            <input type="number" id="orderVendorCost" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label class="form-label">Selling Price ($)</label>
            <input type="number" id="orderSellingPrice" class="form-control" readonly>
          </div>
        </div>

        <div class="profit-calculator-preview">
          <span class="calc-label">Order Net Profit:</span>
          <span class="calc-val" id="orderProfitDisplay">+$0</span>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
          <button type="button" class="btn btn-secondary" onclick="closeModal('modalAddOrder')">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Guest Post Order</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Order / Live URL Modal -->
  <div class="modal-backdrop" id="modalEditOrder">
    <div class="modal-card">
      <div class="modal-header">
        <h3 class="modal-title">✏️ Update Order & Live Link URL</h3>
        <button class="close-btn" onclick="closeModal('modalEditOrder')">&times;</button>
      </div>
      <form onsubmit="submitEditOrderForm(event)">
        @csrf
        <input type="hidden" id="editOrderId">

        <div class="form-group">
          <label class="form-label">Live Published Article URL</label>
          <input type="url" id="editOrderLiveUrl" class="form-control" placeholder="https://domain.com/published-article">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Order Status</label>
            <select id="editOrderStatus" class="form-control">
              <option value="Sent to Vendor">Sent to Vendor</option>
              <option value="Content Review">Content Review</option>
              <option value="Live/Published">Live/Published</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Google Indexing Status</label>
            <select id="editOrderIndexing" class="form-control">
              <option value="Pending">Pending</option>
              <option value="Indexed">Indexed</option>
            </select>
          </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
          <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditOrder')">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Order Status</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Laravel Public Scripts -->
  <script src="{{ asset('js/data.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
