<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingLogs extends Model
{
    protected $table = 'voting_logs';
    protected $fillable = ['voting_id', 'candidate_id', 'user_id'];

    const UPDATED_AT = null; // hapus baris ini kalau kolom updated_at ada di voting_logs

    const TABLE = 'voting_logs';
    const FILEROOT = '/voting_logs';

    const IS_LIST = true;
    const IS_ADD = false;
    const IS_EDIT = false;
    const IS_DELETE = false;
    const IS_VIEW = true;

    const FIELD_LIST = ['id', 'voting_id', 'candidate_id', 'user_id', 'created_at'];
    const FIELD_ADD = [];
    const FIELD_EDIT = [];
    const FIELD_VIEW = ['id', 'voting_id', 'candidate_id', 'user_id', 'created_at'];
    const FIELD_READONLY = [];

    const FIELD_FILTERABLE = [
        'id' => ['operator' => '='],
        'voting_id' => ['operator' => '='],
        'candidate_id' => ['operator' => '='],
        'user_id' => ['operator' => '='],
    ];

    const FIELD_SEARCHABLE = [];
    const FIELD_ARRAY = [];
    const FIELD_SORTABLE = ['id', 'created_at'];
    const FIELD_UNIQUE = [];
    const FIELD_UPLOAD = [];

    const FIELD_TYPE = [
        'id' => 'bigint',
        'voting_id' => 'bigint',
        'candidate_id' => 'bigint',
        'user_id' => 'bigint',
        'created_at' => 'timestamp_with_time_zone',
    ];

    const FIELD_DEFAULT_VALUE = [];

    const FIELD_RELATION = [
        'voting_id' => [
            'linkTable' => 'votings', 'aliasTable' => 'V', 'linkField' => 'id',
            'displayName' => 'rel_voting',
            'selectFields' => ['id', 'title'], 'selectValue' => 'title AS rel_voting',
        ],
        'candidate_id' => [
            'linkTable' => 'voting_candidates', 'aliasTable' => 'K', 'linkField' => 'id',
            'displayName' => 'rel_candidate',
            'selectFields' => ['id', 'title'], 'selectValue' => 'title AS rel_candidate',
        ],
        'user_id' => [
            'linkTable' => 'users', 'aliasTable' => 'U', 'linkField' => 'id',
            'displayName' => 'rel_user',
            'selectFields' => ['id', 'fullname'], 'selectValue' => 'fullname AS rel_user',
        ],
    ];

    const CUSTOM_RELATION = [];
    const CUSTOM_SELECT = '';

    const FIELD_VALIDATION = [
        'voting_id' => 'required|integer',
        'candidate_id' => 'required|integer',
        'user_id' => 'required|integer',
    ];

    const PARENT_CHILD = [];
    const CUSTOM_LIST_FILTER = [];
    const FIELD_CASTING = [];
    const FIELD_VALIDATION_DATA = [];
    const CHILD_TABLE = [];
    const MAPPING_MULTIPLE_ADD = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function candidate()
    {
        return $this->belongsTo(VotingCandidates::class, 'candidate_id');
    }

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