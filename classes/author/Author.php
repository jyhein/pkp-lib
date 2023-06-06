<?php

/**
 * @file classes/author/Author.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2000-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class \PKP\author\Author
 *
 * @ingroup author
 *
 * @see DAO
 *
 * @brief Author metadata class.
 */

namespace PKP\author;

use \DOMDocument;

use APP\facades\Repo;
use PKP\facades\Locale;
use PKP\identity\Identity;

class Author extends Identity
{
    /**
     * Get a piece of data for this object, localized to the current
     * locale if possible.
     *
     * @param string $key
     * @param string $preferredLocale
     */
    public function &getLocalizedData($key, $preferredLocale = null)
    {
        if (is_null($preferredLocale)) {
            $preferredLocale = Locale::getLocale();
        }
        $localePrecedence = [$preferredLocale];
        // the submission locale is the default locale
        if (!in_array($this->getSubmissionLocale(), $localePrecedence)) {
            $localePrecedence[] = $this->getSubmissionLocale();
        }
        // for settings other than givenName, familyName and affiliation (that are required)
        // consider also the application primary locale
        if (!in_array(Locale::getPrimaryLocale(), $localePrecedence)) {
            $localePrecedence[] = Locale::getPrimaryLocale();
        }
        foreach ($localePrecedence as $locale) {
            if (empty($locale)) {
                continue;
            }
            $value = & $this->getData($key, $locale);
            if (!empty($value)) {
                return $value;
            }
            unset($value);
        }

        // Fallback: Get the first available piece of data.
        $data = & $this->getData($key, null);
        foreach ((array) $data as $dataValue) {
            if (!empty($dataValue)) {
                return $dataValue;
            }
        }

        // No data available; return null.
        unset($data);
        $data = null;
        return $data;
    }

    /**
     * @copydoc Identity::getLocalizedGivenName()
     */
    public function getLocalizedGivenName()
    {
        return $this->getLocalizedData(self::IDENTITY_SETTING_GIVENNAME);
    }

    /**
     * @copydoc Identity::getLocalizedFamilyName()
     */
    public function getLocalizedFamilyName()
    {
        // Prioritize the current locale, then the default locale.
        $locale = Locale::getLocale();
        $givenName = $this->getGivenName($locale);
        // Only use the family name if a given name exists (to avoid mixing locale data)
        if (!empty($givenName)) {
            return $this->getFamilyName($locale);
        }
        // Fall back on the submission locale.
        return $this->getFamilyName($this->getSubmissionLocale());
    }

    /**
     * @copydoc Identity::getFullName()
     *
     * @param null|mixed $defaultLocale
     */
    public function getFullName($preferred = true, $familyFirst = false, $defaultLocale = null)
    {
        if (!isset($defaultLocale)) {
            $defaultLocale = $this->getSubmissionLocale();
        }
        return parent::getFullName($preferred, $familyFirst, $defaultLocale);
    }

    //
    // Get/set methods
    //

    /**
     * Get ID of submission.
     *
     * @return int
     */
    public function getSubmissionId()
    {
        return $this->getData('submissionId');
    }

    /**
     * Set ID of submission.
     *
     * @param int $submissionId
     */
    public function setSubmissionId($submissionId)
    {
        $this->setData('submissionId', $submissionId);
    }

    /**
     * Get submission locale.
     *
     * @return string
     */
    public function getSubmissionLocale()
    {
        return $this->getData('locale');
    }

    /**
     * Set submission locale.
     *
     * @param string $locale
     */
    public function setSubmissionLocale($locale)
    {
        return $this->setData('locale', $locale);
    }

    /**
     * Set the user group id
     *
     * @param int $userGroupId
     */
    public function setUserGroupId($userGroupId)
    {
        $this->setData('userGroupId', $userGroupId);
    }

    /**
     * Get the user group id
     *
     * @return int
     */
    public function getUserGroupId()
    {
        return $this->getData('userGroupId');
    }

    /**
     * Set whether or not to include in browse lists.
     *
     * @param bool $include
     */
    public function setIncludeInBrowse($include)
    {
        $this->setData('includeInBrowse', $include);
    }

    /**
     * Get whether or not to include in browse lists.
     *
     * @return bool
     */
    public function getIncludeInBrowse()
    {
        return $this->getData('includeInBrowse');
    }

    /**
     * Get the "show title" flag (whether or not the title of the role
     * should be included in the list of submission contributor names).
     * This is fetched from the user group for performance reasons.
     *
     * @return bool
     */
    public function getShowTitle()
    {
        return $this->getData('showTitle');
    }

    /**
     * Set the "show title" flag. This attribute belongs to the user group,
     * NOT the author; fetched for performance reasons only.
     *
     * @param bool $showTitle
     */
    public function _setShowTitle($showTitle)
    {
        $this->setData('showTitle', $showTitle);
    }

    /**
     * Get primary contact.
     *
     * @return bool
     */
    public function getPrimaryContact()
    {
        return $this->getData('primaryContact');
    }

    /**
     * Set primary contact.
     *
     * @param bool $primaryContact
     */
    public function setPrimaryContact($primaryContact)
    {
        $this->setData('primaryContact', $primaryContact);
    }

    /**
     * Get sequence of author in submissions' author list.
     *
     * @return float
     */
    public function getSequence()
    {
        return $this->getData('seq');
    }

    /**
     * Set sequence of author in submissions' author list.
     *
     * @param float $sequence
     */
    public function setSequence($sequence)
    {
        $this->setData('seq', $sequence);
    }

    /**
     * Get the user group for this contributor.
     *
     * @return \PKP\userGroup\UserGroup
     */
    public function getUserGroup()
    {
        //FIXME: should this be queried when fetching Author from DB? - see #5231.
        static $userGroup; // Frequently we'll fetch the same one repeatedly
        if (!$userGroup || $this->getUserGroupId() != $userGroup->getId()) {
            $userGroup = Repo::userGroup()->get($this->getUserGroupId());
        }
        return $userGroup;
    }

    /**
     * Get a localized version of the User Group
     *
     * @return string
     */
    public function getLocalizedUserGroupName()
    {
        $userGroup = $this->getUserGroup();
        return $userGroup->getLocalizedName();
    }

    /**
     * Get contrubutor roles
     *
     * @return array
     */
    public function getContributorRoles()
    {
        return $this->getData('contributorRoles') ?? [];
    }

    /**
     * Get contributor role terms
     */
    public static function getContributorRoleTerms($locale = null) {
        return self::getTypeRoleTerms('contributor-roles', $locale);
    }

    /**
     * Get credit role terms
     */
    public static function getCreditRoleTerms($locale = null) {
        return self::getTypeRoleTerms('credit-roles', $locale);
    }

    /**
     * Type of roles in an associative URI => Term array
     * @param $typeRoles The type of roles (the name of the xml file), e.g. contributor-roles, credit-roles
     * @param $locale The locale for which to fetch the data (default primary locale; en if not available)
     */
    protected static function getTypeRoleTerms($typeRoles, $locale = null): array
    {
        $doc = new DOMDocument();

        if (!$locale) {
            $locale = \PKP\facades\Locale::getPrimaryLocale();
        }

        if (!\PKP\facades\Locale::isLocaleValid($locale)) {
            $locale = 'en';
        }

        if (file_exists($filename = "lib/pkp/xml/schema/$typeRoles-{$locale}.xml")) {
            $doc->load($filename);
        } else {
            $doc->load("lib/pkp/xml/schema/{$typeRoles}.xml");
        }

        $roles = [];
        foreach ($doc->getElementsByTagName($typeRoles) as $troles) {
            foreach ($troles->getElementsByTagName('item') as $item) {
                $roles[$item->getAttribute('uri')] = $item->getAttribute('term');
            }
        }
        return $roles;
    }
}
