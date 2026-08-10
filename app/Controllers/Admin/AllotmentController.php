<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AllotmentModel;

class AllotmentController extends BaseController
{
    private $cats = ['SM'=>'State Merit (SM)','EWS'=>'EWS','EZ'=>'Ezhava (EZ)','MU'=>'Muslim (MU)','BH'=>'Other Backward Hindu (BH)','LA'=>'Latin Catholic and Anglo Indian (LA)','BX'=>'Other Backward Christian (BX)','KU'=>'Kudumbi (KU)','VK'=>'Viswakarma and related communities (VK)','DV'=>'Dheevara and related communities (DV)','KN'=>'Kusavan and related communities (KN)','SC'=>'Scheduled Castes (SC)','ST'=>'Scheduled Tribes (ST)','OEC'=>'OEC','XS'=>'Ex-servicemen (XS)','PI'=>'PI','PT'=>'PT'];
    private $branches = [
        'AI' => 'Artificial Intelligence & Machine Learning (AI)', 
        'CE' => 'Civil Engineering (CE)', 
        'CSE'=> 'Computer Science and Engineering (CSE)', 
        'DS' => 'Computer Science and Engineering (Data Science) (DS)', 
        'EEE'=> 'Electrical and Electronics Engineering (EEE)', 
        'ECE'=> 'Electronics and Communication Engineering (ECE)', 
        'ME' => 'Mechanical Engineering (ME)'
    ];

    public function index()
    {
        // Initial page load is fast; data fetched via AJAX
        return view('admin/allotment');
    }

    public function ranklist()
    {
        return view('admin/ranklist');
    }

    public function fetchRanklist()
    {
        $branch = $this->request->getGet('branch');
        $category = $this->request->getGet('category');
        if (!$branch) return $this->response->setJSON(['applicants' => []]);

        $model = new AllotmentModel();
        $applicants = $model->getByBranch($branch, $category ?? '');

        foreach ($applicants as &$app) {
            $pref = null;
            for ($i = 1; $i <= 7; $i++) {
                if ($app['option_' . $i] === $branch) {
                    $pref = $i;
                    break;
                }
            }
            $app['pref_no'] = $pref;
        }

        return $this->response->setJSON(['applicants' => $applicants]);
    }

    public function admit()
    {
        $id = $this->request->getPost('id');
        $branch = $this->request->getPost('branch');

        if (!$id || !$branch) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid data']);
        }

        $model = new AllotmentModel();
        $model->update($id, ['allotted_course' => $branch]);

        return $this->response->setJSON(['success' => true]);
    }

    public function unadmit()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid data']);
        }

        $model = new AllotmentModel();
        $model->update($id, ['allotted_course' => null]);

        return $this->response->setJSON(['success' => true]);
    }

    public function fetch()
    {
        $model = new AllotmentModel();
        
        $category = $this->request->getGet('category') ?? 'SM';
        $search   = $this->request->getGet('search');
        $page     = (int) ($this->request->getGet('page') ?? 1);
        $sortBy   = $this->request->getGet('sort_by') ?? 'entrance_rank';
        $sortDir  = strtoupper($this->request->getGet('sort_dir') ?? 'ASC');
        
        // Allowed sort columns
        $allowedSorts = ['entrance_rank', 'entrance_roll_no', 'full_name', 'eligible_category', 'time_of_reporting'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'entrance_rank';
        }
        if ($sortDir !== 'ASC' && $sortDir !== 'DESC') {
            $sortDir = 'ASC';
        }

        $limit    = 200;
        $offset   = ($page - 1) * $limit;
        
        $applicants = [];
        
        if (!empty($search)) {
            // Check if search is likely a rank (numeric and no letters) or roll no
            if (is_numeric($search) && strlen($search) < 7) {
                $applicants = $model->searchByRank((int)$search);
            } else {
                $applicants = $model->searchByRollNo(strtoupper($search));
            }
        } else {
            if ($category === 'SM') {
                $applicants = $model->getAll($limit, $offset, $sortBy, $sortDir);
            } else {
                $applicants = $model->getByCategory($category, $limit, $offset, $sortBy, $sortDir);
            }
        }
        
        return $this->response->setJSON([
            'applicants' => $applicants,
            'category'   => $category,
            'search'     => $search,
            'page'       => $page,
            'limit'      => $limit
        ]);
    }
    
    public function export()
    {
        $category = $this->request->getGet('category') ?? 'SM';
        $categoryLabel = $category === 'SM' ? 'State Merit (SM) - All Students' : ($this->cats[$category] ?? $category);
        
        $db = \Config\Database::connect();
        $builder = $db->table('spot_registrations')
                      ->select('entrance_rank, entrance_roll_no, full_name, mobile_no, email, eligible_category, admitted_elsewhere, present_college, present_branch, has_noc, has_tc_cc, option_1, option_2, option_3, option_4, option_5, option_6, option_7, registered_at');
        
        if ($category !== 'SM') {
            $builder->where('eligible_category', $category);
        }
        $builder->orderBy('entrance_rank', 'ASC');
        
        $query = $builder->get();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="mace_spot_export_' . $category . '_' . date('Ymd_His') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $out = fopen('php://output', 'w');
        
        // ── Metadata Header ──
        fputcsv($out, ['Mar Athanasius College of Engineering, Kothamangalam']);
        fputcsv($out, ['B.Tech Spot Admission 2026']);
        fputcsv($out, ['Applicant List']);
        fputcsv($out, ['Category:', $categoryLabel]);
        fputcsv($out, ['Date of Export:', date('d-M-Y H:i:s')]);
        fputcsv($out, ['Generated by:', 'MACE Admission Portal (macesoft.in)']);
        fputcsv($out, []); // blank separator row
        
        // ── Column Headers ──
        fputcsv($out, [
            'Rank', 'Roll No', 'Name', 'Mobile', 'Email', 'Category', 
            'Admitted Elsewhere', 'Present College', 'Present Branch', 
            'Has NOC', 'Has TC/CC', 
            'Opt 1', 'Opt 2', 'Opt 3', 'Opt 4', 'Opt 5', 'Opt 6', 'Opt 7', 
            'Registered At'
        ]);
        
        foreach ($query->getResultArray() as $row) {
            $row['admitted_elsewhere'] = $row['admitted_elsewhere'] ? 'Yes' : 'No';
            $row['has_noc'] = $row['has_noc'] !== null ? ($row['has_noc'] ? 'Yes' : 'No') : 'N/A';
            $row['has_tc_cc'] = $row['has_tc_cc'] !== null ? ($row['has_tc_cc'] ? 'Yes' : 'No') : 'N/A';
            $row['eligible_category'] = $this->cats[$row['eligible_category']] ?? $row['eligible_category'];
            
            for($i = 1; $i <= 7; $i++) {
                if(!empty($row["option_$i"])) {
                    $row["option_$i"] = $this->branches[$row["option_$i"]] ?? $row["option_$i"];
                }
            }
            
            fputcsv($out, $row);
        }
        
        fclose($out);
        exit();
    }

    public function exportPdf()
    {
        $category = $this->request->getGet('category') ?? 'SM';
        $categoryLabel = $category === 'SM' ? 'State Merit (SM) - All Students' : ($this->cats[$category] ?? $category);
        
        $db = \Config\Database::connect();
        $builder = $db->table('spot_registrations')
                      ->select('entrance_rank, entrance_roll_no, full_name, mobile_no, eligible_category, time_of_reporting, option_1, option_2, option_3, option_4, option_5, option_6, option_7')
                      ->where('status', 'submitted');
        
        if ($category !== 'SM') {
            $builder->where('eligible_category', $category);
        }
        $builder->orderBy('entrance_rank', 'ASC');
        
        $query = $builder->get();
        $applicants = [];
        
        while ($row = $query->getUnbufferedRow('array')) {
            $options = [];
            for ($i = 1; $i <= 7; $i++) {
                if (!empty($row['option_' . $i])) {
                    $brCode = $row['option_' . $i];
                    $brDisplay = $this->branches[$brCode] ?? $brCode;
                    $options[] = "<strong>$i:</strong> " . $brDisplay;
                }
            }
            
            $applicants[] = [
                'rank' => $row['entrance_rank'],
                'roll_no' => $row['entrance_roll_no'],
                'name' => $row['full_name'],
                'category' => $this->cats[$row['eligible_category']] ?? $row['eligible_category'],
                'options' => implode('<br>', $options)
            ];
        }

        $html = view('admin/pdf_applicants', [
            'categoryLabel' => $categoryLabel,
            'applicants' => $applicants,
            'date' => date('d-M-Y H:i:s')
        ]);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $dompdf->stream('mace_applicants_' . $category . '_' . date('Ymd_His') . '.pdf', ["Attachment" => false]);
        exit();
    }

    public function toggleRegistration()
    {
        $settingsPath = WRITEPATH . 'settings.json';
        $settings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : ['registration_closed' => false];
        
        $settings['registration_closed'] = !$settings['registration_closed'];
        file_put_contents($settingsPath, json_encode($settings));
        
        $status = $settings['registration_closed'] ? 'closed' : 'opened';
        return redirect()->back()->with('success', "New registrations have been $status.");
    }

    public function exportRanklistCsv()
    {
        $branch = $this->request->getGet('branch');
        $category = $this->request->getGet('category');
        if (!$branch) return redirect()->back();

        $categoryLabel = empty($category) ? 'All Categories' : ($this->cats[$category] ?? $category);
        $branchLabel = $this->branches[$branch] ?? $branch;

        $model = new AllotmentModel();
        $applicants = $model->getByBranch($branch, $category ?? '');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="mace_ranklist_' . $branch . '_' . ($category ?: 'ALL') . '_' . date('Ymd_His') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $out = fopen('php://output', 'w');
        
        // Metadata Header
        fputcsv($out, ['Mar Athanasius College of Engineering, Kothamangalam']);
        fputcsv($out, ['B.Tech Spot Admission 2026']);
        fputcsv($out, ['Branch Rank List']);
        fputcsv($out, ['Branch:', $branchLabel]);
        fputcsv($out, ['Category:', $categoryLabel]);
        fputcsv($out, ['Date of Export:', date('d-M-Y H:i:s')]);
        fputcsv($out, ['Generated by:', 'MACE Admission Portal (macesoft.in)']);
        fputcsv($out, []); // blank separator row
        
        // Column Headers (No Category)
        fputcsv($out, ['Rank', 'Roll No', 'Name', 'Mobile', 'Option Preference', 'Status']);
        
        foreach ($applicants as $row) {
            $pref = null;
            for ($i = 1; $i <= 7; $i++) {
                if ($row['option_' . $i] === $branch) {
                    $pref = $i; break;
                }
            }
            $status = '';
            if (!empty($row['allotted_course'])) {
                $status = ($row['allotted_course'] === $branch) ? 'Admitted' : 'Admitted to ' . $row['allotted_course'];
            }
            fputcsv($out, [
                $row['entrance_rank'],
                $row['entrance_roll_no'],
                $row['full_name'],
                $row['mobile_no'],
                $pref,
                $status
            ]);
        }
        
        fclose($out);
        exit();
    }

    public function exportRanklistPdf()
    {
        $branch = $this->request->getGet('branch');
        $category = $this->request->getGet('category');
        if (!$branch) return redirect()->back();

        $categoryLabel = empty($category) ? 'All Categories' : ($this->cats[$category] ?? $category);
        $branchLabel = $this->branches[$branch] ?? $branch;

        $model = new AllotmentModel();
        $dbApplicants = $model->getByBranch($branch, $category ?? '');

        $applicants = [];
        foreach ($dbApplicants as $row) {
            $pref = null;
            for ($i = 1; $i <= 7; $i++) {
                if ($row['option_' . $i] === $branch) {
                    $pref = $i; break;
                }
            }
            $status = '';
            if (!empty($row['allotted_course'])) {
                $status = ($row['allotted_course'] === $branch) ? 'Admitted' : 'Admitted to ' . $row['allotted_course'];
            }
            
            $applicants[] = [
                'rank' => $row['entrance_rank'],
                'roll_no' => $row['entrance_roll_no'],
                'name' => $row['full_name'],
                'mobile' => $row['mobile_no'],
                'pref' => $pref,
                'status' => $status
            ];
        }

        $html = view('admin/pdf_ranklist', [
            'branch' => $branchLabel,
            'categoryLabel' => $categoryLabel,
            'applicants' => $applicants,
            'date' => date('d-M-Y H:i:s')
        ]);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $dompdf->stream('mace_ranklist_' . $branch . '_' . ($category ?: 'ALL') . '_' . date('Ymd_His') . '.pdf', ["Attachment" => false]);
        exit();
    }
}
