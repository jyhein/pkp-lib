<?php

/**
 * @file classes/submission/SubmissionSubject.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2000-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class SubmissionSubject
 *
 * @ingroup submission
 *
 * @see SubmissionSubjectEntryDAO
 *
 * @brief Basic class describing a submission subject
 */

namespace PKP\submission;

use PKP\controlledVocab\ControlledVocabEntry;
use SubmissionSubjectDAO;

class SubmissionSubject extends \PKP\controlledVocab\ControlledVocabEntry
{
    //
    // Get/set methods
    //

    /**
     * Get the subject
     *
     * @return array
     */
    public function getSubject()
    {
        return $this->getData(SubmissionSubjectDAO::CONTROLLED_VOCAB_SUBMISSION_SUBJECT);
    }

    /**
     * Get the subject's label
     *
     * @return string|null
     */
    public function getLabel(): ?array
    {
        return $this->getData(SubmissionSubjectDAO::CONTROLLED_VOCAB_SUBMISSION_SUBJECT_LABEL);
    }

    /**
     * Get the subject's identifier
     *
     * @return string|null
     */
    public function getIdentifier(): ?string
    {
        return $this->getData(SubmissionSubjectDAO::CONTROLLED_VOCAB_SUBMISSION_SUBJECT_ID);
    }

    /**
     * Set the subject text
     *
     * @param string $subject
     * @param string $locale
     */
    public function setSubject($subject, $locale)
    {
        $this->setData(SubmissionSubjectDAO::CONTROLLED_VOCAB_SUBMISSION_SUBJECT, $subject, $locale);
    }

    /**
     * Set the subject's label
     */
    public function setLabel(string $label, string $locale): void
    {
        $this->setData(SubmissionSubjectDAO::CONTROLLED_VOCAB_SUBMISSION_SUBJECT_LABEL, $label, $locale);
    }

    /**
     * Set the subject's identifier
     */
    public function setIdentifier(string $id): void
    {
        $this->setData(SubmissionSubjectDAO::CONTROLLED_VOCAB_SUBMISSION_SUBJECT_ID, $id, '');
    }

    /**
     * @copydoc \PKP\controlledVocab\ControlledVocabEntry::getLocaleMetadataFieldNames()
     */
    public function getLocaleMetadataFieldNames(): array
    {
        return [
            SubmissionSubjectDAO::CONTROLLED_VOCAB_SUBMISSION_SUBJECT,
            SubmissionSubjectDAO::CONTROLLED_VOCAB_SUBMISSION_SUBJECT_LABEL,
            SubmissionSubjectDAO::CONTROLLED_VOCAB_SUBMISSION_SUBJECT_ID,
        ];
    }

    /**
     * Get entry related data
     */
    public function getEntryData(string $vocab = SubmissionSubjectDAO::CONTROLLED_VOCAB_SUBMISSION_SUBJECT): ?array
    {
        return parent::getEntryData($vocab);
    }

    /**
     * Set entry related data
     */
    public function setEntryData(array $data, string $locale, string $vocab = SubmissionSubjectDAO::CONTROLLED_VOCAB_SUBMISSION_SUBJECT): void
    {
        parent::setEntryData($data, $locale, $vocab);
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\PKP\submission\SubmissionSubject', '\SubmissionSubject');
}
