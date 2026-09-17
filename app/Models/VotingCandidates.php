<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingCandidates extends Model
{
    protected $table = 'voting_candidates';
    protected $dateFormat = 'c';
    protected $fillable = [
        'voting_id', 'img_cover', 'title', 'description',
        'order', 'status_code', 'created_by', 'updated_by',
    ];

    const TABLE = "voting_candidates";
    const FILEROOT = "/voting_candidates";

    const IS_LIST = true;
    const IS_ADD = true;
    const IS_EDIT = true;
    const IS_DELETE = true;
    const IS_VIEW = true;

    const FIELD_LIST = [
        "id", "voting_id", "img_cover", "title", "description",
        "order", "status_code", "created_by", "updated_by",
        "created_at", "updated_at",
    ];

    const FIELD_ADD = [
        "voting_id", "img_cover", "title", "description",
        "order", "status_code", "created_by",
    ];

    const FIELD_EDIT = [
        "img_cover", "title", "description", "order", "status_code", "updated_by",
    ];

    const FIELD_VIEW = [
        "id", "voting_id", "img_cover", "title", "description",
        "order", "status_code", "created_by", "updated_by",
        "created_at", "updated_at",
    ];

    const FIELD_READONLY = ["voting_id"];

    const FIELD_FILTERABLE = [
        "id" => ["operator" => "="],
        "voting_id" => ["operator" => "="],
        "title" => ["operator" => "ILIKE"],
        "status_code" => ["operator" => "="],
    ];

    const FIELD_SEARCHABLE = ["title", "description"];
    const FIELD_ARRAY = [];
    const FIELD_SORTABLE = ["id", "title", "order", "created_at"];
    const FIELD_UNIQUE = [];
    const FIELD_UPLOAD = ["img_cover"];

    const FIELD_TYPE = [
        "id" => "bigint",
        "voting_id" => "bigint",
        "img_cover" => "text",
        "title" => "character_varying",
        "description" => "character_varying",
        "order" => "integer",
        "status_code" => "boolean",
        "created_by" => "bigint",
        "updated_by" => "bigint",
        "created_at" => "timestamp_with_time_zone",
        "updated_at" => "timestamp_with_time_zone",
    ];

    const FIELD_DEFAULT_VALUE = [
        "status_code" => true,
    ];

    const FIELD_RELATION = [
        "voting_id" => [
            "linkTable" => "votings", "aliasTable" => "V", "linkField" => "id",
            "displayName" => "rel_voting",
            "selectFields" => ["id", "title"], "selectValue" => "title AS rel_voting",
        ],
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
        "voting_id" => "required|integer",
        "img_cover" => "nullable|string",
        "title" => "required|string",
        "description" => "nullable|string",
        "order" => "nullable|integer",
        "status_code" => "required|boolean",
        "created_by" => "nullable|integer",
        "updated_by" => "nullable|integer",
    ];

    const PARENT_CHILD = [];
    const CUSTOM_LIST_FILTER = [];

    const FIELD_CASTING = [
        "status_code" => "boolean",
    ];

    const FIELD_VALIDATION_DATA = [
        "voting_id" => ["table" => "votings", "field" => "id"],
        "created_by" => ["table" => "users", "field" => "id"],
        "updated_by" => ["table" => "users", "field" => "id"],
    ];

    const CHILD_TABLE = [];
    const MAPPING_MULTIPLE_ADD = [];

    public function voting()
    {
        return $this->belongsTo(Voting::class, 'voting_id');
    }

    public static function beforeInsert($input) { return $input; }
    public static function afterInsert($data, $input) { return []; }
    public static function beforeUpdate($input) { return $input; }
    public static function afterUpdate($data, $input) { return []; }
    public static function beforeDelete($input) { return $input; }
    public static function afterDelete($data, $input) { return []; }
}