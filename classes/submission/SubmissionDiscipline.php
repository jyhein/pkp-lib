<?php

/**
 * @file classes/submission/SubmissionDiscipline.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2000-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class SubmissionDiscipline
 *
 * @ingroup submission
 *
 * @see SubmissionDisciplineEntryDAO
 *
 * @brief Basic class describing a submission discipline
 */

namespace PKP\submission;

use PKP\controlledVocab\ControlledVocabEntry;
use SubmissionDisciplineDAO;

class SubmissionDiscipline extends \PKP\controlledVocab\ControlledVocabEntry
{
    //
    // Get/set methods
    //

    /**
     * Get the discipline
     *
     * @return string
     */
    public function getDiscipline()
    {
        return $this->getData('submissionDiscipline');
    }

    /**
     * Get the discipline's label
     *
     * @return string|null
     */
    public function getLabel(): ?array
    {
        return $this->getData('submissionDisciplineLabel');
    }

    /**
     * Get the discipline's uri
     *
     * @return string|null
     */
    public function getUri(): ?string
    {
        return $this->getData('submissionDisciplineUri');
    }

    /**
     * Set the discipline text
     *
     * @param string $discipline
     * @param string $locale
     */
    public function setDiscipline($discipline, $locale)
    {
        $this->setData('submissionDiscipline', $discipline, $locale);
    }

    /**
     * Set the discipline's label
     */
    public function setLabel(string $label, string $locale): void
    {
        $this->setData('submissionDisciplineLabel', $label, $locale);
    }

    /**
     * Set the discipline's uri
     */
    public function setUri(string $uri): void
    {
        $this->setData('submissionDisciplineUri', $uri, '');
    }

    public function getLocaleMetadataFieldNames(): array
    {
        return ['submissionDiscipline', 'submissionDisciplineLabel', 'submissionDisciplineUri'];
    }

    /**
     * Get entry related data
     */
    public function getEntryData(string $vocab = SubmissionDisciplineDAO::CONTROLLED_VOCAB_SUBMISSION_DISCIPLINE): ?array
    {
        return parent::getEntryData($vocab);
    }

    /**
     * Set entry related data
     */
    public function setEntryData(array $data, string $locale, string $vocab = SubmissionDisciplineDAO::CONTROLLED_VOCAB_SUBMISSION_DISCIPLINE): void
    {
        parent::setEntryData($data, $locale, $vocab);
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\PKP\submission\SubmissionDiscipline', '\SubmissionDiscipline');
}
