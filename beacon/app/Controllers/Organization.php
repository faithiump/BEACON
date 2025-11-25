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
            
            $constitutionOriginalName = $constitutionFile->getClientName();
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
            
            $certificationOriginalName = $certificationFile->getClientName();
            $certificationFileSize = $certificationFile->getSize();
            $certificationMimeType = $certificationFile->getClientMimeType();
            $newName = $certificationFile->getRandomName();
            
            if ($certificationFile->move($uploadPath, $newName)) {
                $certificationFileName = $newName;
            } else {
                return redirect()->back()->withInput()->with('errors', ['Failed to upload certification file. Please try again.']);
            }
        }

        // Start database transaction AFTER file uploads (so we can rollback if DB fails)
        $db->transStart();

        try {
            // IMPORTANT: This only saves the APPLICATION for review (status: pending)
            // The actual user account and organization record are created ONLY when admin approves
            // This ensures data is only saved to users/organizations tables after approval
            
            // 1. Save organization application (only application data, not user/organization records)
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
                'current_members' => (int)$this->request->getPost('current_members'),
                'status' => 'pending',
                'submitted_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $db->table('organization_applications')->insert($applicationData);
            
            if (!$result) {
                $error = $db->error();
                log_message('error', 'Failed to insert application: ' . json_encode($error));
                throw new \Exception('Failed to save application. ' . ($error['message'] ?? 'Database error occurred.'));
            }
            
            $applicationId = $db->insertID();
            
            if (!$applicationId || $applicationId <= 0) {
                throw new \Exception('Failed to get application ID after insert. Please check database connection and table structure.');
            }

            // 2. Save advisor information
            $advisorData = [
                'application_id' => $applicationId,
                'name' => $this->request->getPost('advisor_name'),
                'email' => $this->request->getPost('advisor_email'),
                'phone' => $this->request->getPost('advisor_phone'),
                'department' => $this->request->getPost('advisor_department')
            ];
            
            $advisorResult = $db->table('organization_advisors')->insert($advisorData);
            if (!$advisorResult) {
                $error = $db->error();
                log_message('error', 'Failed to insert advisor: ' . json_encode($error));
                throw new \Exception('Failed to save advisor info: ' . ($error['message'] ?? 'Database error occurred.'));
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
            
            $officerResult = $db->table('organization_officers')->insert($officerData);
            if (!$officerResult) {
                $error = $db->error();
                log_message('error', 'Failed to insert officer: ' . json_encode($error));
                throw new \Exception('Failed to save officer info: ' . ($error['message'] ?? 'Database error occurred.'));
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
                $fileResult = $db->table('organization_files')->insert($constitutionFileData);
                if (!$fileResult) {
                    log_message('warning', 'Failed to save constitution file info: ' . json_encode($db->error()));
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
                $fileResult = $db->table('organization_files')->insert($certificationFileData);
                if (!$fileResult) {
                    log_message('warning', 'Failed to save certification file info: ' . json_encode($db->error()));
                    // Don't throw - file info is optional for the transaction
                }
            }

            // Complete transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                $error = $db->error();
                log_message('error', 'Transaction failed: ' . json_encode($error));
                throw new \Exception('Transaction failed: ' . ($error['message'] ?? 'Unknown database error'));
            }

            log_message('info', 'Organization application submitted successfully. ID: ' . $applicationId);
            return redirect()->to(base_url('organization/launch'))->with('success', 'Your organization launch application has been submitted successfully! It will be reviewed by the administration. You will receive an email notification once a decision has been made.');

        } catch (\Exception $e) {
            // Rollback transaction if still active
            if ($db->transStatus() !== false) {
                $db->transRollback();
            }
            
            // Clean up uploaded files if transaction failed
            if ($constitutionFileName && file_exists($uploadPath . $constitutionFileName)) {
                @unlink($uploadPath . $constitutionFileName);
            }
            if ($certificationFileName && file_exists($uploadPath . $certificationFileName)) {
                @unlink($uploadPath . $certificationFileName);
            }
            
            $errorMessage = $e->getMessage();
            $dbError = $db->error();
            
            log_message('error', 'Organization application error: ' . $errorMessage);
            if (!empty($dbError) && !empty($dbError['message'])) {
                log_message('error', 'Database error: ' . json_encode($dbError));
            }
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Show detailed error for debugging
            $displayError = $errorMessage;
            if (!empty($dbError) && !empty($dbError['message'])) {
                $displayError .= ' (DB: ' . $dbError['message'] . ')';
            }
            
            return redirect()->back()->withInput()->with('errors', [$displayError]);
        }
    }
}

