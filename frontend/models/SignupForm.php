<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;
use common\models\Profiles;
use common\models\Actions;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $firstname;
    public $lastname;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['firstname', 'lastname'], 'required'],
            [['firstname', 'lastname'], 'string', 'max' => 15],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $profile = new Profiles();
            $profile->firstname = $this->firstname;
            $profile->lastname = $this->lastname;
            if ($user->validate() AND $profile->validate()) {
                $user->save();
                $profile->user_id = $user->id;
                $profile->save();
                $action = new Actions();
                $action->user_id = $user->id;
                $action->type = 'newAccount';
                $action->privacy = 'Public';
                $action->save();
                return $user;
            }
        }

        return null;
    }
}
