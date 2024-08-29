<?php

/**
 * @file classes/controlledVocab/ControlledVocabEntry.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2000-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ControlledVocabEntry
 *
 * @ingroup controlled_vocabs
 *
 * @see ControlledVocabEntryDAO
 *
 * @brief Basic class describing a controlled vocab.
 */

namespace PKP\controlledVocab;

class ControlledVocabEntry extends \PKP\core\DataObject
{
    public const CONTROLLED_VOCAB_ENTRY_TERM = 'term';
    public const CONTROLLED_VOCAB_ENTRY_LABEL = 'label';
    public const CONTROLLED_VOCAB_ENTRY_URI = 'uri';

    //
    // Get/set methods
    //

    /**
     * Get the ID of the controlled vocab.
     *
     * @return int
     */
    public function getControlledVocabId()
    {
        return $this->getData('controlledVocabId');
    }

    /**
     * Set the ID of the controlled vocab.
     *
     * @param int $controlledVocabId
     */
    public function setControlledVocabId($controlledVocabId)
    {
        $this->setData('controlledVocabId', $controlledVocabId);
    }

    /**
     * Get sequence number.
     *
     * @return float
     */
    public function getSequence()
    {
        return $this->getData('sequence');
    }

    /**
     * Set sequence number.
     *
     * @param float $sequence
     */
    public function setSequence($sequence)
    {
        $this->setData('sequence', $sequence);
    }

    /**
     * Get the localized name.
     *
     * @return string
     */
    public function getLocalizedName()
    {
        return $this->getLocalizedData('name');
    }

    /**
     * Get the name of the controlled vocabulary entry.
     *
     * @param string $locale
     *
     * @return string
     */
    public function getName($locale)
    {
        return $this->getData('name', $locale);
    }

    /**
     * Set the name of the controlled vocabulary entry.
     *
     * @param string $name
     * @param string $locale
     */
    public function setName($name, $locale)
    {
        $this->setData('name', $name, $locale);
    }

    /**
     * Get entry related data
     */
    public function getEntryData(string $vocab): ?array
    {
        $labels = $this->getData($vocab . ucfirst(self::CONTROLLED_VOCAB_ENTRY_LABEL)) ?? [];
        $uri = $this->getData($vocab . ucfirst(self::CONTROLLED_VOCAB_ENTRY_URI));
        return collect($this->getData($vocab) ?? [])
            ->map(fn (string $term, string $l): array =>
                [
                    self::CONTROLLED_VOCAB_ENTRY_TERM => $term,
                    self::CONTROLLED_VOCAB_ENTRY_LABEL => $labels[$l] ?? $term,
                    self::CONTROLLED_VOCAB_ENTRY_URI => $uri,
                ])
            ->toArray() ?: null;
    }

    /**
     * Set entry related data
     */
    public function setEntryData(array $data, string $locale, string $vocab): void
    {
        $this->setData($vocab, $data[self::CONTROLLED_VOCAB_ENTRY_TERM], $locale);
        if (isset($data[self::CONTROLLED_VOCAB_ENTRY_LABEL])) {
            $this->setData($vocab . ucfirst(self::CONTROLLED_VOCAB_ENTRY_LABEL), $data[self::CONTROLLED_VOCAB_ENTRY_LABEL], $locale);
        }
        if (isset($data[self::CONTROLLED_VOCAB_ENTRY_URI])) {
            $this->setData($vocab . ucfirst(self::CONTROLLED_VOCAB_ENTRY_URI), $data[self::CONTROLLED_VOCAB_ENTRY_URI], '');
        }
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\PKP\controlledVocab\ControlledVocabEntry', '\ControlledVocabEntry');
}
