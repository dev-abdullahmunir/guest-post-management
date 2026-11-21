/**
 * Professional Modern Custom Select Component for GuestPost Pro
 * Replaces native ugly <select> with searchable, beautifully styled dropdowns.
 */
class CustomSelect {
  constructor(selectElement, options = {}) {
    this.select = typeof selectElement === 'string' ? document.getElementById(selectElement) : selectElement;
    if (!this.select || this.select._customSelect) return;

    this.options = {
      searchable: this.select.dataset.searchable !== 'false',
      placeholder: this.select.dataset.placeholder || this.getPlaceholder(),
      icon: this.select.dataset.icon || '',
      ...options
    };

    this.isOpen = false;
    this.init();
    this.select._customSelect = this;
  }

  getPlaceholder() {
    const firstOpt = this.select.options[0];
    if (firstOpt && (firstOpt.value === '' || firstOpt.disabled)) {
      return firstOpt.text;
    }
    return 'Select an option...';
  }

  init() {
    // Hide native select visually but keep it accessible for forms & JS
    this.select.style.display = 'none';

    // Create custom wrapper
    this.wrapper = document.createElement('div');
    this.wrapper.className = 'custom-select-wrapper relative w-full text-xs';

    // Create trigger button
    this.trigger = document.createElement('button');
    this.trigger.type = 'button';
    this.trigger.className = 'custom-select-trigger w-full bg-slate-50 border border-slate-200/90 hover:border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-left flex items-center justify-between gap-2 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white';
    
    this.triggerContent = document.createElement('div');
    this.triggerContent.className = 'flex items-center gap-2.5 min-w-0 flex-1';

    this.triggerChevron = document.createElement('i');
    this.triggerChevron.className = 'fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200 shrink-0';

    this.trigger.appendChild(this.triggerContent);
    this.trigger.appendChild(this.triggerChevron);
    this.wrapper.appendChild(this.trigger);

    // Insert wrapper next to native select
    this.select.parentNode.insertBefore(this.wrapper, this.select.nextSibling);

    // Create floating dropdown menu (appended to body to avoid overflow clipping in modals)
    this.menu = document.createElement('div');
    this.menu.className = 'custom-select-menu fixed z-[99999] hidden bg-white rounded-2xl border border-slate-100 shadow-[0_12px_40px_-8px_rgba(0,0,0,0.18)] p-2 transition-all transform opacity-0 scale-95 duration-150';
    document.body.appendChild(this.menu);

    // Build Search Bar if searchable
    this.searchContainer = document.createElement('div');
    this.searchContainer.className = 'relative mb-1.5 px-1 pt-1';
    this.searchContainer.innerHTML = `
      <div class="relative">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-[11px] text-slate-400"></i>
        <input type="text" placeholder="Search..." class="custom-select-search w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-7 py-1.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition" />
        <button type="button" class="custom-select-clear-search absolute right-2.5 top-2 text-slate-300 hover:text-slate-500 hidden text-xs">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
    `;
    this.searchInput = this.searchContainer.querySelector('.custom-select-search');
    this.clearSearchBtn = this.searchContainer.querySelector('.custom-select-clear-search');

    if (this.options.searchable && this.select.options.length > 3) {
      this.menu.appendChild(this.searchContainer);
    }

    // Options List Container
    this.optionsList = document.createElement('div');
    this.optionsList.className = 'custom-select-options max-h-56 overflow-y-auto space-y-0.5 custom-scroll pr-1';
    this.menu.appendChild(this.optionsList);

    // Events
    this.trigger.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      this.toggle();
    });

    if (this.searchInput) {
      this.searchInput.addEventListener('input', () => this.filterOptions());
      this.clearSearchBtn.addEventListener('click', () => {
        this.searchInput.value = '';
        this.clearSearchBtn.classList.add('hidden');
        this.filterOptions();
        this.searchInput.focus();
      });
    }

    // Sync when native select changes programmatically
    this.select.addEventListener('change', () => this.syncFromSelect());

    // Close on click outside or escape
    document.addEventListener('click', (e) => {
      if (this.isOpen && !this.wrapper.contains(e.target) && !this.menu.contains(e.target)) {
        this.close();
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isOpen) {
        this.close();
      }
    });

    window.addEventListener('resize', () => {
      if (this.isOpen) this.positionMenu();
    });

    window.addEventListener('scroll', () => {
      if (this.isOpen) this.positionMenu();
    }, true);

    // Initial render
    this.renderOptions();
    this.syncFromSelect();
  }

  renderOptions() {
    this.optionsList.innerHTML = '';
    const options = Array.from(this.select.options);

    options.forEach((opt) => {
      if (opt.value === '' && opt.disabled) return; // skip disabled header

      const item = document.createElement('div');
      item.className = 'custom-select-option px-3 py-2 rounded-xl cursor-pointer transition flex items-center justify-between gap-3 text-xs text-slate-700 hover:bg-indigo-50/70 hover:text-indigo-900';
      item.dataset.value = opt.value;
      item.dataset.text = opt.text.toLowerCase();

      // Custom formatting based on data attributes
      let contentHtml = '';
      const icon = opt.dataset.icon || this.options.icon || '';
      const badge = opt.dataset.badge || '';
      const subtext = opt.dataset.subtext || '';

      contentHtml += '<div class="flex items-center gap-2.5 min-w-0 flex-1">';
      if (icon) {
        contentHtml += `<i class="${icon} text-slate-400 text-xs shrink-0"></i>`;
      } else {
        // Status indicator dot or standard dot
        const val = opt.value.toLowerCase();
        if (['active', 'completed', 'published'].includes(val)) {
          contentHtml += `<span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>`;
        } else if (['pending', 'in_progress', 'paused'].includes(val)) {
          contentHtml += `<span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>`;
        } else if (['cancelled', 'failed', 'inactive'].includes(val)) {
          contentHtml += `<span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>`;
        } else {
          contentHtml += `<span class="w-1.5 h-1.5 rounded-full bg-slate-300 shrink-0"></span>`;
        }
      }

      contentHtml += '<div class="min-w-0">';
      contentHtml += `<span class="font-medium truncate block">${escapeHtml(opt.text)}</span>`;
      if (subtext) {
        contentHtml += `<span class="text-[10px] text-slate-400 block truncate">${escapeHtml(subtext)}</span>`;
      }
      contentHtml += '</div></div>';

      if (badge) {
        contentHtml += `<span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/60 shrink-0">${escapeHtml(badge)}</span>`;
      }

      // Checkmark icon for selected
      const isSelected = this.select.value === opt.value;
      contentHtml += `<i class="fa-solid fa-check text-indigo-600 text-[11px] check-icon ${isSelected ? '' : 'hidden'} shrink-0 ml-1"></i>`;

      item.innerHTML = contentHtml;

      if (isSelected) {
        item.classList.add('bg-indigo-50/90', 'text-indigo-700', 'font-bold');
      }

      item.addEventListener('click', (e) => {
        e.stopPropagation();
        this.setValue(opt.value);
        this.close();
      });

      this.optionsList.appendChild(item);
    });

    if (this.optionsList.children.length === 0) {
      this.optionsList.innerHTML = '<div class="py-4 text-center text-slate-400 text-xs">No options available</div>';
    }
  }

  filterOptions() {
    const query = this.searchInput.value.toLowerCase().trim();
    if (this.clearSearchBtn) {
      if (query.length > 0) {
        this.clearSearchBtn.classList.remove('hidden');
      } else {
        this.clearSearchBtn.classList.add('hidden');
      }
    }

    const items = this.optionsList.querySelectorAll('.custom-select-option');
    let hasMatch = false;

    items.forEach(item => {
      const text = item.dataset.text || '';
      if (text.includes(query)) {
        item.style.display = 'flex';
        hasMatch = true;
      } else {
        item.style.display = 'none';
      }
    });

    let noResults = this.optionsList.querySelector('.no-results-msg');
    if (!hasMatch) {
      if (!noResults) {
        noResults = document.createElement('div');
        noResults.className = 'no-results-msg py-4 text-center text-slate-400 text-xs flex flex-col items-center gap-1';
        noResults.innerHTML = '<i class="fa-solid fa-magnifying-glass text-slate-300 mb-1"></i> No matching options';
        this.optionsList.appendChild(noResults);
      }
      noResults.style.display = 'flex';
    } else if (noResults) {
      noResults.style.display = 'none';
    }
  }

  positionMenu() {
    const rect = this.trigger.getBoundingClientRect();
    const menuWidth = Math.max(rect.width, 220);
    const windowHeight = window.innerHeight;
    const spaceBelow = windowHeight - rect.bottom;
    const menuHeight = 260; // expected max height

    this.menu.style.width = `${menuWidth}px`;
    this.menu.style.left = `${rect.left}px`;

    if (spaceBelow < menuHeight && rect.top > menuHeight) {
      // Open upwards if near bottom
      this.menu.style.top = 'auto';
      this.menu.style.bottom = `${windowHeight - rect.top + 6}px`;
    } else {
      // Open downwards
      this.menu.style.bottom = 'auto';
      this.menu.style.top = `${rect.bottom + 6}px`;
    }
  }

  open() {
    // Close other open custom selects
    document.querySelectorAll('.custom-select-menu').forEach(m => {
      if (m !== this.menu) {
        m.classList.add('hidden');
        m.classList.add('opacity-0', 'scale-95');
      }
    });
    document.querySelectorAll('.custom-select-trigger i.fa-chevron-down').forEach(ch => {
      if (ch !== this.triggerChevron) ch.classList.remove('rotate-180');
    });

    this.isOpen = true;
    this.renderOptions();
    this.positionMenu();

    this.menu.classList.remove('hidden');
    setTimeout(() => {
      this.menu.classList.remove('opacity-0', 'scale-95');
    }, 10);

    this.triggerChevron.classList.add('rotate-180');
    this.trigger.classList.add('ring-2', 'ring-indigo-500/20', 'border-indigo-500', 'bg-white');

    if (this.searchInput) {
      this.searchInput.value = '';
      this.filterOptions();
      setTimeout(() => this.searchInput.focus(), 50);
    }
  }

  close() {
    this.isOpen = false;
    this.menu.classList.add('opacity-0', 'scale-95');
    this.triggerChevron.classList.remove('rotate-180');
    this.trigger.classList.remove('ring-2', 'ring-indigo-500/20', 'border-indigo-500', 'bg-white');
    setTimeout(() => {
      if (!this.isOpen) this.menu.classList.add('hidden');
    }, 150);
  }

  toggle() {
    if (this.isOpen) {
      this.close();
    } else {
      this.open();
    }
  }

  setValue(value) {
    this.select.value = value;
    // Dispatch native change event so all existing onchange / event listeners fire!
    this.select.dispatchEvent(new Event('change', { bubbles: true }));
    this.syncFromSelect();
  }

  syncFromSelect() {
    const selectedOpt = this.select.options[this.select.selectedIndex];
    const icon = selectedOpt?.dataset?.icon || this.options.icon || '';

    if (selectedOpt && selectedOpt.value !== '') {
      let html = '';
      if (icon) {
        html += `<i class="${icon} text-slate-500 text-xs shrink-0"></i>`;
      } else {
        const val = selectedOpt.value.toLowerCase();
        if (['active', 'completed', 'published'].includes(val)) {
          html += `<span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>`;
        } else if (['pending', 'in_progress', 'paused'].includes(val)) {
          html += `<span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>`;
        } else if (['cancelled', 'failed', 'inactive'].includes(val)) {
          html += `<span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>`;
        } else {
          html += `<span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shrink-0"></span>`;
        }
      }
      html += `<span class="font-bold text-slate-800 truncate">${escapeHtml(selectedOpt.text)}</span>`;
      this.triggerContent.innerHTML = html;
    } else {
      let placeholderHtml = '';
      if (icon) {
        placeholderHtml += `<i class="${icon} text-slate-400 text-xs shrink-0"></i>`;
      }
      placeholderHtml += `<span class="text-slate-400 font-medium truncate">${escapeHtml(this.options.placeholder)}</span>`;
      this.triggerContent.innerHTML = placeholderHtml;
    }

    // Update active highlight in menu
    const items = this.optionsList.querySelectorAll('.custom-select-option');
    items.forEach(item => {
      const isSel = item.dataset.value === this.select.value;
      const check = item.querySelector('.check-icon');
      if (isSel) {
        item.classList.add('bg-indigo-50/90', 'text-indigo-700', 'font-bold');
        if (check) check.classList.remove('hidden');
      } else {
        item.classList.remove('bg-indigo-50/90', 'text-indigo-700', 'font-bold');
        if (check) check.classList.add('hidden');
      }
    });
  }
}

function escapeHtml(text) {
  if (!text) return '';
  return text.toString()
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

// Global Initialization helper
window.initCustomSelects = function(scope = document) {
  const selects = scope.querySelectorAll('select.custom-select');
  selects.forEach(select => {
    if (!select._customSelect) {
      new CustomSelect(select);
    } else {
      select._customSelect.syncFromSelect();
    }
  });
};

window.refreshCustomSelect = function(selectIdOrElem) {
  const el = typeof selectIdOrElem === 'string' ? document.getElementById(selectIdOrElem) : selectIdOrElem;
  if (el && el._customSelect) {
    el._customSelect.syncFromSelect();
  }
};

window.setCustomSelectValue = function(selectIdOrElem, value) {
  const el = typeof selectIdOrElem === 'string' ? document.getElementById(selectIdOrElem) : selectIdOrElem;
  if (el) {
    el.value = value;
    if (el._customSelect) {
      el._customSelect.syncFromSelect();
    }
    el.dispatchEvent(new Event('change', { bubbles: true }));
  }
};

// Auto-run on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  window.initCustomSelects();
});
