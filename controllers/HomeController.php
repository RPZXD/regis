<?php
/**
 * Home Controller
 * Handles dashboard and home page
 */
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../class/StudentRegis.php';

class HomeController extends Controller {
    private $studentRegis;
    
    public function __construct() {
        parent::__construct();
        $this->studentRegis = new StudentRegis($this->db);
    }
    
    /**
     * Display dashboard
     */
    public function index() {
        $data = [
            'pageTitle' => 'รับสมัครนักเรียน 2568',
            'stats' => $this->getDashboardStats()
        ];
        
        $this->render('home/index', $data);
    }
    
    /**
     * Get dashboard statistics
     */
    private function getDashboardStats() {
        $year = date('Y') + 543; // Buddhist year
        
        return [
            'm1_in' => $this->studentRegis->countRegis(1, 'ในเขต', $year),
            'm1_out' => $this->studentRegis->countRegis(1, 'นอกเขต', $year),
            'm4_quota' => $this->studentRegis->countRegis(4, 'โควต้า', $year),
            'm4_normal' => $this->studentRegis->countRegis(4, 'รอบทั่วไป', $year),
        ];
    }
    
    /**
     * API endpoint for chart data
     */
    public function chartData() {
        $startDate = date('Y-m-d', strtotime('-7 days'));
        $endDate = date('Y-m-d');
        
        $dailyCounts = $this->studentRegis->getDailyRegistrationCounts($startDate, $endDate);
        
        $this->json([
            'success' => true,
            'data' => $dailyCounts
        ]);
    }
}
