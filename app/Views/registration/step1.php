<?= $this->extend('layouts/student_layout') ?>
<?= $this->section('content') ?>

<div class="w-full relative animate-[fadeIn_0.5s_ease-out]">
    <div class="glass-panel rounded-md overflow-hidden relative">
        <div class="p-8 md:p-12">
            <div class="mb-8">
                <span class="text-mace-600 font-bold tracking-wider text-sm uppercase">Step 1 of 5</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Basic & KEAM Details</h2>
                <p class="text-slate-500 mt-1 font-medium">Please provide your primary academic information.</p>
            </div>

            <?php if(isset($errors) && !empty($errors)): ?>
                <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200 rounded-xl text-sm">
                    <ul class="list-disc list-inside">
                        <?php foreach($errors as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <form action="<?= base_url('register/step1_submit') ?>" method="POST" class="space-y-8" id="step1Form">
                <?= csrf_field() ?>
                <?php if(isset($app)): ?>
                    <input type="hidden" name="application_no" id="app_no" value="<?= esc($app['application_no']) ?>">
                <?php else: ?>
                    <input type="hidden" id="app_no" value="">
                <?php endif; ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Full Name <span class="text-mace-500">*</span></label>
                        <input type="text" name="full_name" required value="<?= set_value('full_name', $app['full_name'] ?? '') ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl px-4 py-3 text-slate-800" placeholder="As per KEAM records">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">WhatsApp Mobile No <span class="text-mace-500">*</span></label>
                        <input type="text" name="mobile_no" id="mobile_no" required pattern="[0-9]{10}" maxlength="10" value="<?= set_value('mobile_no', $app['mobile_no'] ?? '') ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl px-4 py-3 text-slate-800" placeholder="10 Digit Mobile No">
                        <p id="mobile_no_err" class="hidden text-xs font-semibold text-red-600 mt-1">&#9888; This mobile number is already registered.</p>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Email Address <span class="text-mace-500">*</span></label>
                        <input type="email" name="email" id="email" required value="<?= set_value('email', $app['email'] ?? '') ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl px-4 py-3 text-slate-800">
                        <p id="email_err" class="hidden text-xs font-semibold text-red-600 mt-1">&#9888; This email address is already registered.</p>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Time of Reporting <span class="text-mace-500">*</span></label>
                        <?php 
                        $timeVal = set_value('time_of_reporting', $app['time_of_reporting'] ?? '');
                        if (empty($timeVal)) {
                            $timeVal = date('H:i');
                        }
                        ?>
                        <input type="time" name="time_of_reporting" required value="<?= $timeVal ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl px-4 py-3 text-slate-800">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Entrance Roll Number <span class="text-mace-500">*</span></label>
                        <input type="text" name="entrance_roll_no" required value="<?= set_value('entrance_roll_no', $app['entrance_roll_no'] ?? '') ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 uppercase font-mono">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700">KEAM State Rank <span class="text-mace-500">*</span></label>
                        <input type="number" name="entrance_rank" id="entrance_rank" required min="1" value="<?= set_value('entrance_rank', $app['entrance_rank'] ?? '') ?>" class="input-premium w-full bg-white/70 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 font-mono">
                        <p id="entrance_rank_err" class="hidden text-xs font-semibold text-red-600 mt-1">&#9888; This KEAM rank is already registered by another student.</p>
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700">Claimed Category <span class="text-mace-500">*</span></label>
                        <select name="eligible_category" required class="input-premium w-full bg-white/90 border border-slate-300 rounded-xl px-4 py-3 text-slate-800">
                            <option value="" disabled selected>Select Category...</option>
                            <?php 
                            $cats = ['SM'=>'State Merit (SM)','EWS'=>'EWS','EZ'=>'Ezhava (EZ)','MU'=>'Muslim (MU)','BH'=>'Other Backward Hindu (BH)','LA'=>'Latin Catholic and Anglo Indian (LA)','BX'=>'Other Backward Christian (BX)','KU'=>'Kudumbi (KU)','VK'=>'Viswakarma and related communities (VK)','DV'=>'Dheevara and related communities (DV)','KN'=>'Kusavan and related communities (KN)','SC'=>'Scheduled Castes (SC)','ST'=>'Scheduled Tribes (ST)','OEC'=>'OEC','XS'=>'Ex-servicemen (XS)','PI'=>'PI','PT'=>'PT'];
                            foreach($cats as $code => $label): 
                                $sel = set_value('eligible_category', $app['eligible_category'] ?? '') === $code ? 'selected' : '';
                            ?>
                                <option value="<?= $code ?>" <?= $sel ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" id="submitBtn" class="bg-mace-600 hover:bg-mace-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center">
                        Next Step
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const CHECK_URL = '<?= base_url('register/check_unique') ?>';
const APP_NO    = document.getElementById('app_no')?.value || '';

function checkField(field, value, errId) {
    const errEl = document.getElementById(errId);
    if (!value) { errEl.classList.add('hidden'); return; }
    fetch(`${CHECK_URL}?field=${field}&value=${encodeURIComponent(value)}&app_no=${encodeURIComponent(APP_NO)}`)
        .then(r => r.json())
        .then(data => { errEl.classList.toggle('hidden', !data.taken); })
        .catch(() => {});
}

// All three checks fire only when user loses focus (blur)
document.getElementById('mobile_no').addEventListener('blur', function() {
    if (this.value) checkField('mobile_no', this.value, 'mobile_no_err');
});

document.getElementById('email').addEventListener('blur', function() {
    if (this.value) checkField('email', this.value, 'email_err');
});

document.getElementById('entrance_rank').addEventListener('blur', function() {
    if (this.value > 0) checkField('entrance_rank', this.value, 'entrance_rank_err');
});

// Block form submit if any inline error is visible
document.getElementById('step1Form').addEventListener('submit', function(e) {
    const errorIds = ['mobile_no_err', 'email_err', 'entrance_rank_err'];
    for (const id of errorIds) {
        if (!document.getElementById(id).classList.contains('hidden')) {
            e.preventDefault();
            document.getElementById(id).scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
    }
});
</script>

<?= $this->endSection() ?>
