<?= $this->extend('layouts/student_layout') ?>
<?= $this->section('content') ?>

<div class="w-full relative animate-[fadeIn_0.5s_ease-out]">
    <div class="glass-panel rounded-md overflow-hidden relative">
        <div class="p-8 md:p-12">
            <div class="mb-8 flex justify-between items-start">
                <div>
                    <span class="text-emerald-600 font-bold tracking-wider text-sm uppercase">Step 3 of 5</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Branch Preferences</h2>
                    <p class="text-slate-500 mt-1 font-medium text-sm">Select desired branches in descending order of your priority.</p>
                </div>
                <div class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-mono border border-slate-200 shadow-inner">
                    App No: <span class="font-bold text-slate-900"><?= esc($app['application_no']) ?></span>
                </div>
            </div>

            <form action="<?= base_url('register/step3_submit') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                <input type="hidden" name="application_no" value="<?= esc($app['application_no']) ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
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
                    for($i=1; $i<=7; $i++): 
                        $currentVal = $app["option_$i"] ?? '';
                    ?>
                    <div class="flex items-center space-x-3 bg-white/60 p-2 rounded-xl border border-slate-200/60 hover:bg-white transition-colors group">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shadow-inner">
                            <?= $i ?>
                        </div>
                        <div class="flex-grow">
                            <select name="option_<?= $i ?>" class="branch-select w-full bg-transparent text-slate-800 font-medium focus:outline-none focus:ring-0 border-none px-1 py-1 cursor-pointer appearance-none" onchange="validateBranches()">
                                <option value="" class="text-slate-400 font-normal">-- Select Preference <?= $i ?> --</option>
                                <?php foreach($branches as $code => $label): ?>
                                    <option value="<?= $code ?>" <?= $currentVal === $code ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="pr-2 pointer-events-none text-slate-400 group-hover:text-emerald-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <div id="branchError" class="p-3 bg-red-50 text-red-600 rounded-lg text-sm font-semibold flex items-center hidden border border-red-100">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Duplicate branches detected! Please ensure each preference is unique.
                </div>

                <div class="pt-4 flex justify-between items-center">
                    <a href="<?= base_url("register/step2/".$app['application_no']) ?>" class="text-slate-500 font-medium hover:text-slate-800 transition-colors">
                        &larr; Back
                    </a>
                    <button type="submit" id="submitBtn" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center">
                        Save & Continue
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validateBranches() {
        const selects = document.querySelectorAll('.branch-select');
        const btn = document.getElementById('submitBtn');
        const errorMsg = document.getElementById('branchError');
        const values = new Set();
        let hasDuplicate = false;

        selects.forEach(select => {
            const container = select.closest('.flex');
            if (select.value !== '') {
                if (values.has(select.value)) {
                    hasDuplicate = true;
                    container.classList.add('border-red-400', 'bg-red-50');
                } else {
                    values.add(select.value);
                    container.classList.remove('border-red-400', 'bg-red-50');
                }
            } else {
                container.classList.remove('border-red-400', 'bg-red-50');
            }
        });

        if (hasDuplicate) {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            errorMsg.classList.remove('hidden');
        } else {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            errorMsg.classList.add('hidden');
        }
    }
    document.addEventListener('DOMContentLoaded', validateBranches);
</script>

<?= $this->endSection() ?>
