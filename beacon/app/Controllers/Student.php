<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\UserPhotoModel;
use App\Models\AddressModel;
use App\Models\StudentModel;
use App\Models\OrganizationModel;
use App\Models\EventModel;
use App\Models\AnnouncementModel;
use App\Models\ProductModel;
use App\Models\StudentOrganizationMembershipModel;
use App\Models\ForumPostModel;

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
        
        // Get photo from user_photos table
        $userPhotoModel = new \App\Models\UserPhotoModel();
        $userPhoto = $userPhotoModel->where('user_id', $userId)->first();
        $photoUrl = null;
        if ($userPhoto && !empty($userPhoto['photo_path'])) {
            $photoUrl = base_url($userPhoto['photo_path']);
            // Update session with photo from database
            session()->set('photo', $photoUrl);
        }
        
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
            
            // Get followed organizations
            $followModel = new \App\Models\OrganizationFollowModel();
            $followedOrgs = $followModel->getFollowedOrganizations($student['id']);
            
            // Initialize models (needed for all organizations)
            $orgModel = new OrganizationModel();
            $announcementModel = new AnnouncementModel();
            $eventModel = new EventModel();
            $userPhotoModel = new \App\Models\UserPhotoModel();
            
            // Filter followed organizations to only include active ones
            $followedOrgIds = [];
            if (!empty($followedOrgs)) {
                foreach ($followedOrgs as $followed) {
                    if (isset($followed['org_id']) && !empty($followed['org_id'])) {
                        $org = $orgModel->find($followed['org_id']);
                        if ($org && isset($org['is_active']) && $org['is_active'] == 1) {
                            $followedOrgIds[] = $followed['org_id'];
                        }
                    }
                }
            }
            
            $orgIds = [];
            if ($hasJoinedOrg) {
                // Build joined organizations list and collect org IDs
                foreach ($memberships as $membership) {
                    // Get organization photo from user_photos table
                    $org = $orgModel->find($membership['org_id']);
                    $orgPhoto = null;
                    if ($org && !empty($org['user_id'])) {
                        $orgUserPhoto = $userPhotoModel->where('user_id', $org['user_id'])->first();
                        if ($orgUserPhoto && !empty($orgUserPhoto['photo_path'])) {
                            $orgPhoto = base_url($orgUserPhoto['photo_path']);
                        }
                    }
                    
                    $joinedOrganizations[] = [
                        'id' => $membership['org_id'],
                        'name' => $membership['organization_name'],
                        'acronym' => $membership['organization_acronym'],
                        'status' => $membership['status'],
                        'photo' => $orgPhoto
                    ];
                    $orgIds[] = $membership['org_id'];
                }
            }
            
            // Get posts from followed organizations AND organizations where student is a member
            $orgIdsForPosts = array_unique(array_merge($followedOrgIds, $orgIds));
            if (empty($orgIdsForPosts)) {
                // If student hasn't followed any organizations and isn't a member of any, show empty feed
                $orgIdsForPosts = [];
            }
            
            // Get announcements from followed organizations only
            $allAnnouncementsList = [];
            if (!empty($orgIdsForPosts)) {
                foreach ($orgIdsForPosts as $orgId) {
                    // Verify organization is active before fetching posts
                    $org = $orgModel->find($orgId);
                    if (!$org || $org['is_active'] != 1) {
                        continue; // Skip inactive organizations
                    }
                    
                    $announcements = $announcementModel->getAnnouncementsByOrg($orgId);
                    
                    // Get organization photo
                    $orgPhotoForAnnouncement = null;
                    if ($org && !empty($org['user_id'])) {
                        $orgUserPhotoForAnnouncement = $userPhotoModel->where('user_id', $org['user_id'])->first();
                        if ($orgUserPhotoForAnnouncement && !empty($orgUserPhotoForAnnouncement['photo_path'])) {
                            $orgPhotoForAnnouncement = base_url($orgUserPhotoForAnnouncement['photo_path']);
                        }
                    }
                    
                    foreach ($announcements as $announcement) {
                        $announcementId = $announcement['announcement_id'] ?? $announcement['id'];
                        
                        // Get reaction counts and user's reaction
                        $likeModel = new \App\Models\PostLikeModel();
                        $reactionCounts = $likeModel->getReactionCounts('announcement', $announcementId);
                        $userReaction = null;
                        if ($student) {
                            $userReaction = $likeModel->getUserReaction($student['id'], 'announcement', $announcementId);
                        }
                        
                        // Get comment count
                        $commentModel = new \App\Models\PostCommentModel();
                        $commentCount = $commentModel->getCommentCount('announcement', $announcementId);
                        
                        $announcementData = [
                            'id' => $announcementId,
                            'title' => $announcement['title'],
                            'content' => $announcement['content'],
                            'priority' => $announcement['priority'] ?? 'normal',
                            'created_at' => $announcement['created_at'],
                            'views' => $announcement['views'] ?? 0,
                            'org_id' => $orgId,
                            'org_name' => $org['organization_name'] ?? '',
                            'org_acronym' => $org['organization_acronym'] ?? '',
                            'org_photo' => $orgPhotoForAnnouncement,
                            'reaction_counts' => $reactionCounts,
                            'user_reaction' => $userReaction,
                            'like_count' => $reactionCounts['total'], // For backward compatibility
                            'is_liked' => $userReaction !== null, // For backward compatibility
                            'comment_count' => $commentCount
                        ];
                        
                        $organizationPosts['announcements'][] = $announcementData;
                        $allAnnouncementsList[] = $announcementData;
                    }
                }
                
                // Sort all announcements by date (newest first) for announcements section
                usort($allAnnouncementsList, function($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
                
                // Get events from followed organizations AND events where student is specifically invited
                $allUpcomingEvents = [];
                $allEventsList = [];
                $processedEventIds = []; // Track processed events to avoid duplicates
                
                // First, get events from followed/member organizations
                if (!empty($orgIdsForPosts)) {
                    foreach ($orgIdsForPosts as $orgId) {
                    // Verify organization is active before fetching posts
                    $org = $orgModel->find($orgId);
                    if (!$org || $org['is_active'] != 1) {
                        continue; // Skip inactive organizations
                    }
                    
                    $events = $eventModel->getEventsByOrg($orgId);
                    $eventCount += count($events);
                    
                    // Get organization photo for events
                    $orgPhotoForEvent = null;
                    if ($org && !empty($org['user_id'])) {
                        $orgUserPhotoForEvent = $userPhotoModel->where('user_id', $org['user_id'])->first();
                        if ($orgUserPhotoForEvent && !empty($orgUserPhotoForEvent['photo_path'])) {
                            $orgPhotoForEvent = base_url($orgUserPhotoForEvent['photo_path']);
                        }
                    }
                    
                    foreach ($events as $event) {
                        $eventId = $event['event_id'] ?? $event['id'];
                        $processedEventIds[] = $eventId;
                        
                        // Update event status in database based on current date/time
                        $eventModel->updateEventStatus($eventId);
                        // Refresh event data to get updated status from database
                        $db = \Config\Database::connect();
                        $event = $db->table('events')
                            ->where('event_id', $eventId)
                            ->get()
                            ->getRowArray();
                        
                        $audienceType = $event['audience_type'] ?? 'all';
                        $departmentAccess = strtolower($event['department_access'] ?? '');
                        $studentAccessList = [];
                        if (!empty($event['student_access'])) {
                            $decodedStudents = is_array($event['student_access']) ? $event['student_access'] : json_decode($event['student_access'], true);
                            if (is_array($decodedStudents)) {
                                $studentAccessList = array_map('intval', $decodedStudents);
                            }
                        }
                        $canView = true;
                        $canJoin = true;
                        if ($audienceType === 'department') {
                            $studentDept = strtolower($student['department'] ?? '');
                            if ($departmentAccess && $studentDept !== $departmentAccess) {
                                $canView = false;
                            }
                        } elseif ($audienceType === 'specific_students') {
                            // All students can see the event, but only specific students can join
                            $canView = true;
                            if (!empty($studentAccessList)) {
                                $canJoin = in_array((int)$student['id'], $studentAccessList, true);
                            } else {
                                // If no students selected, no one can join
                                $canJoin = false;
                            }
                        }
                        if (!$canView) {
                            continue;
                        }
                        
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
                        
                        // Get reaction counts and user's reaction
                        $likeModel = new \App\Models\PostLikeModel();
                        $reactionCounts = $likeModel->getReactionCounts('event', $eventId);
                        $userReaction = null;
                        if ($student) {
                            $userReaction = $likeModel->getUserReaction($student['id'], 'event', $eventId);
                        }
                        
                        // Get comment count
                        $commentModel = new \App\Models\PostCommentModel();
                        $commentCount = $commentModel->getCommentCount('event', $eventId);
                        
                        // Check if student has joined this event
                        $hasJoined = false;
                        $db = \Config\Database::connect();
                        $attendeeCheck = $db->table('event_attendees')
                            ->where('event_id', $eventId)
                            ->where('student_id', $student['id'])
                            ->get()
                            ->getRowArray();
                        if ($attendeeCheck) {
                            $hasJoined = true;
                        }
                        
                        // Check if student is interested in this event
                        $isInterested = false;
                        $interestCheck = $db->table('event_interests')
                            ->where('event_id', $eventId)
                            ->where('student_id', $student['id'])
                            ->get()
                            ->getRowArray();
                        if ($interestCheck) {
                            $isInterested = true;
                        }
                        
                        // Get interest count
                        $interestCount = $db->table('event_interests')
                            ->where('event_id', $eventId)
                            ->countAllResults();
                        
                        // Check if event is ongoing (date and time have arrived)
                        $isOngoing = false;
                        // Parse time - handle both 24-hour (08:00) and 12-hour (8:00 AM) formats
                        $eventTime = $event['time'] ?? '00:00:00';
                        // If time doesn't have AM/PM and is in 24-hour format, convert for strtotime
                        if (strpos($eventTime, 'AM') === false && strpos($eventTime, 'PM') === false && strpos($eventTime, ':') !== false) {
                            // Time is in 24-hour format like "08:00" or "08:00:00"
                            $timeParts = explode(':', $eventTime);
                            $hour = (int)$timeParts[0];
                            $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
                            $eventTime = sprintf('%02d:%02d:00', $hour, $minute);
                        }
                        $eventDateTime = $event['date'] . ' ' . $eventTime;
                        $eventTimestamp = strtotime($eventDateTime);
                        $now = time();
                        
                        // Determine end time
                        $endDateTime = null;
                        if (!empty($event['end_date']) && !empty($event['end_time'])) {
                            $endDateTime = strtotime($event['end_date'] . ' ' . $event['end_time']);
                        } elseif (!empty($event['end_time'])) {
                            // If only end_time is set, use same date
                            $endDateTime = strtotime($event['date'] . ' ' . $event['end_time']);
                        } elseif (!empty($event['end_date'])) {
                            // If only end_date is set, use end of that day
                            $endDateTime = strtotime($event['end_date'] . ' 23:59:59');
                        } else {
                            // Default: end of start date
                            $endDateTime = strtotime($event['date'] . ' 23:59:59');
                        }
                        
                        // Event is ongoing if current time is between start and end time
                        if ($now >= $eventTimestamp && $now <= $endDateTime) {
                            $isOngoing = true;
                        }
                        
                        // Determine if event has ended
                        $isEnded = ($now > $endDateTime);
                        
                        $eventData = [
                            'id' => $eventId,
                            'title' => $event['event_name'] ?? $event['title'],
                            'description' => $event['description'],
                            'date' => $event['date'],
                            'date_formatted' => $eventDate,
                            'time' => $timeFormatted,
                            'end_date' => $event['end_date'] ?? null,
                            'end_time' => $event['end_time'] ?? null,
                            'location' => $event['venue'] ?? $event['location'],
                            'audience_type' => $audienceType,
                            'department_access' => $departmentAccess,
                            'student_access' => $studentAccessList,
                            'can_join' => $canJoin,
                            'has_joined' => $hasJoined,
                            'is_interested' => $isInterested,
                            'interest_count' => $interestCount,
                            'is_ongoing' => $isOngoing,
                            'is_ended' => $isEnded,
                            'attendees' => $event['current_attendees'] ?? 0,
                            'max_attendees' => $event['max_attendees'],
                            'status' => $event['status'] ?? 'upcoming',
                            'image' => $event['image'] ?? null,
                            'created_at' => $event['created_at'] ?? $event['date'],
                            'org_id' => $orgId,
                            'org_name' => $org['organization_name'] ?? '',
                            'org_acronym' => $org['organization_acronym'] ?? '',
                            'org_type' => ucfirst(str_replace('_', ' ', $event['org_type'] ?? 'academic')),
                            'org_photo' => $orgPhotoForEvent,
                            'reaction_counts' => $reactionCounts,
                            'user_reaction' => $userReaction,
                            'like_count' => $reactionCounts['total'], // For backward compatibility
                            'is_liked' => $userReaction !== null, // For backward compatibility
                            'comment_count' => $commentCount
                        ];
                        
                        $organizationPosts['events'][] = $eventData;
                        $allEventsList[] = $eventData;
                        
                        // Collect upcoming events for sidebar (only future events that haven't started yet)
                        // Exclude ongoing events and events that have already started
                        // Use the same eventTimestamp that was calculated for isOngoing check
                        // Only add if event hasn't started yet (current time < event start time)
                        if (!$isOngoing && $now < $eventTimestamp) {
                            $allUpcomingEvents[] = $eventData;
                        }
                    }
                    }
                }
                
                // Also fetch events from ALL active organizations where student is specifically invited
                // This ensures students see events they're invited to even if they don't follow the org
                $allActiveOrgs = $orgModel->where('is_active', 1)->findAll();
                foreach ($allActiveOrgs as $org) {
                    // Skip if already processed (from followed/member orgs)
                    if (in_array($org['id'], $orgIdsForPosts)) {
                        continue;
                    }
                    
                    $events = $eventModel->getEventsByOrg($org['id']);
                    
                    // Get organization photo for events
                    $orgPhotoForEvent = null;
                    if ($org && !empty($org['user_id'])) {
                        $orgUserPhotoForEvent = $userPhotoModel->where('user_id', $org['user_id'])->first();
                        if ($orgUserPhotoForEvent && !empty($orgUserPhotoForEvent['photo_path'])) {
                            $orgPhotoForEvent = base_url($orgUserPhotoForEvent['photo_path']);
                        }
                    }
                    
                    foreach ($events as $event) {
                        $eventId = $event['event_id'] ?? $event['id'];
                        
                        // Skip if already processed
                        if (in_array($eventId, $processedEventIds)) {
                            continue;
                        }
                        
                        // Update event status in database based on current date/time
                        $eventModel->updateEventStatus($eventId);
                        // Refresh event data to get updated status
                        $event = $eventModel->find($eventId);
                        
                        $audienceType = $event['audience_type'] ?? 'all';
                        
                        // Show all events with 'specific_students' to everyone, but only allow specific students to join
                        if ($audienceType === 'specific_students') {
                            $studentAccessList = [];
                            if (!empty($event['student_access'])) {
                                $decodedStudents = is_array($event['student_access']) ? $event['student_access'] : json_decode($event['student_access'], true);
                                if (is_array($decodedStudents)) {
                                    $studentAccessList = array_map('intval', $decodedStudents);
                                }
                            }
                            
                            // All students can see, but check if this student can join
                            $canJoin = false;
                            if (!empty($studentAccessList) && in_array((int)$student['id'], $studentAccessList, true)) {
                                $canJoin = true;
                            }
                            
                            $processedEventIds[] = $eventId;
                                
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
                                
                                // Get reaction counts and user's reaction
                                $likeModel = new \App\Models\PostLikeModel();
                                $reactionCounts = $likeModel->getReactionCounts('event', $eventId);
                                $userReaction = null;
                                if ($student) {
                                    $userReaction = $likeModel->getUserReaction($student['id'], 'event', $eventId);
                                }
                                
                                // Get comment count
                                $commentModel = new \App\Models\PostCommentModel();
                                $commentCount = $commentModel->getCommentCount('event', $eventId);
                                
                                // Check if student has joined this event
                                $hasJoined = false;
                                $db = \Config\Database::connect();
                                $attendeeCheck = $db->table('event_attendees')
                                    ->where('event_id', $eventId)
                                    ->where('student_id', $student['id'])
                                    ->get()
                                    ->getRowArray();
                                if ($attendeeCheck) {
                                    $hasJoined = true;
                                }
                                
                                // Check if student is interested in this event
                                $isInterested = false;
                                $interestCheck = $db->table('event_interests')
                                    ->where('event_id', $eventId)
                                    ->where('student_id', $student['id'])
                                    ->get()
                                    ->getRowArray();
                                if ($interestCheck) {
                                    $isInterested = true;
                                }
                                
                                // Get interest count
                                $interestCount = $db->table('event_interests')
                                    ->where('event_id', $eventId)
                                    ->countAllResults();
                                
                                // Check if event is ongoing (date and time have arrived)
                                $isOngoing = false;
                                // Parse time - handle both 24-hour (08:00) and 12-hour (8:00 AM) formats
                                $eventTime = $event['time'] ?? '00:00:00';
                                // If time doesn't have AM/PM and is in 24-hour format, convert for strtotime
                                if (strpos($eventTime, 'AM') === false && strpos($eventTime, 'PM') === false && strpos($eventTime, ':') !== false) {
                                    // Time is in 24-hour format like "08:00" or "08:00:00"
                                    $timeParts = explode(':', $eventTime);
                                    $hour = (int)$timeParts[0];
                                    $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
                                    $eventTime = sprintf('%02d:%02d:00', $hour, $minute);
                                }
                                $eventDateTime = $event['date'] . ' ' . $eventTime;
                                $eventTimestamp = strtotime($eventDateTime);
                                $now = time();
                                
                                // Determine end time
                                $endDateTime = null;
                                if (!empty($event['end_date']) && !empty($event['end_time'])) {
                                    $endDateTime = strtotime($event['end_date'] . ' ' . $event['end_time']);
                                } elseif (!empty($event['end_time'])) {
                                    // If only end_time is set, use same date
                                    $endDateTime = strtotime($event['date'] . ' ' . $event['end_time']);
                                } elseif (!empty($event['end_date'])) {
                                    // If only end_date is set, use end of that day
                                    $endDateTime = strtotime($event['end_date'] . ' 23:59:59');
                                } else {
                                    // Default: end of start date
                                    $endDateTime = strtotime($event['date'] . ' 23:59:59');
                                }
                                
                                // Event is ongoing if current time is between start and end time
                                if ($now >= $eventTimestamp && $now <= $endDateTime) {
                                    $isOngoing = true;
                                }
                                
                                // Determine if event has ended
                                $isEnded = ($now > $endDateTime);
                                
                                $eventData = [
                                    'id' => $eventId,
                                    'title' => $event['event_name'] ?? $event['title'],
                                    'description' => $event['description'],
                                    'date' => $event['date'],
                                    'date_formatted' => $eventDate,
                                    'time' => $timeFormatted,
                                    'end_date' => $event['end_date'] ?? null,
                                    'end_time' => $event['end_time'] ?? null,
                                    'location' => $event['venue'] ?? $event['location'],
                                    'audience_type' => $audienceType,
                                    'department_access' => strtolower($event['department_access'] ?? ''),
                                    'student_access' => $studentAccessList,
                                    'can_join' => $canJoin,
                                    'has_joined' => $hasJoined,
                                    'is_interested' => $isInterested,
                                    'interest_count' => $interestCount,
                                    'is_ongoing' => $isOngoing,
                                    'is_ended' => $isEnded,
                                    'attendees' => $event['current_attendees'] ?? 0,
                                    'max_attendees' => $event['max_attendees'],
                                    'status' => $event['status'] ?? 'upcoming',
                                    'image' => $event['image'] ?? null,
                                    'created_at' => $event['created_at'] ?? $event['date'],
                                    'org_id' => $org['id'],
                                    'org_name' => $org['organization_name'] ?? '',
                                    'org_acronym' => $org['organization_acronym'] ?? '',
                                    'org_type' => ucfirst(str_replace('_', ' ', $event['org_type'] ?? 'academic')),
                                    'org_photo' => $orgPhotoForEvent,
                                    'reaction_counts' => $reactionCounts,
                                    'user_reaction' => $userReaction,
                                    'like_count' => $reactionCounts['total'],
                                    'is_liked' => $userReaction !== null,
                                    'comment_count' => $commentCount
                                ];
                                
                                $organizationPosts['events'][] = $eventData;
                                $allEventsList[] = $eventData;
                                
                                // Collect upcoming events for sidebar (only future events that haven't started yet)
                                // Exclude ongoing events and events that have already started
                                // Use the same eventTimestamp that was calculated for isOngoing check
                                // Only add if event hasn't started yet (current time < event start time)
                                if (!$isOngoing && $now < $eventTimestamp) {
                                    $allUpcomingEvents[] = $eventData;
                                }
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
                
                // Get products from followed organizations only
                $productModel = new ProductModel();
                $orgModelForProducts = new OrganizationModel();
                foreach ($orgIdsForPosts as $orgId) {
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
        
        // Get student's follow status for all organizations
        $followModel = new \App\Models\OrganizationFollowModel();
        $studentFollows = [];
        if ($student) {
            $follows = $followModel->getFollowedOrganizations($student['id']);
            foreach ($follows as $follow) {
                $studentFollows[] = $follow['org_id'];
            }
        }
        
        // Get organization photos from user_photos table
        $userPhotoModel = new \App\Models\UserPhotoModel();
        
        foreach ($organizations as $org) {
            // Get event count for this organization
            $orgEvents = $eventModel->getEventsByOrg($org['id']);
            $orgEventCount = count($orgEvents);
            
            // Check if student is a member (active) or has pending request
            $membershipStatus = $studentMemberships[$org['id']] ?? null;
            $isMember = ($membershipStatus === 'active');
            $isPending = ($membershipStatus === 'pending');
            
            // Check if student is following this organization
            $isFollowing = in_array($org['id'], $studentFollows);
            
            // Get organization photo from user_photos table
            $orgPhoto = null;
            if (!empty($org['user_id'])) {
                $orgUserPhoto = $userPhotoModel->where('user_id', $org['user_id'])->first();
                if ($orgUserPhoto && !empty($orgUserPhoto['photo_path'])) {
                    $orgPhoto = base_url($orgUserPhoto['photo_path']);
                }
            }
            
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
                'is_following' => $isFollowing,
                'photo' => $orgPhoto
            ];
        }

        // Get suggested organizations (organizations student hasn't joined AND hasn't followed)
        $suggestedOrganizations = [];
        if ($student && isset($allMemberships)) {
            // Get all organization IDs the student has joined (active or pending)
            $joinedOrgIds = array_column($allMemberships, 'org_id');
            
            // Filter out organizations student has already joined (active or pending) OR is following
            foreach ($formattedOrgs as $org) {
                // Exclude if student has joined (active or pending) OR is following
                if (!in_array($org['id'], $joinedOrgIds) && !in_array($org['id'], $studentFollows)) {
                    $suggestedOrganizations[] = $org;
                }
            }
            
            // Sort by member count (most popular first) and limit to 3
            usort($suggestedOrganizations, function($a, $b) {
                return ($b['members'] ?? 0) - ($a['members'] ?? 0);
            });
            $suggestedOrganizations = array_slice($suggestedOrganizations, 0, 3);
        } else {
            // If no student or no memberships, filter out followed organizations if any
            if ($student && !empty($studentFollows)) {
                foreach ($formattedOrgs as $org) {
                    if (!in_array($org['id'], $studentFollows)) {
                        $suggestedOrganizations[] = $org;
                    }
                }
                $suggestedOrganizations = array_slice($suggestedOrganizations, 0, 3);
            } else {
                // If no student or no follows, just show first 3 organizations
                $suggestedOrganizations = array_slice($formattedOrgs, 0, 3);
            }
        }
        
        // Format all memberships (including pending) for sidebar
        $allJoinedOrgs = [];
        if ($student && isset($allMemberships)) {
            $userPhotoModelForSidebar = new \App\Models\UserPhotoModel();
            foreach ($allMemberships as $membership) {
                // Get organization photo from user_photos table
                $orgForSidebar = $orgModel->find($membership['org_id']);
                $orgPhotoForSidebar = null;
                if ($orgForSidebar && !empty($orgForSidebar['user_id'])) {
                    $orgUserPhotoForSidebar = $userPhotoModelForSidebar->where('user_id', $orgForSidebar['user_id'])->first();
                    if ($orgUserPhotoForSidebar && !empty($orgUserPhotoForSidebar['photo_path'])) {
                        $orgPhotoForSidebar = base_url($orgUserPhotoForSidebar['photo_path']);
                    }
                }
                
                $allJoinedOrgs[] = [
                    'id' => $membership['org_id'],
                    'name' => $membership['organization_name'],
                    'acronym' => $membership['organization_acronym'],
                    'status' => $membership['status'],
                    'photo' => $orgPhotoForSidebar
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
                'province' => $this->request->getPost('province') ?? '',
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
        // Allow both students and organizations to comment
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $content = trim($this->request->getPost('content'));
        $postType = $this->request->getPost('post_type') ?? $this->request->getPost('type'); // 'announcement' or 'event'
        $postId = $this->request->getPost('post_id') ?? $this->request->getPost('target_id');
        $parentCommentId = $this->request->getPost('parent_comment_id'); // For replies

        if (empty($content)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comment cannot be empty']);
        }

        if (!in_array($postType, ['announcement', 'event', 'forum_post'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid post type']);
        }

        $userId = session()->get('user_id');
        $role = session()->get('role');
        
        // Get user ID and type
        $commenterId = null;
        $userType = null;
        
        if ($role === 'student') {
            $student = $this->studentModel->where('user_id', $userId)->first();
            if (!$student) {
                return $this->response->setJSON(['success' => false, 'message' => 'Student record not found']);
            }
            $commenterId = $student['id'];
            $userType = 'student';
        } elseif ($role === 'organization') {
            $orgModel = new \App\Models\OrganizationModel();
            $organization = $orgModel->where('user_id', $userId)->first();
            if (!$organization) {
                return $this->response->setJSON(['success' => false, 'message' => 'Organization record not found']);
            }
            $commenterId = $organization['id'];
            $userType = 'organization';
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid user role']);
        }

        try {
            $commentModel = new \App\Models\PostCommentModel();
            
            $commentData = [
                'post_type' => $postType,
                'post_id' => $postId,
                'content' => $content,
                'commenter_type' => $userType,
                'parent_comment_id' => $parentCommentId ? (int)$parentCommentId : null
            ];
            
            if ($userType === 'student') {
                $commentData['student_id'] = $commenterId;
                $commentData['organization_id'] = null;
            } else {
                $commentData['organization_id'] = $commenterId;
                $commentData['student_id'] = null;
            }
            
            $commentId = $commentModel->insert($commentData);

            if ($commentId) {
                // Get the created comment with user info
                if ($userType === 'student') {
                    $comment = $commentModel->select('post_comments.*, user_profiles.firstname, user_profiles.lastname, students.student_id')
                                            ->join('students', 'students.id = post_comments.student_id')
                                            ->join('user_profiles', 'user_profiles.user_id = students.user_id')
                                            ->where('post_comments.id', $commentId)
                                            ->first();
                    $profile = $this->userProfileModel->where('user_id', $userId)->first();
                    $userName = ($profile['firstname'] ?? '') . ' ' . ($profile['lastname'] ?? '');
                } else {
                    $comment = $commentModel->select('post_comments.*, organizations.name as org_name, organizations.acronym as org_acronym')
                                            ->join('organizations', 'organizations.id = post_comments.organization_id')
                                            ->where('post_comments.id', $commentId)
                                            ->first();
                    $userName = $comment['org_name'] ?? 'Organization';
                }

                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Comment posted successfully!',
                    'comment' => [
                        'id' => $commentId,
                        'content' => $content,
                        'user_name' => trim($userName),
                        'student_id' => $comment['student_id'] ?? null,
                        'organization_id' => $comment['organization_id'] ?? null,
                        'is_organization' => $userType === 'organization',
                        'created_at' => $comment['created_at'] ?? date('Y-m-d H:i:s')
                    ]
                ]);
            }

            return $this->response->setJSON(['success' => false, 'message' => 'Failed to post comment']);
        } catch (\Exception $e) {
            log_message('error', 'Comment error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while posting comment']);
        }
    }

    public function likePost()
    {
        // Allow both students and organizations to like posts
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $postType = $this->request->getPost('type'); // 'announcement', 'event', or 'forum_post'
        $postId = $this->request->getPost('post_id');
        $reactionType = $this->request->getPost('reaction_type') ?? 'like'; // Default to 'like'

        if (empty($postType) || !in_array($postType, ['announcement', 'event', 'forum_post'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid post type']);
        }

        if (empty($postId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Post ID is required']);
        }

        // Ensure post_id is an integer
        $postId = (int)$postId;

        $userId = session()->get('user_id');
        $role = session()->get('role');
        
        // Get user ID and type
        $reactorId = null;
        $userType = null;
        
        if ($role === 'student') {
            $student = $this->studentModel->where('user_id', $userId)->first();
            if (!$student) {
                return $this->response->setJSON(['success' => false, 'message' => 'Student record not found']);
            }
            $reactorId = $student['id'];
            $userType = 'student';
        } elseif ($role === 'organization') {
            $orgModel = new \App\Models\OrganizationModel();
            $organization = $orgModel->where('user_id', $userId)->first();
            if (!$organization) {
                return $this->response->setJSON(['success' => false, 'message' => 'Organization record not found']);
            }
            $reactorId = $organization['id'];
            $userType = 'organization';
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid user role']);
        }

        try {
            $likeModel = new \App\Models\PostLikeModel();
            
            // Validate reactor ID
            if (empty($reactorId)) {
                return $this->response->setJSON(['success' => false, 'message' => 'User ID not found']);
            }
            
            // Use setReaction for Facebook-style reactions (supports both students and organizations)
            $result = $likeModel->setReaction($reactorId, $postType, $postId, $reactionType, $userType);

            return $this->response->setJSON([
                'success' => true,
                'reacted' => $result['reacted'],
                'reaction_type' => $result['reaction_type'],
                'counts' => $result['counts'],
                'message' => $result['reacted'] ? 'Reaction added!' : 'Reaction removed!'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Like error: ' . $e->getMessage());
            log_message('error', 'Like error trace: ' . $e->getTraceAsString());
            $errorMessage = 'An error occurred while reacting to post: ' . $e->getMessage();
            // Check if it's a table doesn't exist error
            if (strpos($e->getMessage(), "doesn't exist") !== false || strpos($e->getMessage(), 'Table') !== false) {
                $errorMessage = 'Database table not found. Please run the SQL script to create post_likes table.';
            }
            return $this->response->setJSON([
                'success' => false, 
                'message' => $errorMessage
            ]);
        }
    }

    public function getComments()
    {
        // Allow both students and organizations to view comments
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $postType = $this->request->getGet('post_type') ?? $this->request->getGet('type');
        $postId = $this->request->getGet('post_id');

        if (!in_array($postType, ['announcement', 'event', 'forum_post'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid post type']);
        }

        try {
            $commentModel = new \App\Models\PostCommentModel();
            $comments = $commentModel->getComments($postType, $postId);

            $formattedComments = [];
            foreach ($comments as $comment) {
                $formattedComment = [
                    'id' => $comment['id'],
                    'content' => $comment['content'],
                    'user_name' => trim(($comment['firstname'] ?? '') . ' ' . ($comment['lastname'] ?? '')),
                    'firstname' => $comment['firstname'] ?? '',
                    'lastname' => $comment['lastname'] ?? '',
                    'student_id' => $comment['student_id'] ?? '',
                    'created_at' => $comment['created_at'],
                    'parent_comment_id' => $comment['parent_comment_id'] ?? null,
                    'is_organization' => $comment['is_organization'] ?? false,
                    'replies' => []
                ];
                
                // Format replies recursively if they exist
                if (isset($comment['replies']) && is_array($comment['replies'])) {
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
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while fetching comments']);
        }
    }
    
    /**
     * Recursively format replies
     */
    private function formatRepliesRecursive($replies)
    {
        $formattedReplies = [];
        foreach ($replies as $reply) {
            $replyUserName = '';
            if (isset($reply['is_organization']) && $reply['is_organization']) {
                $replyUserName = $reply['firstname'] ?? 'Organization';
            } else {
                $replyUserName = trim(($reply['firstname'] ?? '') . ' ' . ($reply['lastname'] ?? ''));
            }
            
            $formattedReply = [
                'id' => $reply['id'],
                'content' => $reply['content'],
                'created_at' => $reply['created_at'],
                'user_name' => $replyUserName,
                'firstname' => $reply['firstname'] ?? '',
                'lastname' => $reply['lastname'] ?? '',
                'student_id' => $reply['student_id'] ?? '',
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

        // Get student data
        $userId = session()->get('user_id');
        $student = $this->studentModel->where('user_id', $userId)->first();
        
        if (!$student) {
            return $this->response->setJSON(['success' => false, 'message' => 'Student record not found']);
        }

        // Get event data
        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);
        
        if (!$event) {
            return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
        }

        // Check if student can join based on audience type
        $audienceType = $event['audience_type'] ?? 'all';
        if ($audienceType === 'specific_students') {
            $studentAccessList = [];
            if (!empty($event['student_access'])) {
                $decodedStudents = is_array($event['student_access']) ? $event['student_access'] : json_decode($event['student_access'], true);
                if (is_array($decodedStudents)) {
                    $studentAccessList = array_map('intval', $decodedStudents);
                }
            }
            
            if (empty($studentAccessList) || !in_array((int)$student['id'], $studentAccessList, true)) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'You are not authorized to join this event. This event is only for specific invited students.'
                ]);
            }
        } elseif ($audienceType === 'department') {
            $studentDept = strtolower($student['department'] ?? '');
            $departmentAccess = strtolower($event['department_access'] ?? '');
            if ($departmentAccess && $studentDept !== $departmentAccess) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'You are not authorized to join this event. This event is only for students from ' . strtoupper($departmentAccess) . ' department.'
                ]);
            }
        }

        // Check if student has already joined
        $db = \Config\Database::connect();
        $existingAttendee = $db->table('event_attendees')
            ->where('event_id', $eventId)
            ->where('student_id', $student['id'])
            ->get()
            ->getRowArray();
        
        if ($existingAttendee) {
            // Check if event is ongoing even if already joined
            $isOngoing = false;
            $eventDateTime = $event['date'] . ' ' . ($event['time'] ?? '00:00:00');
            $eventTimestamp = strtotime($eventDateTime);
            $now = time();
            
            // Determine end time
            $endDateTime = null;
            if (!empty($event['end_date']) && !empty($event['end_time'])) {
                $endDateTime = strtotime($event['end_date'] . ' ' . $event['end_time']);
            } elseif (!empty($event['end_time'])) {
                $endDateTime = strtotime($event['date'] . ' ' . $event['end_time']);
            } elseif (!empty($event['end_date'])) {
                $endDateTime = strtotime($event['end_date'] . ' 23:59:59');
            } else {
                $endDateTime = strtotime($event['date'] . ' 23:59:59');
            }
            
            if ($now >= $eventTimestamp && $now <= $endDateTime) {
                $isOngoing = true;
            }
            
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'You have already joined this event.',
                'has_joined' => true,
                'is_ongoing' => $isOngoing
            ]);
        }
        
        // Check max attendees limit
        if (!empty($event['max_attendees']) && $event['current_attendees'] >= $event['max_attendees']) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'This event has reached its maximum capacity.'
            ]);
        }
        
        // Add student to event attendees
        $attendeeData = [
            'event_id' => $eventId,
            'student_id' => $student['id'],
            'joined_at' => date('Y-m-d H:i:s')
        ];
        
        if ($db->table('event_attendees')->insert($attendeeData)) {
            // Update current_attendees count
            $newAttendeeCount = ($event['current_attendees'] ?? 0) + 1;
            $eventModel->update($eventId, [
                'current_attendees' => $newAttendeeCount
            ]);
            
            // Check if event is ongoing
            $isOngoing = false;
            $eventDateTime = $event['date'] . ' ' . ($event['time'] ?? '00:00:00');
            $eventTimestamp = strtotime($eventDateTime);
            $now = time();
            
            // Determine end time
            $endDateTime = null;
            if (!empty($event['end_date']) && !empty($event['end_time'])) {
                $endDateTime = strtotime($event['end_date'] . ' ' . $event['end_time']);
            } elseif (!empty($event['end_time'])) {
                $endDateTime = strtotime($event['date'] . ' ' . $event['end_time']);
            } elseif (!empty($event['end_date'])) {
                $endDateTime = strtotime($event['end_date'] . ' 23:59:59');
            } else {
                $endDateTime = strtotime($event['date'] . ' 23:59:59');
            }
            
            if ($now >= $eventTimestamp && $now <= $endDateTime) {
                $isOngoing = true;
            }
            
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Successfully registered for the event!',
                'event_id' => $eventId,
                'has_joined' => true,
                'is_ongoing' => $isOngoing,
                'attendees' => $newAttendeeCount
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Failed to join event. Please try again.'
            ]);
        }
    }

    /**
     * Get event details
     */
    public function getEventDetails($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $eventId = $id ?? $this->request->getGet('event_id');
        if (empty($eventId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Event ID is required']);
        }

        // Get student data
        $userId = session()->get('user_id');
        $student = $this->studentModel->where('user_id', $userId)->first();
        
        if (!$student) {
            return $this->response->setJSON(['success' => false, 'message' => 'Student record not found']);
        }

        // Get event data
        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);
        
        if (!$event) {
            return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
        }

        // Get organization data
        $db = \Config\Database::connect();
        $org = $db->table('organizations')
            ->where('id', $event['org_id'])
            ->get()
            ->getRowArray();
        
        if (!$org) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization not found']);
        }

        // Get organization photo
        $userPhotoModel = new \App\Models\UserPhotoModel();
        $orgPhoto = null;
        if (!empty($org['user_id'])) {
            $orgUserPhoto = $userPhotoModel->where('user_id', $org['user_id'])->first();
            if ($orgUserPhoto && !empty($orgUserPhoto['photo_path'])) {
                $orgPhoto = base_url($orgUserPhoto['photo_path']);
            }
        }

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

        // Format end time if available
        $endTimeFormatted = null;
        if (!empty($event['end_time'])) {
            $endTimeFormatted = $event['end_time'];
            if (strpos($endTimeFormatted, ':') !== false) {
                $timeParts = explode(':', $endTimeFormatted);
                $hour = (int)$timeParts[0];
                $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
                $period = $hour >= 12 ? 'PM' : 'AM';
                $hour12 = $hour > 12 ? $hour - 12 : ($hour == 0 ? 12 : $hour);
                $endTimeFormatted = sprintf('%d:%02d %s', $hour12, $minute, $period);
            }
        }

        // Format date
        $eventDate = date('F j, Y', strtotime($event['date']));
        
        // Format end date if available
        $endDateFormatted = null;
        if (!empty($event['end_date'])) {
            $endDateFormatted = date('F j, Y', strtotime($event['end_date']));
        }

        // Check if student has joined
        $hasJoined = false;
        $attendeeCheck = $db->table('event_attendees')
            ->where('event_id', $eventId)
            ->where('student_id', $student['id'])
            ->get()
            ->getRowArray();
        if ($attendeeCheck) {
            $hasJoined = true;
        }

        // Check if student is interested
        $isInterested = false;
        $likeModel = new \App\Models\PostLikeModel();
        $userReaction = $likeModel->getUserReaction($student['id'], 'event', $eventId);
        if ($userReaction) {
            $isInterested = true;
        }

        // Get reaction counts
        $reactionCounts = $likeModel->getReactionCounts('event', $eventId);
        $interestCount = $reactionCounts['total'] ?? 0;

        // Check audience type and access
        $audienceType = $event['audience_type'] ?? 'all';
        $canJoin = true;
        if ($audienceType === 'specific_students') {
            $studentAccessList = [];
            if (!empty($event['student_access'])) {
                $decodedStudents = is_array($event['student_access']) ? $event['student_access'] : json_decode($event['student_access'], true);
                if (is_array($decodedStudents)) {
                    $studentAccessList = array_map('intval', $decodedStudents);
                }
            }
            if (empty($studentAccessList) || !in_array((int)$student['id'], $studentAccessList, true)) {
                $canJoin = false;
            }
        } elseif ($audienceType === 'department') {
            $studentDept = strtolower($student['department'] ?? '');
            $departmentAccess = strtolower($event['department_access'] ?? '');
            if ($departmentAccess && $studentDept !== $departmentAccess) {
                $canJoin = false;
            }
        }

        // Format event image URL
        $eventImage = null;
        if (!empty($event['image'])) {
            $eventImage = base_url('uploads/events/' . $event['image']);
        }

        // Update event status in database based on current date/time
        $eventModel->updateEventStatus($eventId);
        // Refresh event data to get updated status from database
        $db = \Config\Database::connect();
        $event = $db->table('events')
            ->where('event_id', $eventId)
            ->get()
            ->getRowArray();
        
        // Check if event is ongoing (date and time have arrived)
        $isOngoing = false;
        $eventDateTime = $event['date'] . ' ' . ($event['time'] ?? '00:00:00');
        $eventTimestamp = strtotime($eventDateTime);
        $now = time();
        
        // Determine end time
        $endDateTime = null;
        if (!empty($event['end_date']) && !empty($event['end_time'])) {
            $endDateTime = strtotime($event['end_date'] . ' ' . $event['end_time']);
        } elseif (!empty($event['end_time'])) {
            // If only end_time is set, use same date
            $endDateTime = strtotime($event['date'] . ' ' . $event['end_time']);
        } elseif (!empty($event['end_date'])) {
            // If only end_date is set, use end of that day
            $endDateTime = strtotime($event['end_date'] . ' 23:59:59');
        } else {
            // Default: end of start date
            $endDateTime = strtotime($event['date'] . ' 23:59:59');
        }
        
        // Event is ongoing if current time is between start and end time
        if ($now >= $eventTimestamp && $now <= $endDateTime) {
            $isOngoing = true;
        }
        
        // Determine if event has ended
        $isEnded = ($now > $endDateTime);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'id' => $eventId,
                'title' => $event['event_name'] ?? $event['title'],
                'description' => $event['description'] ?? '',
                'date' => $event['date'],
                'date_formatted' => $eventDate,
                'time' => $timeFormatted,
                'end_date' => $event['end_date'] ?? null,
                'end_date_formatted' => $endDateFormatted,
                'end_time' => $endTimeFormatted,
                'location' => $event['venue'] ?? $event['location'],
                'image' => $eventImage,
                'max_attendees' => $event['max_attendees'] ?? null,
                'current_attendees' => $event['current_attendees'] ?? 0,
                'audience_type' => $audienceType,
                'department_access' => $event['department_access'] ?? '',
                'org_id' => $org['id'],
                'org_name' => $org['organization_name'] ?? '',
                'org_acronym' => $org['organization_acronym'] ?? '',
                'org_type' => ucfirst(str_replace('_', ' ', $event['org_type'] ?? 'academic')),
                'org_photo' => $orgPhoto,
                'can_join' => $canJoin,
                'has_joined' => $hasJoined,
                'is_ongoing' => $isOngoing,
                'is_ended' => $isEnded,
                'is_interested' => $isInterested,
                'interest_count' => $interestCount,
                'status' => $event['status'] ?? 'upcoming',
                'created_at' => $event['created_at'] ?? $event['date']
            ]
        ]);
    }

    /**
     * Toggle interest in an event
     */
    public function toggleEventInterest()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $eventId = $this->request->getPost('event_id');

        if (empty($eventId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Event ID is required']);
        }

        // Get student data
        $userId = session()->get('user_id');
        $student = $this->studentModel->where('user_id', $userId)->first();
        
        if (!$student) {
            return $this->response->setJSON(['success' => false, 'message' => 'Student record not found']);
        }

        // Get event data
        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);
        
        if (!$event) {
            return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
        }

        $db = \Config\Database::connect();
        
        // Check if student is already interested
        $existingInterest = $db->table('event_interests')
            ->where('event_id', $eventId)
            ->where('student_id', $student['id'])
            ->get()
            ->getRowArray();
        
        if ($existingInterest) {
            // Remove interest
            $db->table('event_interests')
                ->where('event_id', $eventId)
                ->where('student_id', $student['id'])
                ->delete();
            
            // Get updated interest count
            $interestCount = $db->table('event_interests')
                ->where('event_id', $eventId)
                ->countAllResults();
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Removed from interested',
                'is_interested' => false,
                'interest_count' => $interestCount
            ]);
        } else {
            // Add interest
            $interestData = [
                'event_id' => $eventId,
                'student_id' => $student['id'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            if ($db->table('event_interests')->insert($interestData)) {
                // Get updated interest count
                $interestCount = $db->table('event_interests')
                    ->where('event_id', $eventId)
                    ->countAllResults();
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Marked as interested',
                    'is_interested' => true,
                    'interest_count' => $interestCount
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to mark as interested. Please try again.'
                ]);
            }
        }
    }

    /**
     * Join Organization
     */
    public function followOrg()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $orgId = $this->request->getPost('org_id');
        if (!$orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization ID is required']);
        }

        $userId = session()->get('user_id');
        $student = $this->studentModel->where('user_id', $userId)->first();
        
        if (!$student) {
            return $this->response->setJSON(['success' => false, 'message' => 'Student record not found']);
        }

        try {
            $followModel = new \App\Models\OrganizationFollowModel();
            
            // Check if already following
            if ($followModel->isFollowing($student['id'], $orgId)) {
                return $this->response->setJSON(['success' => false, 'message' => 'You are already following this organization']);
            }

            // Add follow
            $followModel->insert([
                'student_id' => $student['id'],
                'org_id' => $orgId
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'You are now following this organization',
                'is_following' => true
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Follow organization error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while following the organization']);
        }
    }

    public function unfollowOrg()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $orgId = $this->request->getPost('org_id');
        if (!$orgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Organization ID is required']);
        }

        $userId = session()->get('user_id');
        $student = $this->studentModel->where('user_id', $userId)->first();
        
        if (!$student) {
            return $this->response->setJSON(['success' => false, 'message' => 'Student record not found']);
        }

        try {
            $followModel = new \App\Models\OrganizationFollowModel();
            
            // Find and delete follow record
            $follow = $followModel->where('student_id', $student['id'])
                                  ->where('org_id', $orgId)
                                  ->first();
            
            if (!$follow) {
                return $this->response->setJSON(['success' => false, 'message' => 'You are not following this organization']);
            }

            $followModel->delete($follow['id']);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'You have unfollowed this organization',
                'is_following' => false
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Unfollow organization error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while unfollowing the organization']);
        }
    }

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
     * View Organization Page
     */
    public function viewOrganization($orgId)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $orgModel = new OrganizationModel();
        $organization = $orgModel->find($orgId);
        
        if (!$organization) {
            return redirect()->to(base_url('student/dashboard'))->with('error', 'Organization not found');
        }

        // Get student data
        $userId = session()->get('user_id');
        $student = $this->studentModel->where('user_id', $userId)->first();
        $profile = $this->userProfileModel->where('user_id', $userId)->first();
        $user = $this->userModel->find($userId);
        
        // Get photo from user_photos table
        $userPhotoModel = new \App\Models\UserPhotoModel();
        $userPhoto = $userPhotoModel->where('user_id', $userId)->first();
        $photoUrl = null;
        if ($userPhoto && !empty($userPhoto['photo_path'])) {
            $photoUrl = base_url($userPhoto['photo_path']);
        }

        // Get organization photo
        $orgPhoto = null;
        if (!empty($organization['user_id'])) {
            $orgUserPhoto = $userPhotoModel->where('user_id', $organization['user_id'])->first();
            if ($orgUserPhoto && !empty($orgUserPhoto['photo_path'])) {
                $orgPhoto = base_url($orgUserPhoto['photo_path']);
            }
        }

        // Get all announcements from this organization
        $announcementModel = new AnnouncementModel();
        $announcements = $announcementModel->getAnnouncementsByOrg($orgId);
        $formattedAnnouncements = [];
        foreach ($announcements as $announcement) {
            $announcementId = $announcement['announcement_id'] ?? $announcement['id'];
            
            // Get reaction counts and user's reaction
            $likeModel = new \App\Models\PostLikeModel();
            $reactionCounts = $likeModel->getReactionCounts('announcement', $announcementId);
            $userReaction = null;
            if ($student) {
                $userReaction = $likeModel->getUserReaction($student['id'], 'announcement', $announcementId);
            }
            
            // Get comment count
            $commentModel = new \App\Models\PostCommentModel();
            $commentCount = $commentModel->getCommentCount('announcement', $announcementId);
            
            $formattedAnnouncements[] = [
                'id' => $announcementId,
                'title' => $announcement['title'],
                'content' => $announcement['content'],
                'priority' => $announcement['priority'] ?? 'normal',
                'created_at' => $announcement['created_at'],
                'views' => $announcement['views'] ?? 0,
                'org_id' => $orgId,
                'org_name' => $organization['organization_name'],
                'org_acronym' => $organization['organization_acronym'],
                'org_photo' => $orgPhoto,
                'reaction_counts' => $reactionCounts,
                'user_reaction' => $userReaction,
                'like_count' => $reactionCounts['total'],
                'is_liked' => $userReaction !== null,
                'comment_count' => $commentCount
            ];
        }

        // Sort announcements by date (newest first)
        usort($formattedAnnouncements, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Get all events from this organization
        $eventModel = new EventModel();
        $events = $eventModel->getEventsByOrg($orgId);
        $formattedEvents = [];
        foreach ($events as $event) {
            $eventId = $event['event_id'] ?? $event['id'];
            
            // Update event status in database based on current date/time
            $eventModel->updateEventStatus($eventId);
            // Refresh event data to get updated status from database
            $db = \Config\Database::connect();
            $event = $db->table('events')
                ->where('event_id', $eventId)
                ->get()
                ->getRowArray();
            
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
            
            // Check audience type and permissions
            $audienceType = $event['audience_type'] ?? 'all';
            $departmentAccess = strtolower($event['department_access'] ?? '');
            $studentAccessList = [];
            if (!empty($event['student_access'])) {
                $decodedStudents = is_array($event['student_access']) ? $event['student_access'] : json_decode($event['student_access'], true);
                if (is_array($decodedStudents)) {
                    $studentAccessList = array_map('intval', $decodedStudents);
                }
            }
            
            $canView = true;
            $canJoin = true;
            if ($audienceType === 'department') {
                $studentDept = strtolower($student['department'] ?? '');
                if ($departmentAccess && $studentDept !== $departmentAccess) {
                    $canView = false;
                }
            } elseif ($audienceType === 'specific_students') {
                // All students can see the event, but only specific students can join
                $canView = true;
                if (!empty($studentAccessList)) {
                    $canJoin = in_array((int)$student['id'], $studentAccessList, true);
                } else {
                    $canJoin = false;
                }
            }
            
            // Get reaction counts and user's reaction
            $likeModel = new \App\Models\PostLikeModel();
            $reactionCounts = $likeModel->getReactionCounts('event', $eventId);
            $userReaction = null;
            if ($student) {
                $userReaction = $likeModel->getUserReaction($student['id'], 'event', $eventId);
            }
            
            // Get comment count
            $commentModel = new \App\Models\PostCommentModel();
            $commentCount = $commentModel->getCommentCount('event', $eventId);
            
            // Check if student has joined this event
            $hasJoined = false;
            $db = \Config\Database::connect();
            $attendeeCheck = $db->table('event_attendees')
                ->where('event_id', $eventId)
                ->where('student_id', $student['id'])
                ->get()
                ->getRowArray();
            if ($attendeeCheck) {
                $hasJoined = true;
            }
            
            // Check if student is interested in this event
            $isInterested = false;
            $interestCheck = $db->table('event_interests')
                ->where('event_id', $eventId)
                ->where('student_id', $student['id'])
                ->get()
                ->getRowArray();
            if ($interestCheck) {
                $isInterested = true;
            }
            
            // Get interest count
            $interestCount = $db->table('event_interests')
                ->where('event_id', $eventId)
                ->countAllResults();
            
            $formattedEvents[] = [
                'id' => $eventId,
                'title' => $event['event_name'] ?? $event['title'],
                'description' => $event['description'],
                'date' => $event['date'],
                'date_formatted' => $eventDate,
                'time' => $timeFormatted,
                'location' => $event['venue'] ?? $event['location'],
                'audience_type' => $audienceType,
                'department_access' => $departmentAccess,
                'student_access' => $studentAccessList,
                'can_join' => $canJoin,
                'has_joined' => $hasJoined,
                'is_interested' => $isInterested,
                'interest_count' => $interestCount,
                'attendees' => $event['current_attendees'] ?? 0,
                'max_attendees' => $event['max_attendees'],
                'status' => $event['status'] ?? 'upcoming',
                'image' => $event['image'] ?? null,
                'created_at' => $event['created_at'] ?? $event['date'],
                'org_id' => $orgId,
                'org_name' => $organization['organization_name'],
                'org_acronym' => $organization['organization_acronym'],
                'org_type' => ucfirst(str_replace('_', ' ', $organization['organization_type'] ?? 'academic')),
                'org_photo' => $orgPhoto,
                'reaction_counts' => $reactionCounts,
                'user_reaction' => $userReaction,
                'like_count' => $reactionCounts['total'],
                'is_liked' => $userReaction !== null,
                'comment_count' => $commentCount
            ];
        }

        // Sort events by date (newest first)
        usort($formattedEvents, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        // Combine announcements and events into posts
        $allPosts = [];
        foreach ($formattedAnnouncements as $announcement) {
            $allPosts[] = [
                'type' => 'announcement',
                'data' => $announcement,
                'date' => strtotime($announcement['created_at'])
            ];
        }
        foreach ($formattedEvents as $event) {
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

        // Check if student is following this organization
        $isFollowing = false;
        if ($student) {
            $followModel = new \App\Models\OrganizationFollowModel();
            $isFollowing = $followModel->isFollowing($student['id'], $orgId);
        }

        // Check if student is a member
        $isMember = false;
        $isPending = false;
        if ($student) {
            $membershipModel = new StudentOrganizationMembershipModel();
            $membership = $membershipModel->hasMembership($student['id'], $orgId);
            if ($membership) {
                $isMember = ($membership['status'] === 'active');
                $isPending = ($membership['status'] === 'pending');
            }
        }

        $data = [
            'student' => $student,
            'profile' => $profile,
            'user' => $user,
            'organization' => [
                'id' => $organization['id'],
                'name' => $organization['organization_name'],
                'acronym' => $organization['organization_acronym'],
                'type' => ucfirst(str_replace('_', ' ', $organization['organization_type'] ?? 'academic')),
                'mission' => $organization['mission'] ?? '',
                'vision' => $organization['vision'] ?? '',
                'members' => $organization['current_members'] ?? 0,
                'photo' => $orgPhoto,
                'is_following' => $isFollowing,
                'is_member' => $isMember,
                'is_pending' => $isPending
            ],
            'allPosts' => $allPosts,
            'announcements' => $formattedAnnouncements,
            'events' => $formattedEvents,
            'pageTitle' => $organization['organization_name'] . ' - BEACON'
        ];

        return view('student/organization', $data);
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
     * Get Notifications for Student
     */
    public function getNotifications()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $userId = session()->get('user_id');
        $studentId = session()->get('student_id');
        $student = $this->studentModel->where('user_id', $userId)->first();

        if (!$student) {
            return $this->response->setJSON(['success' => false, 'message' => 'Student not found']);
        }

        $notifications = [];

        // 1. Upcoming Events (events happening within 2 days)
        $eventModel = new EventModel();
        $eventAttendeesModel = new \App\Models\EventAttendeesModel();
        
        // Get events the student has joined
        $joinedEvents = $eventAttendeesModel->where('student_id', $student['id'])->findAll();
        $joinedEventIds = array_column($joinedEvents, 'event_id');
        
        if (!empty($joinedEventIds)) {
            $upcomingEvents = $eventModel
                ->whereIn('event_id', $joinedEventIds)
                ->where('date >=', date('Y-m-d'))
                ->where('date <=', date('Y-m-d', strtotime('+2 days')))
                ->findAll();
            
            foreach ($upcomingEvents as $event) {
                $eventDate = $event['date'] . ' ' . ($event['time'] ?? '00:00:00');
                $eventDateTime = strtotime($eventDate);
                $hoursUntil = round(($eventDateTime - time()) / 3600);
                
                if ($hoursUntil <= 24 && $hoursUntil > 0) {
                    $notifications[] = [
                        'id' => 'event_' . $event['event_id'],
                        'type' => 'event',
                        'icon' => 'event',
                        'title' => 'Event Reminder: ' . $event['title'],
                        'text' => $event['title'] . ' is happening in ' . ($hoursUntil < 1 ? 'less than an hour' : ($hoursUntil == 1 ? '1 hour' : $hoursUntil . ' hours')),
                        'time' => $this->formatTimeAgo($eventDate),
                        'created_at' => $eventDate,
                        'unread' => true
                    ];
                } elseif ($hoursUntil <= 48 && $hoursUntil > 24) {
                    $daysUntil = round($hoursUntil / 24);
                    $notifications[] = [
                        'id' => 'event_' . $event['event_id'],
                        'type' => 'event',
                        'icon' => 'event',
                        'title' => 'Upcoming Event: ' . $event['title'],
                        'text' => $event['title'] . ' is happening in ' . ($daysUntil == 1 ? '1 day' : $daysUntil . ' days'),
                        'time' => $this->formatTimeAgo($eventDate),
                        'created_at' => $eventDate,
                        'unread' => true
                    ];
                }
            }
        }

        // 2. New Announcements (posted in last 7 days)
        $announcementModel = new AnnouncementModel();
        $membershipModel = new StudentOrganizationMembershipModel();
        $orgFollowModel = new \App\Models\OrganizationFollowModel();
        
        // Get organizations student follows or is a member of
        $memberships = $membershipModel->getStudentOrganizations($student['id']);
        $followedOrgs = $orgFollowModel->where('student_id', $student['id'])->findAll();
        
        $orgIds = array_column($memberships, 'organization_id');
        $followedOrgIds = array_column($followedOrgs, 'organization_id');
        $allOrgIds = array_unique(array_merge($orgIds, $followedOrgIds));
        
        if (!empty($allOrgIds)) {
            $recentAnnouncements = $announcementModel
                ->whereIn('org_id', $allOrgIds)
                ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->findAll();
            
            foreach ($recentAnnouncements as $announcement) {
                $orgModel = new OrganizationModel();
                $org = $orgModel->find($announcement['org_id']);
                $orgName = $org ? ($org['organization_acronym'] ?? $org['organization_name'] ?? 'Organization') : 'Organization';
                
                $notifications[] = [
                    'id' => 'announcement_' . $announcement['id'],
                    'type' => 'announcement',
                    'icon' => 'announcement',
                    'title' => $announcement['priority'] === 'high' ? 'Important: ' . $announcement['title'] : 'New Announcement: ' . $announcement['title'],
                    'text' => $orgName . ' posted: ' . (strlen($announcement['content']) > 50 ? substr($announcement['content'], 0, 50) . '...' : $announcement['content']),
                    'time' => $this->formatTimeAgo($announcement['created_at']),
                    'created_at' => $announcement['created_at'],
                    'unread' => true
                ];
            }
        }

        // 3. Membership Approvals
        $pendingMemberships = $membershipModel
            ->where('student_id', $student['id'])
            ->where('status', 'active')
            ->where('updated_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->findAll();
        
        foreach ($pendingMemberships as $membership) {
            $orgModel = new OrganizationModel();
            $org = $orgModel->find($membership['organization_id']);
            if ($org) {
                $orgName = $org['organization_acronym'] ?? $org['organization_name'] ?? 'Organization';
                $daysAgo = round((time() - strtotime($membership['updated_at'])) / 86400);
                
                if ($daysAgo <= 7) {
                    $notifications[] = [
                        'id' => 'membership_' . $membership['id'],
                        'type' => 'org',
                        'icon' => 'org',
                        'title' => 'Membership Approved',
                        'text' => $orgName . ' membership approved!',
                        'time' => $this->formatTimeAgo($membership['updated_at']),
                        'created_at' => $membership['updated_at'],
                        'unread' => $daysAgo <= 3
                    ];
                }
            }
        }

        // 4. New Comments/Replies on student's comments
        $commentModel = new \App\Models\PostCommentModel();
        $studentComments = $commentModel->where('user_id', $userId)->findAll();
        $studentCommentIds = array_column($studentComments, 'id');
        
        if (!empty($studentCommentIds)) {
            // Get replies to student's comments (last 7 days)
            $replies = $commentModel
                ->whereIn('parent_comment_id', $studentCommentIds)
                ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
                ->where('user_id !=', $userId)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->findAll();
            
            foreach ($replies as $reply) {
                $userModel = new UserModel();
                $replyUser = $userModel->find($reply['user_id']);
                $replyUserName = $replyUser ? ($replyUser['name'] ?? 'Someone') : 'Someone';
                
                $notifications[] = [
                    'id' => 'comment_' . $reply['id'],
                    'type' => 'comment',
                    'icon' => 'comment',
                    'title' => 'New Reply to Your Comment',
                    'text' => $replyUserName . ' replied to your comment.',
                    'time' => $this->formatTimeAgo($reply['created_at']),
                    'created_at' => $reply['created_at'],
                    'unread' => true
                ];
            }
        }

        // Sort notifications by date (newest first)
        usort($notifications, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Limit to 20 most recent
        $notifications = array_slice($notifications, 0, 20);

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => count(array_filter($notifications, function($n) { return $n['unread']; }))
        ]);
    }

    /**
     * Format time ago
     */
    private function formatTimeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            return 'Just now';
        } elseif ($diff < 3600) {
            $mins = round($diff / 60);
            return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = round($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = round($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M d, Y', $timestamp);
        }
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

    /**
     * Create Forum Post
     */
    public function createPost()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $userId = session()->get('user_id');
        $student = $this->studentModel->where('user_id', $userId)->first();
        
        if (!$student) {
            return $this->response->setJSON(['success' => false, 'message' => 'Student record not found']);
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
                'student_id' => $student['id'],
                'organization_id' => null,
                'author_type' => 'student',
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
                // Get the created post with student info
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
     * Get Forum Posts
     */
    public function getPosts()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $category = $this->request->getGet('category') ?? 'all';
        $limit = $this->request->getGet('limit') ? (int)$this->request->getGet('limit') : 20;
        $offset = $this->request->getGet('offset') ? (int)$this->request->getGet('offset') : 0;

        try {
            $postModel = new ForumPostModel();
            $posts = $postModel->getAllPosts($category, $limit, $offset);

            // Get reaction and comment counts for each post
            $userId = session()->get('user_id');
            $student = $this->studentModel->where('user_id', $userId)->first();
            
            $likeModel = new \App\Models\PostLikeModel();
            $commentModel = new \App\Models\PostCommentModel();

            foreach ($posts as &$post) {
                // Get reaction counts
                $reactionCounts = $likeModel->getReactionCounts('forum_post', $post['id']);
                $post['reaction_counts'] = $reactionCounts;
                
                // Get user's reaction
                if ($student) {
                    $userReaction = $likeModel->getUserReaction($student['id'], 'forum_post', $post['id'], 'student');
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

            return $this->response->setJSON([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get posts error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while fetching posts'
            ]);
        }
    }
}

