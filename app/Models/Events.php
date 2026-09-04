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


class Events extends Model
{
    protected $table = 'events';
    protected $dateFormat = 'c';
    const TABLE = "events";
    const FILEROOT = "/events";
    const IS_LIST = true;
    const IS_ADD = true;
    const IS_EDIT = true;
    const IS_DELETE = true;
    const IS_VIEW = true;
    const FIELD_LIST = ["id", "slug", "title", "content", "location", "start_date", "end_date", "img_cover", "status", "is_highlight", "created_by", "updated_by", "created_at", "updated_at"];
    const FIELD_ADD = ["slug", "title", "content", "location", "start_date", "end_date", "img_cover", "status", "is_highlight", "created_by", "updated_by"];
    const FIELD_EDIT = ["slug", "title", "content", "location", "start_date", "end_date", "img_cover", "status", "is_highlight", "updated_by"];
    const FIELD_VIEW = ["id", "slug", "title", "content", "location", "start_date", "end_date", "img_cover", "status", "is_highlight", "created_by", "updated_by", "created_at", "updated_at"];
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
        "location" => [
            "operator" => "=",
        ],
        "start_date" => [
            "operator" => "=",
        ],
        "end_date" => [
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
    ];
    const FIELD_SEARCHABLE = ["slug", "title", "location", "status"];
    const FIELD_ARRAY = [];
    const FIELD_SORTABLE = ["id", "slug", "title", "content", "location", "start_date", "end_date", "img_cover", "status", "is_highlight", "created_by", "updated_by", "created_at", "updated_at"];
    const FIELD_UNIQUE = [["slug"]];
    const FIELD_UPLOAD = ["img_cover"];
    const FIELD_TYPE = [
        "id" => "bigint",
        "slug" => "character_varying",
        "title" => "character_varying",
        "content" => "text",
        "location" => "character_varying",
        "start_date" => "date",
        "end_date" => "date",
        "img_cover" => "text",
        "status" => "character_varying",
        "is_highlight" => "boolean",
        "created_by" => "bigint",
        "updated_by" => "bigint",
        "created_at" => "timestamp_with_time_zone",
        "updated_at" => "timestamp_with_time_zone",
    ];

    const FIELD_DEFAULT_VALUE = [
        "slug" => "",
        "title" => "",
        "content" => "",
        "location" => "",
        "start_date" => "",
        "end_date" => "",
        "img_cover" => "",
        "status" => "",
        "is_highlight" => "false",
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
            "selectFields" => ["username"],
            "selectValue" => "id AS rel_created_by"
        ],
        "updated_by" => [
            "linkTable" => "users",
            "aliasTable" => "C",
            "linkField" => "id",
            "displayName" => "rel_updated_by",
            "selectFields" => ["username"],
            "selectValue" => "id AS rel_updated_by"
        ],
    ];
    const CUSTOM_RELATION = [];
    const CUSTOM_SELECT = "";
    const FIELD_VALIDATION = [
        "slug" => "required|string|max:255",
        "title" => "required|string|max:255",
        "content" => "required|string",
        "location" => "nullable|string|max:255",
        "start_date" => "required",
        "end_date" => "required",
        "img_cover" => "nullable|string|exists_file",
        "status" => "required|string|max:255",
        "is_highlight" => "required",
        "created_by" => "required|integer",
        "updated_by" => "required|integer",
        "created_at" => "nullable|date",
        "updated_at" => "nullable|date",
    ];
    const PARENT_CHILD = [];
    // start custom
    const CUSTOM_LIST_FILTER = [];
    const FIELD_CASTING = [
    //"nama field" => "float",
    ];
    const FIELD_VALIDATION_DATA = [];
    const CHILD_TABLE = [
    //"child_table" => [
    // "foreignField" => "field"
    //]
    ];
    const MAPPING_MULTIPLE_ADD = [
    //"contracts" => [ -- main table (contract_id)
    //    "dataIdTable" => "m_poc", -- data (mapping id)
    //    "fieldAdd" => [],
    //    "fieldUnique" => [],
    //],
    ];

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
    }// end custom
}
