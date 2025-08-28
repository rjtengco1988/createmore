<?php

namespace App\Models;

use CodeIgniter\Model;

class RolesPermission extends Model
{
    protected $table            = 'acl_role_permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['role_id', 'permission_id', 'created_at', 'created_by'];

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

    public function insertRolePermission(int $roleId, array $permissionIds, ?string $createdBy = null, bool $ignoreDuplicates = true): int
    {
        if (empty($permissionIds)) {
            return 0;
        }

        $rows = array_map(static function ($pid) use ($roleId, $createdBy) {
            return [
                'role_id'       => (int) $roleId,
                'permission_id' => (int) $pid,
                'created_by'    => $createdBy,
            ];
        }, $permissionIds);

        // Use the builder so we can set ignore(true) for duplicate-safe inserts
        $builder = $this->builder();
        if ($ignoreDuplicates) {
            $builder->ignore(true); // requires a unique index on (role_id, permission_id)
        }

        return $builder->insertBatch($rows);
    }

    public function countPermissionsAttached($id)
    {
        return $this->where('role_id', $id)->countAllResults();
    }

    public function findPermissionsAttached(int $roleId)
    {
        return $this->select('acl_role_permissions.*, acl_permissions.*')
            ->join('acl_permissions', 'acl_permissions.id = acl_role_permissions.permission_id', 'left')
            ->where('acl_role_permissions.role_id', $roleId)
            ->paginate((int) env('SHOW_ITEM_PER_PAGE', 10));
    }
}
