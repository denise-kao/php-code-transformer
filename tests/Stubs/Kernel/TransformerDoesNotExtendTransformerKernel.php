<?php

namespace Okapi\CodeTransformer\Tests\Stubs\Kernel;

use Okapi\CodeTransformer\CodeTransformerKernel;
use Okapi\CodeTransformer\Tests\Stubs\Transformer\InvalidTransformer;
use Okapi\CodeTransformer\Tests\Util;

class TransformerDoesNotExtendTransformerKernel extends CodeTransformerKernel
{
    protected ?string $cacheDir = Util::CACHE_DIR;

    protected array $transformers = [
        InvalidTransformer::class,
    ];
}
