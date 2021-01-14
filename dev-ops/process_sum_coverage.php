<?php declare(strict_types=1);

namespace Shopware\DevOps\Common\Script;

use SimpleXMLElement;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * MERGE the log files from different test suites into one cohesive file
 *
 * WARNING: Does not merge but sum, if multiple test-suites cover the same files, this will not work
 */

$artifacts = __DIR__ . '/../build/artifacts';

/** @var string[] $logs */
$logs = [];

echo "\n## Loading clover\n";
foreach(glob($artifacts . '/*clover.xml') as $file) {
    $logs[$file] = file_get_contents($file);
}

if($logs === []) {
    trigger_error('NO RESULT FILES FOUND', E_USER_ERROR);
    exit(-1);
}

echo "### Coverage\n";

$sum = CoverageOverview::empty();
foreach($logs as $file => $contents) {
    $elements = new SimpleXMLElement($contents);
    $metrics = $elements->project[0]->metrics;

    $local = CoverageOverview::fromClover($metrics);

    $sum->add($local);

    echo "\t - " . $file . PHP_EOL;
    echo
        $local->printable(2) . PHP_EOL;
}

echo "### Total Coverage\n";
echo $sum->printable(1) . PHP_EOL;


exit;

class Coverage {

    /**  @var int */
    private $covered;

    /** @var int */
    private $total;

    public function __construct(int $covered, int $total)
    {
        $this->covered = $covered;
        $this->total = $total;
    }

    public function add(Coverage $coverage): self
    {
        $this->covered += $coverage->covered();
        $this->total += $coverage->total();

        return $this;
    }

    public function covered(): int
    {
        return $this->covered;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function percantage(): float
    {
        return ($this->covered / $this->total) * 100;
    }

    public function printable(): string
    {
        // padded, from/total => %
        return round($this->percantage(), 2) . '%';
    }
}

class CoverageOverview {
    /** @var Coverage */
    private $statements;

    /** @var Coverage */
    private $methods;

    public static function empty(): self
    {
        return new self(
            new Coverage(0, 0),
            new Coverage(0, 0)
        );
    }

    public static function fromClover(SimpleXMLElement $element): self
    {
        return new self(
            new Coverage((int) (string) $element['coveredstatements'], (int) (string)$element['statements']),
            new Coverage((int) (string) $element['coveredmethods'], (int) (string) $element['methods'])
        );
    }

    public function __construct(
        Coverage $statements,
        Coverage $methods
    ) {
        $this->statements = $statements;
        $this->methods = $methods;
    }

    public function add(CoverageOverview $anotherOverview): void
    {
        $this->statements->add($anotherOverview->statements());
        $this->methods->add($anotherOverview->methods());
    }

    public function statements(): Coverage
    {
        return $this->statements;
    }

    public function methods(): Coverage
    {
        return $this->methods;
    }

    public function printable(int $indentation = 0): string
    {
        $prefix = str_repeat("\t", $indentation);

        return $prefix . "Statements: \t " . $this->statements->printable() . PHP_EOL .
                $prefix ."Methods: \t " . $this->methods->printable() . PHP_EOL;
    }
}
