<?php

declare(strict_types=1);

namespace Meilisearch\Contracts;

class SimilarDocumentsQuery
{
    /**
     * @var int|string
     */
    private $id;

    /**
     * @var non-negative-int|null
     */
    private ?int $offset = null;

    /**
     * @var positive-int|null
     */
    private ?int $limit = null;

    /**
     * @var non-empty-string|null
     */
    private ?string $embedder = null;

    /**
     * @var list<non-empty-string>|null
     */
    private ?array $attributesToRetrieve = null;

    private ?bool $showRankingScore = null;

    private ?bool $showRankingScoreDetails = null;

    private ?bool $retrieveVectors = null;

    /**
     * @var array<int, array<int, string>|string>|null
     */
    private ?array $filter = null;

    /**
     * @var int|float|null
     */
    private $rankingScoreThreshold;

    /**
     * @param int|string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param non-negative-int|null $offset
     */
    public function setOffset(?int $offset): SimilarDocumentsQuery
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @param positive-int|null $limit
     */
    public function setLimit(?int $limit): SimilarDocumentsQuery
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param array<int, array<int, string>|string> $filter an array of arrays representing filter conditions
     */
    public function setFilter(array $filter): SimilarDocumentsQuery
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @param non-empty-string $embedder
     */
    public function setEmbedder(string $embedder): SimilarDocumentsQuery
    {
        $this->embedder = $embedder;

        return $this;
    }

    /**
     * @param list<non-empty-string> $attributesToRetrieve an array of attribute names to retrieve
     */
    public function setAttributesToRetrieve(array $attributesToRetrieve): SimilarDocumentsQuery
    {
        $this->attributesToRetrieve = $attributesToRetrieve;

        return $this;
    }

    /**
     * @param bool|null $showRankingScore boolean value to show ranking score
     */
    public function setShowRankingScore(?bool $showRankingScore): SimilarDocumentsQuery
    {
        $this->showRankingScore = $showRankingScore;

        return $this;
    }

    /**
     * @param bool|null $showRankingScoreDetails boolean value to show ranking score details
     */
    public function setShowRankingScoreDetails(?bool $showRankingScoreDetails): SimilarDocumentsQuery
    {
        $this->showRankingScoreDetails = $showRankingScoreDetails;

        return $this;
    }

    /**
     * @param bool|null $retrieveVectors boolean value to show _vector details
     */
    public function setRetrieveVectors(?bool $retrieveVectors): SimilarDocumentsQuery
    {
        $this->retrieveVectors = $retrieveVectors;

        return $this;
    }

    /**
     * @param int|float|null $rankingScoreThreshold
     */
    public function setRankingScoreThreshold($rankingScoreThreshold): SimilarDocumentsQuery
    {
        $this->rankingScoreThreshold = $rankingScoreThreshold;

        return $this;
    }

    /**
     * @return array{
     *     id: int|string,
     *     offset?: non-negative-int,
     *     limit?: positive-int,
     *     filter?: array<int, array<int, string>|string>,
     *     embedder?: non-empty-string,
     *     attributesToRetrieve?: list<non-empty-string>,
     *     showRankingScore?: bool,
     *     showRankingScoreDetails?: bool,
     *     retrieveVectors?: bool,
     *     rankingScoreThreshold?: int|float
     * }
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'offset' => $this->offset,
            'limit' => $this->limit,
            'filter' => $this->filter,
            'embedder' => $this->embedder,
            'attributesToRetrieve' => $this->attributesToRetrieve,
            'showRankingScore' => $this->showRankingScore,
            'showRankingScoreDetails' => $this->showRankingScoreDetails,
            'retrieveVectors' => $this->retrieveVectors,
            'rankingScoreThreshold' => $this->rankingScoreThreshold,
        ], static function ($item) {
            return null !== $item;
        });
    }
}
