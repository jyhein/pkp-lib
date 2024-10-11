<?php

/**
 * @file classes/submission/SubmissionAgency.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2000-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class SubmissionAgency
 *
 * @ingroup submission
 *
 * @see SubmissionAgencyEntryDAO
 *
 * @brief Basic class describing a submission agency
 */

namespace PKP\submission;

use PKP\controlledVocab\ControlledVocabEntry;
use SubmissionAgencyDAO;

class SubmissionAgency extends \PKP\controlledVocab\ControlledVocabEntry
{
    //
    // Get/set methods
    //

    /**
     * Get the agency
     *
     * @return string
     */
    public function getAgency()
    {
        return $this->getData(SubmissionAgencyDAO::CONTROLLED_VOCAB_SUBMISSION_AGENCY);
    }

    /**
     * Get the agency's label
     *
     * @return string|null
     */
    public function getLabel(): ?array
    {
        return $this->getData(SubmissionAgencyDAO::CONTROLLED_VOCAB_SUBMISSION_AGENCY_LABEL);
    }

    /**
     * Get the agency's identifier
     *
     * @return string|null
     */
    public function getIdentifier(): ?string
    {
        return $this->getData(SubmissionAgencyDAO::CONTROLLED_VOCAB_SUBMISSION_AGENCY_ID);
    }

    /**
     * Set the agency text
     *
     * @param string $agency
     * @param string $locale
     */
    public function setAgency($agency, $locale)
    {
        $this->setData(SubmissionAgencyDAO::CONTROLLED_VOCAB_SUBMISSION_AGENCY, $agency, $locale);
    }

    /**
     * Set the agency's label
     */
    public function setLabel(string $label, string $locale): void
    {
        $this->setData(SubmissionAgencyDAO::CONTROLLED_VOCAB_SUBMISSION_AGENCY_LABEL, $label, $locale);
    }

    /**
     * Set the agency's identifier
     */
    public function setIdentifier(string $id): void
    {
        $this->setData(SubmissionAgencyDAO::CONTROLLED_VOCAB_SUBMISSION_AGENCY_ID, $id, '');
    }

    public function getLocaleMetadataFieldNames(): array
    {
        return [
            SubmissionAgencyDAO::CONTROLLED_VOCAB_SUBMISSION_AGENCY,
            SubmissionAgencyDAO::CONTROLLED_VOCAB_SUBMISSION_AGENCY_LABEL,
            SubmissionAgencyDAO::CONTROLLED_VOCAB_SUBMISSION_AGENCY_ID,
        ];
    }

    /**
     * Get entry related data
     */
    public function getEntryData(string $vocab = SubmissionAgencyDAO::CONTROLLED_VOCAB_SUBMISSION_AGENCY): ?array
    {
        return parent::getEntryData($vocab);
    }

    /**
     * Set entry related data
     */
    public function setEntryData(array $data, string $locale, string $vocab = SubmissionAgencyDAO::CONTROLLED_VOCAB_SUBMISSION_AGENCY): void
    {
        parent::setEntryData($data, $locale, $vocab);
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\PKP\submission\SubmissionAgency', '\SubmissionAgency');
}
