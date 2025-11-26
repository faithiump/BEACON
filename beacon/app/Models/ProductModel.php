<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'org_id',
        'product_name',
        'description',
        'price',
        'stock',
        'sold',
        'sizes',
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
        'product_name' => 'required|min_length[3]|max_length[255]',
        'description' => 'permit_empty|max_length[1000]',
        'price' => 'required|decimal|greater_than_equal_to[0]',
        'stock' => 'required|integer|greater_than_equal_to[0]',
        'sold' => 'permit_empty|integer|greater_than_equal_to[0]',
        'sizes' => 'permit_empty|max_length[255]',
        'status' => 'permit_empty|in_list[available,low_stock,out_of_stock]'
    ];

    protected $validationMessages = [
        'product_name' => [
            'required' => 'Product name is required',
            'min_length' => 'Product name must be at least 3 characters',
            'max_length' => 'Product name cannot exceed 255 characters'
        ],
        'price' => [
            'required' => 'Product price is required',
            'decimal' => 'Price must be a valid decimal number',
            'greater_than_equal_to' => 'Price must be 0 or greater'
        ],
        'stock' => [
            'required' => 'Stock quantity is required',
            'integer' => 'Stock must be a whole number',
            'greater_than_equal_to' => 'Stock cannot be negative'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get products by organization ID
     */
    public function getProductsByOrg($orgId, $limit = null)
    {
        $builder = $this->where('org_id', $orgId)
            ->orderBy('created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get available products
     */
    public function getAvailableProducts($orgId = null, $limit = null)
    {
        $builder = $this->where('status', 'available')
            ->where('stock >', 0)
            ->orderBy('created_at', 'DESC');

        if ($orgId) {
            $builder->where('org_id', $orgId);
        }

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Update product status based on stock
     */
    public function updateProductStatus($productId)
    {
        $product = $this->find($productId);
        if (!$product) {
            return false;
        }

        $stock = $product['stock'];
        $status = 'available';
        
        if ($stock == 0) {
            $status = 'out_of_stock';
        } elseif ($stock <= 10) {
            $status = 'low_stock';
        }

        return $this->update($productId, ['status' => $status]);
    }
}

