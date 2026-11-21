<?php $__env->startSection('title', 'Expenses Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

  <!-- Header Section with Actions -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
          <i class="fa-solid fa-receipt"></i>
        </div>
        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Agency Expenses & Overhead</h2>
      </div>
      <p class="text-xs text-slate-500 mt-1">Track operational costs, software licenses, copywriters, hosting, and marketing overhead.</p>
    </div>

    <div class="flex items-center gap-3">
      <!-- Search Input in Expenses Section -->
      <div class="relative w-64">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-xs text-slate-400"></i>
        <input 
          type="text" 
          id="expenseSearchInput" 
          onkeyup="filterExpensesTable()" 
          placeholder="Filter by title, category, method..." 
          class="w-full bg-white border border-slate-200/90 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500 shadow-sm transition"
        />
      </div>

      <!-- Add New Expense Button -->
      <button 
        type="button" 
        onclick="openCreateExpenseModal()" 
        class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/25 rounded-xl px-4 py-2 text-xs font-bold flex items-center gap-2 transition"
      >
        <i class="fa-solid fa-plus text-[10px]"></i>
        <span>Record Expense</span>
      </button>
    </div>
  </div>

  <?php
    $totalExpenseAmount = array_sum(array_column($expenses ?? [], 'amount'));
    $totalCount = count($expenses ?? []);
    $avgExpense = $totalCount > 0 ? $totalExpenseAmount / $totalCount : 0;

    // Calculate category breakdown
    $categoryTotals = [];
    foreach ($expenses ?? [] as $exp) {
      $cat = $exp['category'] ?? 'General';
      $categoryTotals[$cat] = ($categoryTotals[$cat] ?? 0) + (float)($exp['amount'] ?? 0);
    }
    arsort($categoryTotals);
    $topCategoryName = key($categoryTotals) ?? 'None';
    $topCategoryAmount = current($categoryTotals) ?? 0;
  ?>

  <!-- Executive Financial Expense KPI Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    
    <!-- Total Operational Expenses -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-rose-500 text-white shadow-md shadow-rose-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-receipt"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Total Agency Overhead</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">
            $<?php echo e(number_format($totalExpenseAmount, 2)); ?>

          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-100">
        Overhead
      </span>
    </div>

    <!-- Top Spending Category -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-purple-500 text-white shadow-md shadow-purple-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-chart-pie"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs font-medium text-slate-500">Top Cost Driver</p>
          <h3 class="text-base font-black text-slate-800 mt-0.5 truncate" title="<?php echo e($topCategoryName); ?>">
            <?php echo e($topCategoryName); ?>

          </h3>
          <p class="text-[11px] font-mono text-purple-600 font-bold mt-0.5">$<?php echo e(number_format($topCategoryAmount, 2)); ?></p>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-lg border border-purple-100">
        Category
      </span>
    </div>

    <!-- Total Incurred Entries -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-blue-500 text-white shadow-md shadow-blue-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Recorded Bills</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">
            <?php echo e($totalCount); ?>

          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">
        Transactions
      </span>
    </div>

    <!-- Average Expense Cost -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-amber-500 text-white shadow-md shadow-amber-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-calculator"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Average Expense</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">
            $<?php echo e(number_format($avgExpense, 2)); ?>

          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100">
        Avg / Entry
      </span>
    </div>

  </div>

  <!-- Dedicated Expenses Full Table -->
  <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-50 gap-4">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-receipt text-rose-500 text-xs"></i>
        <h3 class="text-sm font-bold text-slate-800">Operational Expenses List</h3>
      </div>

      <!-- Category Filter Tabs -->
      <div class="flex items-center gap-1.5 bg-slate-100/70 p-1 rounded-xl text-xs font-bold text-slate-600 overflow-x-auto">
        <button onclick="filterByCategory('')" class="category-tab px-3 py-1 rounded-lg bg-white shadow-sm text-slate-800 transition" data-category="">All</button>
        <button onclick="filterByCategory('Tools & Software')" class="category-tab px-3 py-1 rounded-lg hover:text-slate-800 transition" data-category="Tools & Software">Tools & Software</button>
        <button onclick="filterByCategory('Content Writing')" class="category-tab px-3 py-1 rounded-lg hover:text-slate-800 transition" data-category="Content Writing">Content Writing</button>
        <button onclick="filterByCategory('Hosting & Domains')" class="category-tab px-3 py-1 rounded-lg hover:text-slate-800 transition" data-category="Hosting & Domains">Hosting & Domains</button>
        <button onclick="filterByCategory('Banking & Fees')" class="category-tab px-3 py-1 rounded-lg hover:text-slate-800 transition" data-category="Banking & Fees">Banking Fees</button>
      </div>
    </div>

    <div class="overflow-x-auto mt-2">
      <table class="min-w-full text-left text-xs divide-y divide-slate-100" id="expensesTable">
        <thead class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
          <tr>
            <th class="py-3.5 pr-4">Date</th>
            <th class="py-3.5 px-4">Title & Details</th>
            <th class="py-3.5 px-4">Category</th>
            <th class="py-3.5 px-4">Amount</th>
            <th class="py-3.5 px-4">Payment Method</th>
            <th class="py-3.5 px-4">Description</th>
            <th class="py-3.5 pl-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50 text-slate-600" id="expensesTableBody">
          <?php $__empty_1 = true; $__currentLoopData = $expenses ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-slate-50/70 transition expense-row" 
                data-expense-category="<?php echo e($expense['category'] ?? ''); ?>"
                id="expense-row-<?php echo e($expense['id']); ?>">
              
              <!-- Expense Date -->
              <td class="py-3.5 pr-4 font-mono text-slate-600 whitespace-nowrap">
                <?php echo e(!empty($expense['expense_date']) ? date('M d, Y', strtotime($expense['expense_date'])) : '-'); ?>

              </td>

              <!-- Title -->
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-receipt text-slate-500"></i>
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-800 expense-title"><?php echo e($expense['title']); ?></h4>
                    <span class="text-[10px] text-slate-400">Recorded Expense</span>
                  </div>
                </div>
              </td>

              <!-- Category Badge -->
              <td class="py-3.5 px-4 whitespace-nowrap">
                <?php
                  $cat = $expense['category'] ?? 'General';
                  $badgeClasses = match($cat) {
                    'Tools & Software' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                    'Content Writing' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'Hosting & Domains' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'Banking & Fees' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'Marketing & Outreach' => 'bg-purple-50 text-purple-700 border-purple-200',
                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                  };
                ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border <?php echo e($badgeClasses); ?>">
                  <?php echo e($cat); ?>

                </span>
              </td>

              <!-- Amount -->
              <td class="py-3.5 px-4 font-mono whitespace-nowrap">
                <span class="font-bold text-rose-600 text-xs">
                  -$<?php echo e(number_format($expense['amount'] ?? 0, 2)); ?>

                </span>
                <span class="text-[10px] text-slate-400 ml-0.5"><?php echo e($expense['currency'] ?? 'USD'); ?></span>
              </td>

              <!-- Payment Method -->
              <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="inline-flex items-center gap-1.5 font-medium text-slate-700 expense-method">
                  <i class="fa-regular fa-credit-card text-xs text-slate-400"></i>
                  <?php echo e($expense['payment_method'] ?? 'N/A'); ?>

                </span>
              </td>

              <!-- Description -->
              <td class="py-3.5 px-4 text-slate-500 truncate max-w-[240px] expense-desc" title="<?php echo e($expense['description'] ?? ''); ?>">
                <?php echo e($expense['description'] ?? '-'); ?>

              </td>

              <!-- Actions -->
              <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                <button 
                  type="button" 
                  onclick='openEditExpenseModal(<?php echo json_encode($expense, 15, 512) ?>)' 
                  class="text-indigo-600 hover:text-indigo-800 font-bold mr-3 inline-flex items-center gap-1 transition"
                >
                  <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                </button>
                <button 
                  type="button" 
                  onclick="deleteExpense(<?php echo e($expense['id']); ?>, '<?php echo e(addslashes($expense['title'])); ?>')" 
                  class="text-rose-500 hover:text-rose-700 font-bold inline-flex items-center gap-1 transition"
                >
                  <i class="fa-regular fa-trash-can text-[11px]"></i> Delete
                </button>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr id="emptyExpenseRow">
              <td colspan="7" class="py-12 text-center text-slate-400">
                <div class="flex flex-col items-center justify-center">
                  <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl mb-3">
                    <i class="fa-solid fa-receipt"></i>
                  </div>
                  <h4 class="font-bold text-slate-700 text-sm">No expenses recorded yet</h4>
                  <p class="text-xs text-slate-400 mt-1">Keep track of subscriptions, freelancers, hosting, and agency costs.</p>
                  <button onclick="openCreateExpenseModal()" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    + Record First Expense
                  </button>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- Expense Add / Edit Modal -->
<div id="expenseModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-200">
  <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl w-full max-w-lg overflow-hidden transform transition-all scale-95 duration-200" id="expenseModalCard">
    
    <!-- Modal Header -->
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-rose-500 text-white flex items-center justify-center shadow-md shadow-rose-500/30">
          <i class="fa-solid fa-receipt text-base" id="expenseModalIcon"></i>
        </div>
        <div>
          <h3 class="text-base font-black text-slate-800" id="expenseModalTitle">Record Agency Expense</h3>
          <p class="text-xs text-slate-400">Log software tools, copywriters, or agency operational overhead.</p>
        </div>
      </div>
      <button type="button" onclick="closeExpenseModal()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200/70 flex items-center justify-center transition">
        <i class="fa-solid fa-xmark text-sm"></i>
      </button>
    </div>

    <!-- Modal Body Form -->
    <form id="expenseForm" onsubmit="handleExpenseSubmit(event)" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto custom-scroll">
      <input type="hidden" id="expenseId" name="id" value="">

      <!-- Title -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">
          Expense Title / Payee <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
          <i class="fa-solid fa-tag absolute left-3.5 top-3 text-xs text-slate-400"></i>
          <input 
            type="text" 
            id="expenseTitle" 
            name="title" 
            required 
            placeholder="e.g. Ahrefs Standard Plan, Copywriter Article"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
          >
        </div>
      </div>

      <!-- Category Selection -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">
          Expense Category <span class="text-rose-500">*</span>
        </label>
        <select 
          id="expenseCategory" 
          name="category" 
          required
          class="custom-select"
          data-placeholder="Select Expense Category..."
          data-icon="fa-solid fa-layer-group"
        >
          <option value="Tools & Software" data-subtext="SEO tools, CRM, analytics licenses">Tools & Software (SEO, CRM, Analytics)</option>
          <option value="Content Writing" data-subtext="Freelance article writers & proofreaders">Content Writing & Copywriters</option>
          <option value="Hosting & Domains" data-subtext="VPS servers, domain registrations">Hosting, Domains & Servers</option>
          <option value="Banking & Fees" data-subtext="Payment processing & wire exchange fees">Banking, Wire & Conversion Fees</option>
          <option value="Marketing & Outreach" data-subtext="Cold email software & lead gen">Marketing & Cold Email Outreach</option>
          <option value="Office & Administration" data-subtext="General workspace overhead">Office & Administration</option>
          <option value="Miscellaneous" data-subtext="Other miscellaneous expenses">Miscellaneous</option>
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
              id="expenseAmount" 
              name="amount" 
              required 
              placeholder="e.g. 199.00"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition font-mono font-bold"
            >
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Currency</label>
          <select 
            id="expenseCurrency" 
            name="currency" 
            class="custom-select"
            data-icon="fa-solid fa-coins"
          >
            <option value="USD" selected>USD ($)</option>
            <option value="EUR">EUR (€)</option>
            <option value="GBP">GBP (£)</option>
            <option value="AED">AED (Dirhams)</option>
            <option value="PKR">PKR (Rupees)</option>
          </select>
        </div>
      </div>

      <!-- Date & Payment Method Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Expense Date <span class="text-rose-500">*</span>
          </label>
          <div class="relative">
            <i class="fa-regular fa-calendar absolute left-3.5 top-3 text-xs text-slate-400"></i>
            <input 
              type="date" 
              id="expenseDate" 
              name="expense_date" 
              required 
              value="<?php echo e(date('Y-m-d')); ?>"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
            >
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">
            Payment Method <span class="text-rose-500">*</span>
          </label>
          <div class="relative">
            <i class="fa-regular fa-credit-card absolute left-3.5 top-3 text-xs text-slate-400"></i>
            <input 
              type="text" 
              id="expensePaymentMethod" 
              name="payment_method" 
              required 
              placeholder="e.g. Credit Card, PayPal, Wire"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
            >
          </div>
        </div>
      </div>

      <!-- Description -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">Description / Receipt Notes</label>
        <textarea 
          id="expenseDescription" 
          name="description" 
          rows="2" 
          placeholder="e.g. Invoice #2026-991, 5 articles for Forbes client link campaign."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition resize-none"
        ></textarea>
      </div>

      <!-- Modal Footer Actions -->
      <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
        <button 
          type="button" 
          onclick="closeExpenseModal()" 
          class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
        >
          Cancel
        </button>
        <button 
          type="submit" 
          id="expenseSubmitBtn" 
          class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-600/30 flex items-center gap-2 transition"
        >
          <span id="expenseSubmitText">Save Expense</span>
          <i class="fa-solid fa-check text-xs"></i>
        </button>
      </div>
    </form>

  </div>
</div>

<script>
  let activeCategoryFilter = '';

  function filterByCategory(category) {
    activeCategoryFilter = category;
    document.querySelectorAll('.category-tab').forEach(btn => {
      if (btn.dataset.category === category) {
        btn.className = 'category-tab px-3 py-1 rounded-lg bg-white shadow-sm text-slate-800 transition';
      } else {
        btn.className = 'category-tab px-3 py-1 rounded-lg hover:text-slate-800 transition';
      }
    });
    filterExpensesTable();
  }

  function filterExpensesTable() {
    const query = document.getElementById('expenseSearchInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.expense-row');

    rows.forEach(row => {
      const title = row.querySelector('.expense-title')?.textContent.toLowerCase() || '';
      const method = row.querySelector('.expense-method')?.textContent.toLowerCase() || '';
      const desc = row.querySelector('.expense-desc')?.textContent.toLowerCase() || '';
      const category = row.dataset.expenseCategory || '';

      const matchesQuery = title.includes(query) || method.includes(query) || desc.includes(query);
      const matchesCategory = !activeCategoryFilter || category === activeCategoryFilter;

      if (matchesQuery && matchesCategory) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  function openCreateExpenseModal() {
    document.getElementById('expenseForm').reset();
    document.getElementById('expenseId').value = '';
    document.getElementById('expenseDate').value = new Date().toISOString().substring(0, 10);
    document.getElementById('expenseModalTitle').textContent = 'Record Agency Expense';
    document.getElementById('expenseSubmitText').textContent = 'Save Expense';
    window.refreshCustomSelect('expenseCategory');
    window.refreshCustomSelect('expenseCurrency');

    const modal = document.getElementById('expenseModal');
    const card = document.getElementById('expenseModalCard');
    modal.classList.remove('hidden');
    setTimeout(() => card.classList.remove('scale-95'), 10);
  }

  function openEditExpenseModal(expense) {
    document.getElementById('expenseForm').reset();
    document.getElementById('expenseId').value = expense.id;
    document.getElementById('expenseTitle').value = expense.title || '';
    document.getElementById('expenseCategory').value = expense.category || 'Tools & Software';
    document.getElementById('expenseAmount').value = expense.amount || '';
    document.getElementById('expenseCurrency').value = expense.currency || 'USD';
    document.getElementById('expenseDate').value = expense.expense_date ? expense.expense_date.substring(0, 10) : '';
    document.getElementById('expensePaymentMethod').value = expense.payment_method || '';
    document.getElementById('expenseDescription').value = expense.description || '';
    window.refreshCustomSelect('expenseCategory');
    window.refreshCustomSelect('expenseCurrency');

    document.getElementById('expenseModalTitle').textContent = 'Edit Expense Details';
    document.getElementById('expenseSubmitText').textContent = 'Update Expense';

    const modal = document.getElementById('expenseModal');
    const card = document.getElementById('expenseModalCard');
    modal.classList.remove('hidden');
    setTimeout(() => card.classList.remove('scale-95'), 10);
  }

  function closeExpenseModal() {
    const modal = document.getElementById('expenseModal');
    const card = document.getElementById('expenseModalCard');
    card.classList.add('scale-95');
    setTimeout(() => modal.classList.add('hidden'), 150);
  }

  async function handleExpenseSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('expenseSubmitBtn');
    const submitText = document.getElementById('expenseSubmitText');
    const originalText = submitText.textContent;
    btn.disabled = true;
    submitText.textContent = 'Saving...';

    const id = document.getElementById('expenseId').value;

    const payload = {
      title: document.getElementById('expenseTitle').value.trim(),
      category: document.getElementById('expenseCategory').value,
      amount: parseFloat(document.getElementById('expenseAmount').value || 0),
      currency: document.getElementById('expenseCurrency').value,
      expense_date: document.getElementById('expenseDate').value,
      payment_method: document.getElementById('expensePaymentMethod').value.trim(),
      description: document.getElementById('expenseDescription').value.trim() || null,
    };

    const url = id ? `/api/expenses/${id}/update` : '/api/expenses';

    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });

      const data = await res.json();
      if (res.ok && data.status === 'success') {
        showToast(data.message || 'Expense saved successfully!');
        closeExpenseModal();
        setTimeout(() => window.location.reload(), 600);
      } else {
        showToast(data.message || 'Error occurred while saving expense.', true);
      }
    } catch (err) {
      showToast('Network error, please check connection.', true);
    } finally {
      btn.disabled = false;
      submitText.textContent = originalText;
    }
  }

  async function deleteExpense(id, title) {
    if (!confirm(`Are you sure you want to delete the expense record "${title}"?`)) {
      return;
    }

    try {
      const res = await fetch(`/api/expenses/${id}/delete`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
      });
      const data = await res.json();
      if (res.ok && data.status === 'success') {
        showToast('Expense record deleted successfully!');
        setTimeout(() => window.location.reload(), 600);
      } else {
        showToast(data.message || 'Failed to delete expense.', true);
      }
    } catch (err) {
      showToast('Network connection failed.', true);
    }
  }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repositories\Guest Post\resources\views/expenses/index.blade.php ENDPATH**/ ?>