/**
 * FINAPT Professional Custom Calendar / Datepicker Component
 * Built on Flatpickr with Luxury SaaS Styling & Shortcuts for GuestPost Pro
 */
(function() {
  'use strict';

  const CustomDatePicker = {
    instances: new WeakMap(),

    init(el, options = {}) {
      if (!el || !(el instanceof HTMLElement)) return null;
      if (this.instances.has(el) || el._flatpickr) return el._flatpickr;

      // Convert type="date" to type="text" to prevent browser native collision
      if (el.type === 'date') {
        el.type = 'text';
      }

      const mode = el.dataset.mode || options.mode || 'single';
      const enableShortcuts = el.dataset.shortcuts !== 'false' && options.shortcuts !== false;
      const displayFormat = el.dataset.displayFormat || options.displayFormat || 'M d, Y';
      const dateFormat = el.dataset.dateFormat || options.dateFormat || 'Y-m-d';
      const minDate = el.dataset.minDate || options.minDate || null;
      const maxDate = el.dataset.maxDate || options.maxDate || null;

      const fpConfig = {
        mode: mode,
        dateFormat: dateFormat,
        altInput: true,
        altFormat: displayFormat,
        altInputClass: (el.className || '') + ' flatpickr-input-custom cursor-pointer',
        allowInput: true,
        animate: true,
        minDate: minDate,
        maxDate: maxDate,
        static: false,
        onReady: (selectedDates, dateStr, instance) => {
          if (enableShortcuts) {
            this.injectShortcuts(instance, mode);
          }
        },
        onChange: (selectedDates, dateStr, instance) => {
          // Trigger native change and input events on base element
          const event = new Event('change', { bubbles: true });
          el.dispatchEvent(event);
          const inputEvent = new Event('input', { bubbles: true });
          el.dispatchEvent(inputEvent);

          if (typeof el.onchange === 'function') {
            try {
              el.onchange(event);
            } catch (err) {
              console.error(err);
            }
          }
        },
        ...options
      };

      if (typeof flatpickr !== 'undefined') {
        const fp = flatpickr(el, fpConfig);
        this.instances.set(el, fp);
        el._flatpickr = fp;

        // Re-entrancy guard to prevent infinite recursion loop
        let isSyncing = false;
        try {
          const proto = HTMLInputElement.prototype;
          const origSetter = Object.getOwnPropertyDescriptor(proto, 'value').set;
          const origGetter = Object.getOwnPropertyDescriptor(proto, 'value').get;

          Object.defineProperty(el, 'value', {
            get() {
              return origGetter.call(this);
            },
            set(newVal) {
              origSetter.call(this, newVal);
              if (this._flatpickr && !isSyncing) {
                isSyncing = true;
                try {
                  this._flatpickr.setDate(newVal || '', false);
                } catch (e) {}
                isSyncing = false;
              }
            },
            configurable: true
          });
        } catch (e) {
          // Fail silently if browser prevents property redefinition
        }

        return fp;
      } else {
        setTimeout(() => this.init(el, options), 80);
        return null;
      }
    },

    injectShortcuts(instance, mode) {
      if (!instance || !instance.calendarContainer) return;
      
      const existingBar = instance.calendarContainer.querySelector('.flatpickr-shortcuts-bar');
      if (existingBar) existingBar.remove();

      const bar = document.createElement('div');
      bar.className = 'flatpickr-shortcuts-bar';

      const createBtn = (text, onClick, isClear = false) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = `flatpickr-shortcut-btn ${isClear ? 'btn-clear' : ''}`;
        btn.textContent = text;
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          onClick();
        });
        return btn;
      };

      if (mode === 'range') {
        // Range shortcuts
        bar.appendChild(createBtn('Today', () => {
          const today = new Date();
          instance.setDate([today, today], true);
          instance.close();
        }));

        bar.appendChild(createBtn('Last 7 Days', () => {
          const end = new Date();
          const start = new Date();
          start.setDate(end.getDate() - 6);
          instance.setDate([start, end], true);
          instance.close();
        }));

        bar.appendChild(createBtn('This Month', () => {
          const now = new Date();
          const start = new Date(now.getFullYear(), now.getMonth(), 1);
          const end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
          instance.setDate([start, end], true);
          instance.close();
        }));

        bar.appendChild(createBtn('Last 30 Days', () => {
          const end = new Date();
          const start = new Date();
          start.setDate(end.getDate() - 29);
          instance.setDate([start, end], true);
          instance.close();
        }));

        bar.appendChild(createBtn('Clear', () => {
          instance.clear();
          instance.close();
        }, true));
      } else {
        // Single Date shortcuts
        bar.appendChild(createBtn('Today', () => {
          const now = new Date();
          instance.setDate(now, true);
          instance.close();
        }));

        bar.appendChild(createBtn('Tomorrow', () => {
          const tomorrow = new Date();
          tomorrow.setDate(tomorrow.getDate() + 1);
          instance.setDate(tomorrow, true);
          instance.close();
        }));

        bar.appendChild(createBtn('+3 Days', () => {
          const d = new Date();
          d.setDate(d.getDate() + 3);
          instance.setDate(d, true);
          instance.close();
        }));

        bar.appendChild(createBtn('+1 Week', () => {
          const d = new Date();
          d.setDate(d.getDate() + 7);
          instance.setDate(d, true);
          instance.close();
        }));

        bar.appendChild(createBtn('Clear', () => {
          instance.clear();
          instance.close();
        }, true));
      }

      instance.calendarContainer.appendChild(bar);
    },

    initAll(container = document) {
      const selectors = [
        'input.custom-datepicker',
        'input[data-calendar="true"]',
        'input[type="date"]'
      ];

      const elements = container.querySelectorAll(selectors.join(', '));
      elements.forEach(el => {
        if (el.dataset.calendarIgnore === 'true' || el._flatpickr) return;
        this.init(el);
      });
    }
  };

  // Expose to window
  window.CustomDatePicker = CustomDatePicker;
  window.setDatePickerValue = function(elOrId, val) {
    const el = typeof elOrId === 'string' ? document.getElementById(elOrId) : elOrId;
    if (!el) return;
    if (el._flatpickr) {
      el._flatpickr.setDate(val || '', true);
    } else {
      el.value = val || '';
    }
  };

  // Auto-init on page load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => CustomDatePicker.initAll());
  } else {
    CustomDatePicker.initAll();
  }

  // Handle modal openings or dynamic content insertions
  const observer = new MutationObserver((mutations) => {
    let shouldScan = false;
    for (const mutation of mutations) {
      if (mutation.addedNodes.length > 0) {
        shouldScan = true;
        break;
      }
    }
    if (shouldScan) {
      CustomDatePicker.initAll();
    }
  });

  if (document.body) {
    observer.observe(document.body, { childList: true, subtree: true });
  }

})();
