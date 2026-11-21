@extends('layouts.app')

@section('title', 'Guest Post Site & Vendor Inventory')

@section('content')
<section class="module-view">
  <div class="card-section">
    <div class="card-header">
      <h2 class="card-title">🌐 Guest Post Site Directory & Margin System</h2>
      <button class="btn btn-primary" onclick="openModal('modalAddSite')">➕ Add New Guest Post Site</button>
    </div>

    <div class="controls-bar" style="margin-bottom: 1.25rem;">
      <input type="text" id="inventorySearch" class="search-input" placeholder="🔍 Search domain, niche, or vendor..." oninput="renderInventoryModule()" style="min-width: 280px;">
      
      <select id="inventoryNicheFilter" class="select-input" onchange="renderInventoryModule()">
        <option value="all">All Niches</option>
        <option value="Tech & Software">Tech & Software</option>
        <option value="Finance & Crypto">Finance & Crypto</option>
        <option value="Health & Fitness">Health & Fitness</option>
        <option value="Real Estate">Real Estate</option>
        <option value="Fashion & Lifestyle">Fashion & Lifestyle</option>
        <option value="Marketing & SEO">Marketing & SEO</option>
      </select>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Domain & Info</th>
            <th>Niche</th>
            <th>Authority (DA/DR)</th>
            <th>Traffic</th>
            <th>Vendor Price</th>
            <th>Selling Price</th>
            <th>Profit Margin</th>
            <th>Vendor Contact</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="inventoryTableBody">
          <!-- Dynamically Populated via Laravel JS App -->
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
