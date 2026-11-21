@extends('layouts.app')

@section('title', 'Authentication & Role Management')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

  <!-- Header -->
  <div class="text-center space-y-2">
    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 text-indigo-400 text-xl shadow-lg shadow-indigo-600/20 mb-2">
      <i class="fa-solid fa-shield-halved"></i>
    </div>
    <h2 class="text-2xl font-black text-white tracking-tight">Role-Based Access Control (RBAC)</h2>
    <p class="text-xs text-slate-400">Modular guards & permissions across Super Admin, Site Publishers and VIP Clients.</p>
  </div>

  <!-- Role Cards Grid -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    
    <!-- Admin Role -->
    <div class="glass-card rounded-2xl p-6 border border-slate-800/80 space-y-4 hover:border-purple-500/30 transition">
      <div class="flex items-center justify-between">
        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20 uppercase">
          Full Access
        </span>
        <i class="fa-solid fa-crown text-purple-400 text-sm"></i>
      </div>
      <div>
        <h3 class="text-base font-bold text-white">Super Administrator</h3>
        <p class="text-xs text-slate-400 mt-1">Full control over financials, dispute resolution, pricing margin markup & user bans.</p>
      </div>
      <div class="text-xs text-slate-300 space-y-1.5 pt-3 border-t border-slate-800/80">
        <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> View all net profits</div>
        <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Payout approvals</div>
        <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Website approvals</div>
      </div>
    </div>

    <!-- Client Role -->
    <div class="glass-card rounded-2xl p-6 border border-slate-800/80 space-y-4 hover:border-blue-500/30 transition">
      <div class="flex items-center justify-between">
        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 uppercase">
          Buyer Guard
        </span>
        <i class="fa-solid fa-cart-shopping text-blue-400 text-sm"></i>
      </div>
      <div>
        <h3 class="text-base font-bold text-white">Client / Buyer</h3>
        <p class="text-xs text-slate-400 mt-1">Order guest posts, upload content requirements, track live URLs and manage deposits.</p>
      </div>
      <div class="text-xs text-slate-300 space-y-1.5 pt-3 border-t border-slate-800/80">
        <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Filter marketplace inventory</div>
        <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Live link verification</div>
        <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Request revisions</div>
      </div>
    </div>

    <!-- Seller Role -->
    <div class="glass-card rounded-2xl p-6 border border-slate-800/80 space-y-4 hover:border-emerald-500/30 transition">
      <div class="flex items-center justify-between">
        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase">
          Vendor Guard
        </span>
        <i class="fa-solid fa-newspaper text-emerald-400 text-sm"></i>
      </div>
      <div>
        <h3 class="text-base font-bold text-white">Site Owner / Publisher</h3>
        <p class="text-xs text-slate-400 mt-1">List owned blogs, set minimum pricing, accept incoming orders and submit published links.</p>
      </div>
      <div class="text-xs text-slate-300 space-y-1.5 pt-3 border-t border-slate-800/80">
        <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Manage owned domains</div>
        <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Submit live indexed URL</div>
        <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Request payout (Wise/Wire)</div>
      </div>
    </div>

  </div>

  <!-- Quick Login Simulation Box -->
  <div class="glass-panel rounded-2xl p-6 border border-slate-800/80 text-center max-w-md mx-auto">
    <h3 class="text-sm font-bold text-white">Active Session: Super Admin</h3>
    <p class="text-xs text-slate-400 mt-1">You are currently logged in with full administrative privileges.</p>
    <div class="mt-4 flex items-center justify-center gap-3">
      <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-md shadow-indigo-600/30 transition">
        Return to Dashboard
      </a>
      <button class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
        Switch User Role
      </button>
    </div>
  </div>

</div>
@endsection
