<?= $this->extend('layouts/student_layout') ?>
<?= $this->section('content') ?>

<div class="w-full relative animate-[fadeIn_0.5s_ease-out]">
    <div class="glass-panel rounded-md overflow-hidden relative">
        <div class="p-8 md:p-12">
            <div class="mb-8 flex justify-between items-start">
                <div>
                    <span class="text-mace-600 font-bold tracking-wider text-sm uppercase">Step 5 of 5</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Review & Submit</h2>
                    <p class="text-slate-500 mt-1 font-medium text-sm">Please verify all your details before final submission.</p>
                </div>
                <div class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-mono border border-slate-200 shadow-inner">
                    App No: <span class="font-bold text-slate-900"><?= esc($app['application_no']) ?></span>
                </div>
            </div>

            <div class="bg-white/80 rounded-md border border-slate-200 overflow-hidden mb-8 shadow-sm">
                <?php
                $cats = ['SM'=>'State Merit (SM)','EWS'=>'EWS','EZ'=>'Ezhava (EZ)','MU'=>'Muslim (MU)','BH'=>'Other Backward Hindu (BH)','LA'=>'Latin Catholic and Anglo Indian (LA)','BX'=>'Other Backward Christian (BX)','KU'=>'Kudumbi (KU)','VK'=>'Viswakarma and related communities (VK)','DV'=>'Dheevara and related communities (DV)','KN'=>'Kusavan and related communities (KN)','SC'=>'Scheduled Castes (SC)','ST'=>'Scheduled Tribes (ST)','OEC'=>'OEC','XS'=>'Ex-servicemen (XS)','PI'=>'PI','PT'=>'PT','TFW'=>'Tuition Fee Waiver (TFW)'];
                $branches = [
                    'AI' => 'Artificial Intelligence & Machine Learning (AI)', 
                    'CE' => 'Civil Engineering (CE)', 
                    'CSE'=> 'Computer Science and Engineering (CSE)', 
                    'DS' => 'Computer Science and Engineering (Data Science) (DS)', 
                    'EEE'=> 'Electrical and Electronics Engineering (EEE)', 
                    'ECE'=> 'Electronics and Communication Engineering (ECE)', 
                    'ME' => 'Mechanical Engineering (ME)'
                ];
                $catDisplay = $cats[$app['eligible_category']] ?? $app['eligible_category'];
                ?>
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">1. Personal & KEAM Details</h3>
                    <a href="<?= base_url('register/step1/'.$app['application_no']) ?>" class="text-xs font-semibold text-mace-600 hover:underline">Edit</a>
                </div>
                <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-y-4 text-sm">
                    <div><span class="text-slate-500 block text-xs font-bold uppercase tracking-wider mb-1">Name</span><span class="font-semibold text-slate-900"><?= esc($app['full_name']) ?></span></div>
                    <div><span class="text-slate-500 block text-xs font-bold uppercase tracking-wider mb-1">Mobile</span><span class="font-semibold text-slate-900"><?= esc($app['mobile_no']) ?></span></div>
                    <div><span class="text-slate-500 block text-xs font-bold uppercase tracking-wider mb-1">Category</span><span class="font-semibold text-slate-900"><?= esc($catDisplay) ?></span></div>
                    <div><span class="text-slate-500 block text-xs font-bold uppercase tracking-wider mb-1">Entrance Roll No</span><span class="font-mono font-bold text-mace-700"><?= esc($app['entrance_roll_no']) ?></span></div>
                    <div><span class="text-slate-500 block text-xs font-bold uppercase tracking-wider mb-1">KEAM Rank</span><span class="font-mono font-bold text-mace-700"><?= esc($app['entrance_rank']) ?></span></div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-y border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">2. Current Admission</h3>
                    <a href="<?= base_url('register/step2/'.$app['application_no']) ?>" class="text-xs font-semibold text-mace-600 hover:underline">Edit</a>
                </div>
                <div class="p-6 text-sm">
                    <?php if($app['admitted_elsewhere'] === '1'): ?>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="col-span-2"><span class="text-slate-500 block text-xs font-bold uppercase tracking-wider mb-1">College</span><span class="font-semibold text-slate-900"><?= esc($app['present_college']) ?></span></div>
                            <div class="col-span-2"><span class="text-slate-500 block text-xs font-bold uppercase tracking-wider mb-1">Branch</span><span class="font-semibold text-slate-900"><?= esc($app['present_branch']) ?></span></div>
                            <div><span class="text-slate-500 block text-xs font-bold uppercase tracking-wider mb-1">Has NOC</span><span class="font-semibold text-slate-900"><?= $app['has_noc']==='1' ? 'Yes' : 'No' ?></span></div>
                            <div><span class="text-slate-500 block text-xs font-bold uppercase tracking-wider mb-1">Has TC/CC</span><span class="font-semibold text-slate-900"><?= $app['has_tc_cc']==='1' ? 'Yes' : 'No' ?></span></div>
                        </div>
                    <?php else: ?>
                        <p class="text-slate-600 italic">Not admitted in any engineering college.</p>
                    <?php endif; ?>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-y border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">3. Branch Preferences</h3>
                    <a href="<?= base_url('register/step3/'.$app['application_no']) ?>" class="text-xs font-semibold text-mace-600 hover:underline">Edit</a>
                </div>
                <div class="p-6 text-sm">
                    <ol class="list-decimal pl-5 space-y-1 font-semibold text-slate-800">
                        <?php for($i=1; $i<=7; $i++): ?>
                            <?php if(!empty($app["option_$i"])): ?>
                                <?php $brCode = $app["option_$i"]; $brDisplay = $branches[$brCode] ?? $brCode; ?>
                                <li><?= esc($brDisplay) ?></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </ol>
                    <?php if(empty($app['option_1'])): ?>
                        <p class="text-slate-600 italic">No preferences selected.</p>
                    <?php endif; ?>
                </div>
            </div>

            <form action="<?= base_url('register/final_submit') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="application_no" value="<?= esc($app['application_no']) ?>">
                
                <div class="pt-2 flex justify-between items-center">
                    <a href="<?= base_url("register/step4/".$app['application_no']) ?>" class="text-slate-500 font-medium hover:text-slate-800 transition-colors">
                        &larr; Back
                    </a>
                    
                    <button type="submit" class="w-2/3 md:w-auto flex justify-center items-center py-4 px-8 rounded-md text-lg font-bold text-white bg-nic-blue hover:bg-nic-darkblue transition-all duration-300 border border-nic-darkblue shadow-none hover:shadow-none hover:transform-none">
                        Confirm & Submit
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
