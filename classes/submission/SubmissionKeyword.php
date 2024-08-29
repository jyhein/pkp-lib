<?php

/**
 * @file classes/submission/SubmissionKeyword.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2000-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class SubmissionKeyword
 *
 * @ingroup submission
 *
 * @see SubmissionKeywordEntryDAO
 *
 * @brief Basic class describing a submission keyword
 */

namespace PKP\submission;

use Illuminate\Support\Facades\DB;
use PKP\controlledVocab\ControlledVocabEntry;
use SubmissionKeywordDAO;

class SubmissionKeyword extends ControlledVocabEntry
{
    //
    // Get/set methods
    //

    /**
     * Get the keyword
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->getData('submissionKeyword');
    }

    /**
     * Get the keyword's label
     *
     * @return string|null
     */
    public function getLabel(): ?array
    {
        return $this->getData('submissionKeywordLabel');
    }

    /**
     * Get the keyword's uri
     *
     * @return string|null
     */
    public function getUri(): ?string
    {
        return $this->getData('submissionKeywordUri');
    }

    /**
     * Set the keyword text
     *
     * @param string $keyword
     * @param string $locale
     */
    public function setKeyword($keyword, $locale)
    {
        $this->setData('submissionKeyword', $keyword, $locale);
    }

    /**
     * Set the keyword's label
     */
    public function setLabel(string $label, string $locale): void
    {
        $this->setData('submissionKeywordLabel', $label, $locale);
    }

    /**
     * Set the keyword's uri
     */
    public function setUri(string $uri): void
    {
        $this->setData('submissionKeywordUri', $uri, '');
    }

    public function getLocaleMetadataFieldNames(): array
    {
        return ['submissionKeyword', 'submissionKeywordLabel', 'submissionKeywordUri'];
    }

    /**
     * Get entry related data
     */
    public function getEntryData(string $vocab = SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD): ?array
    {
        return parent::getEntryData($vocab);
    }

    /**
     * Set keyword related data
     */
    public function setEntryData(array $data, string $locale, string $vocab = SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD): void
    {
        parent::setEntryData($data, $locale, $vocab);
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\PKP\submission\SubmissionKeyword', '\SubmissionKeyword');
}
