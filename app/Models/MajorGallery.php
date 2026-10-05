<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MajorGallery extends Model
{
    protected $table = 'major_gallery';
    protected $dateFormat = 'c';

    const TABLE = 'major_gallery';
    const FILEROOT = '/major_gallery';

    const IS_LIST = true;
    const IS_ADD = true;
    const IS_EDIT = true;
    const IS_DELETE = true;
    const IS_VIEW = true;

    const FIELD_LIST = ['id', 'major_id', 'img_cover', 'description', 'status_code', 'created_by', 'updated_by', 'created_at', 'updated_at'];
    const FIELD_ADD = ['major_id', 'img_cover', 'description', 'status_code', 'created_by'];
    const FIELD_EDIT = ['img_cover', 'description', 'status_code', 'updated_by'];
    const FIELD_VIEW = ['id', 'major_id', 'img_cover', 'description', 'status_code', 'created_by', 'updated_by', 'created_at', 'updated_at'];
    const FIELD_READONLY = ['major_id'];

    const FIELD_FILTERABLE = [
        'id' => ['operator' => '='],
        'major_id' => ['operator' => '='],
        'status_code' => ['operator' => '='],
    ];

    const FIELD_SEARCHABLE = ['description'];
    const FIELD_ARRAY = [];
    const FIELD_SORTABLE = ['id', 'created_at'];
    const FIELD_UNIQUE = [];
    const FIELD_UPLOAD = ['img_cover'];

    const FIELD_TYPE = [
        'id' => 'bigint',
        'major_id' => 'bigint',
        'img_cover' => 'text',
        'description' => 'character_varying',
        'status_code' => 'boolean',
        'created_by' => 'bigint',
        'updated_by' => 'bigint',
        'created_at' => 'timestamp_with_time_zone',
        'updated_at' => 'timestamp_with_time_zone',
    ];

    const FIELD_DEFAULT_VALUE = ['status_code' => true];

    const FIELD_RELATION = [
        'created_by' => [
            'linkTable' => 'users', 'aliasTable' => 'B', 'linkField' => 'id',
            'displayName' => 'rel_created_by',
            'selectFields' => ['id', 'fullname'], 'selectValue' => 'id AS rel_created_by',
        ],
        'updated_by' => [
            'linkTable' => 'users', 'aliasTable' => 'C', 'linkField' => 'id',
            'displayName' => 'rel_updated_by',
            'selectFields' => ['id', 'fullname'], 'selectValue' => 'id AS rel_updated_by',
        ],
    ];

    const CUSTOM_RELATION = [];
    const CUSTOM_SELECT = '';

    const FIELD_VALIDATION = [
        'major_id' => 'required|integer',
        'img_cover' => 'required|string|exists_file',
        'description' => 'required|string|max:255',
        'status_code' => 'required|boolean',
        'created_by' => 'nullable|integer',
        'updated_by' => 'nullable|integer',
    ];

    const PARENT_CHILD = [];
    const CUSTOM_LIST_FILTER = [];
    const FIELD_CASTING = ['status_code' => 'boolean'];
    const FIELD_VALIDATION_DATA = [];
    const CHILD_TABLE = [];
    const MAPPING_MULTIPLE_ADD = [];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public static function beforeInsert($input) { return $input; }
    public static function afterInsert($data, $input) { return []; }
    public static function beforeUpdate($input) { return $input; }
    public static function afterUpdate($data, $input) { return []; }
    public static function beforeDelete($input) { return $input; }
    public static function afterDelete($data, $input) { return []; }
}