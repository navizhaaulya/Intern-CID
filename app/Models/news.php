<?php 

namespace App\Models;

use App\CoreService\CallService;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class News extends Model
{
    protected $table = 'news';
    protected $dateFormat = 'c';
    const TABLE = "news";
    const FILEROOT = "/news";
    const IS_LIST = true;
    const IS_ADD = true;
    const IS_EDIT = true;
    const IS_DELETE = true;
    const IS_VIEW = true;
    const FIELD_LIST = ["id", "slug", "title", "content", "img_cover", "status", "is_highlight", "created_by", "updated_by", "created_at", "updated_at", "category_id"];
    const FIELD_ADD = ["slug", "title", "content", "img_cover", "status", "is_highlight", "created_by", "updated_by", "category_id"];
    const FIELD_EDIT = ["slug", "title", "content", "img_cover", "status", "is_highlight", "updated_by", "category_id"];
    const FIELD_VIEW = ["id", "slug", "title", "content", "img_cover", "status", "is_highlight", "created_by", "updated_by", "created_at", "updated_at", "category_id"];
    const FIELD_READONLY = [];
    const FIELD_FILTERABLE = [
        "id" => [
            "operator" => "=",
        ],
        "slug" => [
            "operator" => "=",
        ],
        "title" => [
            "operator" => "=",
        ],
        "content" => [
            "operator" => "=",
        ],
        "img_cover" => [
            "operator" => "=",
        ],
        "status" => [
            "operator" => "=",
        ],
        "is_highlight" => [
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
        "category_id" => [
            "operator" => "=",
        ],
    ];
    const FIELD_SEARCHABLE = ["slug", "title", "status"];
    const FIELD_ARRAY = [];
    const FIELD_SORTABLE = ["id", "slug", "title", "content", "img_cover", "status", "is_highlight", "created_by", "updated_by", "created_at", "updated_at", "category_id"];
    const FIELD_UNIQUE = [["slug"]];
    const FIELD_UPLOAD = ["img_cover"];
    const FIELD_TYPE = [
        "id" => "bigint",
        "slug" => "character_varying",
        "title" => "character_varying",
        "content" => "text",
        "img_cover" => "text",
        "status" => "character_varying",
        "is_highlight" => "boolean",
        "created_by" => "bigint",
        "updated_by" => "bigint",
        "created_at" => "timestamp_with_time_zone",
        "updated_at" => "timestamp_with_time_zone",
        "category_id" => "bigint",
    ];

    const FIELD_DEFAULT_VALUE = [
        "slug" => "",
        "title" => "",
        "content" => "",
        "img_cover" => "",
        "status" => "",
        "is_highlight" => "false",
        "created_by" => "",
        "updated_by" => "",
        "created_at" => "",
        "updated_at" => "",
        "category_id" => "",
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
        "category_id" => [
            "linkTable" => "news_categories",
            "aliasTable" => "D",
            "linkField" => "id",
            "displayName" => "rel_category_id",
            "selectFields" => ["id", "name"],
            "selectValue" => "id AS rel_category_id"
        ],
    ];
    const CUSTOM_RELATION = [];
    const CUSTOM_SELECT = "";
   const FIELD_VALIDATION = [
    "slug" => "required|string|max:255",
    "title" => "required|string|max:255",
    "content" => "required|string",
    "img_cover" => "nullable|string|exists_file",
    "status" => "required|string|max:255",
    "is_highlight" => "required",
    "created_by" => "nullable|integer",
    "updated_by" => "nullable|integer",
    "created_at" => "nullable|date",
    "updated_at" => "nullable|date",
    "category_id" => "nullable|integer",
];
    const PARENT_CHILD = [];
    const CUSTOM_LIST_FILTER = [];
    const FIELD_CASTING = [
    "is_highlight" => "boolean",
];
const FIELD_VALIDATION_DATA = [
    "category_id" => [
        "table" => "news_categories",
        "validation" => [
            "id" => [
                "value" => "current_value"
            ]
        ]
    ],
];

const CHILD_TABLE = [];

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
