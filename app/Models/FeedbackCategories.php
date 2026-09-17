<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackCategories extends Model
{
    protected $table = 'feedback_categories';
    protected $dateFormat = 'c';

    const TABLE = "feedback_categories";
    const FILEROOT = "/feedback_categories";

    // ini modul "Master Data" -> admin full CRUD kategori
    const IS_LIST = true;
    const IS_ADD = true;
    const IS_EDIT = true;
    const IS_DELETE = true;
    const IS_VIEW = true;

    const FIELD_LIST = [
        "id",
        "category_name",
        "status",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    const FIELD_ADD = [
        "category_name",
        "status",
        "created_by",
    ];

    const FIELD_EDIT = [
        "category_name",
        "status",
        "updated_by",
    ];

    const FIELD_VIEW = [
        "id",
        "category_name",
        "status",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    const FIELD_READONLY = [];

    const FIELD_FILTERABLE = [
        "id" => [
            "operator" => "=",
        ],
        "category_name" => [
            "operator" => "ILIKE",
        ],
        "status" => [
            "operator" => "=",
        ],
    ];

    const FIELD_SEARCHABLE = [
        "category_name",
    ];

    const FIELD_ARRAY = [];

    const FIELD_SORTABLE = [
        "id",
        "category_name",
        "status",
        "created_at",
    ];

    const FIELD_UNIQUE = [
        ["category_name"],
    ];

    const FIELD_UPLOAD = [];

    const FIELD_TYPE = [
        "id" => "bigint",
        "category_name" => "character_varying",
        "status" => "boolean",
        "created_by" => "bigint",
        "updated_by" => "bigint",
        "created_at" => "timestamp_with_time_zone",
        "updated_at" => "timestamp_with_time_zone",
    ];

    const FIELD_DEFAULT_VALUE = [
        "category_name" => "",
        "status" => true,
        "created_by" => "",
    ];

    const FIELD_RELATION = [
        "created_by" => [
            "linkTable" => "users",
            "aliasTable" => "B",
            "linkField" => "id",
            "displayName" => "rel_created_by",
            "selectFields" => ["id", "fullname"],
            "selectValue" => "fullname AS rel_created_by",
        ],

        "updated_by" => [
            "linkTable" => "users",
            "aliasTable" => "C",
            "linkField" => "id",
            "displayName" => "rel_updated_by",
            "selectFields" => ["id", "fullname"],
            "selectValue" => "fullname AS rel_updated_by",
        ],
    ];

    const CUSTOM_RELATION = [];

    const CUSTOM_SELECT = "";

    const FIELD_VALIDATION = [
        "category_name" => "required|string|max:100",
        "status" => "required|boolean",
        "created_by" => "nullable|integer",
        "updated_by" => "nullable|integer",
    ];

    const PARENT_CHILD = [];

    const CUSTOM_LIST_FILTER = [];

    const FIELD_CASTING = [
        "status" => "boolean",
    ];

    const FIELD_VALIDATION_DATA = [
        "created_by" => [
            "table" => "users",
            "field" => "id",
        ],

        "updated_by" => [
            "table" => "users",
            "field" => "id",
        ],
    ];

    const CHILD_TABLE = [];

    const MAPPING_MULTIPLE_ADD = [];

    public static function beforeInsert($input)
    {
        return $input;
    }

    public static function afterInsert($data, $input)
    {
        return [];
    }

    public static function beforeUpdate($input)
    {
        return $input;
    }

    public static function afterUpdate($data, $input)
    {
        return [];
    }

    public static function beforeDelete($input)
    {
        return $input;
    }

    public static function afterDelete($data, $input)
    {
        return [];
    }
}