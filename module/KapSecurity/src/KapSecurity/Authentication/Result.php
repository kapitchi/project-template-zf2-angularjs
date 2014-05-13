<?php
/**
 * Created by PhpStorm.
 * User: zemi
 * Date: 12/05/14
 * Time: 19:34
 */

namespace KapSecurity\Authentication;


class Result extends \Zend\Authentication\Result {
    protected $userProfile;
    protected $identityId;

    /**
     * @param int $identityId
     */
    public function setIdentityId($identityId)
    {
        $this->identityId = $identityId;
    }

    /**
     * @return int
     */
    public function getIdentityId()
    {
        return $this->identityId;
    }

    /**
     * @param UserProfile $userProfile
     */
    public function setUserProfile(UserProfile $userProfile)
    {
        $this->userProfile = $userProfile;
    }

    /**
     * @return UserProfile
     */
    public function getUserProfile()
    {
        return $this->userProfile;
    }

} 