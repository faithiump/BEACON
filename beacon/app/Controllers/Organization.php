<?php

namespace App\Controllers;

class Organization extends BaseController
{
    protected $helpers = ['url'];

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
            'constitution_file' => 'uploaded[constitution_file]|ext_in[constitution_file,pdf,doc,docx]|max_size[constitution_file,5120]',
            'certification_file' => 'uploaded[certification_file]|ext_in[certification_file,pdf,doc,docx,jpg,jpeg,png]|max_size[certification_file,5120]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Get form data
        $data = [
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
            'advisor_name' => $this->request->getPost('advisor_name'),
            'advisor_email' => $this->request->getPost('advisor_email'),
            'advisor_phone' => $this->request->getPost('advisor_phone'),
            'advisor_department' => $this->request->getPost('advisor_department'),
            'officer_position' => $this->request->getPost('officer_position'),
            'primary_officer_name' => $this->request->getPost('primary_officer_name'),
            'primary_officer_email' => $this->request->getPost('primary_officer_email'),
            'primary_officer_phone' => $this->request->getPost('primary_officer_phone'),
            'primary_officer_student_id' => $this->request->getPost('primary_officer_student_id'),
            'current_members' => $this->request->getPost('current_members'),
            'status' => 'pending', // Status: pending, approved, rejected
            'submitted_at' => date('Y-m-d H:i:s')
        ];

        // Handle constitution file upload
        $constitutionFile = $this->request->getFile('constitution_file');
        if ($constitutionFile && $constitutionFile->isValid() && !$constitutionFile->hasMoved()) {
            $newName = $constitutionFile->getRandomName();
            $constitutionFile->move(WRITEPATH . 'uploads/organizations/', $newName);
            $data['constitution_file'] = $newName;
        }

        // Handle certification file upload
        $certificationFile = $this->request->getFile('certification_file');
        if ($certificationFile && $certificationFile->isValid() && !$certificationFile->hasMoved()) {
            $newName = $certificationFile->getRandomName();
            $certificationFile->move(WRITEPATH . 'uploads/organizations/', $newName);
            $data['certification_file'] = $newName;
        }

        // TODO: Save to database and notify admin
        // For now, we'll just show a success message
        // In production, you would:
        // 1. Save the application to database
        // 2. Send email notification to admin
        // 3. Store the file path in database

        return redirect()->to(base_url('organization/launch'))->with('success', 'Your organization launch application has been submitted successfully! It will be reviewed by the administration. You will receive an email notification once a decision has been made.');
    }
}

