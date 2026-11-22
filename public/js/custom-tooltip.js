/**
 * FINAPT Professional Custom Tooltip Engine
 * Replaces ugly browser-default tooltips with sleek, dark, animated arrow tooltips.
 * Designed for GuestPost Pro.
 */
(function() {
  'use strict';

  let tooltipContainer = null;
  let tooltipBox = null;
  let tooltipArrow = null;
  let activeTarget = null;
  let showTimeout = null;
  const ARROW_OFFSET = 8;
  const VIEWPORT_PADDING = 8;

  function createTooltipDOM() {
    if (tooltipContainer) return;

    tooltipContainer = document.createElement('div');
    tooltipContainer.className = 'custom-tooltip-container';
    tooltipContainer.setAttribute('role', 'tooltip');
    tooltipContainer.setAttribute('aria-hidden', 'true');

    tooltipBox = document.createElement('div');
    tooltipBox.className = 'custom-tooltip-box';

    tooltipArrow = document.createElement('div');
    tooltipArrow.className = 'custom-tooltip-arrow';

    tooltipContainer.appendChild(tooltipBox);
    tooltipContainer.appendChild(tooltipArrow);
    document.body.appendChild(tooltipContainer);
  }

  /**
   * Preemptively convert native title attributes to data-tooltip
   * to guarantee browser default tooltip NEVER shows.
   */
  function stripNativeTitles(container = document) {
    const elementsWithTitle = container.querySelectorAll('[title]');
    elementsWithTitle.forEach(el => {
      const title = el.getAttribute('title');
      if (title && title.trim()) {
        el.setAttribute('data-tooltip', title.trim());
      }
      el.removeAttribute('title');
    });
  }

  function show(target) {
    if (!target) return;
    const content = target.getAttribute('data-tooltip');
    if (!content || !content.trim()) return;

    createTooltipDOM();
    activeTarget = target;

    // Set content (supports icons and shortcuts)
    const iconClass = target.getAttribute('data-tooltip-icon');
    const shortcutText = target.getAttribute('data-tooltip-shortcut');

    let html = '';
    if (iconClass) {
      html += `<i class="${iconClass} text-[10px] text-slate-300"></i>`;
    }
    html += `<span>${escapeHTML(content)}</span>`;
    if (shortcutText) {
      html += `<span class="custom-tooltip-shortcut">${escapeHTML(shortcutText)}</span>`;
    }

    tooltipBox.innerHTML = html;

    // Temporarily make visible to measure dimensions
    tooltipContainer.style.display = 'flex';
    tooltipContainer.style.visibility = 'hidden';
    tooltipContainer.classList.remove('tooltip-visible');

    const placement = calculatePosition(target);

    // Apply visibility and animation
    tooltipContainer.style.visibility = 'visible';
    tooltipContainer.setAttribute('data-placement', placement);
    tooltipContainer.setAttribute('aria-hidden', 'false');

    requestAnimationFrame(() => {
      tooltipContainer.classList.add('tooltip-visible');
    });
  }

  function hide() {
    clearTimeout(showTimeout);
    showTimeout = null;
    activeTarget = null;

    if (!tooltipContainer) return;

    tooltipContainer.classList.remove('tooltip-visible');
    tooltipContainer.setAttribute('aria-hidden', 'true');

    setTimeout(() => {
      if (!activeTarget && tooltipContainer) {
        tooltipContainer.style.display = 'none';
      }
    }, 150);
  }

  function calculatePosition(target) {
    const rect = target.getBoundingClientRect();
    const tipW = tooltipContainer.offsetWidth;
    const tipH = tooltipContainer.offsetHeight;
    let preferredPlacement = target.getAttribute('data-tooltip-placement') || 'top';

    // Auto-flip if colliding with viewport edges
    if (preferredPlacement === 'top' && rect.top - tipH - ARROW_OFFSET < VIEWPORT_PADDING) {
      preferredPlacement = 'bottom';
    } else if (preferredPlacement === 'bottom' && rect.bottom + tipH + ARROW_OFFSET > window.innerHeight - VIEWPORT_PADDING) {
      preferredPlacement = 'top';
    } else if (preferredPlacement === 'left' && rect.left - tipW - ARROW_OFFSET < VIEWPORT_PADDING) {
      preferredPlacement = 'right';
    } else if (preferredPlacement === 'right' && rect.right + tipW + ARROW_OFFSET > window.innerWidth - VIEWPORT_PADDING) {
      preferredPlacement = 'left';
    }

    let top = 0;
    let left = 0;

    switch (preferredPlacement) {
      case 'bottom':
        top = rect.bottom + ARROW_OFFSET;
        left = rect.left + (rect.width - tipW) / 2;
        break;
      case 'left':
        top = rect.top + (rect.height - tipH) / 2;
        left = rect.left - tipW - ARROW_OFFSET;
        break;
      case 'right':
        top = rect.top + (rect.height - tipH) / 2;
        left = rect.right + ARROW_OFFSET;
        break;
      case 'top':
      default:
        top = rect.top - tipH - ARROW_OFFSET;
        left = rect.left + (rect.width - tipW) / 2;
        break;
    }

    // Clamp horizontally to stay inside viewport
    const minLeft = VIEWPORT_PADDING;
    const maxLeft = window.innerWidth - tipW - VIEWPORT_PADDING;
    const clampedLeft = Math.max(minLeft, Math.min(left, maxLeft));

    // Clamp vertically
    const minTop = VIEWPORT_PADDING;
    const maxTop = window.innerHeight - tipH - VIEWPORT_PADDING;
    const clampedTop = Math.max(minTop, Math.min(top, maxTop));

    tooltipContainer.style.top = `${clampedTop}px`;
    tooltipContainer.style.left = `${clampedLeft}px`;

    // Adjust arrow offset if tooltip was clamped
    if (preferredPlacement === 'top' || preferredPlacement === 'bottom') {
      const targetCenter = rect.left + rect.width / 2;
      const arrowLeft = Math.max(12, Math.min(targetCenter - clampedLeft, tipW - 12));
      tooltipArrow.style.left = `${arrowLeft}px`;
      tooltipArrow.style.top = '';
    } else {
      const targetCenter = rect.top + rect.height / 2;
      const arrowTop = Math.max(10, Math.min(targetCenter - clampedTop, tipH - 10));
      tooltipArrow.style.top = `${arrowTop}px`;
      tooltipArrow.style.left = '';
    }

    return preferredPlacement;
  }

  function escapeHTML(str) {
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  // --- Global Event Listeners ---
  document.addEventListener('mouseover', (e) => {
    const target = e.target.closest('[title], [data-tooltip]');
    if (!target) return;

    // Immediately remove native title so browser default tooltip never shows
    if (target.hasAttribute('title')) {
      const title = target.getAttribute('title');
      if (title && title.trim()) {
        target.setAttribute('data-tooltip', title.trim());
      }
      target.removeAttribute('title');
    }

    clearTimeout(showTimeout);
    showTimeout = setTimeout(() => {
      show(target);
    }, 60); // Snappy 60ms delay
  }, true);

  document.addEventListener('mouseout', (e) => {
    const target = e.target.closest('[data-tooltip]');
    if (target && target === activeTarget) {
      hide();
    }
  }, true);

  // Hide instantly on click, scroll, wheel, or escape key
  document.addEventListener('click', () => hide(), true);
  window.addEventListener('scroll', () => hide(), { passive: true });
  window.addEventListener('resize', () => hide(), { passive: true });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') hide();
  });

  // Handle focus for accessibility
  document.addEventListener('focusin', (e) => {
    const target = e.target.closest('[data-tooltip]');
    if (target) show(target);
  });
  document.addEventListener('focusout', () => hide());

  // Proactive scanner on DOMContentLoaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => stripNativeTitles());
  } else {
    stripNativeTitles();
  }

  // Observe dynamically added nodes or modals
  const observer = new MutationObserver((mutations) => {
    mutations.forEach(m => {
      if (m.addedNodes.length > 0) {
        m.addedNodes.forEach(node => {
          if (node.nodeType === Node.ELEMENT_NODE) {
            stripNativeTitles(node);
          }
        });
      }
    });
  });

  if (document.body) {
    observer.observe(document.body, { childList: true, subtree: true });
  }

  // Expose to window
  window.CustomTooltip = {
    show: (el) => show(el),
    hide: () => hide(),
    scan: (container) => stripNativeTitles(container)
  };

})();
