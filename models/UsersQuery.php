<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Users]].
 *
 * @see Users
 */
class UsersQuery extends \yii\db\ActiveQuery
{
    /**
     * Query only active users (status = 10)
     * @return UsersQuery
     */
    public function active()
    {
        return $this->andWhere(['status' => Users::STATUS_ACTIVE]);
    }

    /**
     * Query inactive users (status = 9)
     * @return UsersQuery
     */
    public function inactive()
    {
        return $this->andWhere(['status' => Users::STATUS_INACTIVE]);
    }

    /**
     * Query deleted users (status = 0)
     * @return UsersQuery
     */
    public function deleted()
    {
        return $this->andWhere(['status' => Users::STATUS_DELETED]);
    }

    /**
     * Query users by specific role
     * @param string $role Role name (Admin, Teacher, Parent)
     * @return UsersQuery
     */
    public function role($role)
    {
        return $this->andWhere(['role' => $role]);
    }

    /**
     * Query only admin users
     * @return UsersQuery
     */
    public function admins()
    {
        return $this->role(Users::ROLE_ADMIN);
    }

    /**
     * Query only teacher users
     * @return UsersQuery
     */
    public function teachers()
    {
        return $this->role(Users::ROLE_TEACHER);
    }

    /**
     * Query only parent users
     * @return UsersQuery
     */
    public function parents()
    {
        return $this->role(Users::ROLE_PARENT);
    }

    /**
     * Query users registered after a specific date
     * @param string $date Date in Y-m-d format
     * @return UsersQuery
     */
    public function registeredAfter($date)
    {
        return $this->andWhere(['>=', 'date_registered', $date]);
    }

    /**
     * Query users registered before a specific date
     * @param string $date Date in Y-m-d format
     * @return UsersQuery
     */
    public function registeredBefore($date)
    {
        return $this->andWhere(['<=', 'date_registered', $date]);
    }

    /**
     * Query users who logged in within last N days
     * @param int $days Number of days
     * @return UsersQuery
     */
    public function recentlyActive($days = 30)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->andWhere(['>=', 'last_login', $date]);
    }

    /**
     * Query users who never logged in
     * @return UsersQuery
     */
    public function neverLoggedIn()
    {
        return $this->andWhere(['last_login' => null]);
    }

    /**
     * Query users by email domain
     * @param string $domain Domain name (e.g., 'gmail.com')
     * @return UsersQuery
     */
    public function emailDomain($domain)
    {
        return $this->andWhere(['like', 'email', "@{$domain}", false]);
    }

    /**
     * Query users with password reset token (pending reset)
     * @return UsersQuery
     */
    public function withPendingPasswordReset()
    {
        return $this->andWhere(['not', ['password_reset_token' => null]]);
    }

    /**
     * Query users with verification token (unverified)
     * @return UsersQuery
     */
    public function unverified()
    {
        return $this->andWhere(['not', ['verification_token' => null]]);
    }

    /**
     * Query verified users (no verification token)
     * @return UsersQuery
     */
    public function verified()
    {
        return $this->andWhere(['verification_token' => null]);
    }

    /**
     * Order by registration date (newest first by default)
     * @param string $order SORT_DESC or SORT_ASC
     * @return UsersQuery
     */
    public function orderByRegistration($order = SORT_DESC)
    {
        return $this->orderBy(['date_registered' => $order]);
    }

    /**
     * Order by last login (most recent first by default)
     * @param string $order SORT_DESC or SORT_ASC
     * @return UsersQuery
     */
    public function orderByLastLogin($order = SORT_DESC)
    {
        return $this->orderBy(['last_login' => $order]);
    }

    /**
     * Search users by username or email
     * @param string $search Search term
     * @return UsersQuery
     */
    public function search($search)
    {
        return $this->andWhere([
            'or',
            ['like', 'username', $search],
            ['like', 'email', $search]
        ]);
    }

    /**
     * {@inheritdoc}
     * @return Users[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Users|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}