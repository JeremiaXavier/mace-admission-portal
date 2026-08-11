<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration Slip - <?= esc($data['application_no']) ?></title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 13px; margin: 0; padding: 0; color: #111; }
        .header { border-bottom: 3px solid #0a4275; padding-bottom: 15px; margin-bottom: 25px; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-decoration: underline; }
        
        .section-title { font-size: 14px; font-weight: bold; background-color: #f1f5f9; padding: 5px 10px; border-left: 4px solid #0a4275; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th, td { padding: 6px 10px; text-align: left; vertical-align: top; border-bottom: 1px solid #eee; }
        th { width: 35%; color: #555; }
        td { font-weight: bold; }
        
        .declaration { background-color: #f8fafc; border: 1px dashed #cbd5e1; padding: 15px; font-style: italic; font-size: 12px; margin-bottom: 60px; line-height: 1.5; }
        
        .footer-sig { width: 100%; margin-top: 50px; }
        .footer-sig td { border: none; padding: 0; }
        .sig-box { text-align: center; }
        .sig-line { border-top: 1px solid #000; width: 200px; margin: 0 auto 5px auto; }
        
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; font-size: 10px; text-align: center; border-top: 1px solid #cbd5e1; padding-top: 5px; color: #64748b; }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%; border: none; margin: 0; padding: 0;">
            <tr>
                <td style="width: 12%; text-align: left; border: none; padding: 0;">
                    <img src="https://macesoft.in/assets/admin/img/logo.png" alt="MACE Logo" style="height: 65px;">
                </td>
                <td style="width: 88%; text-align: center; border: none; padding: 0; padding-right: 12%;">
                    <h1 style="margin: 0; font-size: 19px; color: #0a4275; text-transform: uppercase;">Mar Athanasius College of Engineering, Kothamangalam</h1>
                    <h2 style="margin: 5px 0 0 0; font-size: 15px; color: #444;">B.Tech Spot Admission 2026</h2>
                </td>
            </tr>
        </table>
    </div>

    <div class="title">Registration Acknowledgment Slip</div>
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
    $catDisplay = $cats[$data['eligible_category']] ?? $data['eligible_category'];
    ?>

    <div class="section-title">1. Applicant Details</div>
    <table>
        <tr><th>Application No</th><td style="font-size: 16px; color: #0a4275;"><?= esc($data['application_no']) ?></td></tr>
        <tr><th>Full Name</th><td style="text-transform: uppercase;"><?= esc($data['full_name']) ?></td></tr>
        <tr><th>KEAM Roll Number</th><td><?= esc($data['entrance_roll_no']) ?></td></tr>
        <tr><th>KEAM State Rank</th><td><?= esc($data['entrance_rank']) ?></td></tr>
        <tr><th>Eligible Category</th><td><?= esc($catDisplay) ?></td></tr>
        <tr><th>Mobile Number</th><td><?= esc($data['mobile_no']) ?></td></tr>
        <tr><th>Email Address</th><td><?= esc($data['email']) ?></td></tr>
    </table>

    <div class="section-title">2. Current Admission Details</div>
    <table>
        <tr>
            <th>Admitted Elsewhere?</th>
            <td><?= $data['admitted_elsewhere'] === '1' ? 'YES' : 'NO' ?></td>
        </tr>
        <?php if($data['admitted_elsewhere'] === '1'): ?>
        <tr><th>Present College</th><td><?= esc($data['present_college']) ?></td></tr>
        <tr><th>Present Branch</th><td><?= esc($data['present_branch']) ?></td></tr>
        <tr><th>Has NOC?</th><td><?= $data['has_noc'] === '1' ? 'YES' : 'NO' ?></td></tr>
        <tr><th>Has TC/CC?</th><td><?= $data['has_tc_cc'] === '1' ? 'YES' : 'NO' ?></td></tr>
        <?php endif; ?>
    </table>

    <div class="section-title">3. Branch Preferences</div>
    <table>
        <?php 
        $hasPrefs = false;
        for($i=1; $i<=7; $i++): 
            if(!empty($data["option_$i"])):
                $hasPrefs = true;
                $brCode = $data["option_$i"];
                $brDisplay = $branches[$brCode] ?? $brCode;
        ?>
        <tr><th>Option <?= $i ?></th><td><?= esc($brDisplay) ?></td></tr>
        <?php 
            endif;
        endfor; 
        if(!$hasPrefs):
        ?>
        <tr><td colspan="2" style="font-weight: normal; font-style: italic;">No preferences selected.</td></tr>
        <?php endif; ?>
    </table>

    <div class="section-title">4. Declaration</div>
    <div class="declaration">
        "I hereby solemnly declare that the details furnished above are true and correct to the best of my knowledge and belief. I understand that my admission allocation is strictly provisional and subject to the physical verification of all original certificates at the admission desk."
    </div>

    <table class="footer-sig">
        <tr>
            <td style="width: 50%; text-align: left;">
                <div>Date: ____________________</div>
                <div style="margin-top: 15px;">Place: ___________________</div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="sig-box" style="float: right;">
                    <div class="sig-line"></div>
                    Signature of Applicant
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Generated by MACE Admission Portal (macesoft.in) &bull; Submitted on <?= $data['registered_at'] ?>
    </div>
</body>
</html>
