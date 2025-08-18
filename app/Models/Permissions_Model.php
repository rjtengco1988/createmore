<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Exceptions\DataException;

class Permissions_Model extends Model
{
    protected $table            = 'acl_permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'name', 'group', 'slug', 'description'];

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

    protected $allowedSearchFields = ['name'];


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


    public function searchPaginated(array $opts): array
    {
        $q       = trim($opts['q'] ?? '');
        $sort    = $opts['sort'] ?? 'name';                 // whitelist below
        $dir     = strtolower($opts['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
        $page    = max(1, (int)($opts['page'] ?? 1));
        $perPage = min(50, max(1, (int)($opts['perPage'] ?? env('SHOW_ITEM_PER_PAGE', 1))));

        $builder = $this->builder(); // do not mutate $this

        if ($q !== '') {
            $builder->groupStart()
                ->like('name', $q, 'both')
                ->orLike('slug', $q, 'both')
                ->orLike('description', $q, 'both')
                ->groupEnd();
        }

        // Sort whitelist
        $sortable = ['id', 'name', 'slug', 'description'];
        if (!in_array($sort, $sortable, true)) {
            $sort = 'name';
        }

        // Count FIRST (clone to avoid losing where conditions)
        $countBuilder = clone $builder;
        $total = (int)$countBuilder->countAllResults();

        // Page data
        $rows = $builder
            ->orderBy($sort, $dir)
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()
            ->getResultArray();

        return [
            'rows'    => $rows,
            'total'   => $total,
            'page'    => $page,
            'perPage' => $perPage,
            'pages'   => (int)ceil($total / $perPage),
            'sort'    => $sort,
            'dir'     => strtolower($dir),
        ];
    }


    public function findPermissionsById($permsIds)
    {
        return $this->select('id')->whereIn('id', $permsIds)->findColumn('id') ?? [];
    }
}
