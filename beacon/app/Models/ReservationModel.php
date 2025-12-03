<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'reservation_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'student_id',
        'org_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'total_amount',
        'status',
        'payment_method',
        'proof_image',
        'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'student_id' => 'required|integer',
        'org_id' => 'required|integer',
        'product_id' => 'required|integer',
        'product_name' => 'required|max_length[255]',
        'quantity' => 'required|integer|greater_than[0]',
        'price' => 'required|decimal|greater_than_equal_to[0]',
        'total_amount' => 'required|decimal|greater_than_equal_to[0]',
        'status' => 'permit_empty|in_list[pending,confirmed,rejected,completed]',
        'payment_method' => 'permit_empty|max_length[50]',
        'proof_image' => 'permit_empty|max_length[255]',
        'notes' => 'permit_empty|max_length[500]'
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get pending reservations for an organization
     */
    public function getPendingReservations($orgId)
    {
        return $this->select('reservations.*, students.student_id as student_number, user_profiles.firstname, user_profiles.lastname')
            ->join('students', 'students.id = reservations.student_id')
            ->join('users', 'users.id = students.user_id')
            ->join('user_profiles', 'user_profiles.user_id = users.id', 'left')
            ->where('reservations.org_id', $orgId)
            ->where('reservations.status', 'pending')
            ->orderBy('reservations.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get confirmed reservations for an organization
     */
    public function getConfirmedReservations($orgId)
    {
        return $this->select('reservations.*, students.student_id as student_number, user_profiles.firstname, user_profiles.lastname')
            ->join('students', 'students.id = reservations.student_id')
            ->join('users', 'users.id = students.user_id')
            ->join('user_profiles', 'user_profiles.user_id = users.id', 'left')
            ->where('reservations.org_id', $orgId)
            ->where('reservations.status', 'confirmed')
            ->orderBy('reservations.updated_at', 'DESC')
            ->findAll();
    }

    /**
     * Get reservations by student
     */
    public function getStudentReservations($studentId)
    {
        return $this->where('student_id', $studentId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Update reservation status
     */
    public function updateReservationStatus($reservationId, $status)
    {
        return $this->update($reservationId, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}



