<?php

namespace App\Controllers;

use App\Models\OrganizationModel;
use App\Models\OrganizationApplicationModel;
use App\Models\OrganizationAdvisorModel;
use App\Models\OrganizationOfficerModel;
use App\Models\OrganizationFileModel;

class Organization extends BaseController
{
    protected $helpers = ['url'];
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    /**
     * Organization Dashboard
     */
    public function dashboard()
    {
        // Check if organization is logged in
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return redirect()->to(base_url('auth/login'))->with('error', 'Please login as an organization.');
        }

        $data = [
            'title' => 'Organization Dashboard',
            'organization' => $this->getOrganizationData(),
            'stats' => $this->getDashboardStats(),
            'recentEvents' => $this->getRecentEvents(),
            'recentAnnouncements' => $this->getRecentAnnouncements(),
            'pendingPayments' => $this->getPendingPayments(),
            'recentMembers' => $this->getRecentMembers(),
        ];

        return view('organization/dashboard', $data);
    }

    /**
     * Get organization data
     */
    private function getOrganizationData()
    {
        $orgId = $this->session->get('organization_id');
        
        // Sample data - replace with actual database query
        return [
            'id' => $orgId ?? 1,
            'name' => $this->session->get('organization_name') ?? 'Computer Science Society',
            'acronym' => $this->session->get('organization_acronym') ?? 'CSS',
            'type' => 'Academic',
            'category' => 'Departmental',
            'email' => $this->session->get('email') ?? 'css@university.edu',
            'phone' => '+63 912 345 6789',
            'photo' => $this->session->get('photo') ?? null,
            'mission' => 'To foster excellence in computer science education and innovation.',
            'vision' => 'To be the leading student organization in technology and innovation.',
            'founded' => '2015-06-15',
            'status' => 'active',
        ];
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        return [
            'total_members' => 156,
            'pending_members' => 12,
            'total_events' => 24,
            'upcoming_events' => 3,
            'total_products' => 15,
            'pending_payments' => 28,
            'total_revenue' => 45680,
            'announcements' => 18,
        ];
    }

    /**
     * Get recent events
     */
    private function getRecentEvents()
    {
        return [
            [
                'id' => 1,
                'title' => 'Tech Innovation Summit 2025',
                'description' => 'Annual technology summit featuring industry speakers and workshops.',
                'date' => '2025-12-15',
                'time' => '09:00 AM',
                'location' => 'University Auditorium',
                'attendees' => 89,
                'max_attendees' => 150,
                'status' => 'upcoming',
                'image' => null,
            ],
            [
                'id' => 2,
                'title' => 'Coding Bootcamp: Web Development',
                'description' => 'Intensive 3-day bootcamp on modern web development.',
                'date' => '2025-12-20',
                'time' => '08:00 AM',
                'location' => 'Computer Lab A',
                'attendees' => 35,
                'max_attendees' => 40,
                'status' => 'upcoming',
                'image' => null,
            ],
            [
                'id' => 3,
                'title' => 'Hackathon 2025',
                'description' => '24-hour coding competition with exciting prizes.',
                'date' => '2025-12-28',
                'time' => '06:00 PM',
                'location' => 'Innovation Hub',
                'attendees' => 120,
                'max_attendees' => 200,
                'status' => 'upcoming',
                'image' => null,
            ],
        ];
    }

    /**
     * Get recent announcements
     */
    private function getRecentAnnouncements()
    {
        return [
            [
                'id' => 1,
                'title' => 'Membership Fee Deadline Extended',
                'content' => 'The deadline for membership fee payment has been extended to December 15, 2025.',
                'priority' => 'high',
                'created_at' => '2025-11-24 10:30:00',
                'views' => 234,
            ],
            [
                'id' => 2,
                'title' => 'New Merchandise Available',
                'content' => 'Check out our new CSS hoodies and shirts now available for pre-order!',
                'priority' => 'normal',
                'created_at' => '2025-11-22 14:00:00',
                'views' => 156,
            ],
            [
                'id' => 3,
                'title' => 'General Assembly Schedule',
                'content' => 'Our general assembly will be held on December 5, 2025. Attendance is mandatory.',
                'priority' => 'high',
                'created_at' => '2025-11-20 09:00:00',
                'views' => 312,
            ],
        ];
    }

    /**
     * Get pending payments
     */
    private function getPendingPayments()
    {
        return [
            [
                'id' => 1,
                'student_name' => 'John Dela Cruz',
                'student_id' => 'STU-2024-001',
                'product' => 'CSS T-Shirt (Large)',
                'amount' => 450,
                'submitted_at' => '2025-11-24 15:30:00',
                'proof_image' => null,
            ],
            [
                'id' => 2,
                'student_name' => 'Maria Santos',
                'student_id' => 'STU-2024-002',
                'product' => 'Membership Fee',
                'amount' => 250,
                'submitted_at' => '2025-11-24 12:00:00',
                'proof_image' => null,
            ],
            [
                'id' => 3,
                'student_name' => 'Pedro Garcia',
                'student_id' => 'STU-2024-003',
                'product' => 'CSS Hoodie (Medium)',
                'amount' => 850,
                'submitted_at' => '2025-11-23 18:45:00',
                'proof_image' => null,
            ],
        ];
    }

    /**
     * Get recent members
     */
    private function getRecentMembers()
    {
        return [
            [
                'id' => 1,
                'name' => 'Anna Reyes',
                'student_id' => 'STU-2024-010',
                'email' => 'anna.reyes@student.edu',
                'course' => 'BS Computer Science',
                'year' => '3rd Year',
                'status' => 'pending',
                'applied_at' => '2025-11-24 16:00:00',
            ],
            [
                'id' => 2,
                'name' => 'Carlos Mendoza',
                'student_id' => 'STU-2024-011',
                'email' => 'carlos.m@student.edu',
                'course' => 'BS Information Technology',
                'year' => '2nd Year',
                'status' => 'pending',
                'applied_at' => '2025-11-24 14:30:00',
            ],
            [
                'id' => 3,
                'name' => 'Lisa Fernandez',
                'student_id' => 'STU-2024-012',
                'email' => 'lisa.f@student.edu',
                'course' => 'BS Computer Science',
                'year' => '1st Year',
                'status' => 'active',
                'applied_at' => '2025-11-23 10:00:00',
            ],
        ];
    }

    // ==========================================
    // ANNOUNCEMENT FUNCTIONS
    // ==========================================

    /**
     * View all announcements
     */
    public function viewAnnouncements()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $announcements = $this->getRecentAnnouncements();
        return $this->response->setJSON(['success' => true, 'data' => $announcements]);
    }

    /**
     * Create announcement
     */
    public function createAnnouncement()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
        $priority = $this->request->getPost('priority') ?? 'normal';

        // Validation
        if (empty($title) || empty($content)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Title and content are required']);
        }

        // TODO: Save to database
        $announcement = [
            'id' => rand(100, 999),
            'title' => $title,
            'content' => $content,
            'priority' => $priority,
            'created_at' => date('Y-m-d H:i:s'),
            'views' => 0,
        ];

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Announcement created successfully',
            'data' => $announcement
        ]);
    }

    /**
     * Update announcement
     */
    public function updateAnnouncement($id = null)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
        $priority = $this->request->getPost('priority');

        // TODO: Update in database

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Announcement updated successfully'
        ]);
    }

    /**
     * Delete announcement
     */
    public function deleteAnnouncement($id = null)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // TODO: Delete from database

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Announcement deleted successfully'
        ]);
    }

    // ==========================================
    // EVENT FUNCTIONS
    // ==========================================

    /**
     * View all events
     */
    public function viewEvents()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $events = $this->getRecentEvents();
        return $this->response->setJSON(['success' => true, 'data' => $events]);
    }

    /**
     * Create event
     */
    public function createEvent()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'date' => $this->request->getPost('date'),
            'time' => $this->request->getPost('time'),
            'location' => $this->request->getPost('location'),
            'max_attendees' => $this->request->getPost('max_attendees'),
        ];

        // Validation
        if (empty($data['title']) || empty($data['date'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Title and date are required']);
        }

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/events/', $newName);
            $data['image'] = $newName;
        }

        // TODO: Save to database
        $data['id'] = rand(100, 999);
        $data['attendees'] = 0;
        $data['status'] = 'upcoming';

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $data
        ]);
    }

    /**
     * Update event
     */
    public function updateEvent($id = null)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // TODO: Update in database

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Event updated successfully'
        ]);
    }

    /**
     * Delete event
     */
    public function deleteEvent($id = null)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // TODO: Delete from database

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }

    /**
     * View event attendees
     */
    public function viewEventAttendees($id = null)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // Sample data
        $attendees = [
            ['id' => 1, 'name' => 'John Doe', 'student_id' => 'STU-001', 'registered_at' => '2025-11-20'],
            ['id' => 2, 'name' => 'Jane Smith', 'student_id' => 'STU-002', 'registered_at' => '2025-11-21'],
        ];

        return $this->response->setJSON(['success' => true, 'data' => $attendees]);
    }

    // ==========================================
    // ORGANIZATION INFO FUNCTIONS
    // ==========================================

    /**
     * Edit organization info
     */
    public function editOrgInfo()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $data = [
            'mission' => $this->request->getPost('mission'),
            'vision' => $this->request->getPost('vision'),
            'contact_email' => $this->request->getPost('contact_email'),
            'contact_phone' => $this->request->getPost('contact_phone'),
        ];

        // Handle logo upload
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move(WRITEPATH . 'uploads/organizations/logos/', $newName);
            $data['logo'] = $newName;
        }

        // TODO: Update in database

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Organization information updated successfully'
        ]);
    }

    // ==========================================
    // MEMBER MANAGEMENT FUNCTIONS
    // ==========================================

    /**
     * View all members
     */
    public function viewMembers()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // Sample data - all members
        $members = [
            ['id' => 1, 'name' => 'Anna Reyes', 'student_id' => 'STU-2024-010', 'course' => 'BSCS', 'year' => '3rd', 'status' => 'active', 'joined_at' => '2025-09-01'],
            ['id' => 2, 'name' => 'Carlos Mendoza', 'student_id' => 'STU-2024-011', 'course' => 'BSIT', 'year' => '2nd', 'status' => 'active', 'joined_at' => '2025-09-15'],
            ['id' => 3, 'name' => 'Lisa Fernandez', 'student_id' => 'STU-2024-012', 'course' => 'BSCS', 'year' => '1st', 'status' => 'pending', 'joined_at' => '2025-11-23'],
        ];

        return $this->response->setJSON(['success' => true, 'data' => $members]);
    }

    /**
     * Manage members (approve, reject, remove)
     */
    public function manageMembers()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $action = $this->request->getPost('action'); // approve, reject, remove
        $memberId = $this->request->getPost('member_id');

        if (empty($action) || empty($memberId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        // TODO: Update in database based on action

        $messages = [
            'approve' => 'Member approved successfully',
            'reject' => 'Member application rejected',
            'remove' => 'Member removed from organization',
        ];

        return $this->response->setJSON([
            'success' => true,
            'message' => $messages[$action] ?? 'Action completed'
        ]);
    }

    // ==========================================
    // PRODUCT MANAGEMENT FUNCTIONS
    // ==========================================

    /**
     * View all products
     */
    public function viewProducts()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $products = [
            [
                'id' => 1,
                'name' => 'CSS T-Shirt',
                'description' => 'Official CSS organization t-shirt',
                'price' => 450,
                'stock' => 25,
                'sold' => 75,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'image' => null,
                'status' => 'available',
            ],
            [
                'id' => 2,
                'name' => 'CSS Hoodie',
                'description' => 'Premium quality hoodie with CSS logo',
                'price' => 850,
                'stock' => 10,
                'sold' => 40,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'image' => null,
                'status' => 'available',
            ],
            [
                'id' => 3,
                'name' => 'CSS Lanyard',
                'description' => 'Organization lanyard with ID holder',
                'price' => 150,
                'stock' => 0,
                'sold' => 100,
                'sizes' => null,
                'image' => null,
                'status' => 'out_of_stock',
            ],
        ];

        return $this->response->setJSON(['success' => true, 'data' => $products]);
    }

    /**
     * Create product
     */
    public function createProduct()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'sizes' => $this->request->getPost('sizes'),
        ];

        // Validation
        if (empty($data['name']) || empty($data['price'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Name and price are required']);
        }

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/products/', $newName);
            $data['image'] = $newName;
        }

        // TODO: Save to database
        $data['id'] = rand(100, 999);
        $data['sold'] = 0;
        $data['status'] = 'available';

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $data
        ]);
    }

    /**
     * Manage products (update, delete)
     */
    public function manageProducts()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $action = $this->request->getPost('action'); // update, delete
        $productId = $this->request->getPost('product_id');

        if ($action === 'delete') {
            // TODO: Delete from database
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        }

        // Update product
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
        ];

        // TODO: Update in database

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Product updated successfully'
        ]);
    }

    /**
     * Update product stocks
     */
    public function updateStocks()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $productId = $this->request->getPost('product_id');
        $newStock = $this->request->getPost('stock');

        if (empty($productId) || !is_numeric($newStock)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        // TODO: Update in database

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Stock updated successfully',
            'new_stock' => $newStock
        ]);
    }

    // ==========================================
    // PAYMENT FUNCTIONS
    // ==========================================

    /**
     * View pending payments
     */
    public function viewPendingPayments()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $payments = $this->getPendingPayments();
        return $this->response->setJSON(['success' => true, 'data' => $payments]);
    }

    /**
     * Confirm payment
     */
    public function confirmPayment()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $paymentId = $this->request->getPost('payment_id');
        $action = $this->request->getPost('action'); // approve, reject

        if (empty($paymentId) || empty($action)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        // TODO: Update payment status in database

        $messages = [
            'approve' => 'Payment confirmed successfully',
            'reject' => 'Payment rejected',
        ];

        return $this->response->setJSON([
            'success' => true,
            'message' => $messages[$action] ?? 'Action completed'
        ]);
    }

    /**
     * View payment history
     */
    public function viewPaymentHistory()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // Sample data
        $history = [
            ['id' => 1, 'student_name' => 'John Doe', 'product' => 'CSS T-Shirt', 'amount' => 450, 'status' => 'confirmed', 'confirmed_at' => '2025-11-20'],
            ['id' => 2, 'student_name' => 'Jane Smith', 'product' => 'Membership Fee', 'amount' => 250, 'status' => 'confirmed', 'confirmed_at' => '2025-11-19'],
        ];

        return $this->response->setJSON(['success' => true, 'data' => $history]);
    }

    // ==========================================
    // REPORTS & SUMMARY
    // ==========================================

    /**
     * Generate summary/reports
     */
    public function generateSummary()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $type = $this->request->getGet('type') ?? 'overview'; // overview, financial, members, events
        $period = $this->request->getGet('period') ?? 'month'; // week, month, semester, year

        $summary = [
            'period' => $period,
            'type' => $type,
            'generated_at' => date('Y-m-d H:i:s'),
            'data' => []
        ];

        switch ($type) {
            case 'financial':
                $summary['data'] = [
                    'total_revenue' => 45680,
                    'total_expenses' => 12500,
                    'net_income' => 33180,
                    'pending_collections' => 8500,
                    'breakdown' => [
                        ['category' => 'Merchandise', 'amount' => 32000],
                        ['category' => 'Membership Fees', 'amount' => 10680],
                        ['category' => 'Event Fees', 'amount' => 3000],
                    ]
                ];
                break;
            case 'members':
                $summary['data'] = [
                    'total_members' => 156,
                    'new_members' => 24,
                    'active_members' => 142,
                    'inactive_members' => 14,
                    'by_course' => [
                        ['course' => 'BSCS', 'count' => 89],
                        ['course' => 'BSIT', 'count' => 45],
                        ['course' => 'BSIS', 'count' => 22],
                    ]
                ];
                break;
            case 'events':
                $summary['data'] = [
                    'total_events' => 24,
                    'completed_events' => 21,
                    'upcoming_events' => 3,
                    'total_attendees' => 1250,
                    'avg_attendance' => 52,
                ];
                break;
            default: // overview
                $summary['data'] = [
                    'members' => ['total' => 156, 'new' => 24],
                    'events' => ['total' => 24, 'upcoming' => 3],
                    'revenue' => ['total' => 45680, 'pending' => 8500],
                    'products' => ['total' => 15, 'sold' => 215],
                ];
        }

        return $this->response->setJSON(['success' => true, 'data' => $summary]);
    }

    // ==========================================
    // NOTIFICATIONS
    // ==========================================

    /**
     * Get notifications
     */
    public function getNotifications()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $notifications = [
            ['id' => 1, 'type' => 'payment', 'title' => 'New Payment', 'message' => 'John Dela Cruz submitted payment for CSS T-Shirt', 'time' => '5 min ago', 'read' => false],
            ['id' => 2, 'type' => 'member', 'title' => 'New Member Request', 'message' => 'Anna Reyes requested to join your organization', 'time' => '1 hour ago', 'read' => false],
            ['id' => 3, 'type' => 'event', 'title' => 'Event Reminder', 'message' => 'Tech Innovation Summit is in 3 days', 'time' => '2 hours ago', 'read' => true],
        ];

        return $this->response->setJSON(['success' => true, 'data' => $notifications]);
    }

    // ==========================================
    // LOGOUT
    // ==========================================

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
        $session->remove(['isLoggedIn', 'role', 'user_id', 'organization_id', 'organization_name', 'organization_acronym', 'email', 'name', 'photo']);
        $session->destroy();
        
        // Set flash message for after redirect
        $session = \Config\Services::session();
        $session->setFlashdata('success', 'You have been logged out successfully.');
        
        // Redirect to login page
        return redirect()->to(base_url('auth/login'));
    }

    // ==========================================
    // ORGANIZATION LAUNCH (EXISTING)
    // ==========================================

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
            'status' => 'pending',
            'submitted_at' => date('Y-m-d H:i:s')
        ];
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
