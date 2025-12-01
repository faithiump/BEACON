<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'event_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'org_id',
        'org_type',
        'event_name',
        'description',
        'date',
        'time',
        'end_date',
        'end_time',
        'venue',
        'audience_type',
        'department_access',
        'student_access',
        'max_attendees',
        'current_attendees',
        'image',
        'status',
        'views'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'org_id' => 'required|integer',
        'org_type' => 'required|in_list[academic,non_academic,service,religious,cultural,sports,other]',
        'event_name' => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[10]',
        'date' => 'required|valid_date',
        'time' => 'required',
        'venue' => 'required|min_length[3]|max_length[255]',
        'audience_type' => 'permit_empty|in_list[all,department,specific_students]',
        'department_access' => 'permit_empty|in_list[ccs,cea,cthbm,chs,ctde,cas,gs]',
        'student_access' => 'permit_empty',
        'max_attendees' => 'permit_empty|integer|greater_than[0]'
    ];

    protected $validationMessages = [
        'event_name' => [
            'required' => 'Event name is required',
            'min_length' => 'Event name must be at least 3 characters',
            'max_length' => 'Event name cannot exceed 255 characters'
        ],
        'description' => [
            'required' => 'Event description is required',
            'min_length' => 'Event description must be at least 10 characters'
        ],
        'date' => [
            'required' => 'Event date is required',
            'valid_date' => 'Please provide a valid date'
        ],
        'time' => [
            'required' => 'Event time is required'
        ],
        'venue' => [
            'required' => 'Event venue is required',
            'min_length' => 'Venue must be at least 3 characters',
            'max_length' => 'Venue cannot exceed 255 characters'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    // Flag to prevent recursion in updateEventStatus
    private static $updatingStatus = [];

    /**
     * Get events by organization ID
     */
    public function getEventsByOrg($orgId, $limit = null)
    {
        $builder = $this->where('org_id', $orgId)
            ->orderBy('date', 'ASC')
            ->orderBy('time', 'ASC');

        if ($limit) {
            $builder->limit($limit);
        }

        $events = $builder->findAll();
        
        // Automatically update status for all events (both new and old) before returning
        // This ensures status is always current in the database
        $db = \Config\Database::connect();
        foreach ($events as $event) {
            $eventId = $event['event_id'] ?? $event['id'];
            if ($eventId && !isset(self::$updatingStatus[$eventId])) {
                // Quick status check and update without full find()
                try {
                    $this->updateEventStatusQuick($eventId, $event, $db);
                } catch (\Exception $e) {
                    log_message('error', 'Error updating status for event ' . $eventId . ': ' . $e->getMessage());
                }
            }
        }
        
        // Return events with updated status from database (re-fetch to ensure we have latest status)
        $updatedEvents = $builder->findAll();
        
        // Ensure all events have their status updated in the returned array
        foreach ($updatedEvents as &$event) {
            $eventId = $event['event_id'] ?? $event['id'];
            if ($eventId) {
                // Double-check status is current (in case of any timing issues)
                $currentEvent = $db->table('events')
                    ->where('event_id', $eventId)
                    ->select('status')
                    ->get()
                    ->getRowArray();
                if ($currentEvent) {
                    $event['status'] = $currentEvent['status'];
                }
            }
        }
        
        return $updatedEvents;
    }

    /**
     * Get upcoming events
     */
    public function getUpcomingEvents($orgId = null, $limit = null)
    {
        // Update status for ALL events first (both new and old) to ensure accuracy
        $allEventsQuery = $this;
        if ($orgId) {
            $allEventsQuery = $allEventsQuery->where('org_id', $orgId);
        }
        $allEvents = $allEventsQuery->findAll();
        
        // Batch update status for all events (new and old)
        $db = \Config\Database::connect();
        foreach ($allEvents as $event) {
            $eventId = $event['event_id'] ?? $event['id'];
            if ($eventId && !isset(self::$updatingStatus[$eventId])) {
                $this->updateEventStatusQuick($eventId, $event, $db);
            }
        }
        
        // Now filter for upcoming events only
        $builder = $this->where('date >=', date('Y-m-d'))
            ->where('status', 'upcoming')
            ->orderBy('date', 'ASC')
            ->orderBy('time', 'ASC');

        if ($orgId) {
            $builder->where('org_id', $orgId);
        }

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get event with organization details
     */
    public function getEventWithOrg($eventId)
    {
        // Get event first
        $event = $this->select('events.*, organizations.organization_name, organizations.organization_acronym')
            ->join('organizations', 'organizations.id = events.org_id')
            ->where('events.event_id', $eventId)
            ->first();
            
        // Update status if event found and not already updating
        if ($event && !isset(self::$updatingStatus[$eventId])) {
            $db = \Config\Database::connect();
            $this->updateEventStatusQuick($eventId, $event, $db);
            // Refresh to get updated status
            return $this->select('events.*, organizations.organization_name, organizations.organization_acronym')
                ->join('organizations', 'organizations.id = events.org_id')
                ->where('events.event_id', $eventId)
                ->first();
        }
        
        return $event;
    }

    /**
     * Increment views count for an event
     */
    public function incrementViews($eventId)
    {
        $event = $this->find($eventId);
        if ($event) {
            $this->update($eventId, [
                'views' => ($event['views'] ?? 0) + 1
            ]);
        }
    }

    /**
     * Quick status update using provided event data (avoids recursion and database queries)
     */
    private function updateEventStatusQuick($eventId, $event, $db)
    {
        if (isset(self::$updatingStatus[$eventId])) {
            return; // Already updating, prevent recursion
        }
        
        self::$updatingStatus[$eventId] = true;
        
        try {
            // Parse time - handle both 24-hour (08:00:00) and 12-hour (8:00 AM) formats
            $eventTime = $event['time'] ?? '00:00:00';
            $eventTime = trim($eventTime);
            
            // If time is in 12-hour format (has AM/PM), use strtotime directly
            if (stripos($eventTime, 'AM') !== false || stripos($eventTime, 'PM') !== false) {
                $eventDateTime = $event['date'] . ' ' . $eventTime;
                $eventTimestamp = strtotime($eventDateTime);
            } else {
                // Time is in 24-hour format (e.g., 08:00:00 or 08:00)
                if (substr_count($eventTime, ':') == 2) {
                    $timeParts = explode(':', $eventTime);
                    $eventTime = $timeParts[0] . ':' . $timeParts[1];
                }
                $eventDateTime = $event['date'] . ' ' . $eventTime;
                $eventTimestamp = strtotime($eventDateTime);
            }
            
            // Use application timezone for accurate time comparison
            date_default_timezone_set(config('App')->appTimezone);
            $now = time();
            
            // Determine end time
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
            
            // Determine status based on current date/time
            // Rules:
            // 1. If current time > end date/time: status = 'ended'
            // 2. If current time >= start date/time AND current time <= end date/time: status = 'ongoing'
            // 3. If current time < start date/time: status = 'upcoming'
            $newStatus = 'upcoming';
            
            if ($now > $endDateTime) {
                // Current time has passed the end date/time - event is ended
                $newStatus = 'ended';
            } elseif ($now >= $eventTimestamp) {
                // Current time is equal to or after start date/time
                if ($now <= $endDateTime) {
                    // Current time is still within end date/time - event is ongoing
                    $newStatus = 'ongoing';
                } else {
                    // Current time has passed end date/time - event is ended
                    $newStatus = 'ended';
                }
            } else {
                // Current time is before start date/time - event is upcoming
                $newStatus = 'upcoming';
            }
            
            // Always update status to ensure it's current (for both new and old events)
            // Update status in database to ensure it's always accurate and saved
            // Update regardless of current status to ensure it's always current
            try {
                // Force update status in database using direct SQL to ensure it works
                // This bypasses any model validation that might interfere
                $sql = "UPDATE events SET status = ? WHERE event_id = ?";
                $result = $db->query($sql, [$newStatus, $eventId]);
                
                // Log calculation details for debugging
                $logDetails = sprintf(
                    'Event %d: date=%s, time=%s, end_date=%s, end_time=%s, now=%s, calculated_status=%s',
                    $eventId,
                    $event['date'] ?? 'N/A',
                    $event['time'] ?? 'N/A',
                    $event['end_date'] ?? 'N/A',
                    $event['end_time'] ?? 'N/A',
                    date('Y-m-d H:i:s'),
                    $newStatus
                );
                
                // Log if update was successful
                if ($result) {
                    log_message('debug', 'Status updated for event ' . $eventId . ' to: ' . $newStatus . ' | ' . $logDetails);
                } else {
                    log_message('warning', 'Status update returned false for event ' . $eventId . ' | ' . $logDetails);
                    // Try using model's update method as fallback
                    try {
                        $this->where('event_id', $eventId)->set('status', $newStatus)->update();
                        log_message('debug', 'Fallback update succeeded for event ' . $eventId);
                    } catch (\Exception $e3) {
                        log_message('error', 'Fallback update failed for event ' . $eventId . ': ' . $e3->getMessage());
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Failed to update event status for event ' . $eventId . ': ' . $e->getMessage());
                // Try fallback method
                try {
                    $this->where('event_id', $eventId)->set('status', $newStatus)->update();
                    log_message('debug', 'Exception fallback update succeeded for event ' . $eventId);
                } catch (\Exception $e2) {
                    log_message('error', 'Exception fallback update also failed for event ' . $eventId . ': ' . $e2->getMessage());
                }
            }
            
            return $newStatus;
        } finally {
            unset(self::$updatingStatus[$eventId]);
        }
    }

    /**
     * Update event status based on current date/time
     * Returns the calculated status
     */
    public function updateEventStatus($eventId)
    {
        // Prevent recursion
        if (isset(self::$updatingStatus[$eventId])) {
            return null;
        }
        
        self::$updatingStatus[$eventId] = true;
        
        try {
            // Use direct database query to avoid recursion (don't use find() which might cause loops)
            $db = \Config\Database::connect();
            $event = $db->table('events')
                ->where('event_id', $eventId)
                ->get()
                ->getRowArray();
                
            if (!$event) {
                return null;
            }
            
            // Use the quick update method to avoid additional queries
            $this->updateEventStatusQuick($eventId, $event, $db);
            
            // Get updated status
            $updatedEvent = $db->table('events')
                ->where('event_id', $eventId)
                ->get()
                ->getRowArray();
            
            return $updatedEvent['status'] ?? 'upcoming';
        } finally {
            unset(self::$updatingStatus[$eventId]);
        }
    }
}

