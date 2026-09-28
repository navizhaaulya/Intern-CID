<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsCategories extends Model
{
    protected $table = 'news_categories';
    const TABLE = 'news_categories';

    protected $fillable = [
        'name',
        'description',
        'active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    const IS_LIST = true;
    const IS_ADD = true;
    const IS_EDIT = true;
    const IS_VIEW = true;
    const IS_DELETE = true;

    const FIELD_LIST = ["id", "name", "description", "active", "created_by", "updated_by", "created_at", "updated_at"];
    const FIELD_ADD = ["name", "description", "active", "created_by"];
    const FIELD_EDIT = ["name", "description", "active", "updated_by"];
    const FIELD_VIEW = ["id", "name", "description", "active", "created_by", "updated_by", "created_at", "updated_at"];
    const FIELD_READONLY = [];

    const FIELD_FILTERABLE = [
        "id" => ["operator" => "="],
        "name" => ["operator" => "ILIKE"],
        "active" => ["operator" => "="],
    ];

    const FIELD_SEARCHABLE = ["name"];
    const FIELD_ARRAY = [];
    const FIELD_SORTABLE = ["id", "name", "created_at"];
    const FIELD_UNIQUE = [];
    const FIELD_UPLOAD = [];

    const FIELD_TYPE = [
        "id" => "bigint",
        "name" => "character_varying",
        "description" => "text",
        "active" => "boolean",
        "created_by" => "bigint",
        "updated_by" => "bigint",
        "created_at" => "timestamp_with_time_zone",
        "updated_at" => "timestamp_with_time_zone",
    ];

    const FIELD_DEFAULT_VALUE = ["active" => true];

    const FIELD_RELATION = [
        "created_by" => [
            "linkTable" => "users", "aliasTable" => "B", "linkField" => "id",
            "displayName" => "rel_created_by",
            "selectFields" => ["id", "fullname"], "selectValue" => "fullname AS rel_created_by",
        ],
        "updated_by" => [
            "linkTable" => "users", "aliasTable" => "C", "linkField" => "id",
            "displayName" => "rel_updated_by",
            "selectFields" => ["id", "fullname"], "selectValue" => "fullname AS rel_updated_by",
        ],
    ];

    const CUSTOM_RELATION = [];
    const CUSTOM_SELECT = "";

    const FIELD_VALIDATION = [
        "name" => "required|string|max:255",
        "description" => "nullable|string",
        "active" => "required|boolean",
        "created_by" => "nullable|integer",
        "updated_by" => "nullable|integer",
    ];

    const PARENT_CHILD = [];
    const CUSTOM_LIST_FILTER = [];
    const FIELD_CASTING = ["active" => "boolean"];

    const FIELD_VALIDATION_DATA = [
        "created_by" => ["table" => "users", "field" => "id"],
        "updated_by" => ["table" => "users", "field" => "id"],
    ];

    const CHILD_TABLE = [];
    const MAPPING_MULTIPLE_ADD = [];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'category_id');
    }

    public static function beforeInsert($input) { return $input; }
    public static function afterInsert($data, $input) { return []; }
    public static function beforeUpdate($input) { return $input; }
    public static function afterUpdate($data, $input) { return []; }
    public static function beforeDelete($input) { return $input; }
    public static function afterDelete($data, $input) { return []; }
}