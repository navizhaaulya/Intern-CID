<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    protected $table = 'votings';
    protected $dateFormat = 'c';
    protected $fillable = [
        'slug', 'img_cover', 'title', 'description',
        'start_date', 'end_date', 'status_code', 'is_highlight',
        'created_by', 'updated_by',
    ];

    const TABLE = "votings";
    const FILEROOT = "/votings";

    const IS_LIST = true;
    const IS_ADD = false;  // tetap lewat VotingController::store (butuh slug custom)
    const IS_EDIT = false; // tetap lewat VotingController::update
    const IS_DELETE = false; // soft delete custom di VotingController::delete
    const IS_VIEW = true;

    const FIELD_LIST = [
        "id", "slug", "img_cover", "title", "description",
        "start_date", "end_date", "status_code", "is_highlight",
        "created_by", "updated_by", "created_at", "updated_at",
    ];

    const FIELD_ADD = [];
    const FIELD_EDIT = [];

    const FIELD_VIEW = [
        "id", "slug", "img_cover", "title", "description",
        "start_date", "end_date", "status_code", "is_highlight",
        "created_by", "updated_by", "created_at", "updated_at",
    ];

    const FIELD_READONLY = [];

    const FIELD_FILTERABLE = [
        "id" => ["operator" => "="],
        "title" => ["operator" => "ILIKE"],
        "status_code" => ["operator" => "="],
        "is_highlight" => ["operator" => "="],
    ];

    const FIELD_SEARCHABLE = ["title", "description"];
    const FIELD_ARRAY = [];
    const FIELD_SORTABLE = ["id", "title", "start_date", "end_date", "created_at"];
    const FIELD_UNIQUE = [];
    const FIELD_UPLOAD = ["img_cover"];

    const FIELD_TYPE = [
        "id" => "bigint",
        "slug" => "character_varying",
        "img_cover" => "text",
        "title" => "character_varying",
        "description" => "character_varying",
        "start_date" => "date",
        "end_date" => "date",
        "status_code" => "boolean",
        "is_highlight" => "boolean",
        "created_by" => "bigint",
        "updated_by" => "bigint",
        "created_at" => "timestamp_with_time_zone",
        "updated_at" => "timestamp_with_time_zone",
    ];

    const FIELD_DEFAULT_VALUE = [];

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
        "slug" => "required|string",
        "img_cover" => "nullable|string",
        "title" => "required|string",
        "description" => "required|string",
        "start_date" => "required|date",
        "end_date" => "required|date",
        "status_code" => "required|boolean",
        "is_highlight" => "nullable|boolean",
        "created_by" => "nullable|integer",
        "updated_by" => "nullable|integer",
    ];

    const PARENT_CHILD = [];
    const CUSTOM_LIST_FILTER = [];

    const FIELD_CASTING = [
        "status_code" => "boolean",
        "is_highlight" => "boolean",
    ];

    const FIELD_VALIDATION_DATA = [
        "created_by" => ["table" => "users", "field" => "id"],
        "updated_by" => ["table" => "users", "field" => "id"],
    ];

    const CHILD_TABLE = [];
    const MAPPING_MULTIPLE_ADD = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function beforeInsert($input) { return $input; }
    public static function afterInsert($data, $input) { return []; }
    public static function beforeUpdate($input) { return $input; }
    public static function afterUpdate($data, $input) { return []; }
    public static function beforeDelete($input) { return $input; }
    public static function afterDelete($data, $input) { return []; }
}