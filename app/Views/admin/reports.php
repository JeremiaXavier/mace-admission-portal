<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex justify-between items-end no-print">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Reports & Exports</h2>
        <p class="text-sm text-gray-500 mt-1">Generate and download CSV reports. All exports are memory-optimized and streamed directly from the database.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Admitted Students Report -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <div class="bg-green-100 p-2 rounded-lg text-green-700 mr-3 shadow-sm border border-green-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Admitted Students</h3>
        </div>
        <p class="text-sm text-gray-600 mb-6 h-10">Export a list of all students who have been successfully allotted and admitted to a specific branch.</p>
        
        <form action="<?= base_url('admin/reports/admitted') ?>" method="GET" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-gray-700 mb-1">Select Branch</label>
                <select name="branch" required class="block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-nic-blue focus:border-nic-blue sm:text-sm">
                    <option value="">-- Choose Branch --</option>
                    <?php foreach(['AI','CE','CSE','DS','EEE','ECE','ME'] as $b): ?>
                        <option value="<?= $b ?>"><?= $b ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-nic-blue hover:bg-nic-darkblue text-white px-5 py-2 rounded-md text-sm font-bold shadow-sm transition w-full sm:w-auto">
                Export CSV
            </button>
        </form>
    </div>

    <!-- Applied Students Report -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 p-2 rounded-lg text-blue-700 mr-3 shadow-sm border border-blue-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Applied Students</h3>
        </div>
        <p class="text-sm text-gray-600 mb-6 h-10">Export all students who selected a specific branch as any of their 7 preferences. Includes Option Number.</p>
        
        <form action="<?= base_url('admin/reports/applied') ?>" method="GET" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-gray-700 mb-1">Select Branch</label>
                <select name="branch" required class="block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-nic-blue focus:border-nic-blue sm:text-sm">
                    <option value="">-- Choose Branch --</option>
                    <?php foreach(['AI','CE','CSE','DS','EEE','ECE','ME'] as $b): ?>
                        <option value="<?= $b ?>"><?= $b ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex space-x-2 w-full sm:w-auto">
                <button type="submit" name="format" value="csv" class="bg-nic-blue hover:bg-nic-darkblue text-white px-5 py-2 rounded-md text-sm font-bold shadow-sm transition flex-1 sm:flex-none">
                    Export CSV
                </button>
                <button type="submit" name="format" value="pdf" formtarget="_blank" class="bg-red-700 hover:bg-red-800 text-white px-5 py-2 rounded-md text-sm font-bold shadow-sm transition flex-1 sm:flex-none">
                    Preview PDF
                </button>
            </div>
        </form>
    </div>

    <!-- Full Database Dump -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:col-span-2">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-gray-100 p-2 rounded-lg text-gray-700 mr-3 shadow-sm border border-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Complete Registration Dump</h3>
                    <p class="text-sm text-gray-600">Export all submitted applications across all branches and categories. Contains comprehensive student data.</p>
                </div>
            </div>
            <form action="<?= base_url('admin/reports/all') ?>" method="GET">
                <button type="submit" class="bg-gray-800 hover:bg-black text-white px-6 py-3 rounded-md text-sm font-bold shadow-sm transition ml-4 hidden sm:block whitespace-nowrap">
                    Export All Data (CSV)
                </button>
            </form>
        </div>
        <!-- Mobile button fallback -->
        <form action="<?= base_url('admin/reports/all') ?>" method="GET" class="sm:hidden mt-4">
            <button type="submit" class="bg-gray-800 hover:bg-black text-white px-6 py-3 rounded-md text-sm font-bold shadow-sm transition w-full">
                Export All Data (CSV)
            </button>
        </form>
    </div>

</div>

<?= $this->endSection() ?>
