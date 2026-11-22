@extends('layouts.app')

@section('title', 'System & Branding Settings')

@section('content')
<div class="space-y-6">

  <!-- Top Page Header Banner Card -->
  <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-7 text-white shadow-xl shadow-slate-900/10 flex flex-col md:flex-row md:items-center justify-between gap-5 border border-slate-800/80">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-2xl bg-indigo-600/90 text-white flex items-center justify-center text-xl shadow-lg shadow-indigo-600/30 shrink-0">
        <i class="fa-solid fa-sliders"></i>
      </div>
      <div>
        <div class="flex items-center gap-2">
          <h2 class="text-xl sm:text-2xl font-black tracking-tight text-white">System & White-Label Settings</h2>
          <span class="inline-flex items-center text-[10px] font-bold text-indigo-300 bg-indigo-500/20 px-2.5 py-0.5 rounded-full border border-indigo-400/20">
            Multi-Tenant / Agency
          </span>
        </div>
        <p class="text-xs text-slate-400 mt-1">Customize website name, logo, company contact info, and currency for any agency or client.</p>
      </div>
    </div>

    <!-- Quick Save Action -->
    <button 
      type="button" 
      onclick="saveAllSettings()" 
      class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-5 py-3 rounded-xl shadow-lg shadow-indigo-600/30 flex items-center gap-2 transition shrink-0 active:scale-95"
      id="saveSettingsBtnTop"
    >
      <i class="fa-solid fa-check"></i>
      <span id="saveSettingsBtnTopText">Save All Settings</span>
    </button>
  </div>

  <!-- Settings Grid Layout -->
  <form id="settingsForm" onsubmit="handleSettingsSubmit(event)" class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- Left Column: Live Header Preview Card (Sticky on Desktop) -->
    <div class="lg:col-span-4 space-y-6">
      
      <!-- Live Preview Card -->
      <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Live Header Preview</h4>
          </div>
          <span class="text-[10px] font-bold text-slate-400">Real-Time</span>
        </div>

        <!-- Simulated Header Preview -->
        <div class="p-4 rounded-2xl bg-[#f4f6fa] border border-slate-200/80 shadow-inner space-y-3">
          <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Simulated Top Bar</p>
          
          <div class="bg-white rounded-xl p-3 border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <div id="previewLogoBox" class="h-8 min-w-8 flex items-center justify-center bg-transparent overflow-hidden shrink-0">
                <i id="previewIcon" class="{{ $settings['app_icon'] ?? 'fa-solid fa-chart-line' }} text-indigo-600 text-lg"></i>
                <img id="previewLogoImg" src="{{ $settings['app_logo'] ?? '' }}" alt="Logo" class="h-7 max-w-[120px] object-contain hidden">
              </div>
              <div>
                <h5 id="previewAppName" class="font-black text-xs text-slate-800 leading-tight">
                  {{ $settings['app_name'] ?? 'GuestPost Pro' }}
                </h5>
                <p id="previewAppTagline" class="text-[9px] text-slate-400 font-semibold leading-none mt-0.5">
                  {{ $settings['app_tagline'] ?? 'Publish • Grow • Rank' }}
                </p>
              </div>
            </div>
            <div class="w-6 h-6 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] text-slate-500">
              <i class="fa-regular fa-bell"></i>
            </div>
          </div>
        </div>

        <!-- Logo Upload & File Info -->
        <div class="space-y-3 pt-2">
          <label class="block text-xs font-bold text-slate-700">Logo Image Upload / URL</label>
          
          <div class="flex items-center gap-3">
            <input 
              type="file" 
              id="logoFileInput" 
              accept="image/*" 
              onchange="handleLogoFileSelect(event)" 
              class="hidden"
            >
            <button 
              type="button" 
              onclick="document.getElementById('logoFileInput').click()" 
              class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs py-2.5 px-3 rounded-xl border border-dashed border-slate-300 flex items-center justify-center gap-2 transition"
            >
              <i class="fa-solid fa-cloud-arrow-up text-indigo-600"></i>
              <span>Upload Custom Logo</span>
            </button>
            
            <button 
              type="button" 
              onclick="clearCustomLogo()" 
              class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-rose-50 hover:text-rose-600 text-slate-400 border border-slate-200 flex items-center justify-center shrink-0 transition" 
              title="Reset to default icon"
            >
              <i class="fa-solid fa-trash-can text-xs"></i>
            </button>
          </div>

          <div class="relative">
            <i class="fa-solid fa-link absolute left-3.5 top-3 text-xs text-slate-400"></i>
            <input 
              type="text" 
              id="app_logo" 
              name="app_logo" 
              value="{{ $settings['app_logo'] ?? '' }}" 
              oninput="onLogoUrlChanged(this.value)" 
              placeholder="Or paste direct image URL (https://...)" 
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
            >
          </div>
          <p class="text-[10px] text-slate-400">Recommended size: 256x256px or horizontal logo (PNG, SVG, JPG, WebP).</p>
        </div>

        <!-- Footer Preview Box -->
        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Footer Copyright Preview</p>
          <p id="previewFooterCopyright" class="text-xs text-slate-600 font-medium">
            {{ $settings['footer_copyright'] ?? '© 2026 GuestPost Pro. All rights reserved.' }}
          </p>
        </div>
      </div>

    </div>

    <!-- Right Column: Form Settings Cards -->
    <div class="lg:col-span-8 space-y-6">

      <!-- Card 1: Branding & White-Label Details -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm space-y-5">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
          <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">
            <i class="fa-solid fa-palette"></i>
          </div>
          <div>
            <h3 class="text-sm font-black text-slate-800">Brand & Company Identity</h3>
            <p class="text-[11px] text-slate-400">Set the name, slogan, and icon for your software instance.</p>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- App / Company Name -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Software / Brand Name <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
              <i class="fa-solid fa-tag absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="app_name" 
                name="app_name" 
                required 
                value="{{ $settings['app_name'] ?? 'GuestPost Pro' }}" 
                oninput="document.getElementById('previewAppName').textContent = this.value || 'GuestPost Pro'" 
                placeholder="e.g. Finapt Outreach, GrowthHub" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <!-- App Tagline -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Tagline / Slogan
            </label>
            <div class="relative">
              <i class="fa-solid fa-quote-left absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="app_tagline" 
                name="app_tagline" 
                value="{{ $settings['app_tagline'] ?? 'Publish • Grow • Rank' }}" 
                oninput="document.getElementById('previewAppTagline').textContent = this.value || 'Publish • Grow • Rank'" 
                placeholder="e.g. SEO Management Platform" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <!-- FontAwesome Brand Icon -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Brand Icon (FontAwesome Class)
            </label>
            <div class="relative">
              <i class="fa-solid fa-icons absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="app_icon" 
                name="app_icon" 
                value="{{ $settings['app_icon'] ?? 'fa-solid fa-chart-line' }}" 
                oninput="document.getElementById('previewIcon').className = (this.value || 'fa-solid fa-chart-line') + ' text-sm'" 
                placeholder="e.g. fa-solid fa-rocket, fa-solid fa-crown" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <!-- Company Legal Name -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Agency / Organization Entity
            </label>
            <div class="relative">
              <i class="fa-solid fa-building absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="company_name" 
                name="company_name" 
                value="{{ $settings['company_name'] ?? 'GuestPost Pro Agency' }}" 
                placeholder="e.g. Acorn Media Group LLC" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2: Contact & Company Profile -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm space-y-5">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
          <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-bold">
            <i class="fa-solid fa-address-book"></i>
          </div>
          <div>
            <h3 class="text-sm font-black text-slate-800">Support & Contact Information</h3>
            <p class="text-[11px] text-slate-400">Contact details shown in invoices, order receipts, and system footers.</p>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Email -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Official Support Email</label>
            <div class="relative">
              <i class="fa-solid fa-envelope absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="email" 
                id="company_email" 
                name="company_email" 
                value="{{ $settings['company_email'] ?? 'support@guestpostpro.com' }}" 
                placeholder="support@company.com" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <!-- Phone -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Business Phone Number</label>
            <div class="relative">
              <i class="fa-solid fa-phone absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="company_phone" 
                name="company_phone" 
                value="{{ $settings['company_phone'] ?? '+1 (555) 234-5678' }}" 
                placeholder="+1 (555) 000-0000" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <!-- Address -->
          <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Company Office Address</label>
            <div class="relative">
              <i class="fa-solid fa-location-dot absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="company_address" 
                name="company_address" 
                value="{{ $settings['company_address'] ?? '100 Innovation Way, Suite 400, New York, NY 10001' }}" 
                placeholder="Full address for invoice & footer" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <!-- Copyright Notice -->
          <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Footer Copyright Text</label>
            <div class="relative">
              <i class="fa-regular fa-copyright absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="footer_copyright" 
                name="footer_copyright" 
                value="{{ $settings['footer_copyright'] ?? '© 2026 GuestPost Pro. All rights reserved.' }}" 
                oninput="document.getElementById('previewFooterCopyright').textContent = this.value || '© 2026 GuestPost Pro'" 
                placeholder="© 2026 Company Name. All rights reserved." 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3: Currency & Localization -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm space-y-5">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
          <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">
            <i class="fa-solid fa-coins"></i>
          </div>
          <div>
            <h3 class="text-sm font-black text-slate-800">Currency & Formatting</h3>
            <p class="text-[11px] text-slate-400">Configure financial display symbol, code, and date formats.</p>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Currency Symbol -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Currency Symbol</label>
            <div class="relative">
              <i class="fa-solid fa-dollar-sign absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="currency_symbol" 
                name="currency_symbol" 
                value="{{ $settings['currency_symbol'] ?? '$' }}" 
                placeholder="$, €, £, Rs" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <!-- Currency Code -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Currency Code</label>
            <div class="relative">
              <i class="fa-solid fa-money-bill-transfer absolute left-3.5 top-3 text-xs text-slate-400"></i>
              <input 
                type="text" 
                id="currency_code" 
                name="currency_code" 
                value="{{ $settings['currency_code'] ?? 'USD' }}" 
                placeholder="USD, EUR, PKR, GBP" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
              >
            </div>
          </div>

          <!-- Date Format -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Date Display Format</label>
            <select 
              id="date_format" 
              name="date_format" 
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
            >
              <option value="Y-m-d" {{ ($settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2026-11-21)</option>
              <option value="d M Y" {{ ($settings['date_format'] ?? '') === 'd M Y' ? 'selected' : '' }}>DD Mon YYYY (21 Nov 2026)</option>
              <option value="m/d/Y" {{ ($settings['date_format'] ?? '') === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (11/21/2026)</option>
              <option value="d/m/Y" {{ ($settings['date_format'] ?? '') === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (21/11/2026)</option>
            </select>
          </div>

          <!-- Timezone -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">System Timezone</label>
            <input 
              type="text" 
              id="timezone" 
              name="timezone" 
              value="{{ $settings['timezone'] ?? 'UTC' }}" 
              placeholder="e.g. UTC, Asia/Karachi, America/New_York" 
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
            >
          </div>
        </div>

        <!-- Submit Button in Card -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
          <button 
            type="submit" 
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-md shadow-indigo-600/30 flex items-center gap-2 transition active:scale-95"
            id="saveSettingsBtnBottom"
          >
            <i class="fa-solid fa-floppy-disk"></i>
            <span id="saveSettingsBtnBottomText">Save Changes</span>
          </button>
        </div>
      </div>

    </div>

  </form>

</div>

<script>
  // Initial logo preview setup on load
  document.addEventListener('DOMContentLoaded', () => {
    const logoVal = document.getElementById('app_logo').value;
    if (logoVal) {
      onLogoUrlChanged(logoVal);
    }
  });

  function onLogoUrlChanged(url) {
    const img = document.getElementById('previewLogoImg');
    const icon = document.getElementById('previewIcon');
    if (url && url.trim().length > 0) {
      img.src = url.trim();
      img.classList.remove('hidden');
      icon.classList.add('hidden');
    } else {
      img.classList.add('hidden');
      icon.classList.remove('hidden');
    }
  }

  function handleLogoFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
      alert('Please choose an image file.');
      return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
      const base64Data = e.target.result;
      document.getElementById('app_logo').value = base64Data;
      onLogoUrlChanged(base64Data);
    };
    reader.readAsDataURL(file);
  }

  function clearCustomLogo() {
    document.getElementById('app_logo').value = '';
    document.getElementById('logoFileInput').value = '';
    onLogoUrlChanged('');
  }

  function saveAllSettings() {
    document.getElementById('settingsForm').requestSubmit();
  }

  async function handleSettingsSubmit(event) {
    event.preventDefault();
    
    const btnTop = document.getElementById('saveSettingsBtnTop');
    const btnBottom = document.getElementById('saveSettingsBtnBottom');
    const textTop = document.getElementById('saveSettingsBtnTopText');
    const textBottom = document.getElementById('saveSettingsBtnBottomText');

    btnTop.disabled = true;
    btnBottom.disabled = true;
    textTop.textContent = 'Saving...';
    textBottom.textContent = 'Saving...';

    const formData = new FormData(document.getElementById('settingsForm'));
    const payload = {};
    formData.forEach((value, key) => {
      payload[key] = value;
    });

    try {
      const res = await fetch('/api/settings', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      const data = await res.json();
      if (res.ok && data.status === 'success') {
        showToast(data.message || 'Settings saved successfully!');
        // Refresh page after a brief delay so changes take effect everywhere
        setTimeout(() => window.location.reload(), 800);
      } else {
        showToast(data.message || 'Failed to save settings.', true);
      }
    } catch (err) {
      showToast('Network error while saving settings.', true);
    } finally {
      btnTop.disabled = false;
      btnBottom.disabled = false;
      textTop.textContent = 'Save All Settings';
      textBottom.textContent = 'Save Changes';
    }
  }
</script>
@endsection
