<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rank List - <?= esc($branch) ?> - <?= esc($categoryLabel) ?></title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; color: #333; }
        .header { border-bottom: 2px solid #0a4275; padding-bottom: 10px; margin-bottom: 20px; }
        .header img { height: 60px; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 19px; color: #0a4275; text-transform: uppercase; font-weight: bold; }
        .header h2 { margin: 5px 0 0 0; font-size: 15px; color: #444; }
        
        .info-box { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 10px; margin-bottom: 20px; font-size: 12px; border-radius: 4px; }
        .info-box strong { color: #0f172a; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; }
        th { background-color: #f1f5f9; font-weight: bold; color: #0f172a; text-transform: uppercase; font-size: 11px; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; }
        .signature-table { width: 100%; border: none; margin-top: 50px; margin-bottom: 20px; page-break-inside: avoid; }
        .signature-table td { border: none; font-weight: bold; font-size: 14px; color: #333; padding: 0; }
        .footer-credits { font-size: 10px; text-align: center; border-top: 1px solid #cbd5e1; padding-top: 5px; color: #64748b; }
        
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%; border: none; margin: 0; padding: 0;">
            <tr>
                <td style="width: 12%; text-align: left; border: none; vertical-align: middle; padding: 0;">
                    <img src="https://macesoft.in/assets/admin/img/logo.png" alt="MACE Logo" style="height: 65px; margin: 0;">
                </td>
                <td style="width: 88%; text-align: center; border: none; vertical-align: middle; padding: 0; padding-right: 12%;">
                    <h1 style="margin: 0; font-size: 19px; color: #0a4275; text-transform: uppercase; font-weight: bold;">Mar Athanasius College of Engineering, Kothamangalam</h1>
                    <h2 style="margin: 5px 0 0 0; font-size: 15px; color: #444;">B.Tech Spot Admission 2026</h2>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-box">
        <table style="border:none; margin:0; width:100%;">
            <tr>
                <td style="border:none; padding:2px;"><strong>Branch / Course:</strong> <?= esc($branch) ?></td>
                <td style="border:none; padding:2px;" class="text-right"><strong>Export Date:</strong> <?= esc($date) ?></td>
            </tr>
            <tr>
                <td style="border:none; padding:2px;"><strong>Category:</strong> <?= esc($categoryLabel) ?></td>
                <td style="border:none; padding:2px;" class="text-right"><strong>Page:</strong> <span class="page-number"></span></td>
            </tr>
            <tr>
                <td style="border:none; padding:2px;"><strong>Total Applicants:</strong> <?= count($applicants) ?></td>
                <td style="border:none; padding:2px;" class="text-right"></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%" class="text-center">Rank</th>
                <th width="15%">Roll No</th>
                <th width="30%">Applicant Name</th>
                <th width="15%">Mobile</th>
                <th width="15%" class="text-center">Option Pref</th>
                <th width="15%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($applicants)): ?>
            <tr><td colspan="6" class="text-center" style="padding:20px;">No applicants found.</td></tr>
            <?php else: ?>
                <?php foreach($applicants as $app): ?>
                <tr>
                    <td class="text-center"><strong><?= esc($app['rank']) ?></strong></td>
                    <td><?= esc($app['roll_no']) ?></td>
                    <td><?= esc($app['name']) ?></td>
                    <td><?= esc($app['mobile']) ?></td>
                    <td class="text-center"><strong><?= esc($app['pref']) ?></strong></td>
                    <td class="text-center"><?= esc($app['status']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td style="text-align: left; padding-left: 20px;">College Seal</td>
            <td style="text-align: right; padding-right: 20px;">Principal Signature</td>
        </tr>
    </table>

    <div class="footer">
        <div class="footer-credits">
            Generated by MACE Admission Portal (macesoft.in)
        </div>
    </div>

</body>
</html>
