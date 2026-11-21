@extends('layouts.app')

@section('title', 'User Authentication & Roles')

@section('content')
<section class="module-view">
  <div class="card-section" style="max-width: 500px; margin: 0 auto;">
    <h2 class="card-title" style="margin-bottom: 1rem;">🔐 GuestPost OS Authentication</h2>
    <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 0.9rem;">
      Select active role view to simulate workspace permissions:
    </p>

    <div class="form-group">
      <label class="form-label">Active Portal Role</label>
      <select class="form-control" id="roleSelector">
        <option value="admin" selected>👑 Agency Owner / Admin (Full Access + Profit Margins)</option>
        <option value="vendor">🤝 Vendor / Site Owner (Manage Own Sites & Live Links)</option>
        <option value="client">💼 Client / Buyer (Browse Sites & Place Guest Post Orders)</option>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label">Email Address</label>
      <input type="email" class="form-control" value="admin@guestpostagency.io">
    </div>

    <div class="form-group">
      <label class="form-label">Password</label>
      <input type="password" class="form-control" value="••••••••••••">
    </div>

    <button class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;" onclick="showToast('Active role updated successfully!')">Switch Role & Save</button>
  </div>
</section>
@endsection
