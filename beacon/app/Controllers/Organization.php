<?php

namespace App\Controllers;

use App\Models\OrganizationModel;
use App\Models\OrganizationApplicationModel;
use App\Models\OrganizationAdvisorModel;
use App\Models\OrganizationOfficerModel;
use App\Models\OrganizationFileModel;
use App\Models\EventModel;
use App\Models\AnnouncementModel;
use App\Models\ProductModel;
use App\Models\StudentModel;
use App\Models\StudentOrganizationMembershipModel;
use App\Models\UserPhotoModel;
use App\Models\ForumPostModel;

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

        // Ensure uploads directories exist
        $eventsPath = FCPATH . 'uploads/events/';
        $productsPath = FCPATH . 'uploads/products/';
        if (!is_dir($eventsPath)) {
            mkdir($eventsPath, 0755, true);
        }
        if (!is_dir($productsPath)) {
            mkdir($productsPath, 0755, true);
        }

        // Get forum category counts
        $forumPostModel = new ForumPostModel();
        $categoryCounts = $forumPostModel->getCategoryCounts();

        // Get forum category counts
        $forumPostModel = new ForumPostModel();
        $categoryCounts = $forumPostModel->getCategoryCounts();

        // Get all events and announcements from all organizations
        $recentEvents = $this->getRecentEvents(null); // Show events from all organizations
        $recentAnnouncements = $this->getRecentAnnouncements();
        
        // Combine announcements and events into a single feed (like student dashboard)
        $allPosts = [];
        foreach ($recentAnnouncements as $announcement) {
            $allPosts[] = [
                'type' => 'announcement',
                'data' => $announcement,
                'date' => strtotime($announcement['created_at'])
            ];
        }
        foreach ($recentEvents as $event) {
            $allPosts[] = [
                'type' => 'event',
                'data' => $event,
                'date' => strtotime($event['created_at'] ?? $event['date'])
            ];
        }
        
        // Sort by date (newest first)
        usort($allPosts, function($a, $b) {
            return $b['date'] - $a['date'];
        });
        
        $data = [
            'title' => 'Organization Dashboard',
            'organization' => $this->getOrganizationData(),
            'stats' => $this->getDashboardStats(),
            'recentEvents' => $recentEvents,
            'recentAnnouncements' => $recentAnnouncements,
            'allPosts' => $allPosts, // Combined feed for overview
            'pendingPayments' => $this->getPendingPayments(),
            'recentMembers' => $this->getRecentMembers(),
            'products' => $this->getRecentProducts(),
            'forumCategoryCounts' => $categoryCounts,
        ];

        return view('organization/dashboard', $data);
    }

    /**
     * Get organization data
     */
    private function getOrganizationData()
    {
        $orgId = $this->session->get('organization_id');
        
        if (!$orgId) {
            return [
                'id' => null,
                'name' => $this->session->get('organization_name') ?? 'Organization',
                'acronym' => $this->session->get('organization_acronym') ?? 'ORG',
                'type' => 'Academic',
                'category' => 'Departmental',
                'department' => '',
                'email' => $this->session->get('email') ?? '',
                'phone' => '',
                'photo' => null,
                'mission' => '',
                'vision' => '',
                'founding_date' => '',
                'objectives' => '',
                'current_members' => 0,
                'status' => 'active',
                'advisor_name' => '',
                'advisor_email' => '',
                'advisor_phone' => '',
                'advisor_department' => '',
                'officer_position' => '',
                'officer_name' => '',
                'officer_email' => '',
                'officer_phone' => '',
                'officer_student_id' => '',
            ];
        }
        
        // Fetch from database
        $orgModel = new OrganizationModel();
        $organization = $orgModel->find($orgId);
        
        if ($organization) {
            // Fetch advisor information
            $advisorModel = new OrganizationAdvisorModel();
            $db = \Config\Database::connect();
            
            // Get email from users table
            $user = $db->table('users')
                ->where('id', $organization['user_id'])
                ->get()
                ->getRowArray();
            
            // Get photo from user_photos table
            $userPhotoModel = new UserPhotoModel();
            $userPhoto = $userPhotoModel->where('user_id', $organization['user_id'])->first();
            $photoUrl = null;
            if ($userPhoto && !empty($userPhoto['photo_path'])) {
                $photoUrl = base_url($userPhoto['photo_path']);
            }
            
            // Get advisor through organization application
            $advisor = $db->table('organization_advisors')
                ->select('organization_advisors.*')
                ->join('organization_applications', 'organization_applications.id = organization_advisors.application_id')
                ->where('organization_applications.organization_name', $organization['organization_name'])
                ->where('organization_applications.status', 'approved')
                ->get()
                ->getRowArray();
            
            // Get primary officer through organization application
            $officer = $db->table('organization_officers')
                ->select('organization_officers.*')
                ->join('organization_applications', 'organization_applications.id = organization_officers.application_id')
                ->where('organization_applications.organization_name', $organization['organization_name'])
                ->where('organization_applications.status', 'approved')
                ->where('organization_officers.position', 'President')
                ->get()
                ->getRowArray();
            
            // If no President found, get the first officer
            if (!$officer) {
                $officer = $db->table('organization_officers')
                    ->select('organization_officers.*')
                    ->join('organization_applications', 'organization_applications.id = organization_officers.application_id')
                    ->where('organization_applications.organization_name', $organization['organization_name'])
                    ->where('organization_applications.status', 'approved')
                    ->limit(1)
                    ->get()
                    ->getRowArray();
            }
            
            // Get department from organization_applications
            $application = $db->table('organization_applications')
                ->where('organization_name', $organization['organization_name'])
                ->where('status', 'approved')
                ->get()
                ->getRowArray();
            
            $department = $application['department'] ?? '';
            
            return [
                'id' => $organization['id'],
                'name' => $organization['organization_name'],
                'acronym' => $organization['organization_acronym'],
                'type' => ucfirst(str_replace('_', ' ', $organization['organization_type'] ?? 'academic')),
                'category' => ucfirst(str_replace('_', ' ', $organization['organization_category'] ?? 'departmental')),
                'department' => $department,
                'email' => $user['email'] ?? '',
                'contact_email' => $user['email'] ?? '',
                'phone' => $organization['contact_phone'] ?? '',
                'photo' => $photoUrl,
                'mission' => $organization['mission'] ?? '',
                'vision' => $organization['vision'] ?? '',
                'founding_date' => $organization['founding_date'] ?? '',
                'objectives' => $organization['objectives'] ?? '',
                'current_members' => $organization['current_members'] ?? 0,
                'status' => $organization['is_active'] ? 'active' : 'inactive',
                'advisor_name' => $advisor['name'] ?? '',
                'advisor_email' => $advisor['email'] ?? '',
                'advisor_phone' => $advisor['phone'] ?? '',
                'advisor_department' => $advisor['department'] ?? '',
                'officer_position' => $officer['position'] ?? '',
                'officer_name' => $officer['name'] ?? '',
                'officer_email' => $officer['email'] ?? '',
                'officer_phone' => $officer['phone'] ?? '',
                'officer_student_id' => $officer['student_id'] ?? '',
            ];
        }
        
        // Fallback to session data
        return [
            'id' => $orgId,
            'name' => $this->session->get('organization_name') ?? 'Organization',
            'acronym' => $this->session->get('organization_acronym') ?? 'ORG',
            'type' => 'Academic',
            'category' => 'Departmental',
            'email' => $this->session->get('email') ?? '',
            'phone' => '',
            'photo' => null,
            'mission' => '',
            'vision' => '',
            'founding_date' => '',
            'objectives' => '',
            'current_members' => 0,
            'status' => 'active',
            'advisor_name' => '',
            'advisor_email' => '',
            'advisor_phone' => '',
            'advisor_department' => '',
            'officer_position' => '',
            'officer_name' => '',
            'officer_email' => '',
            'officer_phone' => '',
            'officer_student_id' => '',
        ];
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        $orgId = $this->session->get('organization_id');
        
        // Get event statistics
        $eventModel = new EventModel();
        $totalEvents = 0;
        $upcomingEvents = 0;
        
        // Get announcement statistics
        $announcementModel = new AnnouncementModel();
        $totalAnnouncements = 0;
        
        // Get product statistics
        $productModel = new ProductModel();
        $totalProducts = 0;
        
        // Get member statistics
        $totalMembers = 0;
        $pendingMembers = 0;
        
        if ($orgId) {
            // Get organization data to get organization name
            $orgModel = new OrganizationModel();
            $organization = $orgModel->find($orgId);
            
            if ($organization) {
                // Count active and pending members using membership table
                $membershipModel = new StudentOrganizationMembershipModel();
                $activeMembers = $membershipModel->getActiveMemberships($orgId);
                $pendingMembersList = $membershipModel->getPendingMemberships($orgId);
                
                $totalMembers = count($activeMembers);
                $pendingMembers = count($pendingMembersList);
                
                // Update organization member count if it's different
                if ($organization['current_members'] != $totalMembers) {
                    $orgModel->update($orgId, ['current_members' => $totalMembers]);
                }
            }
            
            $allEvents = $eventModel->getEventsByOrg($orgId);
            $totalEvents = count($allEvents);
            
            $upcoming = $eventModel->getUpcomingEvents($orgId);
            $upcomingEvents = count($upcoming);
            
            $allAnnouncements = $announcementModel->getAnnouncementsByOrg($orgId);
            $totalAnnouncements = count($allAnnouncements);
            
            $allProducts = $productModel->getProductsByOrg($orgId);
            $totalProducts = count($allProducts);
        }

        return [
            'total_members' => $totalMembers,
            'pending_members' => $pendingMembers,
            'total_events' => $totalEvents,
            'upcoming_events' => $upcomingEvents,
            'total_products' => $totalProducts,
            'pending_payments' => 28,
            'total_revenue' => 45680,
            'announcements' => $totalAnnouncements,
        ];
    }

    /**
     * Get recent events from all active organizations
     * For Events section, filter to show only current organization's events
     */
    private function getRecentEvents($orgIdOnly = null)
    {
        $eventModel = new EventModel();
        $organizationModel = new OrganizationModel();
        $userPhotoModel = new UserPhotoModel();
        $likeModel = new \App\Models\PostLikeModel();
        $commentModel = new \App\Models\PostCommentModel();

        // Get all active organizations or just the specified one
        if ($orgIdOnly) {
            $org = $organizationModel->find($orgIdOnly);
            $allOrganizations = $org ? [$org] : [];
        } else {
            $allOrganizations = $organizationModel->where('is_active', 1)->findAll();
        }
        
        // Get events from all active organizations
        $allEvents = [];
        foreach ($allOrganizations as $org) {
            $orgEvents = $eventModel->getEventsByOrg($org['id']);
            
            // Get organization photo
            $orgPhoto = null;
            $userPhoto = $userPhotoModel->where('user_id', $org['user_id'])->first();
            if ($userPhoto && !empty($userPhoto['photo_path'])) {
                $orgPhoto = base_url($userPhoto['photo_path']);
            }
            
            foreach ($orgEvents as $event) {
                $eventId = $event['event_id'] ?? $event['id'];
                
                // Update event status in database based on current date/time
                // Force update to ensure status is current
                $eventModel->updateEventStatus($eventId);
                
                // Wait a moment to ensure database commit
                usleep(100000); // 0.1 second delay
                
                // Refresh event data to get updated status from database
                $db = \Config\Database::connect();
                $event = $db->table('events')
                    ->where('event_id', $eventId)
                    ->get()
                    ->getRowArray();
                
                // Double-check status is updated - recalculate if status seems incorrect
                // This ensures status matches what's shown on student pages
                $currentStatus = $event['status'] ?? 'upcoming';
                
                // Recalculate status to verify it's correct
                // Use application timezone for accurate time comparison
                date_default_timezone_set(config('App')->appTimezone);
                $eventTime = $event['time'] ?? '00:00:00';
                $eventTime = trim($eventTime);
                $now = time();
                
                // Parse start time
                if (stripos($eventTime, 'AM') !== false || stripos($eventTime, 'PM') !== false) {
                    $eventDateTime = $event['date'] . ' ' . $eventTime;
                    $eventTimestamp = strtotime($eventDateTime);
                } else {
                    if (substr_count($eventTime, ':') == 2) {
                        $timeParts = explode(':', $eventTime);
                        $eventTime = $timeParts[0] . ':' . $timeParts[1];
                    }
                    $eventDateTime = $event['date'] . ' ' . $eventTime;
                    $eventTimestamp = strtotime($eventDateTime);
                }
                
                // Parse end time
                $endDateTime = null;
                if (!empty($event['end_date']) && !empty($event['end_time'])) {
                    $endTime = trim($event['end_time']);
                    if (stripos($endTime, 'AM') !== false || stripos($endTime, 'PM') !== false) {
                        $endDateTime = strtotime($event['end_date'] . ' ' . $endTime);
                    } else {
                        if (substr_count($endTime, ':') == 2) {
                            $timeParts = explode(':', $endTime);
                            $endTime = $timeParts[0] . ':' . $timeParts[1];
                        }
                        $endDateTime = strtotime($event['end_date'] . ' ' . $endTime);
                    }
                } elseif (!empty($event['end_time'])) {
                    $endTime = trim($event['end_time']);
                    if (stripos($endTime, 'AM') !== false || stripos($endTime, 'PM') !== false) {
                        $endDateTime = strtotime($event['date'] . ' ' . $endTime);
                    } else {
                        if (substr_count($endTime, ':') == 2) {
                            $timeParts = explode(':', $endTime);
                            $endTime = $timeParts[0] . ':' . $timeParts[1];
                        }
                        $endDateTime = strtotime($event['date'] . ' ' . $endTime);
                    }
                } elseif (!empty($event['end_date'])) {
                    $endDateTime = strtotime($event['end_date'] . ' 23:59:59');
                } else {
                    $endDateTime = strtotime($event['date'] . ' 23:59:59');
                }
                
                // Calculate expected status
                $expectedStatus = 'upcoming';
                if ($now > $endDateTime) {
                    $expectedStatus = 'ended';
                } elseif ($now >= $eventTimestamp && $now <= $endDateTime) {
                    $expectedStatus = 'ongoing';
                }
                
                // Always force update to ensure status is correct (regardless of current status)
                // This ensures status matches what's shown on student pages
                // Update status ALWAYS to ensure it's current
                try {
                    $updateResult = $db->query("UPDATE events SET status = ? WHERE event_id = ?", [$expectedStatus, $eventId]);
                    if ($updateResult) {
                        $currentStatus = $expectedStatus;
                        log_message('debug', 'Force updated event ' . $eventId . ' status to ' . $expectedStatus . ' (was: ' . ($currentStatus ?? 'unknown') . ')');
                    } else {
                        log_message('warning', 'Failed to update event ' . $eventId . ' status to ' . $expectedStatus);
                        // Try alternative update method
                        try {
                            $db->table('events')->where('event_id', $eventId)->update(['status' => $expectedStatus]);
                            $currentStatus = $expectedStatus;
                        } catch (\Exception $e2) {
                            log_message('error', 'Alternative update also failed for event ' . $eventId . ': ' . $e2->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error updating event ' . $eventId . ' status: ' . $e->getMessage());
                }
                
                // Refresh event data to get updated status
                $event = $db->table('events')
                    ->where('event_id', $eventId)
                    ->get()
                    ->getRowArray();
                
                // Use the status from database (should match expectedStatus after update)
                if ($event && isset($event['status'])) {
                    $currentStatus = $event['status'];
                } else {
                    $currentStatus = $expectedStatus;
                }
                
                // Get reaction counts
                $reactionCounts = $likeModel->getReactionCounts('event', $eventId);
                
                // Get comment count
                $commentCount = $commentModel->getCommentCount('event', $eventId);
                
                // Get interest count
                $db = \Config\Database::connect();
                $interestCount = $db->table('event_interests')
                    ->where('event_id', $eventId)
                    ->countAllResults();
                
                // Format time - handle both TIME format and string format
                $timeFormatted = $event['time'];
                if (strpos($timeFormatted, ':') !== false) {
                    $timeParts = explode(':', $timeFormatted);
                    $hour = (int)$timeParts[0];
                    $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
                    $period = $hour >= 12 ? 'PM' : 'AM';
                    $hour12 = $hour > 12 ? $hour - 12 : ($hour == 0 ? 12 : $hour);
                    $timeFormatted = sprintf('%d:%02d %s', $hour12, $minute, $period);
                }
                
                $studentAccessList = [];
                if (!empty($event['student_access'])) {
                    $decodedStudents = is_array($event['student_access']) ? $event['student_access'] : json_decode($event['student_access'], true);
                    if (is_array($decodedStudents)) {
                        $studentAccessList = array_map('intval', $decodedStudents);
                    }
                }

                // Check if event is ongoing (date and time have arrived)
                $isOngoing = false;
                // Parse time - handle both 24-hour (08:00) and 12-hour (8:00 AM) formats
                // Use the RAW time from database, not the formatted one
                $eventTimeForCheck = $event['time'] ?? '00:00:00';
                // If time doesn't have AM/PM and is in 24-hour format, convert for strtotime
                if (strpos($eventTimeForCheck, 'AM') === false && strpos($eventTimeForCheck, 'PM') === false && strpos($eventTimeForCheck, ':') !== false) {
                    // Time is in 24-hour format like "08:00" or "08:00:00"
                    $timeParts = explode(':', $eventTimeForCheck);
                    $hour = (int)$timeParts[0];
                    $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
                    $eventTimeForCheck = sprintf('%02d:%02d:00', $hour, $minute);
                }
                $eventDateTime = $event['date'] . ' ' . $eventTimeForCheck;
                $eventTimestamp = strtotime($eventDateTime);
                $now = time();
                
                // Determine end time
                $endDateTime = null;
                if (!empty($event['end_date']) && !empty($event['end_time'])) {
                    $endTimeForCheck = $event['end_time'];
                    if (strpos($endTimeForCheck, 'AM') === false && strpos($endTimeForCheck, 'PM') === false && strpos($endTimeForCheck, ':') !== false) {
                        $timeParts = explode(':', $endTimeForCheck);
                        $hour = (int)$timeParts[0];
                        $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
                        $endTimeForCheck = sprintf('%02d:%02d:00', $hour, $minute);
                    }
                    $endDateTime = strtotime($event['end_date'] . ' ' . $endTimeForCheck);
                } elseif (!empty($event['end_time'])) {
                    $endTimeForCheck = $event['end_time'];
                    if (strpos($endTimeForCheck, 'AM') === false && strpos($endTimeForCheck, 'PM') === false && strpos($endTimeForCheck, ':') !== false) {
                        $timeParts = explode(':', $endTimeForCheck);
                        $hour = (int)$timeParts[0];
                        $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
                        $endTimeForCheck = sprintf('%02d:%02d:00', $hour, $minute);
                    }
                    $endDateTime = strtotime($event['date'] . ' ' . $endTimeForCheck);
                } elseif (!empty($event['end_date'])) {
                    $endDateTime = strtotime($event['end_date'] . ' 23:59:59');
                } else {
                    $endDateTime = strtotime($event['date'] . ' 23:59:59');
                }
                
                // Event is ongoing if current time is between start and end time
                if ($now >= $eventTimestamp && $now <= $endDateTime) {
                    $isOngoing = true;
                }
                
                // Status is already updated in database by updateEventStatus() and force update above
                // Use the currentStatus which is guaranteed to be correct after force update
                // This ensures status matches what's shown on student pages
                $eventStatus = $currentStatus ?? $expectedStatus ?? ($event['status'] ?? 'upcoming');
                
                // Final verification - get status directly from database one more time
                $finalEvent = $db->table('events')
                    ->where('event_id', $eventId)
                    ->select('status')
                    ->get()
                    ->getRowArray();
                
                if ($finalEvent && isset($finalEvent['status'])) {
                    $eventStatus = $finalEvent['status'];
                }
                
                // Log for debugging
                log_message('debug', 'Organization dashboard - Event ' . $eventId . ' final status: ' . $eventStatus);
                
                $allEvents[] = [
                    'id' => $eventId,
                    'org_id' => $org['id'],
                    'org_name' => $org['organization_name'],
                    'org_acronym' => $org['organization_acronym'],
                    'org_photo' => $orgPhoto,
                    'title' => $event['event_name'] ?? $event['title'],
                    'description' => $event['description'],
                    'date' => $event['date'],
                    'time' => $timeFormatted,
                    'location' => $event['venue'] ?? $event['location'],
                    'audience_type' => $event['audience_type'] ?? 'all',
                    'department_access' => $event['department_access'] ?? null,
                    'student_access' => $studentAccessList,
                    'attendees' => $event['current_attendees'] ?? 0,
                    'max_attendees' => $event['max_attendees'],
                    'status' => $eventStatus,
                    'is_ongoing' => $isOngoing,
                    'image' => $event['image'] ?? null,
                    'created_at' => $event['created_at'],
                    'reaction_counts' => $reactionCounts,
                    'comment_count' => $commentCount,
                    'interest_count' => $interestCount,
                ];
            }
        }

        // Sort by created_at descending (newest first) and limit to 20
        usort($allEvents, function($a, $b) {
            $dateA = $a['created_at'] ?? $a['date'];
            $dateB = $b['created_at'] ?? $b['date'];
            return strtotime($dateB) - strtotime($dateA);
        });

        return array_slice($allEvents, 0, 20);
    }

    /**
     * Get recent announcements from all active organizations
     */
    private function getRecentAnnouncements()
    {
        $announcementModel = new AnnouncementModel();
        $organizationModel = new OrganizationModel();
        $userPhotoModel = new UserPhotoModel();
        $likeModel = new \App\Models\PostLikeModel();
        $commentModel = new \App\Models\PostCommentModel();

        // Get all active organizations
        $allOrganizations = $organizationModel->where('is_active', 1)->findAll();
        
        // Get announcements from all active organizations
        $allAnnouncements = [];
        foreach ($allOrganizations as $org) {
            $orgAnnouncements = $announcementModel->getAnnouncementsByOrg($org['id'], 50);
            
            // Get organization photo
            $orgPhoto = null;
            $userPhoto = $userPhotoModel->where('user_id', $org['user_id'])->first();
            if ($userPhoto && !empty($userPhoto['photo_path'])) {
                $orgPhoto = base_url($userPhoto['photo_path']);
            }
            
            foreach ($orgAnnouncements as $announcement) {
                $announcementId = $announcement['announcement_id'];
                
                // Get reaction counts
                $reactionCounts = $likeModel->getReactionCounts('announcement', $announcementId);
                
                // Get comment count
                $commentCount = $commentModel->getCommentCount('announcement', $announcementId);
                
                $allAnnouncements[] = [
                    'id' => $announcementId,
                    'org_id' => $org['id'],
                    'org_name' => $org['organization_name'],
                    'org_acronym' => $org['organization_acronym'],
                    'org_photo' => $orgPhoto,
                    'title' => $announcement['title'],
                    'content' => $announcement['content'],
                    'priority' => $announcement['priority'] ?? 'normal',
                    'created_at' => $announcement['created_at'],
                    'views' => $announcement['views'] ?? 0,
                    'reaction_counts' => $reactionCounts,
                    'comment_count' => $commentCount,
                ];
            }
        }

        // Sort by created_at descending (newest first) and limit to 20
        usort($allAnnouncements, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return array_slice($allAnnouncements, 0, 20);
    }

    /**
     * Get recent products
     */
    private function getRecentProducts()
    {
        $orgId = $this->session->get('organization_id');
        if (!$orgId) {
            return [];
        }

        $productModel = new ProductModel();
        $products = $productModel->getProductsByOrg($orgId, 10);

        // Transform database format to view format
        $formattedProducts = [];
        foreach ($products as $product) {
            // Determine status based on stock
            $status = 'available';
            if ($product['stock'] == 0) {
                $status = 'out_of_stock';
            } elseif ($product['stock'] <= 10) {
                $status = 'low_stock';
            }

            $formattedProducts[] = [
                'id' => $product['product_id'],
                'name' => $product['product_name'],
                'description' => $product['description'] ?? '',
                'price' => (float)$product['price'],
                'stock' => (int)$product['stock'],
                'sold' => (int)($product['sold'] ?? 0),
                'sizes' => $product['sizes'] ? explode(',', $product['sizes']) : null,
                'image' => $product['image'] ?? null,
                'status' => $status,
            ];
        }

        return $formattedProducts;
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
        $orgId = $this->session->get('organization_id');
        if (!$orgId) {
            return [];
        }

        $membershipModel = new StudentOrganizationMembershipModel();
        
        // Get recent members (both active and pending)
        $allMemberships = array_merge(
            $membershipModel->getActiveMemberships($orgId),
            $membershipModel->getPendingMemberships($orgId)
        );
        
        // Sort by joined_at descending and limit to 10
        usort($allMemberships, function($a, $b) {
            return strtotime($b['joined_at']) - strtotime($a['joined_at']);
        });
        
        // Get student photos from user_photos table
        $userPhotoModel = new UserPhotoModel();
        $studentModel = new StudentModel();
        
        $recentMembers = [];
        foreach (array_slice($allMemberships, 0, 10) as $membership) {
            $yearLevel = $membership['year_level'] ?? 1;
            $yearText = $yearLevel == 1 ? '1st Year' : ($yearLevel == 2 ? '2nd Year' : ($yearLevel == 3 ? '3rd Year' : ($yearLevel == 4 ? '4th Year' : $yearLevel . 'th Year')));
            
            // Get student photo from user_photos table
            // Use user_id directly from the membership data
            $studentPhoto = null;
            if (!empty($membership['user_id'])) {
                $userPhoto = $userPhotoModel->where('user_id', $membership['user_id'])->first();
                if ($userPhoto && !empty($userPhoto['photo_path'])) {
                    $studentPhoto = base_url($userPhoto['photo_path']);
                }
            }
            
            $recentMembers[] = [
                'id' => $membership['id'],
                'name' => ($membership['firstname'] ?? '') . ' ' . ($membership['lastname'] ?? ''),
                'student_id' => $membership['student_id'] ?? '',
                'email' => '', // Can be added if needed
                'course' => $membership['course'] ?? '',
                'year' => $yearText,
                'status' => $membership['status'],
                'applied_at' => $membership['joined_at'] ?? $membership['created_at'] ?? '',
                'photo' => $studentPhoto
            ];
        }
        
        return $recentMembers;
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

        $orgId = $this->session->get('organization_id');
        if (!$orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization not found']);
        }

        // Prepare announcement data
        $announcementData = [
            'org_id' => $orgId,
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'priority' => $this->request->getPost('priority') ?? 'normal',
            'views' => 0,
            'is_pinned' => 0,
        ];

        // Save to database
        $announcementModel = new AnnouncementModel();
        if (!$announcementModel->insert($announcementData)) {
            $errors = $announcementModel->errors();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create announcement',
                'errors' => $errors
            ]);
        }

        $announcementId = $announcementModel->getInsertID();
        $createdAnnouncement = $announcementModel->find($announcementId);

        // Format response data
        $responseData = [
            'id' => $createdAnnouncement['announcement_id'],
            'title' => $createdAnnouncement['title'],
            'content' => $createdAnnouncement['content'],
            'priority' => $createdAnnouncement['priority'],
            'created_at' => $createdAnnouncement['created_at'],
            'views' => $createdAnnouncement['views'],
        ];

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Announcement created successfully',
            'data' => $responseData
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

        $orgId = $this->session->get('organization_id');
        if (!$orgId || !$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $announcementModel = new AnnouncementModel();
        $announcement = $announcementModel->find($id);

        // Verify announcement belongs to organization
        if (!$announcement || $announcement['org_id'] != $orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Announcement not found or unauthorized']);
        }

        // Prepare update data
        $updateData = [];
        if ($this->request->getPost('title')) {
            $updateData['title'] = $this->request->getPost('title');
        }
        if ($this->request->getPost('content')) {
            $updateData['content'] = $this->request->getPost('content');
        }
        if ($this->request->getPost('priority') !== null) {
            $updateData['priority'] = $this->request->getPost('priority');
        }

        if (empty($updateData)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No data to update']);
        }

        // Update in database
        if (!$announcementModel->update($id, $updateData)) {
            $errors = $announcementModel->errors();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update announcement',
                'errors' => $errors
            ]);
        }

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

        $orgId = $this->session->get('organization_id');
        if (!$orgId || !$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $announcementModel = new AnnouncementModel();
        $announcement = $announcementModel->find($id);

        // Verify announcement belongs to organization
        if (!$announcement || $announcement['org_id'] != $orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Announcement not found or unauthorized']);
        }

        // Delete from database
        if (!$announcementModel->delete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete announcement'
            ]);
        }

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

        // Get current organization ID to filter events
        $orgId = $this->session->get('organization_id');
        $events = $this->getRecentEvents($orgId);
        return $this->response->setJSON(['success' => true, 'data' => $events]);
    }

    /**
     * Get event data for editing
     */
    public function getEvent($id = null)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $orgId = $this->session->get('organization_id');
        if (!$orgId || !$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $eventModel = new EventModel();
        $event = $eventModel->find($id);

        // Verify event belongs to organization
        if (!$event || $event['org_id'] != $orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Event not found or unauthorized']);
        }

        // Format time for input field (HH:MM format)
        $timeFormatted = $event['time'];
        if ($timeFormatted && strpos($timeFormatted, ':') !== false) {
            $timeParts = explode(':', $timeFormatted);
            $hour = $timeParts[0];
            $minute = isset($timeParts[1]) ? $timeParts[1] : '00';
            $timeFormatted = $hour . ':' . $minute;
        }

        // Decode student access
        $specificStudents = [];
        if (!empty($event['student_access'])) {
            $decodedStudents = json_decode($event['student_access'], true);
            if (is_array($decodedStudents)) {
                $specificStudents = array_map('intval', $decodedStudents);
            }
        }

        // Format end time for input field (HH:MM format)
        $endTimeFormatted = $event['end_time'] ?? null;
        if ($endTimeFormatted && strpos($endTimeFormatted, ':') !== false) {
            $timeParts = explode(':', $endTimeFormatted);
            $hour = $timeParts[0];
            $minute = isset($timeParts[1]) ? $timeParts[1] : '00';
            $endTimeFormatted = $hour . ':' . $minute;
        }

        // Format response data
        $responseData = [
            'id' => $event['event_id'],
            'title' => $event['event_name'],
            'description' => $event['description'],
            'date' => $event['date'],
            'time' => $timeFormatted,
            'end_date' => $event['end_date'] ?? null,
            'end_time' => $endTimeFormatted,
            'location' => $event['venue'],
            'audience_type' => $event['audience_type'] ?? 'all',
            'department_access' => $event['department_access'] ?? null,
            'specific_students' => $specificStudents,
            'max_attendees' => $event['max_attendees'],
            'image' => $event['image'] ?? null,
        ];

        return $this->response->setJSON([
            'success' => true,
            'data' => $responseData
        ]);
    }

    /**
     * Create event
     */
    public function createEvent()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $orgId = $this->session->get('organization_id');
        if (!$orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization not found']);
        }

        // Get organization data to get org_type
        $orgModel = new OrganizationModel();
        $organization = $orgModel->find($orgId);
        if (!$organization) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization not found']);
        }

        // Prepare event data
        $audienceType = strtolower($this->request->getPost('audience_type') ?? 'all');
        if (!in_array($audienceType, ['all', 'department', 'specific_students'])) {
            $audienceType = 'all';
        }
        $departmentAccess = null;
        if ($audienceType === 'department') {
            $departmentAccess = strtolower($this->request->getPost('department_access') ?? '');
        } elseif ($audienceType === 'specific_students') {
            $departmentAccess = strtolower($this->request->getPost('department_access') ?? '');
        }

        $specificStudents = [];
        if ($audienceType === 'specific_students' && !empty($departmentAccess)) {
            $specificStudentsInput = $this->request->getPost('specific_students');
            if (is_array($specificStudentsInput)) {
                $studentIds = array_filter(array_map('intval', $specificStudentsInput));
                if (!empty($studentIds)) {
                    $studentModel = new StudentModel();
                    $validStudents = $studentModel->select('students.id')
                        ->whereIn('students.id', $studentIds)
                        ->where('students.department', $departmentAccess)
                        ->findAll();
                    if (!empty($validStudents)) {
                        $specificStudents = array_column($validStudents, 'id');
                    }
                }
            }
        }

        $studentAccessResponse = $specificStudents;

        $eventData = [
            'org_id' => $orgId,
            'org_type' => $organization['organization_type'] ?? 'academic',
            'event_name' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'date' => $this->request->getPost('date'),
            'time' => $this->request->getPost('time'),
            'end_date' => $this->request->getPost('end_date') ?: null,
            'end_time' => $this->request->getPost('end_time') ?: null,
            'venue' => $this->request->getPost('location'),
            'audience_type' => $audienceType,
            'department_access' => $audienceType === 'department' ? $departmentAccess : null,
            'student_access' => !empty($specificStudents) ? json_encode($specificStudents) : null,
            'max_attendees' => $this->request->getPost('max_attendees') ? (int)$this->request->getPost('max_attendees') : null,
            'current_attendees' => 0,
            'status' => 'upcoming',
        ];

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Use public directory for web-accessible files
            $uploadPath = FCPATH . 'uploads/events/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $newName = $image->getRandomName();
            if ($image->move($uploadPath, $newName)) {
                // Store filename only (path handled in view)
                $eventData['image'] = $newName;
            }
        }

        // Save to database
        $eventModel = new EventModel();
        
        // Debug: Log the event data being inserted
        log_message('debug', 'Creating event with data: ' . json_encode($eventData));
        
        try {
            if (!$eventModel->insert($eventData)) {
                $errors = $eventModel->errors();
                $errorMessage = 'Failed to create event';
                
                // Provide more detailed error message
                if (!empty($errors)) {
                    $errorDetails = [];
                    foreach ($errors as $field => $error) {
                        if (is_array($error)) {
                            $errorDetails[] = $field . ': ' . implode(', ', $error);
                        } else {
                            $errorDetails[] = $field . ': ' . $error;
                        }
                    }
                    $errorMessage .= '. ' . implode('; ', $errorDetails);
                } else {
                    $errorMessage .= '. Please check all required fields are filled correctly.';
                }
                
                log_message('error', 'Event creation failed: ' . json_encode($errors));
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => $errors,
                    'validation_failed' => true
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during event creation: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create event: ' . $e->getMessage(),
                'errors' => ['exception' => $e->getMessage()]
            ]);
        }

        $eventId = $eventModel->getInsertID();
        
        // Update event status based on current date/time (for newly created events)
        // Use application timezone for accurate time comparison
        date_default_timezone_set(config('App')->appTimezone);
        $eventModel->updateEventStatus($eventId);
        
        $createdEvent = $eventModel->find($eventId);

        // Format time
        $timeFormatted = $createdEvent['time'];
        if (strpos($timeFormatted, ':') !== false) {
            $timeParts = explode(':', $timeFormatted);
            $hour = (int)$timeParts[0];
            $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
            $period = $hour >= 12 ? 'PM' : 'AM';
            $hour12 = $hour > 12 ? $hour - 12 : ($hour == 0 ? 12 : $hour);
            $timeFormatted = sprintf('%d:%02d %s', $hour12, $minute, $period);
        }

        // Format response data
        $responseData = [
            'id' => $createdEvent['event_id'],
            'title' => $createdEvent['event_name'],
            'description' => $createdEvent['description'],
            'date' => $createdEvent['date'],
            'time' => $timeFormatted,
            'location' => $createdEvent['venue'],
            'audience_type' => $createdEvent['audience_type'] ?? 'all',
            'department_access' => $createdEvent['department_access'] ?? null,
            'student_access' => $studentAccessResponse,
            'attendees' => $createdEvent['current_attendees'],
            'max_attendees' => $createdEvent['max_attendees'],
            'status' => $createdEvent['status'],
            'image' => $createdEvent['image'] ?? null,
        ];

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $responseData
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

        $orgId = $this->session->get('organization_id');
        if (!$orgId || !$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $eventModel = new EventModel();
        $event = $eventModel->find($id);

        // Verify event belongs to organization
        if (!$event || $event['org_id'] != $orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Event not found or unauthorized']);
        }

        // Prepare update data
        $updateData = [];
        if ($this->request->getPost('title')) {
            $updateData['event_name'] = $this->request->getPost('title');
        }
        if ($this->request->getPost('description')) {
            $updateData['description'] = $this->request->getPost('description');
        }
        if ($this->request->getPost('date')) {
            $updateData['date'] = $this->request->getPost('date');
        }
        if ($this->request->getPost('time')) {
            $updateData['time'] = $this->request->getPost('time');
        }
        if ($this->request->getPost('end_date') !== null) {
            $updateData['end_date'] = $this->request->getPost('end_date') ?: null;
        }
        if ($this->request->getPost('end_time') !== null) {
            $updateData['end_time'] = $this->request->getPost('end_time') ?: null;
        }
        if ($this->request->getPost('location')) {
            $updateData['venue'] = $this->request->getPost('location');
        }
        if ($this->request->getPost('max_attendees') !== null) {
            $updateData['max_attendees'] = $this->request->getPost('max_attendees') ? (int)$this->request->getPost('max_attendees') : null;
        }
        if ($this->request->getPost('audience_type') !== null) {
            $audienceType = strtolower($this->request->getPost('audience_type'));
            if (!in_array($audienceType, ['all', 'department', 'specific_students'])) {
                $audienceType = 'all';
            }
            $updateData['audience_type'] = $audienceType;
            if ($audienceType === 'department') {
                $departmentAccess = strtolower($this->request->getPost('department_access') ?? '');
                $updateData['department_access'] = $departmentAccess ?: null;
                $updateData['student_access'] = null;
            } elseif ($audienceType === 'specific_students') {
                $departmentAccess = strtolower($this->request->getPost('department_access') ?? '');
                $updateData['department_access'] = $departmentAccess ?: null;
            } else {
                $updateData['department_access'] = null;
                $updateData['student_access'] = null;
            }
        } elseif ($this->request->getPost('department_access') !== null) {
            $departmentAccess = strtolower($this->request->getPost('department_access'));
            $updateData['department_access'] = $departmentAccess ?: null;
            if ($departmentAccess) {
                $updateData['audience_type'] = 'department';
            }
        }

        $finalAudienceType = $updateData['audience_type'] ?? $event['audience_type'] ?? 'all';
        $specificStudents = null;
        $specificStudentsInput = $this->request->getPost('specific_students');
        if ($finalAudienceType === 'specific_students') {
            $finalDepartment = $updateData['department_access'] ?? $event['department_access'] ?? null;
            if ($finalDepartment && is_array($specificStudentsInput)) {
                $studentIds = array_filter(array_map('intval', $specificStudentsInput));
                if (!empty($studentIds)) {
                    $studentModel = new StudentModel();
                    $validStudents = $studentModel->select('students.id')
                        ->whereIn('students.id', $studentIds)
                        ->where('students.department', $finalDepartment)
                        ->findAll();
                    if (!empty($validStudents)) {
                        $specificStudents = json_encode(array_column($validStudents, 'id'));
                    }
                }
            }
            // Set student_access - null if no students selected, otherwise the JSON array
            $updateData['student_access'] = $specificStudents;
        } elseif ($finalAudienceType === 'department') {
            $updateData['student_access'] = null;
        } else {
            // For 'all' or any other type, clear student_access
            $updateData['student_access'] = null;
        }

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/events/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Delete old image if exists
            if (!empty($event['image']) && file_exists($uploadPath . $event['image'])) {
                unlink($uploadPath . $event['image']);
            }

            $newName = $image->getRandomName();
            if ($image->move($uploadPath, $newName)) {
                $updateData['image'] = $newName;
            }
        }

        if (empty($updateData)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No data to update']);
        }

        // Update in database
        if (!$eventModel->update($id, $updateData)) {
            $errors = $eventModel->errors();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update event',
                'errors' => $errors
            ]);
        }

        // Update event status based on new date/time values
        // Use application timezone for accurate time comparison
        date_default_timezone_set(config('App')->appTimezone);
        $eventModel->updateEventStatus($id);

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

        $orgId = $this->session->get('organization_id');
        if (!$orgId || !$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $eventModel = new EventModel();
        $event = $eventModel->find($id);

        // Verify event belongs to organization
        if (!$event || $event['org_id'] != $orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Event not found or unauthorized']);
        }

        // Delete image if exists
        if (!empty($event['image'])) {
            $imagePath = FCPATH . 'uploads/events/' . $event['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Delete from database
        if (!$eventModel->delete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete event'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }

    /**
     * Get students by department for event audience selection
     */
    public function getDepartmentStudents()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $department = strtolower($this->request->getGet('department') ?? '');
        if (empty($department)) {
            return $this->response->setJSON(['success' => true, 'students' => []]);
        }

        $studentModel = new StudentModel();
        $students = $studentModel->select('students.id, students.student_id, user_profiles.firstname, user_profiles.lastname')
            ->join('user_profiles', 'user_profiles.user_id = students.user_id')
            ->where('students.department', $department)
            ->orderBy('user_profiles.firstname', 'ASC')
            ->orderBy('user_profiles.lastname', 'ASC')
            ->findAll();

        $formattedStudents = array_map(function($student) {
            return [
                'id' => (int)$student['id'],
                'student_id' => $student['student_id'],
                'name' => trim(($student['firstname'] ?? '') . ' ' . ($student['lastname'] ?? '')),
            ];
        }, $students);

        return $this->response->setJSON([
            'success' => true,
            'students' => $formattedStudents
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

        $eventId = $id ?? $this->request->getGet('event_id');
        if (empty($eventId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Event ID is required']);
        }

        // Verify event belongs to this organization
        $orgId = $this->session->get('organization_id');
        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);
        
        if (!$event) {
            return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
        }

        // Get organization from event
        $db = \Config\Database::connect();
        $eventOrg = $db->table('organizations')
            ->where('id', $event['org_id'])
            ->get()
            ->getRowArray();
        
        if (!$eventOrg || $eventOrg['id'] != $orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized to view this event']);
        }

        // Fetch attendees with student and user profile information
        $attendees = $db->table('event_attendees ea')
            ->select('ea.id, ea.joined_at, s.id as student_id, s.student_id as student_number, s.course, s.department, s.year_level, up.firstname, up.middlename, up.lastname, u.email, u.id as user_id')
            ->join('students s', 'ea.student_id = s.id', 'inner')
            ->join('users u', 's.user_id = u.id', 'inner')
            ->join('user_profiles up', 'u.id = up.user_id', 'left')
            ->where('ea.event_id', $eventId)
            ->orderBy('ea.joined_at', 'DESC')
            ->get()
            ->getResultArray();
        
        // Also get photos from user_photos table
        $userPhotoModel = new UserPhotoModel();

        // Format attendees data
        $formattedAttendees = [];
        foreach ($attendees as $attendee) {
            $fullName = trim(($attendee['firstname'] ?? '') . ' ' . ($attendee['middlename'] ?? '') . ' ' . ($attendee['lastname'] ?? ''));
            $fullName = preg_replace('/\s+/', ' ', $fullName) ?: 'N/A';
            
            // Get student photo from user_photos table
            $photoUrl = null;
            $userPhoto = $userPhotoModel->where('user_id', $attendee['user_id'] ?? null)->first();
            if ($userPhoto && !empty($userPhoto['photo_path'])) {
                $photoUrl = base_url($userPhoto['photo_path']);
            }
            
            $formattedAttendees[] = [
                'id' => $attendee['id'],
                'student_id' => $attendee['student_id'],
                'student_number' => $attendee['student_number'] ?? 'N/A',
                'name' => $fullName,
                'email' => $attendee['email'] ?? 'N/A',
                'course' => $attendee['course'] ?? 'N/A',
                'department' => strtoupper($attendee['department'] ?? 'N/A'),
                'year_level' => $attendee['year_level'] ?? 'N/A',
                'joined_at' => $attendee['joined_at'] ? date('M d, Y h:i A', strtotime($attendee['joined_at'])) : 'N/A',
                'photo_url' => $photoUrl
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'event_title' => $event['event_name'] ?? $event['title'] ?? 'Event',
            'attendees' => $formattedAttendees,
            'total' => count($formattedAttendees)
        ]);
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

        $orgId = $this->session->get('organization_id');
        if (!$orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization not found']);
        }

        $data = [
            'mission' => $this->request->getPost('mission'),
            'vision' => $this->request->getPost('vision'),
            'contact_phone' => $this->request->getPost('contact_phone'),
            'organization_category' => strtolower(str_replace(' ', '_', $this->request->getPost('category') ?? '')),
            'founding_date' => $this->request->getPost('founding_date'),
            'objectives' => $this->request->getPost('objectives'),
            'current_members' => (int)$this->request->getPost('current_members'),
        ];
        
        // Update department in organization_applications table
        $department = $this->request->getPost('department');
        if ($department) {
            $orgModel = new OrganizationModel();
            $organization = $orgModel->find($orgId);
            
            if ($organization) {
                $db = \Config\Database::connect();
                $db->table('organization_applications')
                    ->where('organization_name', $organization['organization_name'])
                    ->where('status', 'approved')
                    ->update(['department' => $department]);
            }
        }

        // Handle photo upload (profile picture)
        $photoUrl = null;
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (in_array($photo->getMimeType(), $allowedTypes)) {
                // Validate file size (max 5MB)
                if ($photo->getSize() <= 5 * 1024 * 1024) {
                    $userId = session()->get('user_id');
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
                    $photoPath = 'uploads/profiles/' . $newName;
                    
                    // Save to user_photos table
                    $userPhotoModel = new UserPhotoModel();
                    $existingPhoto = $userPhotoModel->where('user_id', $userId)->first();
                    
                    if ($existingPhoto) {
                        // Update existing photo record
                        $userPhotoModel->update($existingPhoto['id'], [
                            'photo_path' => $photoPath
                        ]);
                    } else {
                        // Insert new photo record
                        $userPhotoModel->insert([
                            'user_id' => $userId,
                            'photo_path' => $photoPath
                        ]);
                    }
                    
                    // Update session with new photo
                    session()->set('photo', $photoUrl);
                }
            }
        }

        // Update in database
        $orgModel = new OrganizationModel();
        $orgModel->update($orgId, $data);

        // Update advisor information
        $organization = $orgModel->find($orgId);
        
        if ($organization) {
            $db = \Config\Database::connect();
            
            // Get the application ID for this organization
            $application = $db->table('organization_applications')
                ->where('organization_name', $organization['organization_name'])
                ->where('status', 'approved')
                ->get()
                ->getRowArray();
            
            if ($application) {
                $advisorData = [
                    'name' => $this->request->getPost('advisor_name'),
                    'email' => $this->request->getPost('advisor_email'),
                    'phone' => $this->request->getPost('advisor_phone'),
                    'department' => $this->request->getPost('advisor_department')
                ];
                
                // Update or insert advisor
                $advisorModel = new OrganizationAdvisorModel();
                $existingAdvisor = $advisorModel->where('application_id', $application['id'])->first();
                
                if ($existingAdvisor) {
                    $advisorModel->update($existingAdvisor['id'], $advisorData);
                } else {
                    $advisorData['application_id'] = $application['id'];
                    $advisorModel->insert($advisorData);
                }
                
                // Update or insert primary officer
                $officerData = [
                    'position' => $this->request->getPost('officer_position'),
                    'name' => $this->request->getPost('officer_name'),
                    'email' => $this->request->getPost('officer_email'),
                    'phone' => $this->request->getPost('officer_phone'),
                    'student_id' => $this->request->getPost('officer_student_id')
                ];
                
                $officerModel = new OrganizationOfficerModel();
                // Try to find primary officer (President) first, or first officer
                $existingOfficer = $officerModel->where('application_id', $application['id'])
                    ->where('position', 'President')
                    ->first();
                
                if (!$existingOfficer) {
                    $existingOfficer = $officerModel->where('application_id', $application['id'])
                        ->first();
                }
                
                if ($existingOfficer) {
                    $officerModel->update($existingOfficer['id'], $officerData);
                } else {
                    $officerData['application_id'] = $application['id'];
                    $officerModel->insert($officerData);
                }
            }
        }

        $response = [
            'success' => true,
            'message' => 'Organization information updated successfully'
        ];
        
        // Include photo URL in response if photo was uploaded
        if ($photoUrl) {
            $response['photo'] = $photoUrl;
        }
        
        return $this->response->setJSON($response);
    }

    /**
     * Upload Organization Logo/Photo
     */
    public function uploadPhoto()
    {
        // Check authentication
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $photo = $this->request->getFile('photo');
        
        if (!$photo || !$photo->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No photo uploaded or invalid file'
            ]);
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($photo->getMimeType(), $allowedTypes)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid file type. Please upload a JPEG, PNG, or GIF image.'
            ]);
        }

        // Validate file size (max 5MB)
        if ($photo->getSize() > 5 * 1024 * 1024) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File size exceeds 5MB limit'
            ]);
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
            $photoPath = 'uploads/profiles/' . $newName;
            
            // Save to user_photos table
            $userPhotoModel = new UserPhotoModel();
            $existingPhoto = $userPhotoModel->where('user_id', $userId)->first();
            
            if ($existingPhoto) {
                // Update existing photo record
                $userPhotoModel->update($existingPhoto['id'], [
                    'photo_path' => $photoPath
                ]);
            } else {
                // Insert new photo record
                $userPhotoModel->insert([
                    'user_id' => $userId,
                    'photo_path' => $photoPath
                ]);
            }
            
            // Update session with new photo
            session()->set('photo', $photoUrl);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile photo updated successfully!',
                'photo' => $photoUrl,
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

        $orgId = $this->session->get('organization_id');
        if (!$orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization not found']);
        }

        $membershipModel = new StudentOrganizationMembershipModel();
        
        // Get all members (active and pending)
        $allMemberships = array_merge(
            $membershipModel->getActiveMemberships($orgId),
            $membershipModel->getPendingMemberships($orgId)
        );
        
        // Get student photos from user_photos table
        $userPhotoModel = new UserPhotoModel();
        $studentModel = new StudentModel();
        
        $members = [];
        foreach ($allMemberships as $membership) {
            $yearLevel = $membership['year_level'] ?? 1;
            $yearText = $yearLevel == 1 ? '1st' : ($yearLevel == 2 ? '2nd' : ($yearLevel == 3 ? '3rd' : ($yearLevel == 4 ? '4th' : $yearLevel . 'th')));
            
            // Get student photo from user_photos table
            // Use user_id directly from the membership data
            $studentPhoto = null;
            if (!empty($membership['user_id'])) {
                $userPhoto = $userPhotoModel->where('user_id', $membership['user_id'])->first();
                if ($userPhoto && !empty($userPhoto['photo_path'])) {
                    $studentPhoto = base_url($userPhoto['photo_path']);
                }
            }
            
            $members[] = [
                'id' => $membership['id'],
                'name' => ($membership['firstname'] ?? '') . ' ' . ($membership['lastname'] ?? ''),
                'student_id' => $membership['student_id'] ?? '',
                'course' => $membership['course'] ?? '',
                'year' => $yearText,
                'status' => $membership['status'],
                'joined_at' => $membership['joined_at'] ?? $membership['created_at'] ?? '',
                'photo' => $studentPhoto
            ];
        }
        
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
        $membershipId = $this->request->getPost('member_id');

        if (empty($action) || empty($membershipId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        // Get organization ID
        $orgId = $this->session->get('organization_id');
        if (!$orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization not found']);
        }

        $membershipModel = new StudentOrganizationMembershipModel();
        $orgModel = new OrganizationModel();

        // Get the membership record
        $membership = $membershipModel->find($membershipId);
        
        if (!$membership) {
            return $this->response->setJSON(['success' => false, 'message' => 'Membership record not found']);
        }

        // Verify the membership belongs to this organization
        if ($membership['org_id'] != $orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            switch ($action) {
                case 'approve':
                    // Update membership status to active
                    $membershipModel->update($membershipId, ['status' => 'active']);
                    
                    // Update organization member count
                    $activeMembers = $membershipModel->getActiveMemberships($orgId);
                    $orgModel->update($orgId, ['current_members' => count($activeMembers)]);
                    
                    $message = 'Member approved successfully';
                    break;

                case 'reject':
                    // Delete the membership record
                    $membershipModel->delete($membershipId);
                    $message = 'Member application rejected';
                    break;

                case 'remove':
                    // Update membership status to inactive or delete
                    $membershipModel->update($membershipId, ['status' => 'inactive']);
                    
                    // Update organization member count
                    $activeMembers = $membershipModel->getActiveMemberships($orgId);
                    $orgModel->update($orgId, ['current_members' => count($activeMembers)]);
                    
                    $message = 'Member removed from organization';
                    break;

                default:
                    $db->transRollback();
                    return $this->response->setJSON(['success' => false, 'message' => 'Invalid action']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Database error occurred']);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
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

        $orgId = $this->session->get('organization_id');
        if (!$orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization not found']);
        }

        // Prepare product data
        $productData = [
            'org_id' => $orgId,
            'product_name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description') ?: null,
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock') ? (int)$this->request->getPost('stock') : 0,
            'sold' => 0,
            'sizes' => $this->request->getPost('sizes') ? trim($this->request->getPost('sizes')) : null,
            'status' => 'available',
        ];

        // Determine status based on stock
        if ($productData['stock'] == 0) {
            $productData['status'] = 'out_of_stock';
        } elseif ($productData['stock'] <= 10) {
            $productData['status'] = 'low_stock';
        }

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/products/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $newName = $image->getRandomName();
            if ($image->move($uploadPath, $newName)) {
                $productData['image'] = $newName;
            }
        }

        // Save to database
        $productModel = new ProductModel();
        if (!$productModel->insert($productData)) {
            $errors = $productModel->errors();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create product',
                'errors' => $errors
            ]);
        }

        $productId = $productModel->getInsertID();
        $createdProduct = $productModel->find($productId);

        // Format response data
        $status = 'available';
        if ($createdProduct['stock'] == 0) {
            $status = 'out_of_stock';
        } elseif ($createdProduct['stock'] <= 10) {
            $status = 'low_stock';
        }

        $responseData = [
            'id' => $createdProduct['product_id'],
            'name' => $createdProduct['product_name'],
            'description' => $createdProduct['description'] ?? '',
            'price' => (float)$createdProduct['price'],
            'stock' => (int)$createdProduct['stock'],
            'sold' => (int)($createdProduct['sold'] ?? 0),
            'sizes' => $createdProduct['sizes'] ? explode(',', $createdProduct['sizes']) : null,
            'image' => $createdProduct['image'] ?? null,
            'status' => $status,
        ];

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $responseData
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

        $orgId = $this->session->get('organization_id');
        $productId = $this->request->getPost('product_id');
        $newStock = $this->request->getPost('stock');

        if (!$orgId || !$productId || $newStock === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $productModel = new ProductModel();
        $product = $productModel->find($productId);

        // Verify product belongs to organization
        if (!$product || $product['org_id'] != $orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Product not found or unauthorized']);
        }

        // Determine status based on new stock
        $status = 'available';
        if ($newStock == 0) {
            $status = 'out_of_stock';
        } elseif ($newStock <= 10) {
            $status = 'low_stock';
        }

        // Update stock and status
        if (!$productModel->update($productId, [
            'stock' => (int)$newStock,
            'status' => $status
        ])) {
            $errors = $productModel->errors();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update stock',
                'errors' => $errors
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Stock updated successfully'
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

    /**
     * Track view for announcements
     */
    public function trackView()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $type = $this->request->getPost('type');
        $id = $this->request->getPost('id');

        if ($type === 'announcement' && $id) {
            try {
                $announcementModel = new AnnouncementModel();
                $announcementModel->incrementViews($id);
                
                // Get updated views count
                $announcement = $announcementModel->find($id);
                $views = $announcement['views'] ?? 0;
                
                return $this->response->setJSON([
                    'success' => true,
                    'views' => $views
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Track view error: ' . $e->getMessage());
                return $this->response->setJSON(['success' => false, 'message' => 'Error tracking view']);
            }
        } elseif ($type === 'event' && $id) {
            try {
                $eventModel = new EventModel();
                $eventModel->incrementViews($id);
                
                // Get updated views count
                $event = $eventModel->find($id);
                $views = $event['views'] ?? 0;
                
                return $this->response->setJSON([
                    'success' => true,
                    'views' => $views
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Track view error: ' . $e->getMessage());
                return $this->response->setJSON(['success' => false, 'message' => 'Error tracking view']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }

    /**
     * Like/React to a post (for organizations - now enabled)
     */
    public function likePost()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $postType = $this->request->getPost('type');
        $postId = $this->request->getPost('post_id');
        $reactionType = $this->request->getPost('reaction_type') ?? 'like';

        if (!$postType || !$postId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        // Get organization ID
        $orgId = $this->session->get('organization_id');
        if (!$orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization not found']);
        }

        try {
            $likeModel = new \App\Models\PostLikeModel();
            $result = $likeModel->setReaction($orgId, $postType, $postId, $reactionType, 'organization');
            
            return $this->response->setJSON([
                'success' => true,
                'reacted' => $result['reacted'],
                'reaction_type' => $result['reaction_type'],
                'counts' => $result['counts'],
                'message' => $result['reacted'] ? 'Reaction added!' : 'Reaction removed!'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Organization like error: ' . $e->getMessage());
            log_message('error', 'Organization like error trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while reacting to post: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get comments for a post
     */
    public function getComments()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $postType = $this->request->getGet('post_type');
        $postId = $this->request->getGet('post_id');

        if (!$postType || !$postId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            $commentModel = new \App\Models\PostCommentModel();
            $comments = $commentModel->getComments($postType, $postId);
            
            // Format comments for display
            $formattedComments = [];
            foreach ($comments as $comment) {
                if (!$comment || !isset($comment['id'])) {
                    continue; // Skip invalid comments
                }
                
                $userName = '';
                if (isset($comment['is_organization']) && $comment['is_organization']) {
                    $userName = $comment['firstname'] ?? $comment['org_name'] ?? 'Organization';
                } else {
                    $userName = trim(($comment['firstname'] ?? '') . ' ' . ($comment['lastname'] ?? ''));
                    if (empty($userName)) {
                        $userName = 'User';
                    }
                }
                
                $formattedComment = [
                    'id' => $comment['id'] ?? 0,
                    'content' => $comment['content'] ?? '',
                    'created_at' => $comment['created_at'] ?? date('Y-m-d H:i:s'),
                    'user_name' => $userName,
                    'firstname' => $comment['firstname'] ?? $comment['org_name'] ?? '',
                    'lastname' => $comment['lastname'] ?? '',
                    'student_id' => $comment['student_id'] ?? null,
                    'is_organization' => $comment['is_organization'] ?? false,
                    'parent_comment_id' => $comment['parent_comment_id'] ?? null,
                    'replies' => []
                ];
                
                // Format replies recursively if they exist
                if (isset($comment['replies']) && is_array($comment['replies']) && !empty($comment['replies'])) {
                    $formattedComment['replies'] = $this->formatRepliesRecursive($comment['replies']);
                }
                
                $formattedComments[] = $formattedComment;
            }

            return $this->response->setJSON([
                'success' => true,
                'comments' => $formattedComments
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get comments error: ' . $e->getMessage());
            log_message('error', 'Get comments trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error loading comments: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Recursively format replies
     */
    private function formatRepliesRecursive($replies)
    {
        $formattedReplies = [];
        foreach ($replies as $reply) {
            if (!$reply || !isset($reply['id'])) {
                continue; // Skip invalid replies
            }
            
            $replyUserName = '';
            if (isset($reply['is_organization']) && $reply['is_organization']) {
                $replyUserName = $reply['firstname'] ?? $reply['org_name'] ?? 'Organization';
            } else {
                $replyUserName = trim(($reply['firstname'] ?? '') . ' ' . ($reply['lastname'] ?? ''));
                if (empty($replyUserName)) {
                    $replyUserName = 'User';
                }
            }
            
            $formattedReply = [
                'id' => $reply['id'] ?? 0,
                'content' => $reply['content'] ?? '',
                'created_at' => $reply['created_at'] ?? date('Y-m-d H:i:s'),
                'user_name' => $replyUserName,
                'firstname' => $reply['firstname'] ?? $reply['org_name'] ?? '',
                'lastname' => $reply['lastname'] ?? '',
                'student_id' => $reply['student_id'] ?? null,
                'is_organization' => $reply['is_organization'] ?? false,
                'parent_comment_id' => $reply['parent_comment_id'] ?? null,
                'replies' => []
            ];
            
            // Recursively format nested replies
            if (isset($reply['replies']) && is_array($reply['replies']) && !empty($reply['replies'])) {
                $formattedReply['replies'] = $this->formatRepliesRecursive($reply['replies']);
            }
            
            $formattedReplies[] = $formattedReply;
        }
        return $formattedReplies;
    }

    /**
     * Post a comment (for organizations)
     */
    public function comment()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $postType = $this->request->getPost('post_type');
        $postId = $this->request->getPost('post_id');
        $content = $this->request->getPost('content');
        $parentCommentId = $this->request->getPost('parent_comment_id'); // For replies

        if (!$postType || !$postId || !$content || trim($content) === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            // Get organization info
            $orgId = $this->session->get('organization_id');
            $organizationModel = new OrganizationModel();
            $organization = $organizationModel->find($orgId);
            
            if (!$organization) {
                return $this->response->setJSON(['success' => false, 'message' => 'Organization not found']);
            }

            $orgName = $organization['name'] ?? $organization['organization_name'] ?? 'Organization';
            
            $commentModel = new \App\Models\PostCommentModel();
            
            // Insert comment with proper organization support
            $data = [
                'organization_id' => $orgId,
                'student_id' => null,
                'commenter_type' => 'organization',
                'post_type' => $postType,
                'post_id' => $postId,
                'content' => trim($content)
            ];
            
            // Only add parent_comment_id if it's provided and not empty
            if ($parentCommentId && !empty(trim($parentCommentId))) {
                $data['parent_comment_id'] = (int)$parentCommentId;
            }
            
            $commentId = $commentModel->insert($data);
            
            if (!$commentId) {
                $errors = $commentModel->errors();
                log_message('error', 'Post comment insert failed: ' . json_encode($errors));
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to save comment. Please check if the reply feature is enabled in the database.']);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Comment posted successfully',
                'comment' => [
                    'id' => $commentId,
                    'content' => trim($content),
                    'user_name' => $orgName,
                    'created_at' => date('M d, Y \a\t g:i A')
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Post comment error: ' . $e->getMessage());
            log_message('error', 'Post comment trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while posting comment: ' . $e->getMessage()
            ]);
            $errorMessage = 'Error posting comment: ' . $e->getMessage();
            // Check if it's a column doesn't exist error
            if (strpos($e->getMessage(), "doesn't exist") !== false || strpos($e->getMessage(), 'Unknown column') !== false) {
                $errorMessage = 'Reply feature not available. Please run the SQL script to add reply support.';
            }
            return $this->response->setJSON(['success' => false, 'message' => $errorMessage]);
        }
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
            'department' => 'required|in_list[ccs,cea,cthbm,chs,ctde,cas,gs]',
            'mission' => 'required|min_length[50]|max_length[1000]',
            'vision' => 'required|min_length[50]|max_length[1000]',
            'objectives' => 'required|min_length[50]|max_length[2000]',
            'founding_date' => 'required|valid_date',
            'contact_email' => 'required|valid_email',
            'contact_phone' => 'required',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
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
            'department' => $this->request->getPost('department'),
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
                'department' => $this->request->getPost('department'),
                'founding_date' => $this->request->getPost('founding_date'),
                'mission' => $this->request->getPost('mission'),
                'vision' => $this->request->getPost('vision'),
                'objectives' => $this->request->getPost('objectives'),
                'contact_email' => $this->request->getPost('contact_email'),
                'contact_phone' => $this->request->getPost('contact_phone'),
                'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
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

    /**
     * Get Forum Posts (for organizations to view student forum posts)
     */
    public function getPosts()
    {
        // Check if organization is logged in
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $category = $this->request->getGet('category') ?? 'all';
        $filter = $this->request->getGet('filter') ?? 'latest';
        $limit = $this->request->getGet('limit') ? (int)$this->request->getGet('limit') : 20;
        $offset = $this->request->getGet('offset') ? (int)$this->request->getGet('offset') : 0;

        try {
            $postModel = new ForumPostModel();
            $posts = $postModel->getAllPosts($category, $limit, $offset, $filter);

            // Get reaction and comment counts for each post
            $likeModel = new \App\Models\PostLikeModel();
            $commentModel = new \App\Models\PostCommentModel();

            // Get organization ID for reactions
            $orgId = $this->session->get('organization_id');
            
            foreach ($posts as &$post) {
                // Get reaction counts
                $reactionCounts = $likeModel->getReactionCounts('forum_post', $post['id']);
                $post['reaction_counts'] = $reactionCounts;
                $post['reaction_count_total'] = $reactionCounts['total'] ?? 0;

                // Get organization's reaction if logged in
                if ($orgId) {
                    $userReaction = $likeModel->getUserReaction($orgId, 'forum_post', $post['id'], 'organization');
                    $post['user_reaction'] = $userReaction;
                } else {
                    $post['user_reaction'] = null;
                }

                // Get comment count
                $commentCount = $commentModel->getCommentCount('forum_post', $post['id']);
                $post['comment_count'] = $commentCount;

                // Format image URL
                if (!empty($post['image'])) {
                    $post['image_url'] = base_url('uploads/posts/' . $post['image']);
                } else {
                    $post['image_url'] = null;
                }

                // Format tags
                if (!empty($post['tags'])) {
                    $post['tags_array'] = array_map('trim', explode(',', $post['tags']));
                } else {
                    $post['tags_array'] = [];
                }
            }
            
            // Sort posts based on filter
            if ($filter === 'popular') {
                // Sort by reaction count (total reactions), then by pinned status, then by date
                usort($posts, function($a, $b) {
                    // Pinned posts first
                    if ($a['is_pinned'] != $b['is_pinned']) {
                        return $b['is_pinned'] - $a['is_pinned'];
                    }
                    // Then by reaction count
                    $aReactions = $a['reaction_count_total'] ?? 0;
                    $bReactions = $b['reaction_count_total'] ?? 0;
                    if ($aReactions != $bReactions) {
                        return $bReactions - $aReactions;
                    }
                    // Finally by date
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
            }

            return $this->response->setJSON([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get forum posts error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while fetching posts'
            ]);
        }
    }

    /**
     * Create Forum Post (for organizations)
     */
    public function createPost()
    {
        // Check if organization is logged in
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $orgId = $this->session->get('organization_id');
        if (!$orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization not found']);
        }

        $title = trim($this->request->getPost('title'));
        $content = trim($this->request->getPost('content'));
        $category = $this->request->getPost('category') ?? 'general';
        $tags = trim($this->request->getPost('tags') ?? '');

        // Validation
        if (empty($title) || strlen($title) < 3) {
            return $this->response->setJSON(['success' => false, 'message' => 'Title must be at least 3 characters']);
        }

        if (empty($content) || strlen($content) < 10) {
            return $this->response->setJSON(['success' => false, 'message' => 'Content must be at least 10 characters']);
        }

        if (!in_array($category, ['general', 'events', 'academics', 'marketplace', 'help'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid category']);
        }

        try {
            $postModel = new ForumPostModel();
            
            $postData = [
                'organization_id' => $orgId,
                'student_id' => null,
                'author_type' => 'organization',
                'title' => $title,
                'content' => $content,
                'category' => $category,
                'tags' => !empty($tags) ? $tags : null
            ];

            // Handle image upload
            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (in_array($image->getMimeType(), $allowedTypes)) {
                    if ($image->getSize() <= 5 * 1024 * 1024) { // 5MB max
                        $uploadPath = FCPATH . 'uploads/posts/';
                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }

                        $newName = time() . '_' . $image->getRandomName();
                        if ($image->move($uploadPath, $newName)) {
                            $postData['image'] = $newName;
                        }
                    }
                }
            }

            $postId = $postModel->insert($postData);

            if ($postId) {
                // Get the created post with organization info
                $createdPost = $postModel->getPostById($postId);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Post created successfully!',
                    'post' => $createdPost
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create post',
                'errors' => $postModel->errors()
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Create post error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while creating post'
            ]);
        }
    }

    /**
     * Get forum category counts
     */
    public function getCategoryCounts()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'organization') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $postModel = new ForumPostModel();
            $counts = $postModel->getCategoryCounts();
            
            return $this->response->setJSON([
                'success' => true,
                'counts' => $counts
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get category counts error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading category counts'
            ]);
        }
    }
}
