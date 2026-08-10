<?php

namespace App\Controllers;

use Config\Database;

class RegistrationController extends BaseController
{
    private function getDb() {
        return Database::connect();
    }

    private function getApp($app_no) {
        if (empty($app_no)) return null;
        return $this->getDb()->table('spot_registrations')->where('application_no', $app_no)->get()->getRowArray();
    }

    public function instructions()
    {
        return view('registration/instructions');
    }

    public function start()
    {
        $settingsPath = WRITEPATH . 'settings.json';
        $settings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : ['registration_closed' => false];
        return view('registration/start', ['registration_closed' => $settings['registration_closed'] ?? false]);
    }

    public function checkUnique()
    {
        $field  = $this->request->getGet('field');
        $value  = trim($this->request->getGet('value') ?? '');
        $app_no = $this->request->getGet('app_no'); // exclude self when editing

        $allowed = ['mobile_no', 'email', 'entrance_rank'];
        if (!in_array($field, $allowed) || $value === '') {
            return $this->response->setJSON(['taken' => false]);
        }

        $builder = $this->getDb()->table('spot_registrations')->where($field, $value);
        if ($app_no) {
            $builder->where('application_no !=', $app_no);
        }
        $exists = $builder->countAllResults();

        return $this->response->setJSON(['taken' => $exists > 0]);
    }

    public function restore()
    {
        $mobile_no = $this->request->getPost('mobile_no');
        
        $app = $this->getDb()->table('spot_registrations')
                    ->where('mobile_no', $mobile_no)
                    ->get()->getRowArray();

        if (!$app) {
            return redirect()->back()->with('error', 'No application found with this mobile number. Please check and try again.');
        }

        $app_no = $app['application_no'];

        if ($app['status'] === 'submitted') {
            return redirect()->to("/register/confirmation/$app_no");
        }

        // Determine next incomplete step
        if ($app['present_college'] === null && $app['admitted_elsewhere'] === null) {
            return redirect()->to("/register/step2/$app_no");
        }
        if ($app['option_1'] === null) {
            return redirect()->to("/register/step3/$app_no");
        }
        if ($app['declaration'] === null) {
            return redirect()->to("/register/step4/$app_no");
        }
        
        return redirect()->to("/register/summary/$app_no");
    }

    public function step1($app_no = null)
    {
        // Try getting from GET if not in segment (for backwards compatibility if needed)
        if (!$app_no) $app_no = $this->request->getGet('application_no');
        
        $app = null;

        if ($app_no) {
            $app = $this->getApp($app_no);
            if (!$app) return redirect()->to('register')->with('error', 'Invalid Application Number');
            if ($app['status'] === 'submitted') {
                return redirect()->to("register/confirmation/$app_no");
            }
        } else {
            // Check if new registrations are closed
            $settingsPath = WRITEPATH . 'settings.json';
            $settings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : ['registration_closed' => false];
            if ($settings['registration_closed'] ?? false) {
                return redirect()->to('register')->with('error', 'New registrations are currently closed. You can only resume an existing application.');
            }
        }

        return view('registration/step1', ['app' => $app]);
    }

    public function step1_submit()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'full_name'         => 'required|max_length[150]',
            'mobile_no'         => 'required|max_length[15]',
            'email'             => 'required|valid_email|max_length[150]',
            'time_of_reporting' => 'required',
            'entrance_roll_no'  => 'required|max_length[20]',
            'entrance_rank'     => 'required|is_natural_no_zero',
            'eligible_category' => 'required'
        ]);

        $app_no = $this->request->getPost('application_no');
        $db = $this->getDb();

        if (!$validation->withRequest($this->request)->run()) {
            $app = $app_no ? $this->getApp($app_no) : null;
            return view('registration/step1', ['errors' => $validation->getErrors(), 'app' => $app]);
        }
        
        $roll_no  = $this->request->getPost('entrance_roll_no');
        $mobile   = $this->request->getPost('mobile_no');
        $rank     = $this->request->getPost('entrance_rank');

        // Helper: check uniqueness excluding self when editing
        $isDuplicate = function(string $field, $value) use ($db, $app_no): bool {
            $b = $db->table('spot_registrations')->where($field, $value);
            if ($app_no) $b->where('application_no !=', $app_no);
            return $b->countAllResults() > 0;
        };

        $app = $app_no ? $this->getApp($app_no) : null;

        if ($isDuplicate('entrance_roll_no', $roll_no)) {
            return view('registration/step1', ['errors' => ['This Entrance Roll Number is already registered. Please use Restore Application.'], 'app' => $app]);
        }
        if ($isDuplicate('mobile_no', $mobile)) {
            return view('registration/step1', ['errors' => ['This Mobile Number is already registered. Please use Restore Application.'], 'app' => $app]);
        }
        if ($isDuplicate('entrance_rank', $rank)) {
            return view('registration/step1', ['errors' => ['This KEAM Rank is already registered by another student.'], 'app' => $app]);
        }

        $data = [
            'full_name'         => $this->request->getPost('full_name'),
            'mobile_no'         => $this->request->getPost('mobile_no'),
            'email'             => $this->request->getPost('email'),
            'time_of_reporting' => $this->request->getPost('time_of_reporting'),
            'entrance_roll_no'  => $this->request->getPost('entrance_roll_no'),
            'entrance_rank'     => $this->request->getPost('entrance_rank'),
            'eligible_category' => $this->request->getPost('eligible_category'),
        ];

        if ($app_no) {
            $db->table('spot_registrations')->where('application_no', $app_no)->update($data);
        } else {
            // Generate 8 digit application no
            do {
                $app_no = str_pad((string) mt_rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
                $check = $db->table('spot_registrations')->where('application_no', $app_no)->get()->getRowArray();
            } while ($check);
            
            $data['application_no'] = $app_no;
            $data['status'] = 'draft';
            $data['registered_at'] = date('Y-m-d H:i:s');
            
            $db->table('spot_registrations')->insert($data);
        }
        
        return redirect()->to("/register/step2/$app_no");
    }

    public function step2($app_no)
    {
        $app = $this->getApp($app_no);
        if (!$app) return redirect()->to('/register');
        if ($app['status'] === 'submitted') return redirect()->to("/register/confirmation/$app_no");
        return view('registration/step2', ['app' => $app]);
    }

    public function step2_submit()
    {
        $app_no = $this->request->getPost('application_no');
        $admitted = $this->request->getPost('admitted_elsewhere');
        
        $data = ['admitted_elsewhere' => $admitted];
        
        if ($admitted == '1') {
            $data['present_college'] = $this->request->getPost('present_college');
            $data['present_branch']  = $this->request->getPost('present_branch');
            $data['has_noc']         = $this->request->getPost('has_noc');
            $data['has_tc_cc']       = $this->request->getPost('has_tc_cc');
        } else {
            $data['present_college'] = null;
            $data['present_branch']  = null;
            $data['has_noc']         = null;
            $data['has_tc_cc']       = null;
        }

        $this->getDb()->table('spot_registrations')->where('application_no', $app_no)->update($data);
        
        return redirect()->to("/register/step3/$app_no");
    }

    public function step3($app_no)
    {
        $app = $this->getApp($app_no);
        if (!$app) return redirect()->to('/register');
        if ($app['status'] === 'submitted') return redirect()->to("/register/confirmation/$app_no");
        return view('registration/step3', ['app' => $app]);
    }

    public function step3_submit()
    {
        $app_no = $this->request->getPost('application_no');
        
        $optionsList = [];
        for($i = 1; $i <= 7; $i++) {
            $val = $this->request->getPost("option_$i");
            if (!empty($val)) {
                $optionsList[] = $val;
            }
        }
        
        // Remove duplicates and re-index
        $optionsList = array_values(array_unique($optionsList));

        $data = [];
        for($i = 1; $i <= 7; $i++) {
            $data["option_$i"] = isset($optionsList[$i - 1]) ? $optionsList[$i - 1] : null;
        }

        $this->getDb()->table('spot_registrations')->where('application_no', $app_no)->update($data);
        
        return redirect()->to("/register/step4/$app_no");
    }

    public function step4($app_no)
    {
        $app = $this->getApp($app_no);
        if (!$app) return redirect()->to('/register');
        if ($app['status'] === 'submitted') return redirect()->to("/register/confirmation/$app_no");
        return view('registration/step4', ['app' => $app]);
    }

    public function step4_submit()
    {
        $app_no = $this->request->getPost('application_no');
        $declaration = $this->request->getPost('declaration');
        
        if (!$declaration) {
            return redirect()->back()->with('error', 'You must accept the declaration to proceed.');
        }

        $this->getDb()->table('spot_registrations')
             ->where('application_no', $app_no)
             ->update(['declaration' => 1]);
             
        return redirect()->to("/register/summary/$app_no");
    }

    public function summary($app_no)
    {
        $app = $this->getApp($app_no);
        if (!$app) return redirect()->to('/register');
        if ($app['status'] === 'submitted') return redirect()->to("/register/confirmation/$app_no");
        return view('registration/summary', ['app' => $app]);
    }

    public function final_submit()
    {
        $app_no = $this->request->getPost('application_no');
        
        $this->getDb()->table('spot_registrations')
             ->where('application_no', $app_no)
             ->update(['status' => 'submitted']);
             
        return redirect()->to("/register/confirmation/$app_no");
    }

    public function confirmation($app_no)
    {
        $app = $this->getApp($app_no);
        if (!$app || $app['status'] !== 'submitted') return redirect()->to('/register');
        return view('registration/confirmation', ['data' => $app]);
    }

    public function generatePdf($app_no)
    {
        $app = $this->getApp($app_no);
        if (!$app || $app['status'] !== 'submitted') return redirect()->to('/register');

        $html = view('registration/pdf_slip', ['data' => $app]);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $dompdf->stream('mace_registration_slip_' . $app_no . '.pdf', ["Attachment" => false]);
        exit();
    }
}
