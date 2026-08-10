<?= $this->extend('layouts/student_layout') ?>
<?= $this->section('content') ?>

<div class="w-full relative animate-[fadeIn_0.5s_ease-out]">
    <div class="glass-panel rounded-md overflow-hidden relative">
        <div class="p-8 md:p-12">
            <div class="mb-8 flex justify-between items-start">
                <div>
                    <span class="text-nic-blue font-bold tracking-wider text-sm uppercase">Step 4 of 5</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Declaration</h2>
                    <p class="text-slate-500 mt-1 font-medium text-sm">Please review and accept the terms to proceed.</p>
                </div>
                <div class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-mono border border-slate-200 shadow-inner">
                    App No: <span class="font-bold text-slate-900"><?= esc($app['application_no']) ?></span>
                </div>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200 rounded-xl text-sm font-bold">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('register/step4_submit') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                <input type="hidden" name="application_no" value="<?= esc($app['application_no']) ?>">

                <div class="bg-blue-50 border border-blue-200 rounded-md p-6 md:p-8 text-slate-800 relative overflow-hidden shadow-sm">
                    <label class="flex items-start cursor-pointer relative z-10 group">
                        <div class="flex items-center h-6 mt-1">
                            <input type="checkbox" name="declaration" value="1" required <?= $app['declaration'] === '1' ? 'checked' : '' ?> class="w-6 h-6 text-nic-blue bg-white border-slate-300 rounded focus:ring-nic-blue focus:ring-2 cursor-pointer">
                        </div>
                        <div class="ml-4 text-sm">
                            <span class="text-lg font-bold text-nic-darkblue tracking-wide block mb-1">Declaration of Authenticity <span class="text-red-500">*</span></span>
                            <p class="text-slate-600 leading-relaxed">I hereby solemnly declare that the details furnished above are true and correct to the best of my knowledge and belief. I understand that my admission allocation is strictly provisional and subject to the physical verification of all original certificates at the admission desk.</p>
                        </div>
                    </label>
                </div>

                <div class="pt-4 flex justify-between items-center">
                    <a href="<?= base_url("register/step3/".$app['application_no']) ?>" class="text-slate-500 font-medium hover:text-slate-800 transition-colors">
                        &larr; Back
                    </a>
                    <button type="submit" class="bg-nic-blue hover:bg-nic-darkblue text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center">
                        Review Application
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
