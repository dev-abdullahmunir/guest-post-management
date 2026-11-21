@extends('layouts.app')

@section('title', 'Vendor & Client Directory (CRM)')

@section('content')
<section class="module-view">
  <div class="card-section">
    <div class="card-header">
      <h2 class="card-title">🤝 Vendor Directory (Site Owners & Providers)</h2>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Vendor Name / Agency</th>
            <th>Email Contact</th>
            <th>Payment Method</th>
            <th>Sites Listed</th>
            <th>Avg Turnaround</th>
            <th>Total Paid out</th>
          </tr>
        </thead>
        <tbody id="vendorTableBody">
          <!-- Dynamically Populated via Laravel JS App -->
        </tbody>
      </table>
    </div>
  </div>

  <div class="card-section">
    <div class="card-header">
      <h2 class="card-title">💼 Client CRM (Agencies & Buyers)</h2>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Client Company</th>
            <th>Contact Person</th>
            <th>Email</th>
            <th>Total Orders</th>
            <th>Total Spent</th>
          </tr>
        </thead>
        <tbody id="clientTableBody">
          <!-- Dynamically Populated via Laravel JS App -->
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
