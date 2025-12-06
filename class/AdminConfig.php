<?php
/**
 * AdminConfig Class - จัดการตั้งค่าระบบ
 */
class AdminConfig {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // ============ SETTINGS ============
    public function getSetting($key) {
        $stmt = $this->db->prepare("SELECT value FROM settings WHERE key_name = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['value'] : null;
    }
    
    public function setSetting($key, $value, $description = null) {
        $stmt = $this->db->prepare("INSERT INTO settings (key_name, value, description) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE value = VALUES(value)");
        return $stmt->execute([$key, $value, $description]);
    }
    
    public function getAllSettings() {
        $stmt = $this->db->query("SELECT * FROM settings ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ============ MENU CONFIG ============
    public function getMenus() {
        $stmt = $this->db->query("SELECT * FROM menu_config ORDER BY sort_order");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getActiveMenus() {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM menu_config WHERE is_enabled = 1 
                AND (use_schedule = 0 OR (start_datetime <= ? AND end_datetime >= ?))
                ORDER BY sort_order";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$now, $now]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateMenu($id, $data) {
        $sql = "UPDATE menu_config SET 
                is_enabled = ?, use_schedule = ?, 
                start_datetime = ?, end_datetime = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['is_enabled'] ?? 1,
            $data['use_schedule'] ?? 0,
            $data['start_datetime'] ?: null,
            $data['end_datetime'] ?: null,
            $id
        ]);
    }
    
    public function isMenuAvailable($menuKey) {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM menu_config WHERE menu_key = ? AND is_enabled = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$menuKey]);
        $menu = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$menu) return false;
        if (!$menu['use_schedule']) return true;
        
        return ($menu['start_datetime'] <= $now && $menu['end_datetime'] >= $now);
    }
    
    // ============ GRADE LEVELS ============
    public function getGradeLevels() {
        $stmt = $this->db->query("SELECT * FROM grade_levels ORDER BY sort_order");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ============ REGISTRATION TYPES ============
    public function getRegistrationTypes($gradeId = null) {
        $sql = "SELECT rt.*, gl.name as grade_name, gl.code as grade_code 
                FROM registration_types rt 
                JOIN grade_levels gl ON rt.grade_level_id = gl.id";
        if ($gradeId) {
            $sql .= " WHERE rt.grade_level_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$gradeId]);
        } else {
            $sql .= " ORDER BY gl.sort_order, rt.sort_order";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getActiveRegistrationTypes($gradeCode) {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT rt.* FROM registration_types rt 
                JOIN grade_levels gl ON rt.grade_level_id = gl.id
                WHERE gl.code = ? AND rt.is_active = 1
                AND (rt.use_schedule = 0 OR (rt.start_datetime <= ? AND rt.end_datetime >= ?))
                ORDER BY rt.sort_order";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$gradeCode, $now, $now]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addRegistrationType($data) {
        $sql = "INSERT INTO registration_types (grade_level_id, code, name, description, url, is_active, use_schedule, start_datetime, end_datetime, sort_order) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['grade_level_id'], $data['code'], $data['name'], 
            $data['description'] ?? '', $data['url'] ?? '',
            $data['is_active'] ?? 1, $data['use_schedule'] ?? 0, 
            $data['start_datetime'] ?: null, $data['end_datetime'] ?: null, 
            $data['sort_order'] ?? 0
        ]);
    }
    
    public function updateRegistrationType($id, $data) {
        $sql = "UPDATE registration_types SET 
                name = ?, description = ?, url = ?, is_active = ?,
                use_schedule = ?, start_datetime = ?, end_datetime = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'], $data['description'] ?? '', $data['url'] ?? '',
            $data['is_active'] ?? 1, $data['use_schedule'] ?? 0,
            $data['start_datetime'] ?: null, $data['end_datetime'] ?: null,
            $id
        ]);
    }
    
    public function deleteRegistrationType($id) {
        $stmt = $this->db->prepare("DELETE FROM registration_types WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // ============ STUDY PLANS ============
    public function getStudyPlans($typeId = null) {
        $sql = "SELECT sp.*, rt.name as type_name, rt.code as type_code,
                gl.name as grade_name, gl.code as grade_code
                FROM study_plans sp
                JOIN registration_types rt ON sp.registration_type_id = rt.id
                JOIN grade_levels gl ON rt.grade_level_id = gl.id";
        if ($typeId) {
            $sql .= " WHERE sp.registration_type_id = ? ORDER BY sp.sort_order";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$typeId]);
        } else {
            $sql .= " ORDER BY gl.sort_order, rt.sort_order, sp.sort_order";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addStudyPlan($data) {
        $sql = "INSERT INTO study_plans (registration_type_id, code, name, description, quota, is_active, sort_order) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['registration_type_id'], $data['code'], $data['name'],
            $data['description'] ?? '', $data['quota'] ?? 0,
            $data['is_active'] ?? 1, $data['sort_order'] ?? 0
        ]);
    }
    
    public function updateStudyPlan($id, $data) {
        $sql = "UPDATE study_plans SET 
                name = ?, description = ?, quota = ?, is_active = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'], $data['description'] ?? '', 
            $data['quota'] ?? 0, $data['is_active'] ?? 1, $id
        ]);
    }
    
    public function deleteStudyPlan($id) {
        $stmt = $this->db->prepare("DELETE FROM study_plans WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // ============ FORM FIELDS ============
    public function getFormFields($planId = null, $typeId = null) {
        $sql = "SELECT * FROM form_fields WHERE 1=1";
        $params = [];
        if ($planId) {
            $sql .= " AND (study_plan_id = ? OR study_plan_id IS NULL)";
            $params[] = $planId;
        }
        if ($typeId) {
            $sql .= " AND (registration_type_id = ? OR registration_type_id IS NULL)";
            $params[] = $typeId;
        }
        $sql .= " ORDER BY sort_order";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addFormField($data) {
        $sql = "INSERT INTO form_fields (study_plan_id, registration_type_id, field_key, field_label, field_type, placeholder, is_required, options, sort_order) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['study_plan_id'] ?: null, $data['registration_type_id'] ?: null,
            $data['field_key'], $data['field_label'], $data['field_type'] ?? 'text',
            $data['placeholder'] ?? '', $data['is_required'] ?? 1,
            json_encode($data['options'] ?? []), $data['sort_order'] ?? 0
        ]);
    }
    
    public function updateFormField($id, $data) {
        $sql = "UPDATE form_fields SET 
                field_label = ?, field_type = ?, placeholder = ?, 
                is_required = ?, options = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['field_label'], $data['field_type'] ?? 'text',
            $data['placeholder'] ?? '', $data['is_required'] ?? 1,
            json_encode($data['options'] ?? []), $id
        ]);
    }
    
    public function deleteFormField($id) {
        $stmt = $this->db->prepare("DELETE FROM form_fields WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
