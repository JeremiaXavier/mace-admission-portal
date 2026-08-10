<?= $this->extend('layouts/student_layout') ?>
<?= $this->section('content') ?>

<div class="w-full relative animate-[fadeIn_0.5s_ease-out]">
    <!-- Form Card -->
    <div class="glass-panel rounded-3xl overflow-hidden relative">
        <!-- Accent Top Bar -->
        <div class="h-2 w-full bg-gradient-to-r from-mace-500 via-rose-400 to-mace-700"></div>
        
        <div class="p-8 md:p-12">
            
            <div class="text-center mb-10 relative">
                <div class="inline-flex items-center justify-center p-3 bg-mace-50 rounded-2xl mb-4 text-mace-600 ring-1 ring-mace-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Candidate Registration</h2>
                <p class="text-slate-500 mt-2 font-medium max-w-lg mx-auto">Fill in your details accurately to participate in the spot allotment process. Fields marked with an asterisk (*) are mandatory.</p>
            </div>

            <?php if(isset($errors) && !empty($errors)): ?>
                <div class="mb-8 p-5 bg-red-50/80 backdrop-blur-sm border border-red-200 rounded-2xl shadow-sm animate-[slideDown_0.3s_ease-out]">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Registration could not be completed</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                                <?php foreach($errors as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <form action="<?= base_url('register/submit') ?>" method="POST" id="registrationForm" class="space-y-10">
                <!-- CSRF intentionally excluded -->

                <!-- 1. Personal Details -->
                <div class="relative">
                    <div class="absolute -left-12 top-0 bottom-0 w-px bg-slate-200 hidden lg:block"></div>
                    <div class="absolute -left-14 top-1 w-5 h-5 rounded-full bg-white border-4 border-mace-500 hidden lg:block"></div>
                    
                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                        <span class="bg-mace-100 text-mace-700 px-3 py-1 rounded-lg text-sm mr-3 lg:hidden">1</span>
                        Personal Identity
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Full Name <span class="text-mace-500">*</span></label>
                            <input type="text" name="full_name" required value="<?= set_value('full_name') ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-mace-500/50 focus:border-mace-500 transition-all" placeholder="As per KEAM records">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">WhatsApp Mobile No <span class="text-mace-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-slate-400 font-medium">+91</span>
                                </div>
                                <input type="text" name="mobile_no" required pattern="[0-9]{10}" maxlength="10" title="10 digit mobile number" value="<?= set_value('mobile_no') ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl pl-12 pr-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-mace-500/50 focus:border-mace-500 transition-all" placeholder="9876543210">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Email Address</label>
                            <input type="email" name="email" value="<?= set_value('email') ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-mace-500/50 focus:border-mace-500 transition-all" placeholder="your@email.com">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Time of Reporting</label>
                            <?php 
                            $timeVal = set_value('time_of_reporting');
                            if (empty($timeVal)) {
                                $timeVal = date('H:i');
                            }
                            ?>
                            <input type="time" name="time_of_reporting" value="<?= $timeVal ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-mace-500/50 focus:border-mace-500 transition-all">
                        </div>
                    </div>
                </div>

                <!-- 2. KEAM Details -->
                <div class="relative">
                    <div class="absolute -left-12 top-0 bottom-0 w-px bg-slate-200 hidden lg:block"></div>
                    <div class="absolute -left-14 top-1 w-5 h-5 rounded-full bg-white border-4 border-rose-400 hidden lg:block"></div>

                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                        <span class="bg-rose-100 text-rose-700 px-3 py-1 rounded-lg text-sm mr-3 lg:hidden">2</span>
                        KEAM Academic Profile
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-5">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Entrance Roll Number <span class="text-mace-500">*</span></label>
                            <input type="text" name="entrance_roll_no" required value="<?= set_value('entrance_roll_no') ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 uppercase focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-rose-400 font-mono text-lg tracking-wider" placeholder="123456">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">KEAM State Rank <span class="text-mace-500">*</span></label>
                            <input type="number" name="entrance_rank" required min="1" value="<?= set_value('entrance_rank') ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-rose-400 font-mono text-lg" placeholder="1052">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Claimed Category <span class="text-mace-500">*</span></label>
                            <select name="eligible_category" required class="input-premium w-full bg-white/90 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-rose-400">
                                <option value="" disabled selected class="text-slate-400">Select Category...</option>
                                <?php 
                                $categories = ['SM'=>'State Merit','EWS'=>'EWS','EZ'=>'Ezhava','MU'=>'Muslim','BH'=>'Other Backward Hindu','LA'=>'Latin Catholic','BX'=>'Other Backward Christian','KU'=>'Kudumbi','VK'=>'Viswakarma','DV'=>'Dheevara','KN'=>'Kusavan','SC'=>'Scheduled Castes','ST'=>'Scheduled Tribes','OEC'=>'OEC','XS'=>'Ex-servicemen','PI'=>'PI','PT'=>'PT'];
                                foreach($categories as $code => $label): 
                                    $sel = set_value('eligible_category') === $code ? 'selected' : '';
                                ?>
                                    <option value="<?= $code ?>" <?= $sel ?>><?= $code ?> &mdash; <?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 3. Admission Status -->
                <div class="relative">
                    <div class="absolute -left-12 top-0 bottom-0 w-px bg-slate-200 hidden lg:block"></div>
                    <div class="absolute -left-14 top-1 w-5 h-5 rounded-full bg-white border-4 border-indigo-400 hidden lg:block"></div>

                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-lg text-sm mr-3 lg:hidden">3</span>
                        Current Admission Status
                    </h3>
                    
                    <div class="bg-slate-50/50 rounded-2xl p-6 border border-slate-200/60 shadow-inner">
                        <label class="block text-[15px] font-semibold text-slate-800 mb-4">Are you currently admitted in another Engineering College? <span class="text-mace-500">*</span></label>
                        <div class="flex items-center space-x-8 mb-4">
                            <label class="relative flex items-center cursor-pointer group">
                                <input type="radio" name="admitted_elsewhere" value="1" <?= set_value('admitted_elsewhere') === '1' ? 'checked' : '' ?> onchange="toggleAdmissionDetails()" class="peer sr-only">
                                <div class="w-6 h-6 rounded-full border-2 border-slate-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 transition-colors group-hover:border-indigo-400"></div>
                                <div class="absolute left-[7px] top-[7px] w-2.5 h-2.5 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                <span class="ml-3 font-medium text-slate-700 group-hover:text-indigo-600 transition-colors">Yes, I am</span>
                            </label>
                            <label class="relative flex items-center cursor-pointer group">
                                <input type="radio" name="admitted_elsewhere" value="0" <?= set_value('admitted_elsewhere', '0') === '0' ? 'checked' : '' ?> onchange="toggleAdmissionDetails()" class="peer sr-only">
                                <div class="w-6 h-6 rounded-full border-2 border-slate-300 peer-checked:border-mace-500 peer-checked:bg-mace-500 transition-colors group-hover:border-mace-400"></div>
                                <div class="absolute left-[7px] top-[7px] w-2.5 h-2.5 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                <span class="ml-3 font-medium text-slate-700 group-hover:text-mace-500 transition-colors">No, I am not</span>
                            </label>
                        </div>

                        <div id="admissionDetails" class="hidden grid-cols-1 md:grid-cols-2 gap-5 pt-5 border-t border-slate-200 mt-2 animate-[fadeIn_0.3s_ease-out]">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-slate-700">Present College Name <span class="text-mace-500">*</span></label>
                                <input type="text" name="present_college" id="present_college" value="<?= set_value('present_college') ?>" class="input-premium w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400" placeholder="College Name">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-slate-700">Present Branch <span class="text-mace-500">*</span></label>
                                <input type="text" name="present_branch" id="present_branch" value="<?= set_value('present_branch') ?>" class="input-premium w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400" placeholder="Branch Name">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-slate-700">Do you have NOC? <span class="text-mace-500">*</span></label>
                                <select name="has_noc" id="has_noc" class="input-premium w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400">
                                    <option value="" disabled selected>Select...</option>
                                    <option value="1" <?= set_value('has_noc') === '1' ? 'selected' : '' ?>>Yes, Available</option>
                                    <option value="0" <?= set_value('has_noc') === '0' ? 'selected' : '' ?>>No, Not Available</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-slate-700">Do you have TC & CC? <span class="text-mace-500">*</span></label>
                                <select name="has_tc_cc" id="has_tc_cc" class="input-premium w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400">
                                    <option value="" disabled selected>Select...</option>
                                    <option value="1" <?= set_value('has_tc_cc') === '1' ? 'selected' : '' ?>>Yes, Available</option>
                                    <option value="0" <?= set_value('has_tc_cc') === '0' ? 'selected' : '' ?>>No, Not Available</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Branch Preferences -->
                <div class="relative">
                    <div class="absolute -left-12 top-0 bottom-0 w-px bg-slate-200 hidden lg:block"></div>
                    <div class="absolute -left-14 top-1 w-5 h-5 rounded-full bg-white border-4 border-emerald-400 hidden lg:block"></div>

                    <h3 class="text-xl font-bold text-slate-800 mb-2 flex items-center">
                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-lg text-sm mr-3 lg:hidden">4</span>
                        Branch Preferences
                    </h3>
                    <p class="text-sm text-slate-500 mb-6 font-medium">Select desired branches in descending order of your priority. You may leave subsequent options blank if not interested.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <?php 
                        $branches = ['AI'=>'AI & ML', 'CE'=>'Civil Engineering', 'CSE'=>'Computer Science', 'DS'=>'CS Data Science', 'EEE'=>'Electrical & Electronics', 'ECE'=>'Electronics & Communication', 'ME'=>'Mechanical Engineering'];
                        for($i=1; $i<=7; $i++): 
                        ?>
                        <div class="flex items-center space-x-3 bg-white/60 p-2 rounded-xl border border-slate-200/60 hover:bg-white transition-colors group">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shadow-inner">
                                <?= $i ?>
                            </div>
                            <div class="flex-grow">
                                <select name="option_<?= $i ?>" class="branch-select w-full bg-transparent text-slate-800 font-medium focus:outline-none focus:ring-0 border-none px-1 py-1 cursor-pointer appearance-none" onchange="validateBranches()">
                                    <option value="" class="text-slate-400 font-normal">-- Select Preference <?= $i ?> --</option>
                                    <?php foreach($branches as $code => $label): 
                                        $sel = set_value("option_$i") === $code ? 'selected' : '';
                                    ?>
                                        <option value="<?= $code ?>" <?= $sel ?>><?= $code ?> - <?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="pr-2 pointer-events-none text-slate-400 group-hover:text-emerald-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                    <div id="branchError" class="mt-4 p-3 bg-red-50 text-red-600 rounded-lg text-sm font-semibold flex items-center hidden border border-red-100">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Duplicate branches detected! Please ensure each preference is unique.
                    </div>
                </div>

                <!-- Declaration -->
                <div class="bg-slate-900 rounded-2xl p-6 md:p-8 text-white relative overflow-hidden mt-8 shadow-xl">
                    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/10 blur-2xl"></div>
                    
                    <label class="flex items-start cursor-pointer relative z-10 group">
                        <div class="flex items-center h-6 mt-1">
                            <input type="checkbox" name="declaration" value="1" required <?= set_value('declaration') ? 'checked' : '' ?> class="peer sr-only">
                            <div class="w-6 h-6 bg-slate-800 border-2 border-slate-600 rounded flex items-center justify-center peer-checked:bg-emerald-500 peer-checked:border-emerald-500 transition-all">
                                <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transform scale-50 peer-checked:scale-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div class="ml-4 text-sm">
                            <span class="text-lg font-bold text-white tracking-wide block mb-1">Declaration of Authenticity <span class="text-mace-500">*</span></span>
                            <p class="text-slate-300 leading-relaxed font-light">I hereby solemnly declare that the details furnished above are true and correct to the best of my knowledge and belief. I understand that my spot admission allocation is strictly provisional and subject to the physical verification of all original certificates at the admission desk.</p>
                        </div>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 pb-2">
                    <button type="submit" id="submitBtn" class="w-full flex justify-center items-center py-4 px-6 rounded-2xl text-lg font-bold text-white bg-gradient-to-r from-mace-600 to-mace-700 hover:from-mace-500 hover:to-mace-600 shadow-[0_10px_25px_-5px_rgba(225,29,72,0.4)] hover:shadow-[0_15px_35px_-5px_rgba(225,29,72,0.5)] transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-mace-500/30 overflow-hidden relative group">
                        <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                        <span class="relative flex items-center">
                            Submit Registration
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-4 font-medium flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Secure 256-bit encrypted submission
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    function toggleAdmissionDetails() {
        const isAdmitted = document.querySelector('input[name="admitted_elsewhere"]:checked').value;
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
                el.value = ''; // clear values if toggled off
            });
        }
    }

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
                    container.classList.remove('border-slate-200/60', 'bg-white/60');
                } else {
                    values.add(select.value);
                    container.classList.remove('border-red-400', 'bg-red-50');
                    container.classList.add('border-slate-200/60', 'bg-white/60');
                }
            } else {
                container.classList.remove('border-red-400', 'bg-red-50');
                container.classList.add('border-slate-200/60', 'bg-white/60');
            }
        });

        if (hasDuplicate) {
            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-not-allowed', 'saturate-50');
            btn.classList.remove('hover:-translate-y-1', 'hover:shadow-[0_15px_35px_-5px_rgba(225,29,72,0.5)]');
            errorMsg.classList.remove('hidden');
        } else {
            btn.disabled = false;
            btn.classList.remove('opacity-60', 'cursor-not-allowed', 'saturate-50');
            btn.classList.add('hover:-translate-y-1', 'hover:shadow-[0_15px_35px_-5px_rgba(225,29,72,0.5)]');
            errorMsg.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleAdmissionDetails();
        validateBranches();
    });
</script>

<?= $this->endSection() ?>
