<?php
/**
 * @file classes/components/form/publication/ContributorForm.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2000-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ContributorForm
 *
 * @ingroup classes_controllers_form
 *
 * @brief A preset form for adding and editing a contributor for a publication.
 */

namespace PKP\components\forms\publication;

use \DOMDocument;

use APP\facades\Repo;
use APP\submission\Submission;
use PKP\components\forms\FieldOptions;
use PKP\components\forms\FieldRichTextarea;
use PKP\components\forms\FieldSelect;
use PKP\components\forms\FieldText;
use PKP\components\forms\FormComponent;
use PKP\context\Context;
use PKP\security\Role;
use PKP\userGroup\UserGroup;
use Sokil\IsoCodes\IsoCodesFactory;

define('FORM_CONTRIBUTOR', 'contributor');

class ContributorForm extends FormComponent
{
    /** @copydoc FormComponent::$id */
    public $id = FORM_CONTRIBUTOR;

    /** @copydoc FormComponent::$method */
    public $method = 'POST';

    public Submission $submission;
    public Context $context;

    public function __construct(string $action, array $locales, Submission $submission, Context $context)
    {
        $this->action = $action;
        $this->locales = $locales;
        $this->submission = $submission;
        $this->context = $context;

        $authorUserGroupsOptions = Repo::userGroup()
            ->getCollector()
            ->filterByRoleIds([Role::ROLE_ID_AUTHOR])
            ->filterByContextIds([$context->getId()])
            ->getMany()
            ->map(fn (UserGroup $authorUserGroup) => [
                'value' => (int) $authorUserGroup->getId(),
                'label' => $authorUserGroup->getLocalizedName(),
            ]);

        $isoCodes = app(IsoCodesFactory::class);
        $countries = [];
        foreach ($isoCodes->getCountries() as $country) {
            $countries[] = [
                'value' => $country->getAlpha2(),
                'label' => $country->getLocalName()
            ];
        }
        usort($countries, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });

        $this->addField(new FieldText('givenName', [
            'label' => __('user.givenName'),
            'isMultilingual' => true,
            'isRequired' => true
        ]))
            ->addField(new FieldText('familyName', [
                'label' => __('user.familyName'),
                'isMultilingual' => true,
            ]))
            ->addField(new FieldText('preferredPublicName', [
                'label' => __('user.preferredPublicName'),
                'description' => __('user.preferredPublicName.description'),
                'isMultilingual' => true,
            ]))
            ->addField(new FieldText('email', [
                'label' => __('user.email'),
                'isRequired' => true,
            ]))
            ->addField(new FieldSelect('country', [
                'label' => __('common.country'),
                'options' => $countries,
                'isRequired' => true,
            ]))
            ->addField(new FieldText('url', [
                'label' => __('user.url'),
            ]))
            ->addField(new FieldText('orcid', [
                'label' => __('user.orcid'),
            ]))
            ->addField(new FieldRichTextarea('biography', [
                'label' => __('user.biography'),
                'isMultilingual' => true,
            ]))
            ->addField(new FieldText('affiliation', [
                'label' => __('user.affiliation'),
                'isMultilingual' => true,
            ]));

        $this->addHiddenField('userGroupId', $authorUserGroupsOptions->first()['value']);

        $this->addField(new FieldOptions('contributorRoles', [
            'type' => 'checkbox',
            'label' => __('submission.submit.contributorRoles.label'),
            'description' => __('submission.submit.contributorRoles.description'),
            'options' => $this->getTypeRolesOptions('contributor-roles'),
            'value' => [],
            'isRequired' => true,
        ]));

        $this->addField(new FieldOptions('includeInBrowse', [
            'label' => __('submission.submit.includeInBrowse.title'),
            'type' => 'checkbox',
            'value' => true,
            'options' => [
                ['value' => true, 'label' => __('submission.submit.includeInBrowse')],
            ]
        ]));

        $this->addField(new FieldOptions('creditRoles', [
            'type' => 'checkbox',
            'label' => __('submission.submit.creditRoles.label'),
            'description' => __('submission.submit.creditRoles.description'),
            'options' => $this->getTypeRolesOptions('credit-roles'),
            'value' => [],
        ]));
    }

    public static function getContributorRoleTerms($locale = null) {
        return self::getTypeRoleTerms('contributor-roles', $locale);
    }

    public static function getCreditRoleTerms($locale = null) {
        return self::getTypeRoleTerms('credit-roles', $locale);
    }

    /**
     * Get roles to the contributor form
     * @param $locale The locale for which to fetch the data
     */
    protected function getTypeRolesOptions($typeRoles, $locale = null): array
    {
        $roles = [];
        foreach (self::getTypeRoleTerms($typeRoles, $locale) as $uri => $label) {
            $roles[] = ['value' => $uri, 'label' => $label];
        }
        return $roles;
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
