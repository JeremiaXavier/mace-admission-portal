<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MACE Admission</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nic-blue': '#0a4275',
                        'nic-darkblue': '#062c4f',
                        'nic-lightblue': '#e6f0fa',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; }
            .print-table-container { overflow: visible !important; }
        }
    </style>
</head>
<body class="flex flex-col h-screen overflow-hidden bg-gray-50">
    
    <!-- Top Header (Matches Student Portal) -->
    <header class="bg-white py-3 border-b-4 border-nic-blue shadow-md z-30 no-print">
        <div class="w-full px-4 md:px-8 grid grid-cols-1 md:grid-cols-3 items-center gap-4">
            <!-- Left: College Logo -->
            <div class="flex justify-center md:justify-start">
                <img src="https://civil-lab.macesoft.in/college-logo.svg" alt="MACE Logo" class="h-12 md:h-16 object-contain">
            </div>
            
            <!-- Center: Title -->
            <div class="text-center flex flex-col items-center">
                <h1 class="text-lg md:text-2xl font-extrabold text-nic-darkblue uppercase tracking-wide">ADMISSION PORTAL</h1>
                <span class="bg-nic-blue text-white text-xs font-bold px-3 py-0.5 rounded-full mt-1 uppercase tracking-widest shadow-sm">Admin Dashboard</span>
            </div>

            <!-- Right: Accreditation Images -->
            <div class="flex justify-center md:justify-end space-x-4">
                <img src="https://mace.ac.in/wp-content/uploads/2025/01/h1.png" alt="Accreditation 1" class="h-10 md:h-12 object-contain">
                <img src="https://mace.ac.in/wp-content/uploads/2025/01/h2.png" alt="Accreditation 2" class="h-10 md:h-12 object-contain">
            </div>
        </div>
    </header>

    <!-- Bottom Section: Sidebar + Content -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- Sidebar (NIC Theme) -->
        <aside class="w-64 bg-nic-darkblue text-white flex flex-col hidden md:flex no-print shadow-xl z-20">
            <div class="flex-1 overflow-y-auto py-6">
                <div class="px-4 mb-6">
                    <p class="text-xs text-nic-lightblue uppercase font-bold tracking-wider mb-2">Menu</p>
                </div>
                <nav class="space-y-1 px-3">
                    <?php
                    $uri = uri_string();
                    $isAllotments = (strpos($uri, 'admin/allotment') !== false);
                    $isRanklist = (strpos($uri, 'admin/ranklist') !== false);
                    $isReports = (strpos($uri, 'admin/reports') !== false);

                    $activeClass = "flex items-center px-3 py-2.5 rounded-md text-sm font-medium bg-white text-nic-darkblue shadow transition";
                    $activeIconClass = "mr-3 h-5 w-5 text-nic-darkblue";

                    $inactiveClass = "flex items-center px-3 py-2.5 rounded-md text-sm font-medium text-gray-300 hover:bg-nic-blue hover:text-white transition";
                    $inactiveIconClass = "mr-3 h-5 w-5 text-gray-400";
                    ?>

                    <a href="<?= base_url('admin/allotment') ?>" class="<?= $isAllotments ? $activeClass : $inactiveClass ?>">
                        <svg class="<?= $isAllotments ? $activeIconClass : $inactiveIconClass ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Applicants
                    </a>
                    <a href="<?= base_url('admin/ranklist') ?>" class="<?= $isRanklist ? $activeClass : $inactiveClass ?>">
                        <svg class="<?= $isRanklist ? $activeIconClass : $inactiveIconClass ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Rank Lists
                    </a>
                    <a href="<?= base_url('admin/reports') ?>" class="<?= $isReports ? $activeClass : $inactiveClass ?>">
                        <svg class="<?= $isReports ? $activeIconClass : $inactiveIconClass ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Reports
                    </a>
                </nav>
            </div>
            <div class="p-4 border-t border-nic-blue bg-nic-darkblue">
                <?php
                $settingsPath = WRITEPATH . 'settings.json';
                $settings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : ['registration_closed' => false];
                $isClosed = $settings['registration_closed'] ?? false;
                ?>
                <form action="<?= base_url('admin/toggle_registration') ?>" method="POST" class="mb-4">
                    <?= csrf_field() ?>
                    <button type="submit" onclick="return confirm('Are you sure you want to <?= $isClosed ? 'open' : 'close' ?> new registrations?')" class="w-full text-center px-4 py-2 border rounded-md text-sm font-medium transition <?= $isClosed ? 'border-green-500 text-green-500 hover:bg-green-500 hover:text-white' : 'border-red-400 text-red-400 hover:bg-red-500 hover:text-white' ?>">
                        <?= $isClosed ? 'Open Registrations' : 'Close Registrations' ?>
                    </button>
                </form>

                <div class="mb-4 px-2">
                    <p class="text-xs text-nic-lightblue">Logged in as:</p>
                    <p class="text-sm font-bold text-white"><?= esc(session()->get('admin_username') ?? 'Admin') ?></p>
                </div>
                <a href="<?= base_url('admin/logout') ?>" class="block w-full text-center px-4 py-2 border border-nic-lightblue rounded-md text-sm font-medium text-nic-lightblue hover:bg-nic-lightblue hover:text-nic-darkblue transition">
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
            <?= $this->renderSection('content') ?>
        </main>
        
    </div>

</body>
</html>
