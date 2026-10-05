<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $table = 'majors';
    protected $dateFormat = 'c';
    const TABLE = "majors";
    const FILEROOT = "majors";
    const IS_LIST = true;
    const IS_ADD = true;
    const IS_EDIT = true;
    const IS_DELETE = true;
    const IS_VIEW = true;
    const FIELD_LIST = ["id", "slug", "img_logo", "code", "major_name", "summary", "total_classes", "major_duration", "full_description", "status_code", "created_by", "updated_by", "created_at", "updated_at"];
    const FIELD_ADD = ["slug", "img_logo", "code", "major_name", "summary", "total_classes", "major_duration", "full_description", "status_code", "created_by", "updated_by"];
    const FIELD_EDIT = ["slug", "img_logo", "code", "major_name", "summary", "total_classes", "major_duration", "full_description", "status_code", "updated_by"];
    const FIELD_VIEW = ["id", "slug", "img_logo", "code", "major_name", "summary", "total_classes", "major_duration", "full_description", "status_code", "created_by", "updated_by", "created_at", "updated_at"];
    const FIELD_READONLY = [];
    const FIELD_FILTERABLE = [
        "id" => [
            "operator" => "=",
        ],
        "slug" => [
            "operator" => "=",
        ],
        "img_logo" => [
            "operator" => "=",
        ],
        "code" => [
            "operator" => "=",
        ],
        "major_name" => [
            "operator" => "=",
        ],
        "summary" => [
            "operator" => "=",
        ],
        "total_classes" => [
            "operator" => "=",
        ],
        "major_duration" => [
            "operator" => "=",
        ],
        "full_description" => [
            "operator" => "=",
        ],
        "status_code" => [
            "operator" => "=",
        ],
        "created_by" => [
            "operator" => "=",
        ],
        "updated_by" => [
            "operator" => "=",
        ],
        "created_at" => [
            "operator" => "=",
        ],
        "updated_at" => [
            "operator" => "=",
        ],
    ];
    const FIELD_SEARCHABLE = ["slug", "code", "major_name"];
    const FIELD_ARRAY = [];
    const FIELD_SORTABLE = ["id", "slug", "img_logo", "code", "major_name", "summary", "total_classes", "major_duration", "full_description", "status_code", "created_by", "updated_by", "created_at", "updated_at"];
    const FIELD_UNIQUE = [["slug"]];
    const FIELD_UPLOAD = ["img_logo"];
    const FIELD_TYPE = [
        "id" => "bigint",
        "slug" => "character_varying",
        "img_logo" => "text",
        "code" => "character_varying",
        "major_name" => "character_varying",
        "summary" => "character_varying",
        "total_classes" => "integer",
        "major_duration" => "integer",
        "full_description" => "text",
        "status_code" => "boolean",
        "created_by" => "bigint",
        "updated_by" => "bigint",
        "created_at" => "timestamp_with_time_zone",
        "updated_at" => "timestamp_with_time_zone",
    ];

    const FIELD_DEFAULT_VALUE = [
        "slug" => "",
        "img_logo" => "",
        "code" => "",
        "major_name" => "",
        "summary" => "",
        "total_classes" => "",
        "major_duration" => "",
        "full_description" => "",
        "status_code" => "true",
        "created_by" => "",
        "updated_by" => "",
        "created_at" => "",
        "updated_at" => "",
    ];
    const FIELD_RELATION = [
        "created_by" => [
            "linkTable" => "users",
            "aliasTable" => "B",
            "linkField" => "id",
            "displayName" => "rel_created_by",
            "selectFields" => ["id", "fullname"],
            "selectValue" => "id AS rel_created_by"
        ],
        "updated_by" => [
            "linkTable" => "users",
            "aliasTable" => "C",
            "linkField" => "id",
            "displayName" => "rel_updated_by",
            "selectFields" => ["id", "fullname"],
            "selectValue" => "id AS rel_updated_by"
        ],
    ];
    const CUSTOM_RELATION = [];
    const CUSTOM_SELECT = "";
    const FIELD_VALIDATION = [
        "slug" => "required|string|max:255",
        "img_logo" => "required|string|exists_file",
        "code" => "required|string|max:10",
        "major_name" => "required|string|max:100",
        "summary" => "required|string|max:255",
        "total_classes" => "required|integer",
        "major_duration" => "required|integer",
        "full_description" => "required|string",
        "status_code" => "required",
        "created_by" => "nullable|integer",
        "updated_by" => "nullable|integer",
        "created_at" => "nullable|date",
        "updated_at" => "nullable|date",
    ];
    const PARENT_CHILD = [];
    const CUSTOM_LIST_FILTER = [];
    const FIELD_CASTING = [
        "status_code" => "boolean",
    ];
    const FIELD_VALIDATION_DATA = [];

    const CHILD_TABLE = [];

    public function galleries()
    {
        return $this->hasMany(MajorGallery::class, 'major_id');
    }

    public function competencies()
{
    return $this->hasMany(MajorCompetent::class, 'major_id');
}

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

    const MAPPING_MULTIPLE_ADD = [];
}