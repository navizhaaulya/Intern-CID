<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';
    protected $dateFormat = 'c';

    const TABLE = "banners";
    const FILEROOT = "/banners";

    const IS_LIST = true;
    const IS_ADD = true;
    const IS_EDIT = true;
    const IS_DELETE = true;
    const IS_VIEW = true;


    // =========================
    // FIELD
    // =========================

    const FIELD_LIST = [
        "id",
        "title",
        "img_cover",
        "url",
        "status_code",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    const FIELD_ADD = [
        "title",
        "img_cover",
        "url",
        "status_code",
        "created_by",
    ];

    const FIELD_EDIT = [
        "title",
        "img_cover",
        "url",
        "status_code",
        "updated_by",
    ];

    const FIELD_VIEW = [
        "id",
        "title",
        "img_cover",
        "url",
        "status_code",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    const FIELD_READONLY = [];


    // =========================
    // FILTER
    // =========================

    const FIELD_FILTERABLE = [
        "id" => [
            "operator" => "=",
        ],

        "title" => [
            "operator" => "ILIKE",
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
    ];


    // =========================
    // SEARCH & SORT
    // =========================

    const FIELD_SEARCHABLE = [
        "title",
    ];

    const FIELD_ARRAY = [];

    const FIELD_SORTABLE = [
        "id",
        "title",
        "status_code",
        "created_at",
        "updated_at",
    ];


    // =========================
    // UNIQUE
    // =========================

    const FIELD_UNIQUE = [];


    // =========================
    // UPLOAD
    // =========================

    const FIELD_UPLOAD = [
        "img_cover",
    ];


    // =========================
    // FIELD TYPE
    // =========================

    const FIELD_TYPE = [
        "id" => "bigint",
        "title" => "character_varying",
        "img_cover" => "text",
        "url" => "text",
        "status_code" => "boolean",
        "created_by" => "bigint",
        "updated_by" => "bigint",
        "created_at" => "timestamp_with_time_zone",
        "updated_at" => "timestamp_with_time_zone",
    ];


    // =========================
    // DEFAULT VALUE
    // =========================

    const FIELD_DEFAULT_VALUE = [
        "title" => "",
        "img_cover" => "",
        "url" => "",
        "status_code" => "true",
        "created_by" => "",
    ];


    // =========================
    // RELATION
    // =========================

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
        "title" => "nullable|string|max:255",

        "img_cover" => "required|string|exists_file",

        "url" => "nullable|string",

        "status_code" => "required|boolean",

        "created_by" => "required|integer",

        "updated_by" => "nullable|integer",
    ];


    const PARENT_CHILD = [];

    const CUSTOM_LIST_FILTER = [];


    // =========================
    // CASTING
    // =========================

    const FIELD_CASTING = [
        "status_code" => "boolean",
    ];


    // =========================
    // VALIDATION DATA
    // =========================

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
}