<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* Tailwind compatibility overrides for DataTables */
    .dataTables_wrapper .dataTables_length select { 
        border: 1px solid #d1d5db; 
        border-radius: 0.375rem; 
        padding: 0.25rem 2rem 0.25rem 0.5rem; 
        background-color: white;
    }
    .dataTables_wrapper .dataTables_filter input { 
        border: 1px solid #d1d5db; 
        border-radius: 0.375rem; 
        padding: 0.25rem 0.5rem; 
        margin-left: 0.5rem; 
    }
    table.dataTable.no-footer { border-bottom: 1px solid #e5e7eb; }
    table.dataTable thead th { border-bottom: 1px solid #e5e7eb; padding: 12px 18px; }
    table.dataTable tbody td { padding: 12px 18px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #0a4275 !important;
        color: white !important;
        border: 1px solid #062c4f;
        border-radius: 4px;
    }
</style>

<div class="mb-6 flex justify-between items-end no-print">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Branch-wise Rank Lists</h2>
        <p class="text-sm text-gray-500 mt-1">Select a branch to view all applicants who selected it, and manually sort by Option Preference.</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-200">
    <div class="flex flex-col sm:flex-row items-end gap-4">
        <div class="w-full sm:w-1/2 md:w-64">
            <label for="branchSelect" class="block text-sm font-bold text-nic-darkblue mb-1">Select Course / Branch</label>
            <select id="branchSelect" class="block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-nic-blue focus:border-nic-blue sm:text-sm font-medium text-gray-700">
                <option value="">-- Choose a Branch --</option>
                <?php 
                $branches = [
                    'AI' => 'Artificial Intelligence & Machine Learning (AI)', 
                    'CE' => 'Civil Engineering (CE)', 
                    'CSE'=> 'Computer Science and Engineering (CSE)', 
                    'DS' => 'Computer Science and Engineering (Data Science) (DS)', 
                    'EEE'=> 'Electrical and Electronics Engineering (EEE)', 
                    'ECE'=> 'Electronics and Communication Engineering (ECE)', 
                    'ME' => 'Mechanical Engineering (ME)'
                ];
                foreach($branches as $code => $name): ?>
                    <option value="<?= $code ?>"><?= $name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="w-full sm:w-1/2 md:w-64">
            <label for="categorySelect" class="block text-sm font-bold text-nic-darkblue mb-1">Filter by Category</label>
            <select id="categorySelect" class="block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-nic-blue focus:border-nic-blue sm:text-sm font-medium text-gray-700">
                <option value="">All Categories</option>
                <option value="SM">State Merit (SM)</option>
                <option value="EWS">EWS</option>
                <option value="EZ">Ezhava (EZ)</option>
                <option value="MU">Muslim (MU)</option>
                <option value="BH">Other Backward Hindu (BH)</option>
                <option value="LA">Latin Catholic and Anglo Indian (LA)</option>
                <option value="DV">Dheevara and related communities (DV)</option>
                <option value="VK">Viswakarma and related communities (VK)</option>
                <option value="BX">Other Backward Christian (BX)</option>
                <option value="KU">Kudumbi (KU)</option>
                <option value="KN">Kusavan and related communities (KN)</option>
                <option value="SC">Scheduled Castes (SC)</option>
                <option value="ST">Scheduled Tribes (ST)</option>
                <option value="OEC">OEC</option>
                <option value="XS">Ex-servicemen (XS)</option>
                <option value="PI">PI</option>
                <option value="PT">PT</option>
                <option value="TFW">Tuition Fee Waiver (TFW)</option>
            </select>
        </div>
        
        <div class="w-full sm:w-auto flex space-x-2 mt-4 sm:mt-0 ml-auto hidden" id="exportContainer">
            <button onclick="exportRanklistCsv()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center transition shadow-sm w-1/2 sm:w-auto">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </button>
            <button onclick="exportRanklistPdf()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center transition shadow-sm w-1/2 sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print PDF
            </button>
        </div>
    </div>
</div>

<!-- Data Table Container -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4" id="tableContainer" style="display:none;">
    <div class="overflow-x-auto">
        <table id="rankTable" class="min-w-full divide-y divide-gray-200 stripe hover" style="width:100%">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">KEAM Rank</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Roll No</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name & Mobile</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cat.</th>
                    <th class="text-left text-xs font-semibold text-nic-darkblue uppercase tracking-wider">Option Pref</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody id="rankTbody" class="bg-white divide-y divide-gray-200 text-sm">
                <!-- Populated by DataTables -->
            </tbody>
        </table>
    </div>
</div>

<!-- jQuery and DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    let dataTable = null;

    function fetchRankListData() {
        const branch = document.getElementById('branchSelect').value;
        const category = document.getElementById('categorySelect').value;
        
        if (!branch) {
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('exportContainer').classList.add('hidden');
            return;
        }

        document.getElementById('tableContainer').style.display = 'block';
        document.getElementById('exportContainer').classList.remove('hidden');
        document.getElementById('exportContainer').classList.add('flex');
        
        const url = `<?= base_url('admin/ranklist/fetch') ?>?branch=${encodeURIComponent(branch)}&category=${encodeURIComponent(category)}`;

        if (dataTable) {
            dataTable.destroy(); // Destroy previous instance
            document.getElementById('rankTbody').innerHTML = ''; // Clear DOM
        }

        dataTable = $('#rankTable').DataTable({
            ajax: {
                url: url,
                dataSrc: 'applicants'
            },
            columns: [
                { 
                    data: 'entrance_rank', 
                    render: function(data) { return `<strong class="text-gray-900">${data}</strong>`; } 
                },
                { 
                    data: 'entrance_roll_no',
                    render: function(data) { return `<span class="font-bold text-nic-darkblue">${data}</span>`; }
                },
                { 
                    data: null,
                    render: function(data, type, row) { 
                        return `
                        <div class="font-bold text-gray-800">${row.full_name}</div>
                        <div class="text-gray-500 text-xs mt-0.5 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            ${row.mobile_no}
                        </div>`;
                    }
                },
                { 
                    data: 'eligible_category',
                    render: function(data) {
                        return `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">${data}</span>`;
                    }
                },
                { 
                    data: 'pref_no', 
                    render: function(data) { 
                        return `<span class="bg-nic-lightblue text-nic-darkblue px-2.5 py-1 rounded text-sm font-extrabold border border-blue-200">Option ${data}</span>`;
                    }
                },
                {
                    data: null,
                    className: "text-right",
                    render: function(data, type, row) {
                        if (row.allotted_course) {
                            if (row.allotted_course === branch) {
                                return `
                                    <div class="flex items-center justify-end space-x-2">
                                        <span class="bg-green-100 text-green-800 px-3 py-1.5 rounded text-xs font-bold border border-green-200 shadow-sm"><svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Admitted</span>
                                        <button onclick="unadmitStudent(${row.id})" class="bg-red-100 hover:bg-red-200 text-red-600 px-2 py-1 rounded text-xs font-bold transition border border-red-200" title="Undo Admission"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    </div>
                                `;
                            } else {
                                return `
                                    <div class="flex items-center justify-end space-x-2">
                                        <span class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded text-xs font-bold border border-gray-300">Admitted to ${row.allotted_course}</span>
                                        <button onclick="unadmitStudent(${row.id})" class="bg-red-100 hover:bg-red-200 text-red-600 px-2 py-1 rounded text-xs font-bold transition border border-red-200" title="Undo Admission"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    </div>
                                `;
                            }
                        } else {
                            return `<button onclick="admitStudent(${row.id}, '${branch}')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-xs font-bold transition shadow-sm">Admit</button>`;
                        }
                    }
                }
            ],
            order: [[0, 'asc']], // Default sort: column 0 (KEAM Rank) Ascending
            pageLength: 50,
            language: {
                emptyTable: "No applicants found for this branch and category."
            }
        });
    }

    document.getElementById('branchSelect').addEventListener('change', fetchRankListData);
    document.getElementById('categorySelect').addEventListener('change', fetchRankListData);

    window.exportRanklistCsv = function() {
        const branch = document.getElementById('branchSelect').value;
        const category = document.getElementById('categorySelect').value;
        if (!branch) return;
        window.location.href = `<?= base_url('admin/ranklist/export_csv') ?>?branch=${encodeURIComponent(branch)}&category=${encodeURIComponent(category)}`;
    };

    window.exportRanklistPdf = function() {
        const branch = document.getElementById('branchSelect').value;
        const category = document.getElementById('categorySelect').value;
        if (!branch) return;
        window.open(`<?= base_url('admin/ranklist/export_pdf') ?>?branch=${encodeURIComponent(branch)}&category=${encodeURIComponent(category)}`, '_blank');
    };

    window.admitStudent = function(id, branch) {
        if(confirm(`Are you sure you want to ADMIT this student to ${branch}?`)) {
            $.ajax({
                url: '<?= base_url('admin/ranklist/admit') ?>',
                type: 'POST',
                data: {
                    id: id,
                    branch: branch,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if(response.success) {
                        dataTable.ajax.reload(null, false); // Reload table without resetting pagination
                    } else {
                        alert("Error admitting student.");
                    }
                }
            });
        }
    };

    window.unadmitStudent = function(id) {
        if(confirm(`Are you sure you want to UNDO this admission? The student will be available to admit in another branch.`)) {
            $.ajax({
                url: '<?= base_url('admin/ranklist/unadmit') ?>',
                type: 'POST',
                data: {
                    id: id,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if(response.success) {
                        dataTable.ajax.reload(null, false);
                    } else {
                        alert("Error un-admitting student.");
                    }
                }
            });
        }
    };
</script>

<?= $this->endSection() ?>
