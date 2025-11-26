<?php

namespace App\Models;

use CodeIgniter\Model;

class PostLikeModel extends Model
{
    protected $table            = 'post_likes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id',
        'post_type',
        'post_id',
        'reaction_type',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = null;
    protected $deletedField  = null;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Check if student has reacted to a post
     */
    public function hasLiked($studentId, $postType, $postId)
    {
        // Ensure values are properly typed
        $studentId = (int)$studentId;
        $postId = (int)$postId;
        return $this->where('student_id', $studentId)
                    ->where('post_type', $postType)
                    ->where('post_id', $postId)
                    ->first() !== null;
    }

    /**
     * Get user's reaction to a post
     */
    public function getUserReaction($studentId, $postType, $postId)
    {
        $studentId = (int)$studentId;
        $postId = (int)$postId;
        $reaction = $this->where('student_id', $studentId)
                        ->where('post_type', $postType)
                        ->where('post_id', $postId)
                        ->first();
        return $reaction ? $reaction['reaction_type'] : null;
    }

    /**
     * Get reaction counts for a post
     */
    public function getReactionCounts($postType, $postId)
    {
        $postId = (int)$postId;
        $reactions = $this->select('reaction_type, COUNT(*) as count')
                         ->where('post_type', $postType)
                         ->where('post_id', $postId)
                         ->groupBy('reaction_type')
                         ->findAll();
        
        $counts = [
            'like' => 0,
            'love' => 0,
            'care' => 0,
            'haha' => 0,
            'wow' => 0,
            'sad' => 0,
            'angry' => 0,
            'total' => 0
        ];
        
        foreach ($reactions as $reaction) {
            $type = $reaction['reaction_type'];
            $count = (int)$reaction['count'];
            if (isset($counts[$type])) {
                $counts[$type] = $count;
                $counts['total'] += $count;
            }
        }
        
        return $counts;
    }

    /**
     * Alias for hasLiked (for backward compatibility)
     */
    public function isLiked($studentId, $postType, $postId)
    {
        return $this->hasLiked($studentId, $postType, $postId);
    }

    /**
     * Get like count for a post
     */
    public function getLikeCount($postType, $postId)
    {
        // Ensure post_id is an integer
        $postId = (int)$postId;
        return $this->where('post_type', $postType)
                    ->where('post_id', $postId)
                    ->countAllResults();
    }

    /**
     * Alias for getLikeCount (for backward compatibility)
     */
    public function getLikesCount($postType, $postId)
    {
        return $this->getLikeCount($postType, $postId);
    }

    /**
     * Toggle like (like if not liked, unlike if liked)
     */
    public function toggleLike($studentId, $postType, $postId)
    {
        // Ensure values are properly typed
        $studentId = (int)$studentId;
        $postId = (int)$postId;
        
        $existing = $this->where('student_id', $studentId)
                         ->where('post_type', $postType)
                         ->where('post_id', $postId)
                         ->first();
        
        if ($existing) {
            // Unlike
            $deleteResult = $this->delete($existing['id']);
            if (!$deleteResult) {
                throw new \RuntimeException('Failed to delete like: ' . implode(', ', $this->errors()));
            }
            return ['liked' => false, 'count' => $this->getLikeCount($postType, $postId)];
        } else {
            // Like - use DB builder directly to avoid timestamp issues
            $db = \Config\Database::connect();
            
            $data = [
                'student_id' => $studentId,
                'post_type' => $postType,
                'post_id' => $postId,
                'reaction_type' => 'like',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $db->table($this->table)->insert($data);
            
            if ($db->affectedRows() === 0) {
                throw new \RuntimeException('Failed to insert like: Database insert failed');
            }
            
            return ['liked' => true, 'count' => $this->getLikeCount($postType, $postId)];
        }
    }

    /**
     * Set reaction (like, love, care, haha, wow, sad, angry)
     */
    public function setReaction($studentId, $postType, $postId, $reactionType = 'like')
    {
        // Ensure values are properly typed
        $studentId = (int)$studentId;
        $postId = (int)$postId;
        
        // Validate reaction type
        $validReactions = ['like', 'love', 'care', 'haha', 'wow', 'sad', 'angry'];
        if (!in_array($reactionType, $validReactions)) {
            throw new \InvalidArgumentException('Invalid reaction type: ' . $reactionType);
        }
        
        $existing = $this->where('student_id', $studentId)
                         ->where('post_type', $postType)
                         ->where('post_id', $postId)
                         ->first();
        
        $db = \Config\Database::connect();
        
        if ($existing) {
            // Update existing reaction
            if ($existing['reaction_type'] === $reactionType) {
                // Same reaction - remove it (unlike)
                $this->delete($existing['id']);
                $reactionCounts = $this->getReactionCounts($postType, $postId);
                return [
                    'reacted' => false,
                    'reaction_type' => null,
                    'counts' => $reactionCounts
                ];
            } else {
                // Change reaction
                $db->table($this->table)
                   ->where('id', $existing['id'])
                   ->update(['reaction_type' => $reactionType]);
                
                $reactionCounts = $this->getReactionCounts($postType, $postId);
                return [
                    'reacted' => true,
                    'reaction_type' => $reactionType,
                    'counts' => $reactionCounts
                ];
            }
        } else {
            // Add new reaction
            $data = [
                'student_id' => $studentId,
                'post_type' => $postType,
                'post_id' => $postId,
                'reaction_type' => $reactionType,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $db->table($this->table)->insert($data);
            
            if ($db->affectedRows() === 0) {
                throw new \RuntimeException('Failed to insert reaction: Database insert failed');
            }
            
            $reactionCounts = $this->getReactionCounts($postType, $postId);
            return [
                'reacted' => true,
                'reaction_type' => $reactionType,
                'counts' => $reactionCounts
            ];
        }
    }
}

