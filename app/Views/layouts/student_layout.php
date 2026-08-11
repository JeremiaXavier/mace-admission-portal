<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'MACE Admission Registration') ?></title>
    <!-- Standard fonts for a formal NIC/Govt aesthetic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Open Sans"', 'Arial', 'sans-serif'],
                    },
                    colors: {
                        nic: {
                            blue: '#0a4275',
                            darkblue: '#062c50',
                            lightblue: '#e9ecef'
                        },
                        mace: {
                            500: '#b91c1c', // MACE Red
                            600: '#991b1b',
                            700: '#7f1d1d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f4f4f4;
            color: #333;
        }
        
        /* Overriding modern classes for a structured, formal NIC style */
        .glass-panel {
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-top: 4px solid #0a4275;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }
        
        .input-premium {
            background-color: #ffffff !important;
            border: 1px solid #999999 !important;
            border-radius: 2px !important;
            transition: none !important;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.05) !important;
        }
        .input-premium:focus {
            border-color: #0a4275 !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(10, 66, 117, 0.2) !important;
        }

        .btn-primary {
            background-color: #0a4275 !important;
            border-radius: 2px !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: none !important;
            border: 1px solid #062c50;
        }
        .btn-primary:hover {
            background-color: #062c50 !important;
            transform: none !important;
        }

        /* Govt portal top bar */
        .top-bar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e5e5e5;
            font-size: 12px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-sm antialiased">

    <!-- Top Utility Bar -->
    <div class="top-bar py-1">
        <div class="w-[96%] xl:w-[92%] mx-auto px-4 flex justify-between items-center text-gray-600">
            <div class="flex space-x-4">
                <span class="font-semibold">Admission 2026</span>
            </div>
            <div class="flex space-x-4 font-semibold">
                <a href="#main-content" class="hover:text-nic-blue">Skip to Main Content</a>
                <span class="border-l border-gray-300 pl-4 flex space-x-2">
                    <button onclick="changeFontSize(-1)" class="hover:text-nic-blue" aria-label="Decrease Font Size">A-</button> 
                    <span>|</span> 
                    <button onclick="changeFontSize(0)" class="hover:text-nic-blue" aria-label="Reset Font Size">A</button> 
                    <span>|</span> 
                    <button onclick="changeFontSize(1)" class="hover:text-nic-blue" aria-label="Increase Font Size">A+</button>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white py-4 border-b border-gray-200 shadow-sm">
        <div class="w-[96%] xl:w-[92%] mx-auto px-4 grid grid-cols-1 md:grid-cols-3 items-center gap-4">
            <!-- Left: College Logo -->
            <div class="flex justify-center md:justify-start">
                <img src="https://civil-lab.macesoft.in/college-logo.svg" alt="MACE Logo" class="h-16 md:h-20 object-contain">
            </div>
            
            <!-- Center: Title -->
            <div class="text-center">
                <h1 class="text-xl md:text-3xl font-extrabold text-nic-darkblue uppercase tracking-wide">ADMISSION PORTAL</h1>
            </div>

            <!-- Right: Accreditation Images -->
            <div class="flex justify-center md:justify-end space-x-4">
                <img src="https://mace.ac.in/wp-content/uploads/2025/01/h1.png" alt="Accreditation 1" class="h-12 md:h-16 object-contain">
                <img src="https://mace.ac.in/wp-content/uploads/2025/01/h2.png" alt="Accreditation 2" class="h-12 md:h-16 object-contain">
            </div>
        </div>
    </header>

    <!-- Navigation Bar -->
    <nav class="bg-nic-blue text-white shadow-md">
        <div class="w-[96%] xl:w-[92%] mx-auto px-4">
            <?php 
                $current_uri = uri_string();
                $isInstructions = (strpos($current_uri, 'instructions') !== false || $current_uri == '');
                $isRegistration = (strpos($current_uri, 'register') !== false);
            ?>
            <ul class="flex space-x-1 font-semibold text-sm">
                <li><a href="<?= base_url('instructions') ?>" class="block px-4 py-3 <?= $isInstructions ? 'bg-nic-darkblue' : '' ?> hover:bg-nic-darkblue transition-colors">Instructions</a></li>
                <li><a href="<?= base_url('register') ?>" class="block px-4 py-3 <?= $isRegistration ? 'bg-nic-darkblue' : '' ?> hover:bg-nic-darkblue transition-colors">Registration</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main id="main-content" class="flex-grow w-[96%] xl:w-[92%] mx-auto px-4 py-8 transition-all duration-300">
        <!-- Breadcrumb -->
        <div class="mb-4 text-xs text-gray-500 font-semibold">
            <a href="<?= base_url('instructions') ?>" class="hover:text-nic-blue">Instructions</a> &raquo; <span class="text-gray-700">Registration Form</span>
        </div>

        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="mt-auto bg-black text-gray-300 text-xs border-t-4 border-nic-blue">
        <div class="w-[96%] xl:w-[92%] mx-auto px-4 py-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex space-x-4 font-semibold text-gray-400">
                <a href="#" class="hover:text-white">Privacy Policy</a>
                <span class="border-l border-gray-700 pl-4"><a href="#" class="hover:text-white">Terms & Conditions</a></span>
                <span class="border-l border-gray-700 pl-4"><a href="#" class="hover:text-white">Disclaimer</a></span>
            </div>
            <div class="text-center md:text-right text-gray-500">
                <p>&copy; <?= date('Y') ?> MACE. All rights reserved.</p>
                <p class="mt-1 font-semibold text-gray-400">
                    Designed & Developed by: <span class="text-white">Jeremia Xavier</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Accessibility Script -->
    <script>
        let currentZoom = 1;
        function changeFontSize(step) {
            if (step === 0) {
                currentZoom = 1;
            } else {
                currentZoom += step * 0.05;
                if(currentZoom < 0.9) currentZoom = 0.9;
                if(currentZoom > 1.2) currentZoom = 1.2;
            }
            document.querySelector('main').style.zoom = currentZoom;
        }
    </script>
</body>
</html>
