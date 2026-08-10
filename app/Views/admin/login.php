<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clerk Login - MACE Admission</title>
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
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-md border border-gray-200">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900">
                Admin / Clerk Login
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                MACE B.Tech Admission 2026
            </p>
        </div>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= esc(session()->getFlashdata('error')) ?></span>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="<?= base_url('admin/login') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="username" class="sr-only">Username</label>
                    <input id="username" name="username" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-nic-blue focus:border-nic-blue focus:z-10 sm:text-sm" placeholder="Username">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-nic-blue focus:border-nic-blue focus:z-10 sm:text-sm" placeholder="Password">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-nic-blue hover:bg-nic-darkblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nic-blue">
                    Sign in
                </button>
            </div>
            
            <p class="text-center text-xs text-gray-500 mt-4">For demo, table admin_users is auto-seeded with admin / admin123 on first login attempt if it doesn't exist.</p>
        </form>
    </div>
</body>
</html>
