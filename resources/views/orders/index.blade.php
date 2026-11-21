@extends('layouts.app')

@section('title', 'Orders & Live Link Status Tracker')

@section('content')
<section class="module-view">
  <div class="card-section">
    <div class="card-header">
      <h2 class="card-title">🚀 Guest Post Orders & Live Link Status Tracker</h2>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Client Name</th>
            <th>Target Domain</th>
            <th>Target URL & Anchor</th>
            <th>Vendor Price</th>
            <th>Selling Price</th>
            <th>Net Profit</th>
            <th>Status & Indexing</th>
            <th>Live Link URL</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="ordersTableBody">
          <!-- Dynamically Populated via Laravel JS App -->
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
