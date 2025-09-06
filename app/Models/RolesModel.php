<?php

namespace App\Models;

use CodeIgniter\Model;

class RolesModel extends Model
{
    protected $table            = 'acl_roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'description', 'created_by', 'created_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function show_all()
    {
        return $this->paginate(env('SHOW_ITEM_PER_PAGE'));
    }

    public function findPermissions(array $criteria = [])
    {
        $builder = $this;

        $hasFilter = false;

        foreach ($criteria as $field => $value) {
            if (!empty($value) && in_array($field, $this->allowedSearchFields)) {
                $builder->like($field, $value, 'both');
                $hasFilter = true;
            }
        }

        // Fallback: show all if no filter is applied
        if (!$hasFilter) {
            return $this->paginate(env('SHOW_ITEM_PER_PAGE'));
        }

        return $builder->paginate(env('SHOW_ITEM_PER_PAGE'));
    }


    public function insertRole($data)
    {
        return $this->insert($data);
    }

    public function findBySlugName($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function findById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function editRoleById($id) {}
}
