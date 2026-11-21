<?php $__env->startSection('title', 'Clients'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

  <!-- Header with Page Title and Action -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Clients Directory (CRM)</h2>
      <p class="text-xs text-slate-500 mt-0.5">Manage guest post buyers, direct agency clients, and contact details.</p>
    </div>
    <div class="flex items-center gap-2.5">
      <button class="bg-white border border-slate-200/90 shadow-sm rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-700 flex items-center gap-2 hover:bg-slate-50 transition">
        <i class="fa-solid fa-download text-slate-400"></i>
        <span>Export</span>
      </button>
      <button class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/20 rounded-xl px-4 py-2 text-xs font-bold flex items-center gap-2 transition">
        <i class="fa-solid fa-plus text-[10px]"></i>
        <span>Add New Client</span>
      </button>
    </div>
  </div>

  <!-- Clients Cards Grid (Dynamic Database Data Loop) -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <?php $__empty_1 = true; $__currentLoopData = $clients ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] p-6 space-y-4">
        <div class="flex items-start justify-between">
          <div class="flex items-center gap-3">
            <?php if(!empty($client['avatar'])): ?>
              <img src="<?php echo e($client['avatar']); ?>" alt="Avatar" class="w-12 h-12 rounded-2xl object-cover shadow-sm">
            <?php else: ?>
              <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-sm shadow-sm">
                <?php echo e(substr($client['name'] ?? 'CL', 0, 2)); ?>

              </div>
            <?php endif; ?>
            <div>
              <h4 class="text-sm font-bold text-slate-800"><?php echo e($client['name'] ?? 'Client'); ?></h4>
              <p class="text-xs text-slate-400"><?php echo e($client['company_name'] ?? 'Agency / Buyer'); ?></p>
            </div>
          </div>
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border bg-emerald-50 text-emerald-600 border-emerald-100">
            Active
          </span>
        </div>

        <div class="pt-3 border-t border-slate-50 space-y-2 text-xs text-slate-500">
          <div class="flex justify-between">
            <span>Email:</span>
            <span class="font-bold text-slate-700"><?php echo e($client['email'] ?? 'N/A'); ?></span>
          </div>
          <div class="flex justify-between">
            <span>Total Orders:</span>
            <span class="font-bold text-slate-700"><?php echo e($client['orders_count'] ?? 0); ?> Posts</span>
          </div>
          <div class="flex justify-between">
            <span>Total Spent:</span>
            <span class="font-bold text-emerald-600">$<?php echo e(number_format($client['total_spent'] ?? 0, 0)); ?></span>
          </div>
        </div>

        <div class="pt-2 flex items-center gap-2">
          <button class="flex-1 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-bold transition">
            View Profile
          </button>
          <button class="px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-500 transition">
            <i class="fa-regular fa-envelope"></i>
          </button>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="col-span-3 text-center py-8 text-slate-400">
        No clients registered in CRM.
      </div>
    <?php endif; ?>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repositories\Guest Post\resources\views/contacts/index.blade.php ENDPATH**/ ?>