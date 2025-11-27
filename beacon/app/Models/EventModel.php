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
        'venue',
        'audience_type',
        'department_access',
        'max_attendees',
        'current_attendees',
        'image',
        'status'
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
        'audience_type' => 'permit_empty|in_list[all,department,students]',
        'department_access' => 'permit_empty|in_list[ccs,cea,cthbm,chs,ctde,cas,gs]',
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

        return $builder->findAll();
    }

    /**
     * Get upcoming events
     */
    public function getUpcomingEvents($orgId = null, $limit = null)
    {
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
        return $this->select('events.*, organizations.organization_name, organizations.organization_acronym')
            ->join('organizations', 'organizations.id = events.org_id')
            ->where('events.event_id', $eventId)
            ->first();
    }
}

