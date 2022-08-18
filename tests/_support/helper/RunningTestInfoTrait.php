<?php

declare(strict_types=1);

namespace app\tests\support\helper;

use Codeception\TestInterface;

trait RunningTestInfoTrait
{
    protected ?string $currentSuiteClass = null;

    protected ?string $currentStepMethodName = null;

    protected ?string $currentSuiteRootPath = null;

    public function _before(TestInterface $test): void
    {
        $this->parseCurrentSuiteMetadata($test);
        parent::_before($test);
    }

    protected function parseCurrentSuiteMetadata(TestInterface $test): void
    {
        $pathInfo = pathinfo($test->getMetadata()->getFilename());
        $this->currentStepMethodName = $test->getName();
        $this->currentSuiteRootPath = $pathInfo['dirname'];
        $this->currentSuiteClass = $pathInfo['filename'];
    }
}
