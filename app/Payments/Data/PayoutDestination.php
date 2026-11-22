<?php

declare(strict_types=1);

namespace App\Payments\Data;

final readonly class PayoutDestination
{
    public function __construct(
        public string $accountNumber,
        public string $accountName,
        public string $bankCode,
        public ?string $recipientCode = null,
    ) {
    }

    public function withRecipient(?string $recipientCode): self
    {
        return new self($this->accountNumber, $this->accountName, $this->bankCode, $recipientCode);
    }

    public function withAccountName(string $accountName): self
    {
        return new self($this->accountNumber, $accountName, $this->bankCode, $this->recipientCode);
    }
}
