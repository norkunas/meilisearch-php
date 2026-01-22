<?php

declare(strict_types=1);

namespace Meilisearch\Contracts;

final class BatchStep
{
    /**
     * @param non-empty-string $currentStep
     * @param non-negative-int $finished
     * @param non-negative-int $total
     */
    public function __construct(
        private readonly string $currentStep,
        private readonly int $finished,
        private readonly int $total,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getCurrentStep(): string
    {
        return $this->currentStep;
    }

    /**
     * @return non-negative-int
     */
    public function getFinished(): int
    {
        return $this->finished;
    }

    /**
     * @return non-negative-int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param array{
     *     currentStep: non-empty-string,
     *     finished: non-negative-int,
     *     total: non-negative-int
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['currentStep'],
            $data['finished'],
            $data['total'],
        );
    }
}
