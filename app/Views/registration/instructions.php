<?= $this->extend('layouts/student_layout') ?>
<?= $this->section('content') ?>

<div class="w-full relative animate-[fadeIn_0.5s_ease-out]">
    <div class="glass-panel rounded-md overflow-hidden relative shadow-sm">
        
        <div class="p-8 md:p-12">
            <div class="mb-8 border-b border-gray-200 pb-4">
                <h2 class="text-3xl font-bold text-nic-darkblue mt-1">Instructions for Candidates</h2>
                <p class="text-gray-600 mt-2 font-medium">Please read the following instructions carefully before proceeding with your registration.</p>
            </div>

            <div class="space-y-8 text-gray-700 text-sm md:text-base leading-relaxed">
                
                <section>
                    <h3 class="text-xl font-bold text-nic-blue mb-3 flex items-center">
                        <span class="w-8 h-8 rounded bg-nic-lightblue text-nic-darkblue flex items-center justify-center mr-3 text-sm">1</span>
                        Important Prerequisites
                    </h3>
                    <ul class="list-disc pl-11 space-y-2">
                        <li>Candidates must have a valid KEAM Rank and Roll Number.</li>
                        <li>Keep all original certificates (10th, 12th, TC, CC, Category Certificate if applicable) ready for physical verification.</li>
                        <li>The entire registration process is divided into 5 simple steps.</li>
                    </ul>
                </section>

                <section>
                    <h3 class="text-xl font-bold text-nic-blue mb-3 flex items-center">
                        <span class="w-8 h-8 rounded bg-nic-lightblue text-nic-darkblue flex items-center justify-center mr-3 text-sm">2</span>
                        Step-by-Step Registration Process
                    </h3>
                    
                    <div class="pl-11 space-y-6">
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Step 1: Basic & KEAM Details</h4>
                            <p>Enter your full name, mobile number (WhatsApp preferred), category, KEAM Roll No, and State Rank. Please ensure your mobile number is correct, as you can use it to restore your application if needed.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Step 2: Current Admission Status</h4>
                            <p>Specify whether you are currently admitted to any other engineering college. If yes, you must provide the college name, branch, and specify if you hold the NOC and TC/CC.</p>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Step 3: Branch Preferences</h4>
                            <p>Select your desired engineering branches in descending order of priority (Preference 1 being your top choice). You can choose up to 7 options. Do not duplicate choices.</p>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Step 4: Declaration</h4>
                            <p>Read the declaration statement carefully and check the box to confirm that the details provided are authentic.</p>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Step 5: Review & Submit</h4>
                            <p>Verify all your entered details. If any corrections are needed, use the 'Edit' buttons. Once satisfied, click 'Confirm & Submit'. <strong>After submission, no changes can be made.</strong></p>
                        </div>
                    </div>
                </section>

                <section>
                    <h3 class="text-xl font-bold text-nic-blue mb-3 flex items-center">
                        <span class="w-8 h-8 rounded bg-nic-lightblue text-nic-darkblue flex items-center justify-center mr-3 text-sm">3</span>
                        Resuming an Incomplete Application
                    </h3>
                    <div class="pl-11 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-md">
                        <p class="font-semibold text-yellow-800">Connection Lost or Window Closed by Mistake?</p>
                        <p class="mt-2 text-yellow-700">If your registration is interrupted, you do not need to start over. Go to the Home page and use the <strong>"Restore Application"</strong> feature. Enter your registered Mobile Number, and you will be securely redirected to the exact step you left off.</p>
                    </div>
                </section>

            </div>

            <div class="mt-10 pt-6 border-t border-gray-200 text-center">
                <a href="<?= base_url('register') ?>" class="inline-block bg-nic-blue hover:bg-nic-darkblue text-white font-bold py-3 px-10 rounded-md transition-colors shadow-sm">
                    Proceed to Registration
                </a>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
