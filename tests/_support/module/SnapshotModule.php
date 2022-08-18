<?php

declare(strict_types=1);

namespace app\tests\support\module;

use app\tests\support\helper\MatchesSnapshotsTrait;
use Codeception\Module;
use Codeception\TestInterface;

class SnapshotModule extends Module
{
    use MatchesSnapshotsTrait {
        _before as snapshotTraitBefore;
    }

    private bool $_shouldUpdateSnapshots = false;

    public function _before(TestInterface $test)
    {
        $this->snapshotTraitBefore($test);
        parent::_before($test);

        $this->_shouldUpdateSnapshots = isset($_SERVER['argv'])
            && (\in_array('--debug', $_SERVER['argv'], true) || \in_array('-d', $_SERVER['argv'], true))
            && \in_array('--update-snapshots', $_SERVER['argv'], true);
    }

    protected function shouldUpdateSnapshots(): bool
    {
        return $this->_shouldUpdateSnapshots;
    }
}
