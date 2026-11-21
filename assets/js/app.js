// Guest Post Agency Management System - Application Controller Logic

document.addEventListener('DOMContentLoaded', () => {
  initApp();
});

function initApp() {
  setupTheme();
  setupNavigation();
  renderDashboard();
  renderInventory();
  renderOrders();
  renderContacts();
  setupModalCalculators();
}

/* Theme Handler */
function setupTheme() {
  const savedTheme = localStorage.getItem('guestPostTheme') || 'dark';
  document.documentElement.setAttribute('data-theme', savedTheme);
  updateThemeButtonText(savedTheme);

  const themeBtn = document.getElementById('themeToggleBtn');
  if (themeBtn) {
    themeBtn.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('guestPostTheme', newTheme);
      updateThemeButtonText(newTheme);
    });
  }
}

function updateThemeButtonText(theme) {
  const btn = document.getElementById('themeToggleBtn');
  if (btn) {
    btn.innerHTML = theme === 'dark' ? '<span>☀️ Light Mode</span>' : '<span>🌙 Dark Mode</span>';
  }
}

/* Navigation / Module Switcher */
function setupNavigation() {
  const navItems = document.querySelectorAll('.nav-item[data-module]');
  const modules = document.querySelectorAll('.module-view');

  navItems.forEach(item => {
    item.addEventListener('click', (e) => {
      e.preventDefault();
      const targetModule = item.getAttribute('data-module');

      navItems.forEach(nav => nav.classList.remove('active'));
      item.classList.add('active');

      modules.forEach(mod => {
        if (mod.id === `module-${targetModule}`) {
          mod.style.display = 'block';
        } else {
          mod.style.display = 'none';
        }
      });

      const pageTitle = document.getElementById('pageTitleText');
      if (pageTitle) {
        const titleMap = {
          'dashboard': 'Dashboard & Profit Analytics',
          'inventory': 'Guest Post Site & Vendor Inventory',
          'orders': 'Orders & Live Link Status Tracker',
          'contacts': 'Vendor & Client Directory (CRM)',
          'auth': 'User Authentication & Roles'
        };
        pageTitle.textContent = titleMap[targetModule] || 'Guest Post Platform';
      }
    });
  });
}

/* Module 1: Dashboard Rendering */
function renderDashboard() {
  const data = getAppData();
  
  let totalRevenue = 0;
  let totalVendorCosts = 0;
  let liveOrdersCount = 0;

  data.orders.forEach(ord => {
    totalRevenue += ord.sellingPrice;
    totalVendorCosts += ord.vendorCost;
    if (ord.status === 'Live/Published') {
      liveOrdersCount++;
    }
  });

  const netProfit = totalRevenue - totalVendorCosts;
  const profitMarginPercent = totalRevenue > 0 ? ((netProfit / totalRevenue) * 100).toFixed(1) : 0;

  document.getElementById('kpiRevenue').textContent = `$${totalRevenue.toLocaleString()}`;
  document.getElementById('kpiCosts').textContent = `$${totalVendorCosts.toLocaleString()}`;
  document.getElementById('kpiProfit').textContent = `$${netProfit.toLocaleString()}`;
  document.getElementById('kpiMargin').textContent = `${profitMarginPercent}% Margin`;
  document.getElementById('kpiSitesCount').textContent = data.sites.length;
  document.getElementById('kpiLiveOrders').textContent = `${liveOrdersCount} / ${data.orders.length}`;

  // Render Recent Activity / Orders table in Dashboard
  const recentTableBody = document.getElementById('dashboardRecentOrdersBody');
  if (recentTableBody) {
    recentTableBody.innerHTML = '';
    const recentOrders = data.orders.slice(0, 4);

    recentOrders.forEach(ord => {
      const profit = ord.sellingPrice - ord.vendorCost;
      const statusBadge = getStatusBadgeHTML(ord.status);

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><strong>${ord.id}</strong></td>
        <td>${ord.clientName}</td>
        <td><span class="site-domain">${ord.domain}</span></td>
        <td><span class="price-tag vendor-cost">$${ord.vendorCost}</span></td>
        <td><span class="price-tag selling-price">$${ord.sellingPrice}</span></td>
        <td><span class="profit-margin">+$${profit}</span></td>
        <td>${statusBadge}</td>
      `;
      recentTableBody.appendChild(tr);
    });
  }
}

/* Module 3: Inventory Rendering & Filtering */
function renderInventory() {
  const data = getAppData();
  const tbody = document.getElementById('inventoryTableBody');
  if (!tbody) return;

  tbody.innerHTML = '';

  const searchInput = document.getElementById('inventorySearch')?.value.toLowerCase() || '';
  const nicheFilter = document.getElementById('inventoryNicheFilter')?.value || 'all';

  const filteredSites = data.sites.filter(site => {
    const matchesSearch = site.domain.toLowerCase().includes(searchInput) || 
                          site.niche.toLowerCase().includes(searchInput) ||
                          site.vendorName.toLowerCase().includes(searchInput);
    const matchesNiche = nicheFilter === 'all' || site.niche === nicheFilter;
    return matchesSearch && matchesNiche;
  });

  filteredSites.forEach(site => {
    const profit = site.sellingPrice - site.vendorCost;
    const profitPercent = Math.round((profit / site.vendorCost) * 100);

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>
        <div class="site-domain">
          ${site.domain}
          <small>ID: ${site.id} | TAT: ${site.tat}</small>
        </div>
      </td>
      <td><span class="badge badge-niche">${site.niche}</span></td>
      <td>
        <div style="display: flex; gap: 0.35rem;">
          <span class="badge badge-da">DA ${site.da}</span>
          <span class="badge badge-dr">DR ${site.dr}</span>
        </div>
      </td>
      <td><span class="badge badge-traffic">📈 ${site.traffic}</span></td>
      <td><span class="price-tag vendor-cost">$${site.vendorCost}</span></td>
      <td><span class="price-tag selling-price">$${site.sellingPrice}</span></td>
      <td>
        <span class="profit-margin">+$${profit} (${profitPercent}%)</span>
      </td>
      <td><small style="color: var(--text-secondary);">${site.vendorName}</small></td>
      <td>
        <button class="btn btn-secondary btn-sm" onclick="openOrderModalForSite('${site.id}')">🛒 Order</button>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

/* Module 4: Orders & Live Links Rendering */
function renderOrders() {
  const data = getAppData();
  const tbody = document.getElementById('ordersTableBody');
  if (!tbody) return;

  tbody.innerHTML = '';

  data.orders.forEach(ord => {
    const profit = ord.sellingPrice - ord.vendorCost;
    const statusBadge = getStatusBadgeHTML(ord.status);
    const liveLinkDisplay = ord.liveUrl.startsWith('http') 
      ? `<a href="${ord.liveUrl}" target="_blank" style="color: var(--accent-secondary); text-decoration: underline; font-size: 0.85rem;">🔗 View Live Post</a>`
      : `<span style="color: var(--text-muted); font-size: 0.85rem;">${ord.liveUrl}</span>`;

    const indexingBadge = ord.indexingStatus === 'Indexed'
      ? `<span class="badge badge-traffic">✓ Google Indexed</span>`
      : `<span class="badge badge-niche">⏳ Indexing Pending</span>`;

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><strong>${ord.id}</strong><br><small style="color: var(--text-muted);">${ord.date}</small></td>
      <td><strong>${ord.clientName}</strong></td>
      <td><span class="site-domain">${ord.domain}</span></td>
      <td>
        <div style="font-size: 0.82rem; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
          <strong>Target:</strong> ${ord.targetUrl}<br>
          <strong>Anchor:</strong> "${ord.anchorText}"
        </div>
      </td>
      <td><span class="price-tag vendor-cost">$${ord.vendorCost}</span></td>
      <td><span class="price-tag selling-price">$${ord.sellingPrice}</span></td>
      <td><span class="profit-margin">+$${profit}</span></td>
      <td>${statusBadge}<br>${indexingBadge}</td>
      <td>${liveLinkDisplay}</td>
      <td>
        <button class="btn btn-secondary btn-sm" onclick="openEditOrderModal('${ord.id}')">✏️ Edit</button>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

/* Module 5: Vendor & Client Contacts CRM Rendering */
function renderContacts() {
  const data = getAppData();
  
  // Render Vendors
  const vendorTbody = document.getElementById('vendorTableBody');
  if (vendorTbody) {
    vendorTbody.innerHTML = '';
    data.vendors.forEach(v => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><strong>${v.name}</strong><br><small style="color: var(--text-muted);">${v.id}</small></td>
        <td>${v.email}</td>
        <td><span class="badge badge-niche">${v.paymentMethod}</span></td>
        <td><strong>${v.totalSites} Sites</strong></td>
        <td><span class="badge badge-dr">${v.avgTat}</span></td>
        <td><span class="price-tag selling-price">$${v.totalEarned.toLocaleString()}</span></td>
      `;
      vendorTbody.appendChild(tr);
    });
  }

  // Render Clients
  const clientTbody = document.getElementById('clientTableBody');
  if (clientTbody) {
    clientTbody.innerHTML = '';
    data.clients.forEach(c => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><strong>${c.name}</strong><br><small style="color: var(--text-muted);">${c.id}</small></td>
        <td>${c.contactPerson}</td>
        <td>${c.email}</td>
        <td><span class="badge badge-da">${c.ordersCount} Orders</span></td>
        <td><span class="price-tag selling-price">$${c.totalSpent.toLocaleString()}</span></td>
      `;
      clientTbody.appendChild(tr);
    });
  }
}

/* Modal Helper Calculators & Handlers */
function setupModalCalculators() {
  // Add Site Calculator
  const vendorCostInput = document.getElementById('newSiteVendorCost');
  const sellingPriceInput = document.getElementById('newSiteSellingPrice');
  const calcDisplay = document.getElementById('newSiteProfitDisplay');

  function calculateSiteProfit() {
    const vCost = parseFloat(vendorCostInput?.value) || 0;
    const sPrice = parseFloat(sellingPriceInput?.value) || 0;
    const profit = sPrice - vCost;
    const marginPercent = sPrice > 0 ? ((profit / sPrice) * 100).toFixed(1) : 0;

    if (calcDisplay) {
      calcDisplay.textContent = `+$${profit} (${marginPercent}% Margin)`;
    }
  }

  vendorCostInput?.addEventListener('input', calculateSiteProfit);
  sellingPriceInput?.addEventListener('input', calculateSiteProfit);
}

/* Helper Functions */
function getStatusBadgeHTML(status) {
  switch (status) {
    case 'Live/Published':
      return `<span class="badge-status badge-published">Live / Published</span>`;
    case 'Sent to Vendor':
      return `<span class="badge-status badge-processing">Sent to Vendor</span>`;
    case 'Content Review':
    case 'Pending':
    default:
      return `<span class="badge-status badge-pending">${status}</span>`;
  }
}

/* Modal Open/Close Actions */
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.add('active');
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.remove('active');
}

// Add New Site Submit Handler
function submitNewSiteForm(e) {
  e.preventDefault();
  const data = getAppData();

  const domain = document.getElementById('newSiteDomain').value.trim();
  const niche = document.getElementById('newSiteNiche').value;
  const da = parseInt(document.getElementById('newSiteDA').value) || 30;
  const dr = parseInt(document.getElementById('newSiteDR').value) || 30;
  const traffic = document.getElementById('newSiteTraffic').value || '50K';
  const tat = document.getElementById('newSiteTAT').value || '2-4 Days';
  const vendorCost = parseFloat(document.getElementById('newSiteVendorCost').value) || 50;
  const sellingPrice = parseFloat(document.getElementById('newSiteSellingPrice').value) || 120;
  const vendorName = document.getElementById('newSiteVendorName').value || 'Primary Vendor';

  const newSite = {
    id: `SITE-${100 + data.sites.length + 1}`,
    domain,
    niche,
    da,
    dr,
    traffic,
    tat,
    vendorCost,
    sellingPrice,
    vendorName,
    status: 'Active'
  };

  data.sites.unshift(newSite);
  saveAppData(data);

  closeModal('modalAddSite');
  renderInventory();
  renderDashboard();
  showToast(`Site ${domain} successfully added to inventory with +$${sellingPrice - vendorCost} profit margin!`);
}

// Add New Order Modal Handler
function openOrderModalForSite(siteId) {
  const data = getAppData();
  const site = data.sites.find(s => s.id === siteId);
  if (!site) return;

  document.getElementById('orderSiteDomain').value = site.domain;
  document.getElementById('orderVendorCost').value = site.vendorCost;
  document.getElementById('orderSellingPrice').value = site.sellingPrice;
  document.getElementById('orderProfitDisplay').textContent = `+$${site.sellingPrice - site.vendorCost}`;

  openModal('modalAddOrder');
}

function submitNewOrderForm(e) {
  e.preventDefault();
  const data = getAppData();

  const clientName = document.getElementById('orderClientName').value.trim();
  const domain = document.getElementById('orderSiteDomain').value;
  const targetUrl = document.getElementById('orderTargetUrl').value.trim();
  const anchorText = document.getElementById('orderAnchorText').value.trim();
  const vendorCost = parseFloat(document.getElementById('orderVendorCost').value) || 0;
  const sellingPrice = parseFloat(document.getElementById('orderSellingPrice').value) || 0;

  const newOrder = {
    id: `ORD-${9000 + data.orders.length + 1}`,
    clientName,
    domain,
    targetUrl,
    anchorText,
    vendorCost,
    sellingPrice,
    liveUrl: 'Sent to Vendor for Publishing',
    indexingStatus: 'Pending',
    doFollow: true,
    status: 'Sent to Vendor',
    date: new Date().toISOString().split('T')[0]
  };

  data.orders.unshift(newOrder);
  saveAppData(data);

  closeModal('modalAddOrder');
  renderOrders();
  renderDashboard();
  showToast(`New Order ${newOrder.id} created! Profit Margin +$${sellingPrice - vendorCost}`);
}

// Edit Live Link Status
function openEditOrderModal(orderId) {
  const data = getAppData();
  const ord = data.orders.find(o => o.id === orderId);
  if (!ord) return;

  document.getElementById('editOrderId').value = ord.id;
  document.getElementById('editOrderLiveUrl').value = ord.liveUrl.startsWith('http') ? ord.liveUrl : '';
  document.getElementById('editOrderStatus').value = ord.status;
  document.getElementById('editOrderIndexing').value = ord.indexingStatus;

  openModal('modalEditOrder');
}

function submitEditOrderForm(e) {
  e.preventDefault();
  const data = getAppData();
  const orderId = document.getElementById('editOrderId').value;
  const ordIndex = data.orders.findIndex(o => o.id === orderId);

  if (ordIndex !== -1) {
    const liveUrl = document.getElementById('editOrderLiveUrl').value.trim();
    const status = document.getElementById('editOrderStatus').value;
    const indexingStatus = document.getElementById('editOrderIndexing').value;

    data.orders[ordIndex].liveUrl = liveUrl || data.orders[ordIndex].liveUrl;
    data.orders[ordIndex].status = status;
    data.orders[ordIndex].indexingStatus = indexingStatus;

    saveAppData(data);
    closeModal('modalEditOrder');
    renderOrders();
    renderDashboard();
    showToast(`Order ${orderId} updated! Live URL and status saved.`);
  }
}

// Toast Notifier
function showToast(message) {
  let container = document.getElementById('toastContainer');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerHTML = `<span>✅</span> <div>${message}</div>`;
  container.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 4000);
}
