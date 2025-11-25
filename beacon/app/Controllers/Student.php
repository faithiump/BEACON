<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\AddressModel;
use App\Models\StudentModel;

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

        $data = [
            'student' => $student,
            'profile' => $profile,
            'user' => $user,
            'pageTitle' => 'Student Dashboard'
        ];

        return view('student/dashboard', $data);
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

        // Mock organization joining - implement actual database logic
        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Membership request submitted successfully!',
            'org_id' => $orgId
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
        session()->destroy();
        return redirect()->to(base_url('auth/login'))->with('success', 'You have been logged out successfully.');
    }
}

