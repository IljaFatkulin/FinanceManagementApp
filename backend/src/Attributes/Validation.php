<?php
declare(strict_types=1);

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Validation
{
    public function __construct(public bool $required, public string $errorMessage) {}
}