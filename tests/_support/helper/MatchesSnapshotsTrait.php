<?php

declare(strict_types=1);

namespace app\tests\support\helper;

use Codeception\Exception\ModuleException;
use Codeception\TestInterface;
use Spatie\Snapshots\Drivers\JsonDriver;
use Spatie\Snapshots\Drivers\ObjectDriver;
use Spatie\Snapshots\Drivers\TextDriver;
use Spatie\Snapshots\MatchesSnapshots;

trait MatchesSnapshotsTrait
{
    use MatchesSnapshots;
    use RunningTestInfoTrait {
        _before as runningTestTraitBefore;
    }

    private ?string $testClass = null;

    private ?string $testMethod = null;

    private ?string $alreadyProcessedTestName = null;

    /**
     * @param TestInterface $test
     */
    public function _before(TestInterface $test)
    {
        $this->runningTestTraitBefore($test);
        parent::_before($test);
    }

    public function assertMatchesJsonSnapshot($actual): void
    {
        $this->initTestClassMetadata('assertMatchesJsonSnapshot');

        $this->assertMatchesSnapshot($actual, new JsonDriver());
    }

    public function assertMatchesTextSnapshot($actual): void
    {
        $this->initTestClassMetadata('assertMatchesTextSnapshot');

        $this->assertMatchesSnapshot($actual, new TextDriver());
    }

    public function assertObjectMatchesSnapshot($actual): void
    {
        $this->initTestClassMetadata('assertObjectMatchesSnapshot');

        $this->assertMatchesSnapshot($actual, new ObjectDriver());
    }

    /**
     * Necessary for snapshot lib.
     *
     * @return string
     */
    protected function getName(): string
    {
        return $this->testMethod;
    }

    /**
     * Necessary for snapshot lib.
     *
     * @return string
     */
    protected function getSnapshotDirectory(): string
    {
        return $this->currentSuiteRootPath . \DIRECTORY_SEPARATOR . '__snapshots__';
    }

    /**
     * Necessary for snapshot lib.
     *
     * @return string
     */
    protected function getSnapshotId(): string
    {
        return $this->currentSuiteClass . '__' .
            $this->getName() . '__' .
            $this->snapshotIncrementor;
    }

    /*
     * Determines whether or not the snapshot should be updated instead of
     * matched.
     *
     * Override this method it you want to use a different flag or mechanism
     * than `-d --update-snapshots`.
     */
    protected function shouldUpdateSnapshots(): bool
    {
        return in_array('--update-snapshots', $_SERVER['argv'] ?? [], true);
    }

    /*
     * Determines whether or not the snapshot should be created instead of
     * matched.
     *
     * Override this method if you want to use a different flag or mechanism
     * than `-d --without-creating-snapshots`.
     */
    protected function shouldCreateSnapshots(): bool
    {
        return !in_array('--without-creating-snapshots', $_SERVER['argv'] ?? [], true);
    }

    private function initTestClassMetadata(string $name = ''): void
    {
        [$this->testClass, $this->testMethod] = $this->getTestClass($name);

        if (empty($this->testClass) || (empty($this->testMethod))) {
            throw new ModuleException(self::class, 'Something went wrong. Test class not found');
        }

        $this->resetSnapshotIncrementor();
    }

    private function resetSnapshotIncrementor(): void
    {
        $testName = sprintf('%s__%s', $this->testClass, $this->testMethod);

        if (!$this->alreadyProcessedTestName) {
            $this->alreadyProcessedTestName = $testName;
        }
        if ($testName !== $this->alreadyProcessedTestName) {
            $this->setUpSnapshotIncrementor();
            $this->alreadyProcessedTestName = $testName;
        }
    }

    /**
     * Getting real test name and test class from the stack trace,
     * where seeResponseMatchesSnapshot was originally called.
     *
     * @param string $method
     *
     * @return array
     */
    private function getTestClass(string $method): array
    {
        if (null !== $this->currentSuiteClass && null !== $this->currentStepMethodName) {
            return [
                $this->currentSuiteClass,
                $this->currentStepMethodName,
            ];
        }
        $foundMethodAmount = 0;

        $stacktrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        foreach ($stacktrace as $item) {
            if ($foundMethodAmount === 2) {
                return [$item['class'], $item['function']];
            }

            if ($item['function'] === $method) {
                $foundMethodAmount++;
            }
        }

        return ['', ''];
    }
}
