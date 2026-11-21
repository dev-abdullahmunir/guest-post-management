@extends('layouts.app')

@section('title', 'Clients Management')

@section('content')
<div class="space-y-6">

  <!-- Header Section with Actions -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
          <i class="fa-solid fa-users"></i>
        </div>
        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Clients Directory (Buyers CRM)</h2>
      </div>
      <p class="text-xs text-slate-500 mt-1">Manage guest post buyers, agency clients, contact information, and requirements.</p>
    </div>

    <div class="flex items-center gap-3">
      <!-- Search Input in Clients Section -->
      <div class="relative w-64">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-xs text-slate-400"></i>
        <input 
          type="text" 
          id="clientSearchInput" 
          onkeyup="filterClientsTable()" 
          placeholder="Filter clients by name, email..." 
          class="w-full bg-white border border-slate-200/90 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500 shadow-sm transition"
        />
      </div>

      <!-- Add New Client Button -->
      <button 
        type="button" 
        onclick="openCreateClientModal()" 
        class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/25 rounded-xl px-4 py-2 text-xs font-bold flex items-center gap-2 transition"
      >
        <i class="fa-solid fa-plus text-[10px]"></i>
        <span>Add New Client</span>
      </button>
    </div>
  </div>

  <!-- Client Section KPI Summary Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
    
    <!-- Total Active Clients -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-blue-500 text-white shadow-md shadow-blue-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-users"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Total Buyers</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">{{ count($clients ?? []) }}</h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">
        Active CRM
      </span>
    </div>

    <!-- Corporate Agencies -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-purple-500 text-white shadow-md shadow-purple-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-building"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Corporate & Agencies</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">
            {{ count(array_filter($clients ?? [], fn($c) => !empty($c['company_name']))) }}
          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-lg border border-purple-100">
        Verified
      </span>
    </div>

    <!-- Global Countries -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center justify-between">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-emerald-500 text-white shadow-md shadow-emerald-500/20 flex items-center justify-center text-base">
          <i class="fa-solid fa-earth-americas"></i>
        </div>
        <div>
          <p class="text-xs font-medium text-slate-500">Countries Represented</p>
          <h3 class="text-xl font-black text-slate-800 mt-0.5 font-mono">
            {{ count(array_unique(array_filter(array_column($clients ?? [], 'country')))) ?: 'Global' }}
          </h3>
        </div>
      </div>
      <span class="inline-flex items-center text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">
        Worldwide
      </span>
    </div>

  </div>

  <!-- Dedicated Clients Full Table -->
  <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] p-6">
    <div class="flex items-center justify-between pb-4 border-b border-slate-50">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-list-check text-indigo-600 text-xs"></i>
        <h3 class="text-sm font-bold text-slate-800">All Registered Clients</h3>
      </div>
      <p class="text-xs text-slate-400 font-mono">Total: {{ count($clients ?? []) }} records</p>
    </div>

    <div class="overflow-x-auto mt-2">
      <table class="min-w-full text-left text-xs divide-y divide-slate-100" id="clientsTable">
        <thead class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
          <tr>
            <th class="py-3.5 pr-4"># ID</th>
            <th class="py-3.5 px-4">Client Name</th>
            <th class="py-3.5 px-4">Company</th>
            <th class="py-3.5 px-4">Email</th>
            <th class="py-3.5 px-4">Phone</th>
            <th class="py-3.5 px-4">Country</th>
            <th class="py-3.5 px-4">Notes</th>
            <th class="py-3.5 pl-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50 text-slate-600" id="clientsTableBody">
          @forelse($clients ?? [] as $client)
            <tr class="hover:bg-slate-50/70 transition client-row">
              <td class="py-3.5 pr-4 font-mono font-bold text-indigo-600">#CL-{{ $client['id'] ?? '0' }}</td>
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                    {{ substr($client['name'] ?? 'CL', 0, 2) }}
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-800 client-name">{{ $client['name'] ?? 'Client' }}</h4>
                  </div>
                </div>
              </td>
              <td class="py-3.5 px-4 font-medium text-slate-800 client-company">{{ $client['company_name'] ?? '-' }}</td>
              <td class="py-3.5 px-4 font-mono text-slate-600 client-email">{{ $client['email'] ?? '-' }}</td>
              <td class="py-3.5 px-4 font-mono text-slate-500">{{ $client['phone'] ?? '-' }}</td>
              <td class="py-3.5 px-4 text-slate-600 font-medium">{{ $client['country'] ?? '-' }}</td>
              <td class="py-3.5 px-4 text-slate-400 truncate max-w-[220px]" title="{{ $client['notes'] ?? '' }}">
                {{ $client['notes'] ?? '-' }}
              </td>
              <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                <button 
                  type="button" 
                  onclick='openEditClientModal(@json($client))' 
                  class="text-indigo-600 hover:text-indigo-800 font-bold mr-3 inline-flex items-center gap-1 transition"
                >
                  <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                </button>
                <button 
                  type="button" 
                  onclick="deleteClient({{ $client['id'] }}, '{{ addslashes($client['name']) }}')" 
                  class="text-rose-500 hover:text-rose-700 font-bold inline-flex items-center gap-1 transition"
                >
                  <i class="fa-regular fa-trash-can text-[11px]"></i> Delete
                </button>
              </td>
            </tr>
          @empty
            <tr id="emptyRow">
              <td colspan="8" class="py-12 text-center text-slate-400">
                <div class="flex flex-col items-center justify-center">
                  <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mb-3">
                    <i class="fa-solid fa-users"></i>
                  </div>
                  <h4 class="font-bold text-slate-700 text-sm">No clients registered yet</h4>
                  <p class="text-xs text-slate-400 mt-1">Add your first buyer to start managing guest post orders.</p>
                  <button onclick="openCreateClientModal()" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    + Add New Client
                  </button>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
  function filterClientsTable() {
    const query = document.getElementById('clientSearchInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.client-row');

    rows.forEach(row => {
      const name = row.querySelector('.client-name')?.textContent.toLowerCase() || '';
      const email = row.querySelector('.client-email')?.textContent.toLowerCase() || '';
      const company = row.querySelector('.client-company')?.textContent.toLowerCase() || '';

      if (name.includes(query) || email.includes(query) || company.includes(query)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }
</script>
@endsection
