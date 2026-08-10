<?= $this->extend('layouts/student_layout') ?>
<?= $this->section('content') ?>

<div class="w-full max-w-3xl mx-auto relative animate-[fadeIn_0.5s_ease-out]">
    <div class="glass-panel rounded-md overflow-hidden relative shadow-xl">
        <div class="p-8 md:p-12 text-center">
            
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Spot admission 2026</h2>
            <p class="text-slate-500 font-medium mb-10">Choose an option below to begin or resume your registration process.</p>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="mb-8 p-4 bg-red-50 text-red-700 border border-red-200 rounded-xl text-sm font-bold animate-[slideDown_0.3s_ease-out]">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- New Registration -->
                <?php if($registration_closed): ?>
                <div class="group block p-8 bg-gray-50 border border-gray-200 rounded-md text-left cursor-not-allowed opacity-75">
                    <div class="w-12 h-12 bg-gray-200 text-gray-500 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">New Registration</h3>
                    <p class="text-sm text-red-500 font-bold">Applications are now closed.</p>
                </div>
                <?php else: ?>
                <a href="<?= base_url('register/step1') ?>" class="group block p-8 bg-white border border-slate-200 rounded-md hover:border-mace-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-left cursor-pointer">
                    <div class="w-12 h-12 bg-mace-50 text-mace-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-mace-500 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">New Registration</h3>
                    <p class="text-sm text-slate-500">Start a fresh application.</p>
                </a>
                <?php endif; ?>

                <!-- Restore Application -->
                <div class="p-8 bg-white border border-slate-200 rounded-md text-left">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Restore Application</h3>
                    <p class="text-sm text-slate-500 mb-4">Resume an incomplete application using your registered WhatsApp Mobile Number.</p>
                    
                    <form action="<?= base_url('register/restore') ?>" method="POST" class="flex items-center space-x-2">
                        <?= csrf_field() ?>
                        <input type="text" name="mobile_no" placeholder="10-digit Mobile No" required pattern="[0-9]{10}" maxlength="10" class="flex-grow bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-800 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">Resume</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
