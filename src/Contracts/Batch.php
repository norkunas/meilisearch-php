<?php

declare(strict_types=1);

namespace Meilisearch\Contracts;

use Meilisearch\Contracts\TaskDetails\DocumentAdditionOrUpdateDetails;
use Meilisearch\Contracts\TaskDetails\DocumentDeletionDetails;
use Meilisearch\Contracts\TaskDetails\DocumentEditionDetails;
use Meilisearch\Contracts\TaskDetails\DumpCreationDetails;
use Meilisearch\Contracts\TaskDetails\IndexCreationDetails;
use Meilisearch\Contracts\TaskDetails\IndexDeletionDetails;
use Meilisearch\Contracts\TaskDetails\IndexSwapDetails;
use Meilisearch\Contracts\TaskDetails\IndexUpdateDetails;
use Meilisearch\Contracts\TaskDetails\SettingsUpdateDetails;
use Meilisearch\Contracts\TaskDetails\TaskCancelationDetails;
use Meilisearch\Contracts\TaskDetails\TaskDeletionDetails;
use Meilisearch\Contracts\TaskDetails\UnknownTaskDetails;
use Meilisearch\Exceptions\LogicException;

final class Batch
{
    /**
     * @param non-negative-int                   $batchUid
     * @param array{
     *     steps: list<BatchStep>,
     *     percentage: float
     * }|null $progress
     * @param array{
     *     totalNbTasks: non-negative-int,
     *     status: array<non-empty-string, int>,
     *     types: array<non-empty-string, int>,
     *     indexUids: array<non-empty-string, int>,
     *     progressTrace: array<string, string>
     * } $stats
     * @param non-empty-string|null              $duration
     */
    public function __construct(
        private readonly int $batchUid,
        private readonly ?array $progress,
        private readonly array $details,
        private readonly array $stats,
        private readonly ?string $duration = null,
        private readonly ?\DateTimeImmutable $startedAt = null,
        private readonly ?\DateTimeImmutable $finishedAt = null,
        private readonly ?string $batchStrategy = null,
    ) {
    }

    /**
     * @return non-negative-int
     */
    public function getBatchUid(): int
    {
        return $this->batchUid;
    }

    /**
     * @return array{
     *     steps: list<BatchStep>,
     *     percentage: float
     * }|null
     */
    public function getProgress(): ?array
    {
        return $this->progress;
    }

    /**
     * @return non-empty-string|null
     */
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    /**
     * @param array{
     *     batchUid: int,
     *     progress: array{
     *         steps: list<array{
     *             currentStep: non-empty-string,
     *             finished: non-negative-int,
     *             total: non-negative-int
     *         }>,
     *         percentage: float
     *     }|null,
     *     details: array, // @todo: complete
     *     stats: array, // @todo: complete
     * } $data
     */
    public static function fromArray(array $data): Batch
    {
        $details = $data['details'] ?? null;
        $type = TaskType::tryFrom($data['type']) ?? TaskType::Unknown;

        return new self(
            $data['taskUid'] ?? $data['uid'],
            $data['indexUid'] ?? null,
            TaskStatus::tryFrom($data['status']) ?? TaskStatus::Unknown,
            $type,
            new \DateTimeImmutable($data['enqueuedAt']),
            \array_key_exists('startedAt', $data) && null !== $data['startedAt'] ? new \DateTimeImmutable($data['startedAt']) : null,
            \array_key_exists('finishedAt', $data) && null !== $data['finishedAt'] ? new \DateTimeImmutable($data['finishedAt']) : null,
            $data['duration'] ?? null,
            $data['canceledBy'] ?? null,
            $data['batchUid'] ?? null,
            match ($type) {
                TaskType::IndexCreation => null !== $details ? IndexCreationDetails::fromArray($details) : null,
                TaskType::IndexUpdate => null !== $details ? IndexUpdateDetails::fromArray($details) : null,
                TaskType::IndexDeletion => null !== $details ? IndexDeletionDetails::fromArray($details) : null,
                TaskType::IndexSwap => null !== $details ? IndexSwapDetails::fromArray($details) : null,
                TaskType::DocumentAdditionOrUpdate => null !== $details ? DocumentAdditionOrUpdateDetails::fromArray($details) : null,
                TaskType::DocumentDeletion => null !== $details ? DocumentDeletionDetails::fromArray($details) : null,
                TaskType::DocumentEdition => null !== $details ? DocumentEditionDetails::fromArray($details) : null,
                TaskType::SettingsUpdate => null !== $details ? SettingsUpdateDetails::fromArray($details) : null,
                TaskType::DumpCreation => null !== $details ? DumpCreationDetails::fromArray($details) : null,
                TaskType::TaskCancelation => null !== $details ? TaskCancelationDetails::fromArray($details) : null,
                TaskType::TaskDeletion => null !== $details ? TaskDeletionDetails::fromArray($details) : null,
                // It’s intentional that SnapshotCreation tasks don’t have a details object
                // (no SnapshotCreationDetails exists and tests don’t exercise any details)
                TaskType::SnapshotCreation => null,
                TaskType::Unknown => UnknownTaskDetails::fromArray($details ?? []),
            },
            \array_key_exists('error', $data) && null !== $data['error'] ? TaskError::fromArray($data['error']) : null,
            $await,
        );
    }
}
