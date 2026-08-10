<?= $this->extend('layouts/student_layout') ?>
<?= $this->section('content') ?>

<div class="w-full max-w-3xl mx-auto relative animate-[fadeIn_0.5s_ease-out]">
    <div class="bg-white rounded-md overflow-hidden shadow-xl border border-slate-200">
        
        <div class="px-8 py-8 bg-gradient-to-r from-nic-blue to-nic-darkblue text-white text-center print:bg-white print:text-black print:border-b-4 print:border-nic-darkblue">
            <h2 class="text-3xl font-extrabold uppercase tracking-wide print:text-2xl">Registration Acknowledgment</h2>
            <p class="text-nic-lightblue mt-1 print:text-black">Admission <?= date('Y') ?></p>
        </div>

        <div class="p-8 md:p-12">
            <div class="mb-8 text-center print:hidden">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 mb-5 shadow-inner">
                    <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900">Application Submitted!</h3>
                <p class="text-slate-500 mt-2 font-medium">Please print or save this slip and present it at the verification desk.</p>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 mb-8 print:border-none print:p-0">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                    
                    <div class="sm:col-span-1">
                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Applicant Name</dt>
                        <dd class="mt-1 text-lg font-bold text-slate-900"><?= esc($data['full_name']) ?></dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Mobile No</dt>
                        <dd class="mt-1 text-lg font-bold text-slate-900"><?= esc($data['mobile_no']) ?></dd>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">KEAM Roll No</dt>
                        <dd class="mt-1 text-xl font-black text-mace-700 uppercase"><?= esc($data['entrance_roll_no']) ?></dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">KEAM Rank</dt>
                        <dd class="mt-1 text-xl font-black text-mace-700"><?= esc($data['entrance_rank']) ?></dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Category</dt>
                        <dd class="mt-1 text-lg font-bold text-slate-900"><?= esc($data['eligible_category']) ?></dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Application No</dt>
                        <dd class="mt-1 text-lg font-black text-slate-900 font-mono tracking-widest"><?= esc($data['application_no']) ?></dd>
                    </div>
                </dl>
            </div>

            <div class="mb-8">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-3 border-b border-slate-200 pb-2">Branch Preferences</h4>
                <ol class="list-decimal pl-5 space-y-1 text-slate-800 font-bold">
                    <?php for($i=1; $i<=7; $i++): ?>
                        <?php if(!empty($data["option_$i"])): ?>
                            <li><?= esc($data["option_$i"]) ?></li>
                        <?php endif; ?>
                    <?php endfor; ?>
                </ol>
                <?php if(empty($data['option_1'])): ?>
                    <p class="text-sm text-slate-500 italic">No branch preferences recorded.</p>
                <?php endif; ?>
            </div>

            <div class="pt-6 border-t border-dashed border-slate-300 flex justify-between items-end print:block print:mt-16">
                <div class="text-xs text-slate-500 font-mono">
                    <p>Ref: MACE-<?= date('ymd') ?>-<?= esc($data['application_no']) ?></p>
                    <p>Time: <?= date('Y-m-d H:i:s') ?></p>
                </div>
                
                <div class="text-center print:mt-10 print:text-right">
                    <div class="w-40 border-b border-slate-800 mb-2 print:ml-auto"></div>
                    <p class="text-xs font-bold uppercase text-slate-700">Signature of Applicant</p>
                </div>
            </div>

        </div>
        
        <div class="bg-slate-50 px-8 py-5 border-t border-slate-200 print:hidden flex justify-center space-x-4">
            <a href="<?= base_url('register/pdf/'.$data['application_no']) ?>" target="_blank" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-200 transition-all">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Download PDF Slip
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
