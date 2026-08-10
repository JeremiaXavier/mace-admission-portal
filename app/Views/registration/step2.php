<?= $this->extend('layouts/student_layout') ?>
<?= $this->section('content') ?>

<div class="w-full relative animate-[fadeIn_0.5s_ease-out]">
    <div class="glass-panel rounded-md overflow-hidden relative">
        <div class="p-8 md:p-12">
            <div class="mb-8 flex justify-between items-start">
                <div>
                    <span class="text-indigo-600 font-bold tracking-wider text-sm uppercase">Step 2 of 5</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Current Admission Status</h2>
                </div>
                <div class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-mono border border-slate-200 shadow-inner">
                    App No: <span class="font-bold text-slate-900"><?= esc($app['application_no']) ?></span>
                </div>
            </div>


            <form action="<?= base_url('register/step2_submit') ?>" method="POST" class="space-y-8">
                <?= csrf_field() ?>
                <input type="hidden" name="application_no" value="<?= esc($app['application_no']) ?>">

                <div class="bg-slate-50/50 rounded-md p-6 border border-slate-200/60">
                    <label class="block text-[15px] font-semibold text-slate-800 mb-4">Are you currently admitted in another Engineering College? <span class="text-mace-500">*</span></label>
                    <div class="flex items-center space-x-8 mb-4">
                        <label class="relative flex items-center cursor-pointer group">
                            <input type="radio" name="admitted_elsewhere" value="1" <?= $app['admitted_elsewhere'] === '1' ? 'checked' : '' ?> required onchange="toggleAdmissionDetails()" class="peer sr-only">
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 transition-colors"></div>
                            <div class="absolute left-[7px] top-[7px] w-2.5 h-2.5 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            <span class="ml-3 font-medium text-slate-700">Yes, I am</span>
                        </label>
                        <label class="relative flex items-center cursor-pointer group">
                            <input type="radio" name="admitted_elsewhere" value="0" <?= $app['admitted_elsewhere'] === '0' ? 'checked' : '' ?> required onchange="toggleAdmissionDetails()" class="peer sr-only">
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 peer-checked:border-mace-500 peer-checked:bg-mace-500 transition-colors"></div>
                            <div class="absolute left-[7px] top-[7px] w-2.5 h-2.5 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            <span class="ml-3 font-medium text-slate-700">No, I am not</span>
                        </label>
                    </div>

                    <div id="admissionDetails" class="hidden grid-cols-1 md:grid-cols-2 gap-5 pt-5 border-t border-slate-200 mt-2">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Present College Name <span class="text-mace-500">*</span></label>
                            <input type="text" name="present_college" id="present_college" value="<?= esc($app['present_college'] ?? '') ?>" class="input-premium w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Present Branch <span class="text-mace-500">*</span></label>
                            <input type="text" name="present_branch" id="present_branch" value="<?= esc($app['present_branch'] ?? '') ?>" class="input-premium w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Do you have NOC? <span class="text-mace-500">*</span></label>
                            <select name="has_noc" id="has_noc" class="input-premium w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800">
                                <option value="" disabled <?= is_null($app['has_noc']) ? 'selected' : '' ?>>Select...</option>
                                <option value="1" <?= $app['has_noc'] === '1' ? 'selected' : '' ?>>Yes, Available</option>
                                <option value="0" <?= $app['has_noc'] === '0' ? 'selected' : '' ?>>No, Not Available</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Do you have TC & CC? <span class="text-mace-500">*</span></label>
                            <select name="has_tc_cc" id="has_tc_cc" class="input-premium w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800">
                                <option value="" disabled <?= is_null($app['has_tc_cc']) ? 'selected' : '' ?>>Select...</option>
                                <option value="1" <?= $app['has_tc_cc'] === '1' ? 'selected' : '' ?>>Yes, Available</option>
                                <option value="0" <?= $app['has_tc_cc'] === '0' ? 'selected' : '' ?>>No, Not Available</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center">
                        Save & Continue
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleAdmissionDetails() {
        const checked = document.querySelector('input[name="admitted_elsewhere"]:checked');
        if(!checked) return;
        const isAdmitted = checked.value;
        const detailsDiv = document.getElementById('admissionDetails');
        const reqFields = ['present_college', 'present_branch', 'has_noc', 'has_tc_cc'];
        
        if (isAdmitted === '1') {
            detailsDiv.classList.remove('hidden');
            detailsDiv.classList.add('grid');
            reqFields.forEach(f => document.getElementById(f).setAttribute('required', 'required'));
        } else {
            detailsDiv.classList.add('hidden');
            detailsDiv.classList.remove('grid');
            reqFields.forEach(f => {
                const el = document.getElementById(f);
                el.removeAttribute('required');
                el.value = ''; 
            });
        }
    }
    document.addEventListener('DOMContentLoaded', toggleAdmissionDetails);
</script>

<?= $this->endSection() ?>
