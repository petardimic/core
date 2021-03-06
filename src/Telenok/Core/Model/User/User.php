<?php namespace Telenok\Core\Model\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends \Telenok\Core\Interfaces\Eloquent\Object\Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	protected $ruleList = ['title' => ['required', 'min:1'], 'email' => ['unique:user,email,:id:,id'], 'usernick' => ['unique:user,usernick,:id:,id']];
	protected $table = 'user';
	protected $hidden = ['password'];
	protected $fillable = ['remember_token'];


	public function setPasswordAttribute($value)
	{
		if ($value = trim($value))
		{
			$this->attributes['password'] = bcrypt($value);
		}
		else if (!$this->exists && !$value)
		{
			$this->attributes['password'] = bcrypt(str_random());
		}
	}

	public function setUsernickAttribute($value)
	{
		$this->attributes['usernick'] = trim($value) ?: $this->username;
	}

	public function createdBy()
	{
		return $this->hasMany('\App\Telenok\Core\Model\Object\Sequence', 'created_by_user');
	}

	public function updatedBy()
	{
		return $this->hasMany('\App\Telenok\Core\Model\Object\Sequence', 'updated_by_user');
	}

	public function deletedBy()
	{
		return $this->hasMany('\App\Telenok\Core\Model\Object\Sequence', 'deleted_by_user');
	}

	public function lockedBy()
	{
		return $this->hasMany('\App\Telenok\Core\Model\Object\Sequence', 'locked_by_user');
	}

	public function group()
	{
		return $this->belongsToMany('\App\Telenok\Core\Model\User\Group', 'pivot_relation_m2m_group_user', 'group_user', 'group')->withTimestamps();
	}

    public function avatarUserFileExtension()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\File\FileExtension', 'avatar_user_file_extension');
    }

    public function avatarUserFileMimeType()
    {
        return $this->belongsTo('\App\Telenok\Core\Model\File\FileMimeType', 'avatar_user_file_mime_type');
    }
}