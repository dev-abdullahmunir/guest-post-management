@extends('layouts.app')

@section('title', 'Publishers / Websites')

@section('content')
<div class="space-y-6">

  <!-- Header with Page Title and Action -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Publisher Websites Directory</h2>
      <p class="text-xs text-slate-500 mt-0.5">Explore high DA/DR websites, check pricing and request guest post placement.</p>
    </div>
    <div class="flex items-center gap-2.5">
      <button class="bg-white border border-slate-200/90 shadow-sm rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-700 flex items-center gap-2 hover:bg-slate-50 transition">
        <i class="fa-solid fa-arrow-down-wide-short text-slate-400"></i>
        <span>Sort by Traffic</span>
      </button>
      <button class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/20 rounded-xl px-4 py-2 text-xs font-bold flex items-center gap-2 transition">
        <i class="fa-solid fa-plus text-[10px]"></i>
        <span>Add Publisher Website</span>
      </button>
    </div>
  </div>

  <!-- Search & Filter Bar -->
  <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
    <div>
      <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Search Website</label>
      <div class="relative">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
        <input type="text" placeholder="e.g. techcrunch.com..." class="w-full bg-slate-50 border border-slate-200/80 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition">
      </div>
    </div>

    <div>
      <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Niche / Category</label>
      <select class="custom-select" data-placeholder="All Categories" data-icon="fa-solid fa-tag">
        <option value="">All Categories</option>
        <option value="tech">Technology & SaaS</option>
        <option value="finance">Business & Finance</option>
        <option value="health">Health & Fitness</option>
        <option value="crypto">Crypto & Web3</option>
      </select>
    </div>

    <div>
      <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Domain Authority (DA)</label>
      <select class="custom-select" data-placeholder="Any DA" data-icon="fa-solid fa-ranking-star">
        <option value="">Any DA</option>
        <option value="50">DA 50+</option>
        <option value="70">DA 70+</option>
        <option value="85">DA 85+ (Tier 1 Authority)</option>
      </select>
    </div>

    <div>
      <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Link Attribute</label>
      <select class="custom-select" data-placeholder="DoFollow & Indexed" data-icon="fa-solid fa-link">
        <option value="">DoFollow & Indexed</option>
        <option value="dofollow">DoFollow Only</option>
        <option value="sponsored">Sponsored Tag</option>
      </select>
    </div>
  </div>

  <!-- Inventory Table (Dynamic Data Loop from Database) -->
  <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] p-6">
    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-xs divide-y divide-slate-100">
        <thead class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
          <tr>
            <th class="py-3.5 pr-4">Website</th>
            <th class="py-3.5 px-4">Category</th>
            <th class="py-3.5 px-4">DA / DR</th>
            <th class="py-3.5 px-4">Monthly Traffic</th>
            <th class="py-3.5 px-4">Turnaround</th>
            <th class="py-3.5 px-4">Price</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 pl-4 text-right">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50 text-slate-600">
          @forelse($websites ?? [] as $site)
            <tr class="hover:bg-slate-50/70 transition">
              <td class="py-4 pr-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-xl bg-slate-900 text-white font-black text-xs flex items-center justify-center shrink-0">
                    {{ substr($site['website_name'] ?? 'WS', 0, 2) }}
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-800">{{ $site['website_name'] ?? 'Website' }}</h4>
                    <p class="text-[11px] text-slate-400">Indexed: Yes • {{ ucfirst($site['link_type'] ?? 'dofollow') }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-4 font-medium text-slate-700">{{ $site['category'] ?? 'General' }}</td>
              <td class="py-4 px-4 font-mono font-bold">
                <span class="text-slate-800">DA {{ $site['domain_authority'] ?? 0 }}</span> 
                <span class="text-slate-300">/</span> 
                <span class="text-indigo-600">DR {{ $site['domain_rating'] ?? 0 }}</span>
              </td>
              <td class="py-4 px-4 font-semibold text-slate-800">{{ $site['organic_traffic'] ?? '10K+' }}</td>
              <td class="py-4 px-4 text-slate-500">{{ $site['turnaround_days'] ?? '3 - 5 days' }}</td>
              <td class="py-4 px-4 font-black text-slate-900 text-sm">${{ number_format($site['website_price'] ?? 0, 0) }}</td>
              <td class="py-4 px-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border bg-emerald-50 text-emerald-600 border-emerald-100">
                  Active
                </span>
              </td>
              <td class="py-4 pl-4 text-right">
                <a href="{{ route('orders') }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold transition">
                  Order Post
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="py-6 text-center text-slate-400">No websites found in directory.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection
