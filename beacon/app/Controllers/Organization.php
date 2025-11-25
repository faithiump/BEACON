<?php

namespace App\Controllers;

use App\Models\OrganizationApplicationModel;
use App\Models\OrganizationAdvisorModel;
use App\Models\OrganizationOfficerModel;
use App\Models\OrganizationFileModel;

class Organization extends BaseController
{
    protected $helpers = ['url'];
    
    protected $applicationModel;
    protected $advisorModel;
    protected $officerModel;
    protected $fileModel;
    
    public function __construct()
    {
        $this->applicationModel = new OrganizationApplicationModel();
        $this->advisorModel = new OrganizationAdvisorModel();
        $this->officerModel = new OrganizationOfficerModel();
        $this->fileModel = new OrganizationFileModel();
    }

    public function launch(): string
    {
        return view('organization/launch');
    }

    public function processLaunch()
    {
        // Handle organization launch application
        $validation = \Config\Services::validation();
        
        $rules = [
            'organization_name' => 'required|min_length[3]|max_length[100]',
            'organization_acronym' => 'required|min_length[2]|max_length[20]',
            'organization_type' => 'required|in_list[academic,non_academic,service,religious,cultural,sports,other]',
            'organization_category' => 'required|in_list[departmental,inter_departmental,university_wide]',
            'mission' => 'required|min_length[50]|max_length[1000]',
            'vision' => 'required|min_length[50]|max_length[1000]',
            'objectives' => 'required|min_length[50]|max_length[2000]',
            'founding_date' => 'required|valid_date',
            'contact_email' => 'required|valid_email',
            'contact_phone' => 'required',
            'advisor_name' => 'required|min_length[3]|max_length[100]',
            'advisor_email' => 'required|valid_email',
            'advisor_phone' => 'required',
            'advisor_department' => 'required|in_list[ccs,cea,cthbm,chs,ctde,cas,gs]',
            'officer_position' => 'required|min_length[2]|max_length[50]',
            'primary_officer_name' => 'required|min_length[3]|max_length[100]',
            'primary_officer_email' => 'required|valid_email',
            'primary_officer_phone' => 'required',
            'primary_officer_student_id' => 'required|min_length[5]',
            'current_members' => 'required|integer|greater_than[4]',
            'constitution_file' => 'uploaded[constitution_file]|max_size[constitution_file,5120]',
            'certification_file' => 'uploaded[certification_file]|max_size[certification_file,5120]'
        ];

        if (!$this->validate($rules)) {
            // Flatten validation errors for display
            $errors = [];
            foreach ($validation->getErrors() as $field => $messages) {
                if (is_array($messages)) {
                    $errors = array_merge($errors, $messages);
                } else {
                    $errors[] = $messages;
                }
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Ensure uploads directory exists
        $uploadPath = WRITEPATH . 'uploads/organizations/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Handle constitution file upload
        $constitutionFile = $this->request->getFile('constitution_file');
        $constitutionFileName = null;
        $constitutionFileSize = null;
        $constitutionMimeType = null;
        $constitutionOriginalName = null;
        
        if ($constitutionFile && $constitutionFile->isValid() && !$constitutionFile->hasMoved()) {
            // Validate file extension manually
            $allowedExtensions = ['pdf', 'doc', 'docx'];
            $extension = $constitutionFile->getExtension();
            
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return redirect()->back()->withInput()->with('errors', ['Constitution file must be PDF, DOC, or DOCX format.']);
            }
            
            $constitutionOriginalName = $constitutionFile->getName();
            $constitutionFileSize = $constitutionFile->getSize();
            $constitutionMimeType = $constitutionFile->getClientMimeType();
            $newName = $constitutionFile->getRandomName();
            
            if ($constitutionFile->move($uploadPath, $newName)) {
                $constitutionFileName = $newName;
            } else {
                return redirect()->back()->withInput()->with('errors', ['Failed to upload constitution file. Please try again.']);
            }
        }

        // Handle certification file upload
        $certificationFile = $this->request->getFile('certification_file');
        $certificationFileName = null;
        $certificationFileSize = null;
        $certificationMimeType = null;
        $certificationOriginalName = null;
        
        if ($certificationFile && $certificationFile->isValid() && !$certificationFile->hasMoved()) {
            // Validate file extension manually
            $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            $extension = $certificationFile->getExtension();
            
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return redirect()->back()->withInput()->with('errors', ['Certification file must be PDF, DOC, DOCX, JPG, JPEG, or PNG format.']);
            }
            
            $certificationOriginalName = $certificationFile->getName();
            $certificationFileSize = $certificationFile->getSize();
            $certificationMimeType = $certificationFile->getClientMimeType();
            $newName = $certificationFile->getRandomName();
            
            if ($certificationFile->move($uploadPath, $newName)) {
                $certificationFileName = $newName;
            } else {
                return redirect()->back()->withInput()->with('errors', ['Failed to upload certification file. Please try again.']);
            }
        }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // IMPORTANT: This only saves the APPLICATION for review (status: pending)
            // The actual user account and organization record are created ONLY when admin approves
            // This ensures data is only saved to users/organizations tables after approval
            
            // 1. Save organization application (only application data, not user/organization records)
            // User and organization records will be created only when admin approves in approveOrganization()
            $applicationData = [
                'organization_name' => $this->request->getPost('organization_name'),
                'organization_acronym' => $this->request->getPost('organization_acronym'),
                'organization_type' => $this->request->getPost('organization_type'),
                'organization_category' => $this->request->getPost('organization_category'),
                'founding_date' => $this->request->getPost('founding_date'),
                'mission' => $this->request->getPost('mission'),
                'vision' => $this->request->getPost('vision'),
                'objectives' => $this->request->getPost('objectives'),
                'contact_email' => $this->request->getPost('contact_email'),
                'contact_phone' => $this->request->getPost('contact_phone'),
                'current_members' => $this->request->getPost('current_members'),
                'status' => 'pending',
                'submitted_at' => date('Y-m-d H:i:s')
            ];
            
            $db->table('organization_applications')->insert($applicationData);
            $applicationId = $db->insertID();
            
            if (!$applicationId) {
                $error = $db->error();
                throw new \Exception('Failed to save application. ' . ($error['message'] ?? 'Please ensure the organization_applications table exists in the database.'));
            }

            if (!$applicationId) {
                throw new \Exception('Failed to get application ID after insert');
            }

            // 2. Save advisor information
            $advisorData = [
                'application_id' => $applicationId,
                'name' => $this->request->getPost('advisor_name'),
                'email' => $this->request->getPost('advisor_email'),
                'phone' => $this->request->getPost('advisor_phone'),
                'department' => $this->request->getPost('advisor_department')
            ];
            $db->table('organization_advisors')->insert($advisorData);
            $advisorError = $db->error();
            if (!empty($advisorError['message'])) {
                throw new \Exception('Failed to save advisor info: ' . $advisorError['message']);
            }

            // 3. Save primary officer information
            $officerData = [
                'application_id' => $applicationId,
                'position' => $this->request->getPost('officer_position'),
                'name' => $this->request->getPost('primary_officer_name'),
                'email' => $this->request->getPost('primary_officer_email'),
                'phone' => $this->request->getPost('primary_officer_phone'),
                'student_id' => $this->request->getPost('primary_officer_student_id')
            ];
            $db->table('organization_officers')->insert($officerData);
            $officerError = $db->error();
            if (!empty($officerError['message'])) {
                throw new \Exception('Failed to save officer info: ' . $officerError['message']);
            }

            // 4. Save file information
            if ($constitutionFileName) {
                $constitutionFileData = [
                    'application_id' => $applicationId,
                    'file_type' => 'constitution',
                    'file_name' => $constitutionOriginalName,
                    'file_path' => 'uploads/organizations/' . $constitutionFileName,
                    'file_size' => $constitutionFileSize,
                    'mime_type' => $constitutionMimeType
                ];
                $db->table('organization_files')->insert($constitutionFileData);
                $fileError = $db->error();
                if (!empty($fileError['message'])) {
                    log_message('warning', 'Failed to save constitution file info: ' . $fileError['message']);
                    // Don't throw - file info is optional for the transaction
                }
            }

            if ($certificationFileName) {
                $certificationFileData = [
                    'application_id' => $applicationId,
                    'file_type' => 'certification',
                    'file_name' => $certificationOriginalName,
                    'file_path' => 'uploads/organizations/' . $certificationFileName,
                    'file_size' => $certificationFileSize,
                    'mime_type' => $certificationMimeType
                ];
                $db->table('organization_files')->insert($certificationFileData);
                $fileError = $db->error();
                if (!empty($fileError['message'])) {
                    log_message('warning', 'Failed to save certification file info: ' . $fileError['message']);
                    // Don't throw - file info is optional for the transaction
                }
            }

            // Complete transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                $error = $db->error();
                throw new \Exception('Transaction failed: ' . ($error['message'] ?? 'Unknown database error'));
            }

            log_message('info', 'Organization application submitted successfully. ID: ' . $applicationId);
            return redirect()->to(base_url('organization/launch'))->with('success', 'Your organization launch application has been submitted successfully! It will be reviewed by the administration. You will receive an email notification once a decision has been made.');

        } catch (\Exception $e) {
            if (isset($db) && $db->transStatus() !== false) {
                $db->transRollback();
            }
            $errorMessage = $e->getMessage();
            $dbError = $db->error();
            
            log_message('error', 'Organization application error: ' . $errorMessage);
            if (!empty($dbError)) {
                log_message('error', 'Database error: ' . json_encode($dbError));
            }
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Always show detailed error for debugging
            $displayError = $errorMessage;
            if (!empty($dbError['message'])) {
                $displayError .= ' (DB: ' . $dbError['message'] . ')';
            }
            
            return redirect()->back()->withInput()->with('errors', [$displayError]);
        }
        try {
            // 1. Save organization application
            $applicationData = [
                'organization_name' => $this->request->getPost('organization_name'),
                'organization_acronym' => $this->request->getPost('organization_acronym'),
                'organization_type' => $this->request->getPost('organization_type'),
                'organization_category' => $this->request->getPost('organization_category'),
                'mission' => $this->request->getPost('mission'),
                'vision' => $this->request->getPost('vision'),
                'objectives' => $this->request->getPost('objectives'),
                'founding_date' => $this->request->getPost('founding_date'),
                'contact_email' => $this->request->getPost('contact_email'),
                'contact_phone' => $this->request->getPost('contact_phone'),
                'current_members' => $this->request->getPost('current_members'),
                'status' => 'pending',
                'submitted_at' => date('Y-m-d H:i:s')
            ];
            
            $applicationId = $this->applicationModel->insert($applicationData);
            
            if (!$applicationId) {
                throw new \Exception('Failed to save organization application');
            }

            // 2. Save advisor information
            $advisorData = [
                'application_id' => $applicationId,
                'name' => $this->request->getPost('advisor_name'),
                'email' => $this->request->getPost('advisor_email'),
                'phone' => $this->request->getPost('advisor_phone'),
                'department' => $this->request->getPost('advisor_department')
            ];
            
            $advisorId = $this->advisorModel->insert($advisorData);
            
            if (!$advisorId) {
                throw new \Exception('Failed to save advisor information');
            }

            // 3. Save primary officer information
            $officerData = [
                'application_id' => $applicationId,
                'position' => $this->request->getPost('officer_position'),
                'name' => $this->request->getPost('primary_officer_name'),
                'email' => $this->request->getPost('primary_officer_email'),
                'phone' => $this->request->getPost('primary_officer_phone'),
                'student_id' => $this->request->getPost('primary_officer_student_id')
            ];
            
            $officerId = $this->officerModel->insert($officerData);
            
            if (!$officerId) {
                throw new \Exception('Failed to save officer information');
            }

            // 4. Handle and save file uploads
            $uploadPath = WRITEPATH . 'uploads/organizations/';
            
            // Ensure upload directory exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Handle constitution file
            $constitutionFile = $this->request->getFile('constitution_file');
            if ($constitutionFile && $constitutionFile->isValid() && !$constitutionFile->hasMoved()) {
                $newName = $constitutionFile->getRandomName();
                $constitutionFile->move($uploadPath, $newName);
                
                $fileData = [
                    'application_id' => $applicationId,
                    'file_type' => 'constitution',
                    'file_name' => $constitutionFile->getClientName(),
                    'file_path' => 'uploads/organizations/' . $newName,
                    'file_size' => $constitutionFile->getSize(),
                    'mime_type' => $constitutionFile->getClientMimeType()
                ];
                
                $this->fileModel->insert($fileData);
            }

            // Handle certification file
            $certificationFile = $this->request->getFile('certification_file');
            if ($certificationFile && $certificationFile->isValid() && !$certificationFile->hasMoved()) {
                $newName = $certificationFile->getRandomName();
                $certificationFile->move($uploadPath, $newName);
                
                $fileData = [
                    'application_id' => $applicationId,
                    'file_type' => 'certification',
                    'file_name' => $certificationFile->getClientName(),
                    'file_path' => 'uploads/organizations/' . $newName,
                    'file_size' => $certificationFile->getSize(),
                    'mime_type' => $certificationFile->getClientMimeType()
                ];
                
                $this->fileModel->insert($fileData);
            }

            // Complete transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            return redirect()->to(base_url('organization/launch'))->with('success', 'Your organization launch application has been submitted successfully! It will be reviewed by the administration. You will receive an email notification once a decision has been made.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Organization application error: ' . $e->getMessage());
            
            // Format error for display
            $errorMessage = 'An error occurred while submitting your application. Please try again.';
            if (ENVIRONMENT === 'development') {
                $errorMessage .= ' Error: ' . $e->getMessage();
            }
            
            return redirect()->back()->withInput()->with('errors', [$errorMessage]);
        }
    }
}

