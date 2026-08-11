<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex justify-between items-end no-print">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Applicants List</h2>
        <p class="text-sm text-gray-500 mt-1">Manage and verify spot admission candidates dynamically.</p>
    </div>
</div>

<!-- Filters & Search -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6 no-print border border-gray-200">
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
        
        <!-- Category Dropdown (AJAX) -->
        <div class="w-full xl:w-64">
            <label for="categorySelect" class="sr-only">Select Category</label>
            <select id="categorySelect" class="block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-nic-blue focus:border-nic-blue sm:text-sm font-medium text-gray-700">
                <option value="SM">State Merit (SM) - All Students</option>
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

        <!-- Search & Export -->
        <div class="flex flex-wrap sm:flex-nowrap space-y-2 sm:space-y-0 sm:space-x-3">
            <form id="searchForm" class="flex w-full sm:w-auto">
                <input type="text" id="searchInput" placeholder="Roll No or Rank..." class="w-full sm:w-auto px-3 py-1.5 border border-gray-300 rounded-l-md text-sm focus:outline-none focus:ring-1 focus:ring-nic-blue">
                <button type="submit" class="bg-gray-100 px-4 py-1.5 border border-l-0 border-gray-300 rounded-r-md text-sm font-medium text-gray-700 hover:bg-gray-200">Search</button>
            </form>
            
            <a href="<?= base_url('admin/allotment/export?category=SM') ?>" id="exportBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-md text-sm font-medium flex items-center justify-center transition shadow-sm w-1/2 sm:w-auto">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </a>
            
            <button id="printPdfBtn" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-1.5 rounded-md text-sm font-medium flex items-center justify-center transition shadow-sm w-1/2 sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print PDF
            </button>
        </div>
    </div>
</div>

<!-- Print Header (Hidden normally) -->
<div class="hidden print:block mb-4">
    <h2 class="text-xl font-bold">MACE Allotment - <span id="printCategoryLabel">SM</span> Category List</h2>
    <p class="text-sm">Generated on: <?= date('Y-m-d H:i:s') ?></p>
</div>

<!-- Data Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 print-table-container overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 group" onclick="toggleSort('entrance_rank')">
                        <div class="flex items-center">KEAM Rank <span class="ml-1 text-gray-400 group-hover:text-gray-600" id="icon-entrance_rank">⇅</span></div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 group" onclick="toggleSort('entrance_roll_no')">
                        <div class="flex items-center">Roll No <span class="ml-1 text-gray-400 group-hover:text-gray-600" id="icon-entrance_roll_no">⇅</span></div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 group" onclick="toggleSort('full_name')">
                        <div class="flex items-center">Name & Mobile <span class="ml-1 text-gray-400 group-hover:text-gray-600" id="icon-full_name">⇅</span></div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 group" onclick="toggleSort('eligible_category')">
                        <div class="flex items-center">Cat. <span class="ml-1 text-gray-400 group-hover:text-gray-600" id="icon-eligible_category">⇅</span></div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 group" onclick="toggleSort('time_of_reporting')">
                        <div class="flex items-center">Time <span class="ml-1 text-gray-400 group-hover:text-gray-600" id="icon-time_of_reporting">⇅</span></div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Options (1-7)</th>
                </tr>
            </thead>
            <tbody id="applicantsTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Populated by AJAX -->
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Info -->
    <div id="paginationInfo" class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex items-center justify-between no-print hidden">
        <div id="pageInfoText" class="text-sm text-gray-700">
            Showing page <span class="font-bold">1</span>
        </div>
        <div class="flex space-x-2">
            <button id="prevBtn" class="px-4 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 text-gray-700 transition shadow-sm" style="display:none;">Previous</button>
            <button id="nextBtn" class="px-4 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 text-gray-700 transition shadow-sm" style="display:none;">Next</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    
    const categorySelect = document.getElementById('categorySelect');
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const exportBtn = document.getElementById('exportBtn');
    const printLabel = document.getElementById('printCategoryLabel');
    const tbody = document.getElementById('applicantsTableBody');
    const paginationInfo = document.getElementById('paginationInfo');
    const pageInfoText = document.getElementById('pageInfoText');
    const prevBtn = document.getElementById('prevBtn');
    const printPdfBtn = document.getElementById('printPdfBtn');
    let currentSortBy = 'entrance_rank';
    let currentSortDir = 'ASC';

    window.toggleSort = function(field) {
        if (currentSortBy === field) {
            currentSortDir = currentSortDir === 'ASC' ? 'DESC' : 'ASC';
        } else {
            currentSortBy = field;
            currentSortDir = 'ASC';
        }
        
        // Reset all icons
        ['entrance_rank', 'entrance_roll_no', 'full_name', 'eligible_category', 'time_of_reporting'].forEach(f => {
            document.getElementById(`icon-${f}`).innerHTML = '⇅';
            document.getElementById(`icon-${f}`).classList.remove('text-gray-900');
            document.getElementById(`icon-${f}`).classList.add('text-gray-400');
        });
        
        // Set active icon
        const activeIcon = document.getElementById(`icon-${field}`);
        activeIcon.innerHTML = currentSortDir === 'ASC' ? '↑' : '↓';
        activeIcon.classList.remove('text-gray-400');
        activeIcon.classList.add('text-gray-900');
        
        fetchApplicants(1);
    };

    printPdfBtn.addEventListener('click', () => {
        const cat = categorySelect.value;
        window.open(`<?= base_url('admin/allotment/export_pdf') ?>?category=${cat}`, '_blank');
    });

    function fetchApplicants(page = 1) {
        const category = categorySelect.value;
        const search = searchInput.value;
        
        // Update Print and Export
        printLabel.textContent = category;
        exportBtn.href = `<?= base_url('admin/allotment/export') ?>?category=${category}`;
        
        // Loading State
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">Loading applicants...</td></tr>';
        
        const url = `<?= base_url('admin/allotment/fetch') ?>?category=${encodeURIComponent(category)}&page=${page}&search=${encodeURIComponent(search)}&sort_by=${currentSortBy}&sort_dir=${currentSortDir}`;
        
        fetch(url)
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = '';
                
                if (data.applicants.length === 0) {
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                No applicants found.
                            </div>
                        </td>
                    </tr>`;
                } else {
                    let html = '';
                    data.applicants.forEach(app => {
                        let optionsHtml = '';
                        for(let i=1; i<=7; i++) {
                            if(app['option_'+i]) {
                                optionsHtml += `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-nic-lightblue text-nic-darkblue border border-blue-200"><strong class="mr-1">${i}:</strong> ${app['option_'+i]}</span> `;
                            }
                        }
                        
                        html += `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${app.entrance_rank}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-nic-darkblue font-bold">${app.entrance_roll_no}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="font-bold text-gray-800">${app.full_name}</div>
                                <div class="text-gray-500 text-xs mt-0.5 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    ${app.mobile_no}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    ${app.eligible_category}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${app.time_of_reporting || '-'}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex flex-wrap gap-1.5">${optionsHtml}</div>
                            </td>
                        </tr>`;
                    });
                    tbody.innerHTML = html;
                }

                // Pagination logic
                if (search === '') {
                    paginationInfo.classList.remove('hidden');
                    pageInfoText.innerHTML = `Showing page <span class="font-bold">${data.page}</span>`;
                    
                    if (data.page > 1) {
                        prevBtn.style.display = 'block';
                        prevBtn.onclick = () => fetchApplicants(data.page - 1);
                    } else {
                        prevBtn.style.display = 'none';
                    }
                    
                    if (data.applicants.length === data.limit) {
                        nextBtn.style.display = 'block';
                        nextBtn.onclick = () => fetchApplicants(data.page + 1);
                    } else {
                        nextBtn.style.display = 'none';
                    }
                } else {
                    paginationInfo.classList.add('hidden');
                }
            })
            .catch(err => {
                console.error("Failed to fetch data:", err);
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-10 text-center text-red-500">Failed to load data.</td></tr>';
            });
    }

    // Initial Fetch
    fetchApplicants(1);

    // Event Listeners
    categorySelect.addEventListener('change', () => fetchApplicants(1));
    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetchApplicants(1);
    });
});
</script>

<?= $this->endSection() ?>
