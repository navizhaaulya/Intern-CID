<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalConfig extends Model
{
    protected $table = 'global_config';
    protected $dateFormat = 'c';

    protected $casts = [
        'missions' => 'array',   // ⬅️ tambahkan ini
    ];

    const TABLE = "global_config";
    const FILEROOT = "/global_config";

    const IS_LIST = false;
    const IS_ADD = false;
    const IS_EDIT = true;
    const IS_DELETE = false;
    const IS_VIEW = true;

    const FIELD_LIST = [
        "id",
        "profile_title",
        "profile_description",
        "img_profile_1",
        "img_profile_2",
        "school_vission",
        "video_profile",
        "vision",
        "missions",
        "school_name",
        "footer_description",
        "motto",
        "school_telephone",
        "school_email",
        "footer_ig",
        "footer_yt",
        "footer_fb",
        "footer_linkedin",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    const FIELD_ADD = [];

    const FIELD_EDIT = [
        "profile_title",
        "profile_description",
        "img_profile_1",
        "img_profile_2",
        "video_profile",
        "vision",
        "missions",
        "school_name",
        "footer_description",
        "motto",
        "school_telephone",
        "school_email",
        "footer_ig",
        "footer_yt",
        "footer_fb",
        "footer_linkedin",
        "updated_by",
    ];

    const FIELD_VIEW = [
        "id",
        "profile_title",
        "profile_description",
        "img_profile_1",
        "img_profile_2",
        "video_profile",
        "vision",
        "missions",
        "school_name",
        "footer_description",
        "motto",
        "school_telephone",
        "school_email",
        "footer_ig",
        "footer_yt",
        "footer_fb",
        "footer_linkedin",
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
    ];

    const FIELD_SEARCHABLE = [];

    // "missions" is json/array — flag it here so the CRUD service
    // knows to encode/decode it instead of treating it as plain text
    const FIELD_ARRAY = [];

    const FIELD_SORTABLE = [
        "id",
        "updated_at",
    ];

    const FIELD_UNIQUE = [];

    const FIELD_UPLOAD = [
        "img_profile_1",
        "img_profile_2",
    ];

    const FIELD_TYPE = [
        "id" => "bigint",
        "profile_title" => "character_varying",
        "profile_description" => "text",
        "img_profile_1" => "text",
        "img_profile_2" => "text",
        "school_vission" => "text",
        "video_profile" => "text",
        "vision" => "text",
        "missions" => "json",
        "school_name" => "character_varying",
        "footer_description" => "character_varying",
        "motto" => "character_varying",
        "school_telephone" => "character_varying",
        "school_email" => "character_varying",
        "footer_ig" => "text",
        "footer_yt" => "text",
        "footer_fb" => "text",
        "footer_linkedin" => "text",
        "created_by" => "bigint",
        "updated_by" => "bigint",
        "created_at" => "timestamp_with_time_zone",
        "updated_at" => "timestamp_with_time_zone",
    ];

    const FIELD_DEFAULT_VALUE = [
        "vision" => "",
        "missions" => [],
    ];

    const FIELD_RELATION = [
        "created_by" => [
            "linkTable" => "users",
            "aliasTable" => "B",
            "linkField" => "id",
            "displayName" => "rel_created_by",
            "selectFields" => ["id", "fullname"],
            "selectValue" => "id AS rel_created_by",
        ],
        "updated_by" => [
            "linkTable" => "users",
            "aliasTable" => "C",
            "linkField" => "id",
            "displayName" => "rel_updated_by",
            "selectFields" => ["id", "fullname"],
            "selectValue" => "id AS rel_updated_by",
        ],
    ];

    const CUSTOM_RELATION = [];
    const CUSTOM_SELECT = "";

    const FIELD_VALIDATION = [
        "profile_title" => "nullable|string|max:255",
        "profile_description" => "nullable|string",
        "img_profile_1" => "nullable|string|exists_file",
        "img_profile_2" => "nullable|string",
        "video_profile" => "nullable|string",
        "vision" => "nullable|string",
        "missions" => "nullable|array",
        "school_name" => "nullable|string|max:150",
        "footer_description" => "nullable|string|max:255",
        "motto" => "nullable|string|max:100",
        "school_telephone" => "nullable|string|max:150",
        "school_email" => "nullable|email",
        "footer_ig" => "nullable|string",
        "footer_yt" => "nullable|string",
        "footer_fb" => "nullable|string",
        "footer_linkedin" => "nullable|string",
        "updated_by" => "nullable|integer",
    ];

    const PARENT_CHILD = [];
    const CUSTOM_LIST_FILTER = [];

    const FIELD_CASTING = [
        "missions" => "array",
    ];

    const FIELD_VALIDATION_DATA = [
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