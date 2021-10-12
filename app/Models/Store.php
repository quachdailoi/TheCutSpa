<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends CommonModel
{
    use HasFactory;

    protected $table = 'stores';

    /** Column of table */
    const COL_PHONE = 'phone';
    const COL_NAME = 'name';
    const COL_ADDRESS = 'address';
    const COL_OPEN_AT = 'open_at';
    const COL_CLOSE_AT = 'close_at';
    const COL_STATUS = 'status';

    /** value of model */

    /** relations */
    const CATEGORIES = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        self::COL_PHONE,
        self::COL_NAME,
        self::COL_ADDRESS,
        self::COL_OPEN_AT,
        self::COL_CLOSE_AT,
        self::COL_STATUS,
        self::COL_CREATED_AT,
        self::COL_UPDATED_AT,
        self::COL_DELETED_AT,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        self::COL_OPEN_AT => 'datetime:H:i',
        self::COL_CLOSE_AT => 'datetime:H:i',
    ];

    public static function getTableName()
    {
        return with(new static)->getTableName();
    }

    /**
     * @functionName: validator
     * @type:         public static
     * @description:  validate parameter
     * @param:        \Array $data
     * @param:        \Array $rule
     * @param:        \Array $message nullable
     * @return:       \Validate $validate
     */
    public static function validator(array $data)
    {
        $validatedFields = [
            self::COL_ID => 'numeric',
            self::COL_PHONE => 'required|numeric',
            self::COL_NAME => 'required',
            self::COL_OPEN_AT => 'required|date_format:H:i',
            self::COL_CLOSE_AT => 'required|date_format:H:i|after:time_start',
            self::COL_STATUS => 'nullable|numeric',
        ];
        $errorCode = [
            'required' => ':attribute is required.',
            'numeric' => ':attribute must be a number',
            'date_format' => ':attribute is in wrong format',
            'after' => 'Close time must be after open time in real login',
        ];

        return CommonModel::validate($data, $validatedFields, $errorCode);
    }

    /**
     * Get the user's file.
     */
    public function file()
    {
        return $this->morphOne(File::class, 'owner')->first();
    }

    /**
     * Get the store's categories.
     */
    public function categories()
    {
        return $this->hasMany(Category::class, Category::COL_STORE_ID, self::COL_ID);
    }
}
