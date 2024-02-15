<?php

it('will not use debugging functions') // @phpstan-ignore-line
    ->expect(['dd', 'dump', 'ray'])
    ->not()->toBeUsed();
