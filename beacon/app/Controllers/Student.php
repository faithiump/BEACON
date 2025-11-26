<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\AddressModel;
use App\Models\StudentModel;
use App\Models\OrganizationModel;
use App\Models\EventModel;
use App\Models\AnnouncementModel;
use App\Models\ProductModel;
use App\Models\StudentOrganizationMembershipModel;

class Student extends BaseController
{
    protected $helpers = ['url'];
    
    protected $userModel;
    protected $userProfileModel;
    protected $addressModel;
    protected $studentModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userProfileModel = new UserProfileModel();
        $this->addressModel = new AddressModel();
        $this->studentModel = new StudentModel();
    }

    /**
     * Check if student is logged in
     */
    private function checkAuth()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return redirect()->to(base_url('auth/login'))->with('error', 'Please login to access this page.');
        }
        return true;
    }

    /**
     * Student Dashboard
     */
    public function dashboard()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Get student data
        $userId = session()->get('user_id');
        $studentId = session()->get('student_id');
        
        $student = $this->studentModel->where('user_id', $userId)->first();
        $profile = $this->userProfileModel->where('user_id', $userId)->first();
        $user = $this->userModel->find($userId);
        
        // Get address data if exists
        $address = null;
        if ($profile && !empty($profile['address_id'])) {
            $address = $this->addressModel->find($profile['address_id']);
        }

        // Get student's organization memberships
        $membershipModel = new StudentOrganizationMembershipModel();
        $joinedOrganizations = [];
        $organizationPosts = [
            'announcements' => [],
            'events' => []
        ];
        $eventCount = 0;
        $orgCount = 0;
        $hasJoinedOrg = false;
        $upcomingEvents = [];
        $allEventsList = [];
        $allAnnouncementsList = [];
        $allProductsList = [];

        if ($student) {
            // Get all organizations the student has joined (active only for posts)
            $memberships = $membershipModel->getStudentOrganizations($student['id']);
            $orgCount = count($memberships);
            $hasJoinedOrg = $orgCount > 0;
            
            // Get all memberships including pending for sidebar display
            $allMemberships = $membershipModel->getStudentAllOrganizations($student['id']);
            
            if ($hasJoinedOrg) {
                $orgModel = new OrganizationModel();
                $announcementModel = new AnnouncementModel();
                $eventModel = new EventModel();
                $orgIds = [];
                
                // Build joined organizations list and collect org IDs
                foreach ($memberships as $membership) {
                    $joinedOrganizations[] = [
                        'id' => $membership['org_id'],
                        'name' => $membership['organization_name'],
                        'acronym' => $membership['organization_acronym'],
                        'status' => $membership['status']
                    ];
                    $orgIds[] = $membership['org_id'];
                }
                
                // Get announcements from all joined organizations
                $allAnnouncementsList = [];
                foreach ($orgIds as $orgId) {
                    $announcements = $announcementModel->getAnnouncementsByOrg($orgId);
                    
                    // Get organization info for each announcement
                    $org = $orgModel->find($orgId);
                    
                    foreach ($announcements as $announcement) {
                        $announcementData = [
                            'id' => $announcement['announcement_id'] ?? $announcement['id'],
                            'title' => $announcement['title'],
                            'content' => $announcement['content'],
                            'priority' => $announcement['priority'] ?? 'normal',
                            'created_at' => $announcement['created_at'],
                            'views' => $announcement['views'] ?? 0,
                            'org_id' => $orgId,
                            'org_name' => $org['organization_name'] ?? '',
                            'org_acronym' => $org['organization_acronym'] ?? '',
                        ];
                        
                        $organizationPosts['announcements'][] = $announcementData;
                        $allAnnouncementsList[] = $announcementData;
                    }
                }
                
                // Sort all announcements by date (newest first) for announcements section
                usort($allAnnouncementsList, function($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
                
                // Get events from all joined organizations
                $allUpcomingEvents = [];
                $allEventsList = [];
                foreach ($orgIds as $orgId) {
                    $events = $eventModel->getEventsByOrg($orgId);
                    $eventCount += count($events);
                    
                    // Get organization info for each event
                    $org = $orgModel->find($orgId);
                    
                    foreach ($events as $event) {
                        // Format time
                        $timeFormatted = $event['time'];
                        if (strpos($timeFormatted, ':') !== false) {
                            $timeParts = explode(':', $timeFormatted);
                            $hour = (int)$timeParts[0];
                            $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
                            $period = $hour >= 12 ? 'PM' : 'AM';
                            $hour12 = $hour > 12 ? $hour - 12 : ($hour == 0 ? 12 : $hour);
                            $timeFormatted = sprintf('%d:%02d %s', $hour12, $minute, $period);
                        }
                        
                        // Format date for display
                        $eventDate = date('F j, Y', strtotime($event['date']));
                        
                        $eventData = [
                            'id' => $event['event_id'] ?? $event['id'],
                            'title' => $event['event_name'] ?? $event['title'],
                            'description' => $event['description'],
                            'date' => $event['date'],
                            'date_formatted' => $eventDate,
                            'time' => $timeFormatted,
                            'location' => $event['venue'] ?? $event['location'],
                            'attendees' => $event['current_attendees'] ?? 0,
                            'max_attendees' => $event['max_attendees'],
                            'status' => $event['status'] ?? 'upcoming',
                            'image' => $event['image'] ?? null,
                            'created_at' => $event['created_at'] ?? $event['date'],
                            'org_id' => $orgId,
                            'org_name' => $org['organization_name'] ?? '',
                            'org_acronym' => $org['organization_acronym'] ?? '',
                            'org_type' => ucfirst(str_replace('_', ' ', $event['org_type'] ?? 'academic')),
                        ];
                        
                        $organizationPosts['events'][] = $eventData;
                        $allEventsList[] = $eventData;
                        
                        // Collect upcoming events for sidebar (only future events)
                        if (strtotime($event['date']) >= strtotime(date('Y-m-d'))) {
                            $allUpcomingEvents[] = $eventData;
                        }
                    }
                }
                
                // Sort upcoming events by date and limit to 3 for sidebar
                usort($allUpcomingEvents, function($a, $b) {
                    return strtotime($a['date']) - strtotime($b['date']);
                });
                $upcomingEvents = array_slice($allUpcomingEvents, 0, 3);
                
                // Sort all events by date (newest first) for events section
                usort($allEventsList, function($a, $b) {
                    return strtotime($b['date']) - strtotime($a['date']);
                });
                
                // Get products from all joined organizations
                $productModel = new ProductModel();
                $orgModelForProducts = new OrganizationModel();
                foreach ($orgIds as $orgId) {
                    $products = $productModel->getProductsByOrg($orgId);
                    
                    // Get organization info for each product
                    $org = $orgModelForProducts->find($orgId);
                    
                    foreach ($products as $product) {
                        $productData = [
                            'id' => $product['product_id'] ?? $product['id'],
                            'name' => $product['product_name'] ?? $product['name'],
                            'description' => $product['description'] ?? '',
                            'price' => number_format((float)$product['price'], 2, '.', ''),
                            'stock' => $product['stock'] ?? 0,
                            'sold' => $product['sold'] ?? 0,
                            'status' => $product['status'] ?? 'available',
                            'image' => $product['image'] ?? null,
                            'sizes' => $product['sizes'] ?? null,
                            'org_id' => $orgId,
                            'org_name' => $org['organization_name'] ?? '',
                            'org_acronym' => $org['organization_acronym'] ?? '',
                        ];
                        
                        $allProductsList[] = $productData;
                    }
                }
                
                // Sort all products by date (newest first) for shop section
                usort($allProductsList, function($a, $b) {
                    // We'll use the order they were added, but we can sort by name or price if needed
                    return 0;
                });
            }
        }

        // Get available organizations from database
        $orgModel = new OrganizationModel();
        $organizations = $orgModel->where('is_active', 1)
            ->orderBy('organization_name', 'ASC')
            ->findAll();

        // Format organizations for display
        $formattedOrgs = [];
        $eventModel = new EventModel();
        $membershipModel = new StudentOrganizationMembershipModel();
        
        // Get student's membership status for all organizations
        $studentMemberships = [];
        if ($student) {
            $allStudentMemberships = $membershipModel->getStudentAllOrganizations($student['id']);
            foreach ($allStudentMemberships as $membership) {
                $studentMemberships[$membership['org_id']] = $membership['status'];
            }
        }
        
        foreach ($organizations as $org) {
            // Get event count for this organization
            $orgEvents = $eventModel->getEventsByOrg($org['id']);
            $orgEventCount = count($orgEvents);
            
            // Check if student is a member (active) or has pending request
            $membershipStatus = $studentMemberships[$org['id']] ?? null;
            $isMember = ($membershipStatus === 'active');
            $isPending = ($membershipStatus === 'pending');
            
            $formattedOrgs[] = [
                'id' => $org['id'],
                'name' => $org['organization_name'],
                'acronym' => $org['organization_acronym'],
                'type' => ucfirst(str_replace('_', ' ', $org['organization_type'] ?? 'academic')),
                'members' => $org['current_members'] ?? 0,
                'description' => $org['mission'] ?? '',
                'event_count' => $orgEventCount,
                'is_member' => $isMember,
                'is_pending' => $isPending,
            ];
        }

        // Get suggested organizations (organizations student hasn't joined)
        $suggestedOrganizations = [];
        if ($student && isset($allMemberships)) {
            // Get all organization IDs the student has joined (active or pending)
            $joinedOrgIds = array_column($allMemberships, 'org_id');
            
            // Filter out organizations student has already joined (active or pending)
            foreach ($formattedOrgs as $org) {
                if (!in_array($org['id'], $joinedOrgIds)) {
                    $suggestedOrganizations[] = $org;
                }
            }
            
            // Sort by member count (most popular first) and limit to 3
            usort($suggestedOrganizations, function($a, $b) {
                return ($b['members'] ?? 0) - ($a['members'] ?? 0);
            });
            $suggestedOrganizations = array_slice($suggestedOrganizations, 0, 3);
        } else {
            // If no student or no memberships, just show first 3 organizations
            $suggestedOrganizations = array_slice($formattedOrgs, 0, 3);
        }
        
        // Format all memberships (including pending) for sidebar
        $allJoinedOrgs = [];
        if ($student && isset($allMemberships)) {
            foreach ($allMemberships as $membership) {
                $allJoinedOrgs[] = [
                    'id' => $membership['org_id'],
                    'name' => $membership['organization_name'],
                    'acronym' => $membership['organization_acronym'],
                    'status' => $membership['status']
                ];
            }
        }

        $data = [
            'student' => $student,
            'profile' => $profile,
            'address' => $address,
            'user' => $user,
            'hasJoinedOrg' => $hasJoinedOrg,
            'organizationPosts' => $organizationPosts,
            'availableOrganizations' => $formattedOrgs,
            'joinedOrganizations' => $joinedOrganizations,
            'allJoinedOrganizations' => $allJoinedOrgs,
            'suggestedOrganizations' => $suggestedOrganizations ?? [],
            'upcomingEvents' => $upcomingEvents ?? [],
            'allEvents' => $allEventsList ?? [],
            'allAnnouncements' => $allAnnouncementsList ?? [],
            'allProducts' => $allProductsList ?? [],
            'eventCount' => $eventCount,
            'orgCount' => $orgCount,
            'pageTitle' => 'Student Dashboard'
        ];

        return view('student/dashboard', $data);
    }

    /**
     * Update Student Profile
     */
    public function updateProfile()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $userId = session()->get('user_id');
        $student = $this->studentModel->where('user_id', $userId)->first();
        $profile = $this->userProfileModel->where('user_id', $userId)->first();

        if (!$student || !$profile) {
            return $this->response->setJSON(['success' => false, 'message' => 'Student profile not found']);
        }

        try {
            // Update or create address
            $addressData = [
                'region' => $this->request->getPost('region') ?? '',
                'city_municipality' => $this->request->getPost('city_municipality') ?? '',
                'barangay' => $this->request->getPost('barangay') ?? ''
            ];

            $addressId = $profile['address_id'];
            if ($addressId) {
                $this->addressModel->update($addressId, $addressData);
            } else {
                $addressId = $this->addressModel->insert($addressData);
            }

            // Update user profile
            $profileData = [
                'firstname' => $this->request->getPost('firstname') ?? '',
                'middlename' => $this->request->getPost('middlename') ?? '',
                'lastname' => $this->request->getPost('lastname') ?? '',
                'birthday' => $this->request->getPost('birthday') ?: null,
                'gender' => $this->request->getPost('gender') ?? '',
                'phone' => $this->request->getPost('phone') ?? '',
                'address_id' => $addressId
            ];

            $this->userProfileModel->update($profile['id'], $profileData);

            // Update student info
            $studentData = [
                'student_id' => $this->request->getPost('student_id') ?? $student['student_id'],
                'department' => $this->request->getPost('department') ?? '',
                'course' => $this->request->getPost('course') ?? '',
                'year_level' => $this->request->getPost('year_level') ?? null,
                'organization_name' => $this->request->getPost('organization_name') ?? ''
            ];

            $this->studentModel->update($student['id'], $studentData);

            // Update session data
            session()->set([
                'firstname' => $profileData['firstname'],
                'lastname' => $profileData['lastname']
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Profile update error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while updating your profile.'
            ]);
        }
    }

    /**
     * Upload Profile Photo
     */
    public function uploadPhoto()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $photo = $this->request->getFile('photo');
        
        if (!$photo || !$photo->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'No valid photo uploaded']);
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($photo->getMimeType(), $allowedTypes)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid file type. Please upload an image.']);
        }

        // Validate file size (max 5MB)
        if ($photo->getSize() > 5 * 1024 * 1024) {
            return $this->response->setJSON(['success' => false, 'message' => 'File size must be less than 5MB']);
        }

        try {
            $userId = session()->get('user_id');
            
            // Generate unique filename
            $newName = 'profile_' . $userId . '_' . time() . '.' . $photo->getExtension();
            
            // Create upload directory if not exists
            $uploadPath = FCPATH . 'uploads/profiles/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Move uploaded file
            $photo->move($uploadPath, $newName);
            
            // Get the URL for the photo
            $photoUrl = base_url('uploads/profiles/' . $newName);
            
            // Update session with new photo
            session()->set('photo', $photoUrl);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile photo updated successfully!',
                'photo_url' => $photoUrl
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Photo upload error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while uploading photo.'
            ]);
        }
    }

    /**
     * View Events
     */
    public function viewEvents()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Mock events data - replace with actual database queries when tables are available
        $events = [
            [
                'id' => 1,
                'title' => 'Tech Innovation Summit 2025',
                'description' => 'Join us for the annual tech summit featuring industry leaders and workshops.',
                'organization' => 'Computer Science Society',
                'date' => '2025-12-05',
                'time' => '09:00 AM',
                'location' => 'Main Auditorium',
                'fee' => 150.00,
                'status' => 'upcoming'
            ],
            [
                'id' => 2,
                'title' => 'Business Plan Competition',
                'description' => 'Showcase your entrepreneurial skills and win exciting prizes.',
                'organization' => 'Business Administration Club',
                'date' => '2025-12-10',
                'time' => '01:00 PM',
                'location' => 'Conference Hall B',
                'fee' => 0,
                'status' => 'upcoming'
            ],
            [
                'id' => 3,
                'title' => 'Environmental Awareness Week',
                'description' => 'A week-long celebration promoting environmental consciousness.',
                'organization' => 'Green Energy Initiative',
                'date' => '2025-12-15',
                'time' => '08:00 AM',
                'location' => 'Campus Grounds',
                'fee' => 0,
                'status' => 'upcoming'
            ]
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'events' => $events]);
        }

        return view('student/events', ['events' => $events]);
    }

    /**
     * View Announcements
     */
    public function viewAnnouncements()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Mock announcements data
        $announcements = [
            [
                'id' => 1,
                'title' => 'Enrollment Period Extended',
                'content' => 'The enrollment period for the 2nd semester has been extended until December 15, 2025.',
                'author' => 'Registrar Office',
                'date' => '2025-11-20',
                'priority' => 'high'
            ],
            [
                'id' => 2,
                'title' => 'New Library Hours',
                'content' => 'Starting December 1, the library will be open 24/7 during examination week.',
                'author' => 'Library Services',
                'date' => '2025-11-18',
                'priority' => 'medium'
            ],
            [
                'id' => 3,
                'title' => 'Holiday Schedule Announcement',
                'content' => 'Classes will be suspended from December 23, 2025 to January 2, 2026 for the holiday break.',
                'author' => 'Academic Affairs',
                'date' => '2025-11-15',
                'priority' => 'medium'
            ]
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'announcements' => $announcements]);
        }

        return view('student/announcements', ['announcements' => $announcements]);
    }

    /**
     * Search functionality
     */
    public function search()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $query = $this->request->getGet('q') ?? '';
        $type = $this->request->getGet('type') ?? 'all';

        // Mock search results - replace with actual database queries
        $results = [
            'events' => [],
            'organizations' => [],
            'announcements' => [],
            'products' => []
        ];

        if (!empty($query)) {
            // Mock filtered results based on query
            if ($type === 'all' || $type === 'events') {
                $results['events'] = [
                    ['id' => 1, 'title' => 'Tech Summit', 'type' => 'event'],
                ];
            }
            if ($type === 'all' || $type === 'organizations') {
                $results['organizations'] = [
                    ['id' => 1, 'name' => 'Computer Science Society', 'type' => 'organization'],
                ];
            }
        }

        return $this->response->setJSON(['success' => true, 'results' => $results, 'query' => $query]);
    }

    /**
     * Edit User Info
     */
    public function editUserInfo()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $userId = session()->get('user_id');
        
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            
            $rules = [
                'firstname' => 'required|min_length[2]',
                'lastname' => 'required|min_length[2]',
                'phone' => 'required',
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false, 
                    'errors' => $validation->getErrors()
                ]);
            }

            // Update profile
            $profileData = [
                'firstname' => $this->request->getPost('firstname'),
                'middlename' => $this->request->getPost('middlename'),
                'lastname' => $this->request->getPost('lastname'),
                'phone' => $this->request->getPost('phone'),
            ];

            $profile = $this->userProfileModel->where('user_id', $userId)->first();
            if ($profile) {
                $this->userProfileModel->update($profile['id'], $profileData);
            }

            // Update session
            $fullName = $profileData['firstname'] . ' ' . $profileData['lastname'];
            session()->set('name', $fullName);

            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Profile updated successfully!'
            ]);
        }

        // GET request - return user data
        $profile = $this->userProfileModel->where('user_id', $userId)->first();
        $user = $this->userModel->find($userId);
        $student = $this->studentModel->where('user_id', $userId)->first();

        return view('student/edit-profile', [
            'profile' => $profile,
            'user' => $user,
            'student' => $student
        ]);
    }

    /**
     * Post Comment
     */
    public function comment()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $content = $this->request->getPost('content');
        $type = $this->request->getPost('type'); // event, announcement, organization
        $targetId = $this->request->getPost('target_id');

        if (empty($content)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comment cannot be empty']);
        }

        // Mock comment creation - implement actual database logic
        $comment = [
            'id' => rand(1000, 9999),
            'content' => $content,
            'user_id' => session()->get('user_id'),
            'user_name' => session()->get('name'),
            'type' => $type,
            'target_id' => $targetId,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Comment posted successfully!',
            'comment' => $comment
        ]);
    }

    /**
     * Join Event
     */
    public function joinEvent()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $eventId = $this->request->getPost('event_id');

        if (empty($eventId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Event ID is required']);
        }

        // Mock event joining - implement actual database logic
        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Successfully registered for the event!',
            'event_id' => $eventId
        ]);
    }

    /**
     * Join Organization
     */
    public function joinOrg()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $orgId = $this->request->getPost('org_id');

        if (empty($orgId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization ID is required']);
        }

        // Get student data
        $userId = session()->get('user_id');
        $student = $this->studentModel->where('user_id', $userId)->first();
        
        if (!$student) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Student record not found'
            ]);
        }

        // Get organization data
        $orgModel = new OrganizationModel();
        $organization = $orgModel->find($orgId);
        
        if (!$organization) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Organization not found'
            ]);
        }

        // Check if student already has a membership request (pending or active) for this organization
        $membershipModel = new StudentOrganizationMembershipModel();
        $existingMembership = $membershipModel->hasMembership($student['id'], $orgId);
        
        if ($existingMembership) {
            $statusMsg = $existingMembership['status'] === 'pending' 
                ? 'You have a pending request to join ' . $organization['organization_name']
                : 'You are already a member of ' . $organization['organization_name'];
            
            return $this->response->setJSON([
                'success' => false, 
                'message' => $statusMsg
            ]);
        }

        // Create pending membership request
        $membershipData = [
            'student_id' => $student['id'],
            'org_id' => $orgId,
            'status' => 'pending'
        ];

        if ($membershipModel->insert($membershipData)) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Membership request sent to ' . $organization['organization_name'] . '. Waiting for approval.',
                'org_id' => $orgId,
                'status' => 'pending'
            ]);
        }

        return $this->response->setJSON([
            'success' => false, 
            'message' => 'Failed to join organization. Please try again.'
        ]);
    }

    /**
     * View Organizations
     */
    public function viewOrganizations()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Mock organizations data
        $organizations = [
            [
                'id' => 1,
                'name' => 'Computer Science Society',
                'acronym' => 'CSS',
                'type' => 'Academic',
                'members' => 156,
                'description' => 'A community of tech enthusiasts and future IT professionals.',
                'is_member' => true
            ],
            [
                'id' => 2,
                'name' => 'Business Administration Club',
                'acronym' => 'BAC',
                'type' => 'Academic',
                'members' => 203,
                'description' => 'Developing future business leaders and entrepreneurs.',
                'is_member' => false
            ],
            [
                'id' => 3,
                'name' => 'Green Energy Initiative',
                'acronym' => 'GEI',
                'type' => 'Environmental',
                'members' => 89,
                'description' => 'Promoting environmental awareness and sustainability.',
                'is_member' => false
            ]
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'organizations' => $organizations]);
        }

        return view('student/organizations', ['organizations' => $organizations]);
    }

    /**
     * View Products
     */
    public function viewProducts()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Mock products data - organization merchandise
        $products = [
            [
                'id' => 1,
                'name' => 'CSS Official T-Shirt',
                'organization' => 'Computer Science Society',
                'price' => 350.00,
                'description' => 'Official CSS t-shirt with embroidered logo.',
                'stock' => 45,
                'image' => 'css-tshirt.jpg'
            ],
            [
                'id' => 2,
                'name' => 'BAC Hoodie',
                'organization' => 'Business Administration Club',
                'price' => 650.00,
                'description' => 'Premium quality hoodie with BAC branding.',
                'stock' => 23,
                'image' => 'bac-hoodie.jpg'
            ],
            [
                'id' => 3,
                'name' => 'GEI Eco-Bag',
                'organization' => 'Green Energy Initiative',
                'price' => 150.00,
                'description' => 'Reusable eco-bag made from recycled materials.',
                'stock' => 100,
                'image' => 'gei-ecobag.jpg'
            ],
            [
                'id' => 4,
                'name' => 'CSPC Lanyard',
                'organization' => 'Student Council',
                'price' => 75.00,
                'description' => 'Official CSPC lanyard for ID holders.',
                'stock' => 200,
                'image' => 'cspc-lanyard.jpg'
            ]
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'products' => $products]);
        }

        return view('student/products', ['products' => $products]);
    }

    /**
     * Manage Cart Content
     */
    public function manageCartContent()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $action = $this->request->getPost('action'); // add, remove, update
        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity') ?? 1;

        // Get current cart from session
        $cart = session()->get('cart') ?? [];

        switch ($action) {
            case 'add':
                if (isset($cart[$productId])) {
                    $cart[$productId]['quantity'] += $quantity;
                } else {
                    $cart[$productId] = [
                        'product_id' => $productId,
                        'quantity' => $quantity
                    ];
                }
                $message = 'Product added to cart!';
                break;

            case 'remove':
                unset($cart[$productId]);
                $message = 'Product removed from cart!';
                break;

            case 'update':
                if (isset($cart[$productId])) {
                    if ($quantity <= 0) {
                        unset($cart[$productId]);
                    } else {
                        $cart[$productId]['quantity'] = $quantity;
                    }
                }
                $message = 'Cart updated!';
                break;

            case 'clear':
                $cart = [];
                $message = 'Cart cleared!';
                break;

            default:
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid action']);
        }

        session()->set('cart', $cart);

        return $this->response->setJSON([
            'success' => true, 
            'message' => $message,
            'cart' => $cart,
            'cart_count' => count($cart)
        ]);
    }

    /**
     * View Cart
     */
    public function viewCart()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $cart = session()->get('cart') ?? [];
        
        // Mock cart items with product details
        $cartItems = [];
        $total = 0;

        // Mock product data - replace with actual database queries
        $products = [
            1 => ['name' => 'CSS Official T-Shirt', 'price' => 350.00, 'organization' => 'Computer Science Society'],
            2 => ['name' => 'BAC Hoodie', 'price' => 650.00, 'organization' => 'Business Administration Club'],
            3 => ['name' => 'GEI Eco-Bag', 'price' => 150.00, 'organization' => 'Green Energy Initiative'],
            4 => ['name' => 'CSPC Lanyard', 'price' => 75.00, 'organization' => 'Student Council'],
        ];

        foreach ($cart as $productId => $item) {
            if (isset($products[$productId])) {
                $product = $products[$productId];
                $subtotal = $product['price'] * $item['quantity'];
                $cartItems[] = [
                    'product_id' => $productId,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'organization' => $product['organization'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal
                ];
                $total += $subtotal;
            }
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true, 
                'cart_items' => $cartItems,
                'total' => $total
            ]);
        }

        return view('student/cart', ['cartItems' => $cartItems, 'total' => $total]);
    }

    /**
     * Purchase Product
     */
    public function purchaseProduct()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $paymentMethod = $this->request->getPost('payment_method');
        $cart = session()->get('cart') ?? [];

        if (empty($cart)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Your cart is empty']);
        }

        // Mock order creation - implement actual database logic
        $orderId = 'ORD-' . strtoupper(uniqid());
        
        // Clear cart after successful order
        session()->remove('cart');

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Order placed successfully!',
            'order_id' => $orderId,
            'payment_method' => $paymentMethod
        ]);
    }

    /**
     * View Pending Payments
     */
    public function viewPendingPayments()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Mock pending payments data
        $pendingPayments = [
            [
                'id' => 1,
                'order_id' => 'ORD-6745ABC1',
                'description' => 'CSS Official T-Shirt x2',
                'amount' => 700.00,
                'due_date' => '2025-12-01',
                'organization' => 'Computer Science Society',
                'status' => 'pending'
            ],
            [
                'id' => 2,
                'order_id' => 'ORD-6745ABC2',
                'description' => 'Tech Summit 2025 Registration',
                'amount' => 150.00,
                'due_date' => '2025-12-03',
                'organization' => 'Computer Science Society',
                'status' => 'pending'
            ]
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'pending_payments' => $pendingPayments]);
        }

        return view('student/pending-payments', ['pendingPayments' => $pendingPayments]);
    }

    /**
     * View Payment History
     */
    public function viewPaymentHistory()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Mock payment history data
        $paymentHistory = [
            [
                'id' => 1,
                'transaction_id' => 'TXN-9876DEF1',
                'order_id' => 'ORD-5634XYZ1',
                'description' => 'GEI Eco-Bag',
                'amount' => 150.00,
                'payment_date' => '2025-11-15',
                'payment_method' => 'GCash',
                'organization' => 'Green Energy Initiative',
                'status' => 'completed'
            ],
            [
                'id' => 2,
                'transaction_id' => 'TXN-9876DEF2',
                'order_id' => 'ORD-5634XYZ2',
                'description' => 'CSS Membership Fee',
                'amount' => 200.00,
                'payment_date' => '2025-11-10',
                'payment_method' => 'Cash',
                'organization' => 'Computer Science Society',
                'status' => 'completed'
            ],
            [
                'id' => 3,
                'transaction_id' => 'TXN-9876DEF3',
                'order_id' => 'ORD-5634XYZ3',
                'description' => 'Business Workshop Registration',
                'amount' => 300.00,
                'payment_date' => '2025-11-05',
                'payment_method' => 'Bank Transfer',
                'organization' => 'Business Administration Club',
                'status' => 'completed'
            ]
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'payment_history' => $paymentHistory]);
        }

        return view('student/payment-history', ['paymentHistory' => $paymentHistory]);
    }

    /**
     * Get Notifications
     */
    public function getNotifications()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Mock notifications data - replace with actual database queries
        $notifications = [
            [
                'id' => 1,
                'type' => 'event',
                'title' => 'New Event: Tech Innovation Summit',
                'message' => 'Computer Science Society posted a new event. Registration is now open!',
                'time' => '2 hours ago',
                'read' => false,
                'link' => '#events'
            ],
            [
                'id' => 2,
                'type' => 'payment',
                'title' => 'Payment Reminder',
                'message' => 'Your payment for CSS T-Shirt is due on December 1, 2025.',
                'time' => '5 hours ago',
                'read' => false,
                'link' => '#payments'
            ],
            [
                'id' => 3,
                'type' => 'announcement',
                'title' => 'Important: Enrollment Extended',
                'message' => 'The enrollment period has been extended until December 15, 2025.',
                'time' => '1 day ago',
                'read' => false,
                'link' => '#announcements'
            ],
            [
                'id' => 4,
                'type' => 'organization',
                'title' => 'Membership Approved',
                'message' => 'Your membership request to Tech Innovation Hub has been approved!',
                'time' => '2 days ago',
                'read' => true,
                'link' => '#organizations'
            ],
            [
                'id' => 5,
                'type' => 'comment',
                'title' => 'New Reply to Your Comment',
                'message' => 'John Doe replied to your comment on "Business Plan Competition".',
                'time' => '3 days ago',
                'read' => true,
                'link' => '#events'
            ]
        ];

        $unreadCount = count(array_filter($notifications, fn($n) => !$n['read']));

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark Notification as Read
     */
    public function markNotificationRead()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $notificationId = $this->request->getPost('notification_id');
        $markAll = $this->request->getPost('mark_all') === 'true';

        // Mock implementation - replace with actual database logic
        if ($markAll) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Notification marked as read',
            'notification_id' => $notificationId
        ]);
    }

    /**
     * Dismiss Notification
     */
    public function dismissNotification()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $notificationId = $this->request->getPost('notification_id');

        // Mock implementation - replace with actual database logic
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Notification dismissed',
            'notification_id' => $notificationId
        ]);
    }

    /**
     * Logout
     */
    public function logout()
    {
        $session = session();
        
        // Check if user logged in with Google
        if ($session->get('google_login')) {
            // Redirect to Google logout which will handle everything
            return redirect()->to(base_url('auth/googleLogout'));
        }
        
        // Normal logout for non-Google users
        $session->remove(['isLoggedIn', 'role', 'user_id', 'student_id', 'email', 'name', 'photo']);
        $session->destroy();
        
        // Set flash message for after redirect
        $session = \Config\Services::session();
        $session->setFlashdata('success', 'You have been logged out successfully.');
        
        // Redirect to login page
        return redirect()->to(base_url('auth/login'));
    }
}

