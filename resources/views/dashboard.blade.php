@extends('layouts.app')

@section('title', 'Dashboard & Profit Analytics')

@section('content')
<section class="module-view">
  <!-- KPI Cards Grid -->
  <div class="kpi-grid">
    <div class="kpi-card profit">
      <div class="kpi-header">
        <span class="kpi-title">Net Profit</span>
        <div class="kpi-icon">💰</div>
      </div>
      <div class="kpi-value" id="kpiProfit">$0</div>
      <div class="kpi-subtext positive" id="kpiMargin">0% Margin</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-header">
        <span class="kpi-title">Total Client Revenue</span>
        <div class="kpi-icon">📈</div>
      </div>
      <div class="kpi-value" id="kpiRevenue">$0</div>
      <div class="kpi-subtext">Gross Orders Total</div>
    </div>

    <div class="kpi-card cost">
      <div class="kpi-header">
        <span class="kpi-title">Total Vendor Cost</span>
        <div class="kpi-icon">🤝</div>
      </div>
      <div class="kpi-value" id="kpiCosts">$0</div>
      <div class="kpi-subtext">Paid to Site Owners</div>
    </div>

    <div class="kpi-card orders">
      <div class="kpi-header">
        <span class="kpi-title">Live Published Posts</span>
        <div class="kpi-icon">🔗</div>
      </div>
      <div class="kpi-value" id="kpiLiveOrders">0</div>
      <div class="kpi-subtext">Indexing Verified</div>
    </div>
  </div>

  <!-- Recent Activity Section -->
  <div class="card-section">
    <div class="card-header">
      <h2 class="card-title">⚡ Recent Guest Post Orders & Profit Breakdown</h2>
      <a href="{{ route('orders') }}" class="btn btn-primary btn-sm">View All Orders</a>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Client Name</th>
            <th>Guest Post Site</th>
            <th>Vendor Price</th>
            <th>Selling Price</th>
            <th>Net Profit</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="dashboardRecentOrdersBody">
          <!-- Dynamically Populated via Laravel JS App -->
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
