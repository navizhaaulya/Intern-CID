<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedbacks extends Model
{
    protected $table = 'feedbacks';
    protected $dateFormat = 'c';
    protected $fillable = ['sender_name', 'type', 'category_id', 'message', 'created_by']; // ⬅️ ditambahkan lagi

    const TABLE = "feedbacks";
    const FILEROOT = "/feedbacks";

    // sesuai konfirmasi: admin cuma bisa lihat & hapus,
    // data masuk dari submit publik (bukan lewat CRUD ini)
    const IS_LIST = true;
    const IS_ADD = false;
    const IS_EDIT = false;
    const IS_DELETE = true;
    const IS_VIEW = true;

    const FIELD_LIST = [
        "id",
        "sender_name",
        "type",
        "category_id",
        "message",
        "created_by",
        "created_at",
        "updated_at",
    ];

    const FIELD_ADD = [];

    const FIELD_EDIT = [];

    const FIELD_VIEW = [
        "id",
        "sender_name",
        "type",
        "category_id",
        "message",
        "created_by",
        "created_at",
        "updated_at",
    ];

    const FIELD_READONLY = [
        "sender_name",
        "type",
        "category_id",
        "message",
        "created_by",
    ];

    const FIELD_FILTERABLE = [
        "id" => [
            "operator" => "=",
        ],
        "type" => [
            "operator" => "=",
        ],
        "category_id" => [
            "operator" => "=",
        ],
        "created_by" => [
            "operator" => "=",
        ],
    ];

    const FIELD_SEARCHABLE = [
        "sender_name",
        "message",
    ];

    const FIELD_ARRAY = [];

    const FIELD_SORTABLE = [
        "id",
        "sender_name",
        "type",
        "created_at",
    ];

    const FIELD_UNIQUE = [];

    const FIELD_UPLOAD = [];

    const FIELD_TYPE = [
        "id" => "bigint",
        "sender_name" => "character_varying",
        "type" => "boolean",
        "category_id" => "bigint",
        "message" => "text",
        "created_by" => "bigint",
        "created_at" => "timestamp_with_time_zone",
        "updated_at" => "timestamp_with_time_zone",
    ];

    const FIELD_DEFAULT_VALUE = [];

    const FIELD_RELATION = [
        "category_id" => [
            "linkTable" => "feedback_categories",
            "aliasTable" => "B",
            "linkField" => "id",
            "displayName" => "rel_category",
            "selectFields" => ["id", "category_name"],
            "selectValue" => "category_name AS rel_category",
        ],

        "created_by" => [
            "linkTable" => "users",
            "aliasTable" => "C",
            "linkField" => "id",
            "displayName" => "rel_created_by",
            "selectFields" => ["id", "fullname"],
            "selectValue" => "fullname AS rel_created_by",
        ],
    ];

    const CUSTOM_RELATION = [];

    const CUSTOM_SELECT = "";

    const FIELD_VALIDATION = [
        "sender_name" => "nullable|string|max:255",
        "type" => "required|boolean",
        "category_id" => "required|integer",
        "message" => "required|string",
        "created_by" => "nullable|integer",
    ];

    const PARENT_CHILD = [];

    const CUSTOM_LIST_FILTER = [];

    const FIELD_CASTING = [
        "type" => "boolean",
    ];

    const FIELD_VALIDATION_DATA = [
        "category_id" => [
            "table" => "feedback_categories",
            "field" => "id",
        ],

        "created_by" => [
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