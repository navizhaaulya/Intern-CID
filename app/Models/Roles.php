<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';
    protected $dateFormat = 'c';

    const TABLE = 'roles';
    const FILEROOT = '/roles';

    const IS_LIST = true;
    const IS_ADD = true;
    const IS_EDIT = true;
    const IS_DELETE = true;
    const IS_VIEW = true;

    const FIELD_LIST = [
        'id',
        'role_code',
        'role_name',
        'description',
        'allow_login',
        'active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    const FIELD_ADD = [
        'role_code',
        'role_name',
        'description',
        'allow_login',
        'active',
        'created_by',
        'updated_by'
    ];

    const FIELD_EDIT = [
        'role_code',
        'role_name',
        'description',
        'allow_login',
        'active',
        'updated_by'
    ];

    const FIELD_VIEW = [
        'id',
        'role_code',
        'role_name',
        'description',
        'allow_login',
        'active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    const FIELD_READONLY = [];

    const FIELD_FILTERABLE = [
        'id' => [
            'operator' => '='
        ],
        'role_code' => [
            'operator' => '='
        ],
        'role_name' => [
            'operator' => '='
        ],
        'description' => [
            'operator' => '='
        ],
        'allow_login' => [
            'operator' => '='
        ],
        'active' => [
            'operator' => '='
        ],
        'created_by' => [
            'operator' => '='
        ],
        'updated_by' => [
            'operator' => '='
        ],
        'created_at' => [
            'operator' => '='
        ],
        'updated_at' => [
            'operator' => '='
        ]
    ];

    const FIELD_SEARCHABLE = [
        'role_code',
        'role_name'
    ];

    const FIELD_ARRAY = [];

    const FIELD_SORTABLE = [
        'id',
        'role_code',
        'role_name',
        'description',
        'allow_login',
        'active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    const FIELD_UNIQUE = [
        ['role_code']
    ];

    const FIELD_UPLOAD = [];

    const FIELD_TYPE = [
        'id' => 'bigint',
        'role_code' => 'character_varying',
        'role_name' => 'character_varying',
        'description' => 'text',
        'allow_login' => 'boolean',
        'active' => 'boolean',
        'created_by' => 'bigint',
        'updated_by' => 'bigint',
        'created_at' => 'timestamp_with_time_zone',
        'updated_at' => 'timestamp_with_time_zone'
    ];

    const FIELD_DEFAULT_VALUE = [
        'role_code' => '',
        'role_name' => '',
        'description' => '',
        'allow_login' => 'true',
        'active' => 'true',
        'created_by' => '',
        'updated_by' => '',
        'created_at' => '',
        'updated_at' => ''
    ];

    const FIELD_RELATION = [];

    const CUSTOM_RELATION = [];
    const CUSTOM_SELECT = '';

    const FIELD_VALIDATION = [
        'role_code' => 'required|string|max:255',
        'role_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'allow_login' => 'nullable',
        'active' => 'nullable',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
        'created_at' => 'nullable|date',
        'updated_at' => 'nullable|date'
    ];

    const PARENT_CHILD = [];

    const CUSTOM_LIST_FILTER = [];

    const FIELD_CASTING = [];

    const FIELD_VALIDATION_DATA = [];

    const CHILD_TABLE = [];

    const MAPPING_MULTIPLE_ADD = [];

    public static function beforeInsert($input)
    {
        return $input;
    }

    public static function afterInsert($object, $input)
    {
        return $input;
    }

    public static function beforeUpdate($input)
    {
        return $input;
    }

    public static function afterUpdate($object, $input)
    {
        return $input;
    }

    public static function beforeDelete($input)
    {
        return $input;
    }

    public static function afterDelete($object, $input)
    {
        return $input;
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}