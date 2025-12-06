<?php
/**
 * Registration Controller
 * Handles student registration flows
 */
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../class/StudentRegis.php';

class RegistrationController extends Controller {
    private $studentRegis;
    
    public function __construct() {
        parent::__construct();
        $this->studentRegis = new StudentRegis($this->db);
    }
    
    /**
     * Show registration type selection page
     */
    public function index() {
        $data = [
            'pageTitle' => 'เลือกประเภทการสมัคร'
        ];
        
        $this->render('registration/select', $data);
    }
    
    /**
     * Show M.1 registration form
     */
    public function formM1() {
        $data = [
            'pageTitle' => 'สมัครเรียน ม.1',
            'level' => 1
        ];
        
        $this->render('registration/form-m1', $data);
    }
    
    /**
     * Show M.4 registration form
     */
    public function formM4() {
        $data = [
            'pageTitle' => 'สมัครเรียน ม.4',
            'level' => 4
        ];
        
        $this->render('registration/form-m4', $data);
    }
    
    /**
     * Show M.4 Quota registration form
     */
    public function formM4Quota() {
        $data = [
            'pageTitle' => 'สมัครเรียน ม.4 (โควต้า)',
            'level' => 4,
            'type' => 'quota'
        ];
        
        $this->render('registration/form-m4-quota', $data);
    }
    
    /**
     * Submit M.1 registration
     */
    public function submitM1() {
        if (!$this->isPost()) {
            $this->redirect('regis.php');
            return;
        }
        
        try {
            // Get academic year setting
            $settings_stmt = $this->db->prepare("SELECT value FROM setting WHERE config_name = 'year'");
            $settings_stmt->execute();
            $settings = $settings_stmt->fetch(PDO::FETCH_ASSOC);
            $pee = $settings['value'];
            
            // Prepare data
            $data = $this->prepareM1Data($pee);
            
            // Check for duplicate citizen ID
            if ($this->isDuplicateCitizenId($data['citizenid'])) {
                $this->json(['success' => false, 'message' => 'หมายเลขบัตรประชาชนนี้ได้ถูกใช้ไปแล้ว']);
                return;
            }
            
            // Convert location codes to names
            $data = $this->convertLocationCodes($data);
            
            // Insert record
            $this->insertStudent($data);
            
            $this->json(['success' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
            
        } catch (PDOException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Submit M.4 registration
     */
    public function submitM4() {
        if (!$this->isPost()) {
            $this->redirect('regis.php');
            return;
        }
        
        try {
            $settings_stmt = $this->db->prepare("SELECT value FROM setting WHERE config_name = 'year'");
            $settings_stmt->execute();
            $settings = $settings_stmt->fetch(PDO::FETCH_ASSOC);
            $pee = $settings['value'];
            
            $data = $this->prepareM4Data($pee);
            
            if ($this->isDuplicateCitizenId($data['citizenid'])) {
                $this->json(['success' => false, 'message' => 'หมายเลขบัตรประชาชนนี้ได้ถูกใช้ไปแล้ว']);
                return;
            }
            
            $data = $this->convertLocationCodes($data);
            $this->insertStudent($data);
            
            $this->json(['success' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
            
        } catch (PDOException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Prepare M.1 form data
     */
    private function prepareM1Data($pee) {
        return [
            'citizenid' => str_replace('-', '', $this->input('citizenid', '')),
            'date_birth' => $this->input('date_birth'),
            'month_birth' => $this->input('month_birth'),
            'year_birth' => $this->input('year_birth'),
            'stu_prefix' => $this->input('stu_prefix'),
            'stu_name' => $this->input('stu_name'),
            'stu_lastname' => $this->input('stu_lastname'),
            'typeregis' => $this->input('typeregis'),
            'stu_sex' => $this->input('stu_sex'),
            'stu_blood_group' => $this->input('stu_blood_group'),
            'stu_religion' => $this->input('stu_religion'),
            'stu_ethnicity' => $this->input('stu_ethnicity'),
            'stu_nationality' => $this->input('stu_nationality'),
            'old_school' => $this->input('old_school'),
            'old_school_province' => $this->input('old_school_province'),
            'old_school_district' => $this->input('old_school_district'),
            'now_addr' => $this->input('now_addr'),
            'now_moo' => $this->input('now_moo'),
            'now_soy' => $this->input('now_soy'),
            'now_street' => $this->input('now_street'),
            'now_tel' => $this->input('now_tel'),
            'now_province' => $this->input('now_province'),
            'now_district' => $this->input('now_district'),
            'now_subdistrict' => $this->input('now_subdistrict'),
            'now_post' => $this->input('now_post'),
            'old_addr' => $this->input('old_addr'),
            'old_moo' => $this->input('old_moo'),
            'old_soy' => $this->input('old_soy'),
            'old_street' => $this->input('old_street'),
            'old_tel' => $this->input('old_tel'),
            'old_province' => $this->input('old_province'),
            'old_district' => $this->input('old_district'),
            'old_subdistrict' => $this->input('old_subdistrict'),
            'old_post' => $this->input('old_post'),
            'dad_prefix' => $this->input('dad_prefix'),
            'dad_name' => $this->input('dad_name'),
            'dad_lastname' => $this->input('dad_lastname'),
            'dad_job' => $this->input('dad_job'),
            'dad_tel' => $this->input('dad_tel'),
            'mom_prefix' => $this->input('mom_prefix'),
            'mom_name' => $this->input('mom_name'),
            'mom_lastname' => $this->input('mom_lastname'),
            'mom_job' => $this->input('mom_job'),
            'mom_tel' => $this->input('mom_tel'),
            'parent_prefix' => $this->input('parent_prefix'),
            'parent_name' => $this->input('parent_name'),
            'parent_lastname' => $this->input('parent_lastname'),
            'parent_tel' => $this->input('parent_tel'),
            'parent_relation' => $this->input('parent_relation'),
            'gpa_total' => $this->input('gpa_total'),
            'number1' => $this->input('number1'),
            'number2' => $this->input('number2'),
            'number3' => $this->input('number3'),
            'number4' => $this->input('number4'),
            'number5' => $this->input('number5'),
            'number6' => $this->input('number6'),
            'number7' => $this->input('number7'),
            'number8' => $this->input('number8'),
            'number9' => $this->input('number9'),
            'number10' => $this->input('number10'),
            'level' => '1',
            'roles' => 0,
            'reg_pee' => $pee,
            'create_at' => date('Y-m-d H:i:s'),
        ];
    }
    
    /**
     * Prepare M.4 form data
     */
    private function prepareM4Data($pee) {
        $data = $this->prepareM1Data($pee);
        $data['level'] = '4';
        // M.4 doesn't have number7-10
        unset($data['number7'], $data['number8'], $data['number9'], $data['number10']);
        return $data;
    }
    
    /**
     * Check for duplicate citizen ID
     */
    private function isDuplicateCitizenId($citizenId) {
        $stmt = $this->db->prepare("SELECT citizenid FROM users WHERE citizenid = :citizenid");
        $stmt->bindParam(':citizenid', $citizenId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    
    /**
     * Convert location codes to names
     */
    private function convertLocationCodes($data) {
        $locationFields = [
            'old_school_province' => 'province',
            'old_school_district' => 'district',
            'old_province' => 'province',
            'old_district' => 'district',
            'old_subdistrict' => 'subdistrict',
            'now_province' => 'province',
            'now_district' => 'district',
            'now_subdistrict' => 'subdistrict'
        ];
        
        foreach ($locationFields as $field => $table) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $data[$field] = $this->getNameByCode($table, $data[$field]);
            }
        }
        
        return $data;
    }
    
    /**
     * Get name from location code
     */
    private function getNameByCode($table, $code) {
        $stmt = $this->db->prepare("SELECT name_th FROM {$table} WHERE code = :code");
        $stmt->execute([':code' => $code]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['name_th'] : '';
    }
    
    /**
     * Insert student record
     */
    private function insertStudent($data) {
        $columns = array_keys($data);
        $placeholders = array_map(function($col) { return ':' . $col; }, $columns);
        
        $sql = "INSERT INTO users (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }
}
